<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        if (is_null($courses) || count($courses) === 0) {
            return response()->json('No courses found!', 404);
        }
        return response()->json([
            'courses' => CourseResource::collection($courses),
        ]);
    }

    public function searchCourses(Request $request)
    {
        $searchQuery = $request->query('searchQuery');

        if (!$searchQuery) {
            return response()->json(['error' => 'Search query is required'], 400);
        }

        $courses = Course::with('user', 'lessons')
            ->where('title', 'like', '%' . $searchQuery . '%')
            ->orWhere('description', 'like', '%' . $searchQuery . '%')
            ->orWhere('level', 'like', '%' . $searchQuery . '%')
            ->orWhereHas('user', function ($query) use ($searchQuery) {
                $query->where('name', 'like', '%' . $searchQuery . '%');
            })
            ->orWhereHas('lessons', function ($query) use ($searchQuery) {
                $query->where('title', 'like', '%' . $searchQuery . '%')
                    ->orWhere('content', 'like', '%' . $searchQuery . '%');
            })
            ->get();

        return response()->json($courses);
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
        if (Auth::user()->role !== 'instructor') {
            return response()->json(['error' => 'Only instructors can create courses'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:courses',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'level' => 'nullable|string|in:beginner,intermediate,advanced',
        ]);

        $course = Course::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'duration' => $validated['duration'],
            'level' => $validated['level'] ?? 'beginner',
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Course created successfully',
            'course' =>  new CourseResource($course)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $course = Course::find($id);
        if (is_null($course)) {
            return response()->json('Course not found', 404);
        }
        return response()->json([
            'course' => new CourseResource($course)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        if ($course->user_id !== Auth::id()) {
            return response()->json(['error' => 'You can only update your own courses'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'level' => 'nullable|string|in:beginner,intermediate,advanced',
        ]);

        $course->update($validated);

        return response()->json([
            'message' => 'Course updated successfully',
            'course' =>  new CourseResource($course)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        if (Auth::user()->role !== 'admin' && $course->user_id !== Auth::id()) {
            return response()->json(['error' => 'You are not authorized to delete this course'], 403);
        }

        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }
}
