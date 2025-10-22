<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InterviewController extends Controller
{
    /**
     * Display a listing of interviews for an application
     */
    public function index(Request $request, Application $application)
    {
        $user = $request->user();

        // Check if user can view this application
        if (!$user->isSuperAdmin() && $application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $interviews = $application->interviews()->orderBy('interview_date', 'desc')->get();

        return response()->json($interviews);
    }

    /**
     * Store a newly created interview
     */
    public function store(Request $request, Application $application)
    {
        $user = $request->user();

        // Check if user can create interview for this application
        if (!$user->isSuperAdmin() && $application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'interview_date' => 'required|date',
            'interview_time' => 'required|date_format:H:i',
            'type' => 'required|in:phone,video,in-person,technical,hr,final',
            'location' => 'nullable|string|max:255',
            'interviewer_name' => 'nullable|string|max:255',
            'interviewer_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
            'status' => 'sometimes|in:scheduled,completed,cancelled,rescheduled',
        ]);

        $interview = Interview::create([
            'application_id' => $application->id,
            'interview_date' => $request->interview_date,
            'interview_time' => $request->interview_time,
            'type' => $request->type,
            'location' => $request->location,
            'interviewer_name' => $request->interviewer_name,
            'interviewer_email' => $request->interviewer_email,
            'notes' => $request->notes,
            'status' => $request->status ?? 'scheduled',
        ]);

        return response()->json([
            'message' => 'Interview created successfully',
            'interview' => $interview,
        ], 201);
    }

    /**
     * Display the specified interview
     */
    public function show(Request $request, Interview $interview)
    {
        $user = $request->user();

        // Check if user can view this interview
        if (!$user->isSuperAdmin() && $interview->application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $interview->load('application.company');

        return response()->json($interview);
    }

    /**
     * Update the specified interview
     */
    public function update(Request $request, Interview $interview)
    {
        $user = $request->user();

        // Check if user can update this interview
        if (!$user->isSuperAdmin() && $interview->application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'interview_date' => 'sometimes|date',
            'interview_time' => 'sometimes|date_format:H:i',
            'type' => 'sometimes|in:phone,video,in-person,technical,hr,final',
            'location' => 'nullable|string|max:255',
            'interviewer_name' => 'nullable|string|max:255',
            'interviewer_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
            'status' => 'sometimes|in:scheduled,completed,cancelled,rescheduled',
            'feedback' => 'nullable|string',
        ]);

        $interview->update($request->only([
            'interview_date',
            'interview_time',
            'type',
            'location',
            'interviewer_name',
            'interviewer_email',
            'notes',
            'status',
            'feedback',
        ]));

        return response()->json([
            'message' => 'Interview updated successfully',
            'interview' => $interview,
        ]);
    }

    /**
     * Remove the specified interview
     */
    public function destroy(Request $request, Interview $interview)
    {
        $user = $request->user();

        // Check if user can delete this interview
        if (!$user->isSuperAdmin() && $interview->application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $interview->delete();

        return response()->json([
            'message' => 'Interview deleted successfully',
        ]);
    }
}
