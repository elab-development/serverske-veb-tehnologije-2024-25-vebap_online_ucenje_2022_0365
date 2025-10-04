<?php

namespace App\Http\Controllers;

use App\Http\Resources\EntrollmentResource;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollments = Enrollment::all();
        if (is_null($enrollments) || count($enrollments) === 0) {
            return response()->json('No enrollments found!', 404);
        }
        return response()->json([
            'enrollments' => EntrollmentResource::collection($enrollments),
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
            'course_id' => 'required|exists:courses,id',
        ]);

        if (Auth::user()->role !== 'user') {
            return response()->json(['error' => 'Only regular users can enroll in courses'], 403);
        }

        $enrollment = Enrollment::create([
            'course_id' => $validated['course_id'],
            'user_id' => Auth::id(),
            'enrolled_at' => now(),
        ]);

        return response()->json([
            'message' => 'Enrollment created successfully',
            'enrollment' =>  new EntrollmentResource($enrollment)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $enrollment = Enrollment::find($id);
        if (is_null($enrollment)) {
            return response()->json('Enrollment not found', 404);
        }
        return response()->json([
            'enrollment' => new EntrollmentResource($enrollment)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        if (Auth::user()->role !== 'admin' && $enrollment->user_id !== Auth::id()) {
            return response()->json(['error' => 'You can only delete your own enrollment'], 403);
        }

        $enrollment->delete();
        return response()->json(['message' => 'Enrollment canceled']);
    }
}
