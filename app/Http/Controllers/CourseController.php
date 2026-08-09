<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display Course Listing
     */
    public function index()
    {
        $courses = Course::orderBy('course_name', 'ASC')->get();

        return view('backend.course.index', compact('courses'));
    }

    /**
     * Store New Course
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_name'   => 'required|string|max:100|unique:courses,course_name',
            'duration'      => 'required|integer|min:1',
            'duration_type' => 'required|in:Days,Months,Years',
            'total_classes' => 'required|integer|min:1',
        ]);

        Course::create([
            'course_name'   => $request->course_name,
            'duration'      => $request->duration,
            'duration_type' => $request->duration_type,
            'total_classes' => $request->total_classes,
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Course added successfully.');
    }

    /**
     * Edit Course
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);

        return view('backend.course.edit', compact('course'));
    }

    /**
     * Update Course
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'course_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('courses', 'course_name')->ignore($course->id),
            ],
            'duration'      => 'required|integer|min:1',
            'duration_type' => 'required|in:Days,Months,Years',
            'total_classes' => 'required|integer|min:1',
        ]);

        $course->update([
            'course_name'   => $request->course_name,
            'duration'      => $request->duration,
            'duration_type' => $request->duration_type,
            'total_classes' => $request->total_classes,
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Delete Course
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
