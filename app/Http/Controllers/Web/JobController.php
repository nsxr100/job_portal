<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    // 1. Show the homepage with all jobs
    public function index()
    {
        $jobs = Job::latest()->get();
        return view('jobs.index', compact('jobs'));
    }

    // 2. Show the form to post a job
    public function create()
    {
        return view('jobs.create');
    }

    // 3. Process the form submission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'required|string|max:255',
            'type' => 'required|string',
            'category' => 'nullable|string',
            'description' => 'required|string|min:20',
        ]);

        // Attach the currently logged-in user's ID
        $validated['user_id'] = Auth::id();

        // Save to database
        Job::create($validated);

        // Redirect with success message
        return redirect('/')->with('success', 'Job posted successfully!');
    }

    // 4. Show a single job's details
    public function show($id)
    {
        $job = Job::findOrFail($id);
        
        return view('jobs.show', compact('job'));
    }
}