<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Level;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = Batch::with(['course', 'level'])
            ->latest()
            ->get();

        return view('backend.batch.index', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::orderBy('course_name')->get();
        $levels  = Level::orderBy('name')->get();

        return view('backend.batch.create', compact('courses', 'levels'));
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'level_id'    => 'required|exists:levels,id',
            'batch_name'  => 'required|string|max:100',
            'class_days'  => 'required|array|min:1',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'capacity'    => 'required|integer|min:1',
        ]);

        Batch::create([
            'course_id'   => $request->course_id,
            'level_id'    => $request->level_id,
            'batch_name'  => $request->batch_name,
            'class_days'  => $request->class_days,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'capacity'    => $request->capacity,
        ]);

        return redirect()->route('batches.index')
            ->with('success', 'Batch added successfully.');
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit($id)
    {
        $batch = Batch::findOrFail($id);

        $courses = Course::orderBy('course_name')->get();
        $levels  = Level::orderBy('name')->get();

        return view('backend.batch.edit', compact(
            'batch',
            'courses',
            'levels'
        ));
    }

    /**
     * Update the resource.
     */
    public function update(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);

        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'level_id'    => 'required|exists:levels,id',
            'batch_name'  => 'required|string|max:100',
            'class_days'  => 'required|array|min:1',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'capacity'    => 'required|integer|min:1',
        ]);

        $batch->update([
            'course_id'   => $request->course_id,
            'level_id'    => $request->level_id,
            'batch_name'  => $request->batch_name,
            'class_days'  => $request->class_days,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'capacity'    => $request->capacity,
        ]);

        return redirect()->route('batches.index')
            ->with('success', 'Batch updated successfully.');
    }

    /**
     * Delete the resource.
     */
    public function destroy($id)
    {
        $batch = Batch::findOrFail($id);

        $batch->delete();

        return redirect()->route('batches.index')
            ->with('success', 'Batch deleted successfully.');
    }

    public function fetchBatches(Request $request)
    {
        // Validate request
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'level_id'  => 'required|exists:levels,id',
        ]);

        $batches = Batch::with([
                'course',
                'level'
            ])
            ->withCount([
                'studentCourses as enrolled_students_count' => function ($query) {
                    $query->where('is_enroll', 1)
                        ->where('status', 'ongoing');
                }
            ])
            ->where('course_id', $request->course_id)
            ->where('level_id', $request->level_id)
            ->orderBy('batch_name')
            ->get();

        // Format response
        $formattedBatches = $batches->map(function ($batch) {

            $days = '';

            if (!empty($batch->class_days) && is_array($batch->class_days)) {
                $days = implode(', ', $batch->class_days);
            }

            return [
                'id' => $batch->id,
                'batch_name' => $batch->batch_name,
                'start_time' => $batch->start_time
                    ? date('h:i A', strtotime($batch->start_time))
                    : '',
                'end_time' => $batch->end_time
                    ? date('h:i A', strtotime($batch->end_time))
                    : '',
                'class_days' => $batch->class_days,
                'days_text' => $days,
                'capacity' => $batch->capacity,

                'enrolled_students' => $batch->enrolled_students_count,

                'is_full' => (
                    $batch->capacity > 0 &&
                    $batch->enrolled_students_count >= $batch->capacity
                ),

                'display_text' => $batch->batch_name .
                    ' (' .
                    ($batch->start_time ? date('h:i A', strtotime($batch->start_time)) : '') .
                    ' - ' .
                    ($batch->end_time ? date('h:i A', strtotime($batch->end_time)) : '') .
                    ')' .
                    ($days ? ' • ' . $days : ''),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Batches fetched successfully',
            'batches' => $formattedBatches,
        ]);
    }
}
