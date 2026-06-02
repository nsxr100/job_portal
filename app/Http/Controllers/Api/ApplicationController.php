<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\Resume;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    // POST /api/resumes (Applicant: Upload Resume)
    public function uploadResume(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['error' => 'Only applicants can upload resumes.'], 403);
        }

        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx|max:2048', // Max 2MB
        ]);

        // Save the file inside storage/app/public/resumes
        $path = $request->file('file')->store('resumes', 'public');

        if ($path === false) {
            return response()->json([
                'error' => 'The resume could not be saved. Please try again.',
            ], 500);
        }

        $resume = Resume::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['file_path' => $path]
        );

        return response()->json([
            'message' => 'Resume uploaded successfully!',
            'resume' => $resume
        ], 201);
    }

    // POST /api/jobs/{id}/apply (Applicant: Apply for a job)
    public function apply(Request $request, $jobId)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['error' => 'Only applicants can apply for jobs.'], 403);
        }

        $job = Job::findOrFail($jobId);
        $userId = $request->user()->id; 

        $existingApplication = Application::where('job_id', $job->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingApplication) {
            return response()->json(['error' => 'You have already applied for this job.'], 400);
        }

        // Create the application record
        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => $userId,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Application submitted successfully!',
            'application' => $application
        ], 201);
    }

    // GET /api/applications (Employer: View submissions for their jobs)
    public function index(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        $applications = Application::whereHas('job', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->with(['job', 'applicant.resume'])->get();

        return response()->json($applications, 200);
    }

    // PUT /api/applications/{id} (Employer: Accept/Reject an application)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ]);

        $application = Application::findOrFail($id);

        if ($application->job->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized management access.'], 403);
        }

        $application->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Application status updated successfully!',
            'application' => $application
        ], 200);
    }
    
    // === WEB ROUTES BELOW ===

    // View My Applications (Applicant Web View)
    public function indexWeb(Request $request)
    {
        // FIXED: Fetch applications where the user_id matches the person logged in
        $applications = Application::where('user_id', $request->user()->id)
            ->with('job')
            ->latest()
            ->get();

        return view('applications.index', compact('applications'));
    }

    // Process Apply Button (Web View)
    public function applyWeb(Request $request, $jobId)
    {
        $job = Job::findOrFail($jobId);
        $userId = auth()->id(); 

        // 1. Check if they already applied
        $existingApplication = Application::where('job_id', $job->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingApplication) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // 2. Create the application
        Application::create([
            'job_id' => $job->id,
            'user_id' => $userId,
            'status' => 'pending'
        ]);

        // 3. Send them back with a success message
        return back()->with('success', 'Application submitted successfully!');
    }
    // View Employer Dashboard (Web View)
    public function employerIndexWeb(Request $request)
    {
        // Fetch applications ONLY for jobs posted by the logged-in employer
        $applications = \App\Models\Application::whereHas('job', function ($query) {
            $query->where('user_id', auth()->id());
        })
        // Load the related job, the applicant (user), and the applicant's resume
        ->with(['job', 'applicant.resume'])
        ->latest()
        ->get();

        return view('employer.dashboard', compact('applications'));
    }

    // Process Accept/Reject Status Change (Web View)
    public function updateStatusWeb(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ]);

        $application = \App\Models\Application::findOrFail($id);

        // Security check: Make sure the person logged in actually owns the job!
        if ($application->job->user_id !== auth()->id()) {
            abort(403, 'Unauthorized management access.');
        }

        // Update the status
        $application->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Applicant status updated to ' . ucfirst($request->status) . '!');
    }
}
