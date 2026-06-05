<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'users' => User::withCount(['jobs', 'applications'])->latest()->get(),
            'jobs' => Job::with(['employer'])->withCount('applications')->latest()->get(),
            'applications' => Application::with(['job', 'applicant'])->latest()->get(),
            'resumes' => Resume::with('user')->latest()->get(),
            'totalUsers' => User::count(),
            'totalJobs' => Job::count(),
            'totalApplications' => Application::count(),
            'totalResumes' => Resume::count(),
        ]);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:applicant,employer',
            'is_admin' => 'nullable|boolean',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return back()->with('success', 'User account created successfully.');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:applicant,employer',
            'password' => 'nullable|string|min:8',
            'is_admin' => 'nullable|boolean',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->is_admin = $request->boolean('is_admin');

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'User account updated successfully.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('success', 'You cannot delete the admin account currently signed in.');
        }

        $user->delete();

        return back()->with('success', 'User account deleted successfully.');
    }

    public function destroyJob(Job $job)
    {
        $job->delete();

        return back()->with('success', 'Job posting deleted successfully.');
    }

    public function updateApplication(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ]);

        $application->update($validated);

        return back()->with('success', 'Application status updated successfully.');
    }

    public function destroyApplication(Application $application)
    {
        $application->delete();

        return back()->with('success', 'Application deleted successfully.');
    }

    public function destroyResume(Resume $resume)
    {
        if ($resume->file_path && $resume->file_path !== '0') {
            Storage::disk('public')->delete($resume->file_path);
        }

        $resume->delete();

        return back()->with('success', 'Resume record and file deleted successfully.');
    }
}
