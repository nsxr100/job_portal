<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index()
    {
        $jobsCount = Job::count();
        $applicationsCount = Application::count();
        $statusCounts = Application::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentApplications = Application::with(['job', 'applicant'])
            ->latest()
            ->limit(10)
            ->get();

        return view('reports.index', compact(
            'jobsCount',
            'applicationsCount',
            'statusCounts',
            'recentApplications'
        ));
    }

    public function export(Request $request, string $format)
    {
        $type = $request->query('type', 'applications');
        [$headers, $rows, $filename] = $type === 'jobs'
            ? $this->jobReportData()
            : $this->applicationReportData();

        return match ($format) {
            'csv' => response($this->toCsv($headers, $rows), 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ]),
            'json' => response()->json([
                'report' => $filename,
                'generated_at' => now()->toIso8601String(),
                'data' => $this->rowsToObjects($headers, $rows),
            ])->header('Content-Disposition', "attachment; filename=\"{$filename}.json\""),
            'pdf' => response($this->toPdf(Str::headline($filename), $headers, $rows), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$filename}.pdf\"",
            ]),
            'xlsx' => response($this->toXlsx($headers, $rows), 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$filename}.xlsx\"",
            ]),
        };
    }

    public function importJobs(Request $request)
    {
        $request->validate([
            'jobs_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $handle = fopen($request->file('jobs_file')->getRealPath(), 'r');
        $headers = array_map(fn ($header) => Str::snake(trim($header)), fgetcsv($handle) ?: []);
        $created = 0;

        while (($line = fgetcsv($handle)) !== false) {
            $row = array_combine($headers, array_pad($line, count($headers), null));

            if (!$row || empty($row['title']) || empty($row['company']) || empty($row['description'])) {
                continue;
            }

            Job::create([
                'user_id' => Auth::id(),
                'title' => $row['title'],
                'company' => $row['company'],
                'location' => $row['location'] ?? 'Not specified',
                'salary' => $row['salary'] ?? 'Not specified',
                'type' => $row['type'] ?? 'Full-Time',
                'category' => $row['category'] ?? null,
                'description' => $row['description'],
            ]);

            $created++;
        }

        fclose($handle);

        return back()->with('success', "{$created} job posting(s) imported successfully.");
    }

    private function applicationReportData(): array
    {
        $rows = Application::with(['job', 'applicant'])
            ->latest()
            ->get()
            ->map(fn (Application $application) => [
                $application->id,
                $application->applicant?->name ?? 'Unknown applicant',
                $application->applicant?->email ?? 'No email',
                $application->job?->title ?? 'Deleted job',
                $application->job?->company ?? 'No company',
                ucfirst($application->status),
                optional($application->created_at)->format('Y-m-d H:i'),
            ])
            ->all();

        return [
            ['ID', 'Applicant', 'Email', 'Job', 'Company', 'Status', 'Applied At'],
            $rows,
            'application-report',
        ];
    }

    private function jobReportData(): array
    {
        $rows = Job::withCount('applications')
            ->latest()
            ->get()
            ->map(fn (Job $job) => [
                $job->id,
                $job->title,
                $job->company,
                $job->location,
                $job->type,
                $job->salary,
                $job->applications_count,
                optional($job->created_at)->format('Y-m-d H:i'),
            ])
            ->all();

        return [
            ['ID', 'Title', 'Company', 'Location', 'Type', 'Salary', 'Applications', 'Posted At'],
            $rows,
            'job-postings-report',
        ];
    }

    private function rowsToObjects(array $headers, array $rows): array
    {
        return array_map(fn ($row) => array_combine($headers, $row), $rows);
    }

    private function toCsv(array $headers, array $rows): string
    {
        $stream = fopen('php://temp', 'r+');
        fputcsv($stream, $headers);

        foreach ($rows as $row) {
            fputcsv($stream, $row);
        }

        rewind($stream);
        return stream_get_contents($stream);
    }

    private function toPdf(string $title, array $headers, array $rows): string
    {
        $lines = [$title, 'Generated: ' . now()->format('Y-m-d H:i'), '', implode(' | ', $headers)];

        foreach (array_slice($rows, 0, 35) as $row) {
            $lines[] = Str::limit(implode(' | ', array_map('strval', $row)), 110);
        }

        $content = "BT\n/F1 11 Tf\n50 790 Td\n14 TL\n";
        foreach ($lines as $line) {
            $content .= '(' . $this->escapePdf($line) . ") Tj\nT*\n";
        }
        $content .= "ET";

        $objects = [
            "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n",
            "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n",
            "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj\n",
            "4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n",
            "5 0 obj << /Length " . strlen($content) . " >> stream\n{$content}\nendstream endobj\n",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        return $pdf . "trailer << /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF";
    }

    private function escapePdf(string $value): string
    {
        return str_replace(["\\", '(', ')', "\r", "\n"], ["\\\\", "\\(", "\\)", ' ', ' '], $value);
    }

    private function toXlsx(array $headers, array $rows): string
    {
        $sheetRows = array_merge([$headers], $rows);
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';

        foreach ($sheetRows as $rowIndex => $row) {
            $sheetXml .= '<row r="' . ($rowIndex + 1) . '">';
            foreach (array_values($row) as $columnIndex => $value) {
                $cell = $this->columnName($columnIndex + 1) . ($rowIndex + 1);
                $sheetXml .= '<c r="' . $cell . '" t="inlineStr"><is><t>'
                    . htmlspecialchars((string) $value, ENT_XML1 | ENT_COMPAT, 'UTF-8')
                    . '</t></is></c>';
            }
            $sheetXml .= '</row>';
        }

        $sheetXml .= '</sheetData></worksheet>';

        return $this->zipStore([
            '[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>',
            '_rels/.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
            'xl/workbook.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Report" sheetId="1" r:id="rId1"/></sheets></workbook>',
            'xl/_rels/workbook.xml.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>',
            'xl/worksheets/sheet1.xml' => $sheetXml,
        ]);
    }

    private function columnName(int $index): string
    {
        $name = '';
        while ($index > 0) {
            $index--;
            $name = chr(65 + ($index % 26)) . $name;
            $index = intdiv($index, 26);
        }

        return $name;
    }

    private function zipStore(array $files): string
    {
        $zip = '';
        $centralDirectory = '';
        $fileCount = 0;

        foreach ($files as $name => $data) {
            $offset = strlen($zip);
            $crc = crc32($data);
            $size = strlen($data);
            $nameLength = strlen($name);

            $zip .= pack('VvvvvvVVVvv', 0x04034b50, 20, 0, 0, 0, 0, $crc, $size, $size, $nameLength, 0)
                . $name
                . $data;

            $centralDirectory .= pack('VvvvvvvVVVvvvvvVV', 0x02014b50, 20, 20, 0, 0, 0, 0, $crc, $size, $size, $nameLength, 0, 0, 0, 0, 0, $offset)
                . $name;

            $fileCount++;
        }

        $centralOffset = strlen($zip);
        $zip .= $centralDirectory;
        $centralSize = strlen($centralDirectory);

        return $zip . pack('VvvvvVVv', 0x06054b50, 0, 0, $fileCount, $fileCount, $centralSize, $centralOffset, 0);
    }
}
