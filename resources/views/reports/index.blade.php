<x-layout>
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reports</h1>
            <p class="mt-2 text-gray-600">Download application and job posting reports, or import job postings from CSV.</p>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3 mb-8">
        <div class="rounded-lg bg-white p-6 shadow">
            <p class="text-sm font-semibold uppercase text-gray-500">Job Postings</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ $jobsCount }}</p>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
            <p class="text-sm font-semibold uppercase text-gray-500">Applications</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ $applicationsCount }}</p>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
            <p class="text-sm font-semibold uppercase text-gray-500">Pending</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ $statusCounts['pending'] ?? 0 }}</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-lg bg-white p-6 shadow">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Application Report</h2>
            <div class="flex flex-wrap gap-3">
                <a href="/reports/export/pdf?type=applications" class="rounded bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700">PDF</a>
                <a href="/reports/export/xlsx?type=applications" class="rounded bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700">XLSX</a>
                <a href="/reports/export/csv?type=applications" class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">CSV</a>
                <a href="/reports/export/json?type=applications" class="rounded bg-gray-800 px-4 py-2 font-semibold text-white hover:bg-gray-900">JSON</a>
            </div>
        </section>

        <section class="rounded-lg bg-white p-6 shadow">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Job Posting Report</h2>
            <div class="flex flex-wrap gap-3">
                <a href="/reports/export/pdf?type=jobs" class="rounded bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700">PDF</a>
                <a href="/reports/export/xlsx?type=jobs" class="rounded bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700">XLSX</a>
                <a href="/reports/export/csv?type=jobs" class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">CSV</a>
                <a href="/reports/export/json?type=jobs" class="rounded bg-gray-800 px-4 py-2 font-semibold text-white hover:bg-gray-900">JSON</a>
            </div>
        </section>
    </div>

    <section class="rounded-lg bg-white p-6 shadow mt-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Import Job Postings</h2>
        <p class="text-gray-600 mb-4">CSV columns: title, company, location, salary, type, category, description.</p>
        <form action="/reports/import/jobs" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3 md:flex-row md:items-center">
            @csrf
            <input type="file" name="jobs_file" accept=".csv,text/csv" class="rounded border border-gray-300 bg-white p-2">
            <button type="submit" class="rounded bg-blue-600 px-5 py-2 font-semibold text-white hover:bg-blue-700">Import CSV</button>
        </form>
        @error('jobs_file')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </section>

    <section class="rounded-lg bg-white shadow mt-6 overflow-hidden">
        <div class="border-b p-4">
            <h2 class="text-xl font-bold text-gray-900">Recent Applications</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3">Applicant</th>
                        <th class="p-3">Job</th>
                        <th class="p-3">Company</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Applied</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentApplications as $application)
                        <tr class="border-b">
                            <td class="p-3">{{ $application->applicant?->name ?? 'Unknown applicant' }}</td>
                            <td class="p-3">{{ $application->job?->title ?? 'Deleted job' }}</td>
                            <td class="p-3">{{ $application->job?->company ?? 'No company' }}</td>
                            <td class="p-3">{{ ucfirst($application->status) }}</td>
                            <td class="p-3">{{ $application->created_at?->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-6 text-center text-gray-500" colspan="5">No applications yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layout>
