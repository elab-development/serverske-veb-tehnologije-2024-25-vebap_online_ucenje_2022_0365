<?php

namespace App\Http\Controllers;

use App\Http\Resources\LessonResource;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lesson::all();
        if (is_null($lessons) || count($lessons) === 0) {
            return response()->json('No lessons found!', 404);
        }
        return response()->json([
            'lessons' => LessonResource::collection($lessons),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'required|string|max:255',
            'course_id' => 'required|integer|exists:courses,id'
        ]);

        $course = Course::find($validated['course_id']);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        if ($course->user_id !== Auth::id()) {
            return response()->json(['error' => 'Only the course instructor can add lessons'], 403);
        }

        $lesson = Lesson::create([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'video_url' => $validated['video_url'],
            'course_id' => $validated['course_id'],
        ]);

        return response()->json([
            'message' => 'Lesson created successfully',
            'lesson' =>  new LessonResource($lesson)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $lesson = Lesson::find($id);
        if (is_null($lesson)) {
            return response()->json('Lesson not found', 404);
        }
        return response()->json([
            'lesson' => new LessonResource($lesson)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        if ($lesson->course->user_id !== Auth::id()) {
            return response()->json(['error' => 'Only the course instructor can update lessons'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'required|string|max:255',
        ]);

        $lesson->update($validated);

        return response()->json([
            'message' => 'Lesson updated successfully',
            'lesson' =>  new LessonResource($lesson)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        if (Auth::user()->role !== 'admin' && $lesson->course->user_id !== Auth::id()) {
            return response()->json(['error' => 'Only the course instructor can delete lessons'], 403);
        }

        $lesson->delete();
        return response()->json(['message' => 'Lesson deleted successfully']);
    }
}
