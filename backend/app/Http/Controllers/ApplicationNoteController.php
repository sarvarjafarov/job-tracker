<?php

namespace App\Http\Controllers;

use App\Models\ApplicationNote;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApplicationNoteController extends Controller
{
    /**
     * Display a listing of notes for an application
     */
    public function index(Request $request, Application $application)
    {
        $user = $request->user();

        // Check if user can view this application
        if (!$user->isSuperAdmin() && $application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notes = $application->applicationNotes()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notes);
    }

    /**
     * Store a newly created note
     */
    public function store(Request $request, Application $application)
    {
        $user = $request->user();

        // Check if user can create note for this application
        if (!$user->isSuperAdmin() && $application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'note' => 'required|string',
            'is_private' => 'sometimes|boolean',
        ]);

        $note = ApplicationNote::create([
            'application_id' => $application->id,
            'user_id' => $user->id,
            'note' => $request->note,
            'is_private' => $request->is_private ?? false,
        ]);

        $note->load('user');

        return response()->json([
            'message' => 'Note created successfully',
            'note' => $note,
        ], 201);
    }

    /**
     * Display the specified note
     */
    public function show(Request $request, ApplicationNote $note)
    {
        $user = $request->user();

        // Check if user can view this note
        if (!$user->isSuperAdmin() && $note->application->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $note->load('user', 'application.company');

        return response()->json($note);
    }

    /**
     * Update the specified note
     */
    public function update(Request $request, ApplicationNote $note)
    {
        $user = $request->user();

        // Check if user can update this note
        if (!$user->isSuperAdmin() && $note->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'note' => 'sometimes|string',
            'is_private' => 'sometimes|boolean',
        ]);

        $note->update($request->only(['note', 'is_private']));

        $note->load('user');

        return response()->json([
            'message' => 'Note updated successfully',
            'note' => $note,
        ]);
    }

    /**
     * Remove the specified note
     */
    public function destroy(Request $request, ApplicationNote $note)
    {
        $user = $request->user();

        // Check if user can delete this note
        if (!$user->isSuperAdmin() && $note->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json([
            'message' => 'Note deleted successfully',
        ]);
    }
}
