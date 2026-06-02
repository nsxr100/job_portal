<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function index() 
    {
        // Passes the existing resume to the view if it exists
        return view('resume.index', ['resume' => auth()->user()->resume]);
    }
public function store(Request $request) 
{
    // 1. Validate
    $request->validate([
        'resume' => 'required|file|mimes:pdf|max:5120',
    ]);

    // 2. Store the file (We confirmed this works!)
    $path = $request->file('resume')->store('resumes', 'public');

    if ($path === false) {
        return back()->withErrors([
            'resume' => 'The resume could not be saved. Please try again.',
        ]);
    }

    // 3. Save to Database
    // Make sure your Resume model has 'path' and 'user_id' in $fillable
    \App\Models\Resume::updateOrCreate(
        ['user_id' => auth()->id()],
        ['file_path' => $path]
    );

    // 4. Return success to the user
    return back()->with('success', 'Resume uploaded successfully!');
}
}
