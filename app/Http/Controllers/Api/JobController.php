<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    /**
     * DISPLAY A LISTING OF THE RESOURCE (GET /api/jobs)
     * Includes basic filtering as a bonus requirement helper.
     */
    public function index(Request $request)
    {
        $query = Job::query();

        // Optional Bonus Feature: Job Filtering by title or company
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
        }

        $jobs = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Jobs retrieved successfully.',
            'data' => $jobs
        ], 200);
    }

    /**
     * STORE A NEWLY CREATED RESOURCE IN STORAGE (POST /api/jobs)
     */
    public function store(Request $request)
    {
        // Validate incoming REST API data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:Full-Time,Part-Time,Contract,Remote',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create the record
        $job = Job::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Job posting created successfully.',
            'data' => $job
        ], 201); // 201 Created Status
    }

    /**
     * DISPLAY THE SPECIFIED RESOURCE (GET /api/jobs/{id})
     */
    public function show($id)
    {
        $job = Job::find($id);

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job posting not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Job details retrieved successfully.',
            'data' => $job
        ], 200);
    }

    /**
     * UPDATE THE SPECIFIED RESOURCE IN STORAGE (PUT/PATCH /api/jobs/{id})
     */
    public function update(Request $request, $id)
    {
        $job = Job::find($id);

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job posting not found.'
            ], 404);
        }

        // Validate incoming data dynamically for updates
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'company' => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'salary' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'type' => 'sometimes|required|string|in:Full-Time,Part-Time,Contract,Remote',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Apply changes
        $job->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Job posting updated successfully.',
            'data' => $job
        ], 200);
    }

    /**
     * REMOVE THE SPECIFIED RESOURCE FROM STORAGE (DELETE /api/jobs/{id})
     */
    public function destroy($id)
    {
        $job = Job::find($id);

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job posting not found.'
            ], 404);
        }

        // Execute hard delete
        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job posting deleted successfully.'
        ], 200);
    }
}