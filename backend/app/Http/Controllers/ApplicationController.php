<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Application::with(['company', 'job', 'interviews', 'applicationNotes']);

        // If not super admin, only show user's own applications
        if (!$user->isSuperAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('company', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('job', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderBy('applied_date', 'desc')->paginate(15);

        return response()->json($applications);
    }

    /**
     * Store a newly created application
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'job_id' => 'nullable|exists:jobs,id',
            'status' => 'sometimes|in:applied,under_review,phone_screening,interview_scheduled,interviewed,technical_interview,final_interview,offer_received,offer_accepted,offer_declined,rejected,withdrawn',
            'applied_date' => 'required|date',
            'notes' => 'nullable|string',
            'resume_url' => 'nullable|url',
            'cover_letter_url' => 'nullable|url',
            'salary_expectation' => 'nullable|numeric|min:0',
            'source' => 'nullable|string|max:255',
        ]);

        $application = Application::create([
            'user_id' => $request->user()->id,
            'company_id' => $request->company_id,
            'job_id' => $request->job_id,
            'status' => $request->status ?? 'applied',
            'applied_date' => $request->applied_date,
            'notes' => $request->notes,
            'resume_url' => $request->resume_url,
            'cover_letter_url' => $request->cover_letter_url,
            'salary_expectation' => $request->salary_expectation,
            'source' => $request->source,
        ]);

        $application->load(['company', 'job']);

        return response()->json([
            'message' => 'Application created successfully',
            'application' => $application,
        ], 201);
    }

    /**
     * Display the specified application
     */
    public function show(Request $request, Application $application)
    {
        $user = $request->user();

        // Check if user can view this application
        if (!$user->isSuperAdmin() && $application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $application->load(['company', 'job', 'interviews', 'applicationNotes.user']);

        return response()->json($application);
    }

    /**
     * Update the specified application
     */
    public function update(Request $request, Application $application)
    {
        $user = $request->user();

        // Check if user can update this application
        if (!$user->isSuperAdmin() && $application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'sometimes|in:applied,under_review,phone_screening,interview_scheduled,interviewed,technical_interview,final_interview,offer_received,offer_accepted,offer_declined,rejected,withdrawn',
            'applied_date' => 'sometimes|date',
            'notes' => 'nullable|string',
            'resume_url' => 'nullable|url',
            'cover_letter_url' => 'nullable|url',
            'salary_expectation' => 'nullable|numeric|min:0',
            'source' => 'nullable|string|max:255',
        ]);

        $application->update($request->only([
            'status',
            'applied_date',
            'notes',
            'resume_url',
            'cover_letter_url',
            'salary_expectation',
            'source',
        ]));

        $application->load(['company', 'job']);

        return response()->json([
            'message' => 'Application updated successfully',
            'application' => $application,
        ]);
    }

    /**
     * Remove the specified application
     */
    public function destroy(Request $request, Application $application)
    {
        $user = $request->user();

        // Check if user can delete this application
        if (!$user->isSuperAdmin() && $application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $application->delete();

        return response()->json([
            'message' => 'Application deleted successfully',
        ]);
    }
}
