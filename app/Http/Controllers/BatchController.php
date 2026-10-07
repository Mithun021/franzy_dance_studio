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
            'course_id'  => 'required|exists:courses,id',
            'level_id'   => 'required|exists:levels,id',
            'batch_name' => 'required|string|max:100',
            'capacity'   => 'required|integer|min:1',

            // Monday
            'monday_start_time' => 'nullable|date_format:H:i',
            'monday_end_time'   => 'nullable|date_format:H:i|after:monday_start_time',

            // Tuesday
            'tuesday_start_time' => 'nullable|date_format:H:i',
            'tuesday_end_time'   => 'nullable|date_format:H:i|after:tuesday_start_time',

            // Wednesday
            'wednesday_start_time' => 'nullable|date_format:H:i',
            'wednesday_end_time'   => 'nullable|date_format:H:i|after:wednesday_start_time',

            // Thursday
            'thursday_start_time' => 'nullable|date_format:H:i',
            'thursday_end_time'   => 'nullable|date_format:H:i|after:thursday_start_time',

            // Friday
            'friday_start_time' => 'nullable|date_format:H:i',
            'friday_end_time'   => 'nullable|date_format:H:i|after:friday_start_time',

            // Saturday
            'saturday_start_time' => 'nullable|date_format:H:i',
            'saturday_end_time'   => 'nullable|date_format:H:i|after:saturday_start_time',

            // Sunday
            'sunday_start_time' => 'nullable|date_format:H:i',
            'sunday_end_time'   => 'nullable|date_format:H:i|after:sunday_start_time',
        ]);

        Batch::create([
            'course_id'  => $request->course_id,
            'level_id'   => $request->level_id,
            'batch_name' => $request->batch_name,

            // Monday
            'monday_start_time' => $request->monday_start_time,
            'monday_end_time'   => $request->monday_end_time,

            // Tuesday
            'tuesday_start_time' => $request->tuesday_start_time,
            'tuesday_end_time'   => $request->tuesday_end_time,

            // Wednesday
            'wednesday_start_time' => $request->wednesday_start_time,
            'wednesday_end_time'   => $request->wednesday_end_time,

            // Thursday
            'thursday_start_time' => $request->thursday_start_time,
            'thursday_end_time'   => $request->thursday_end_time,

            // Friday
            'friday_start_time' => $request->friday_start_time,
            'friday_end_time'   => $request->friday_end_time,

            // Saturday
            'saturday_start_time' => $request->saturday_start_time,
            'saturday_end_time'   => $request->saturday_end_time,

            // Sunday
            'sunday_start_time' => $request->sunday_start_time,
            'sunday_end_time'   => $request->sunday_end_time,

            'capacity' => $request->capacity,
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
            'course_id'  => 'required|exists:courses,id',
            'level_id'   => 'required|exists:levels,id',
            'batch_name' => 'required|string|max:100',
            'capacity'   => 'required|integer|min:1',

            // Monday
            'monday_start_time' => 'nullable|date_format:H:i',
            'monday_end_time'   => 'nullable|date_format:H:i|after:monday_start_time',

            // Tuesday
            'tuesday_start_time' => 'nullable|date_format:H:i',
            'tuesday_end_time'   => 'nullable|date_format:H:i|after:tuesday_start_time',

            // Wednesday
            'wednesday_start_time' => 'nullable|date_format:H:i',
            'wednesday_end_time'   => 'nullable|date_format:H:i|after:wednesday_start_time',

            // Thursday
            'thursday_start_time' => 'nullable|date_format:H:i',
            'thursday_end_time'   => 'nullable|date_format:H:i|after:thursday_start_time',

            // Friday
            'friday_start_time' => 'nullable|date_format:H:i',
            'friday_end_time'   => 'nullable|date_format:H:i|after:friday_start_time',

            // Saturday
            'saturday_start_time' => 'nullable|date_format:H:i',
            'saturday_end_time'   => 'nullable|date_format:H:i|after:saturday_start_time',

            // Sunday
            'sunday_start_time' => 'nullable|date_format:H:i',
            'sunday_end_time'   => 'nullable|date_format:H:i|after:sunday_start_time',
        ]);

        $batch->update([
            'course_id'  => $request->course_id,
            'level_id'   => $request->level_id,
            'batch_name' => $request->batch_name,

            // Monday
            'monday_start_time' => $request->monday_start_time,
            'monday_end_time'   => $request->monday_end_time,

            // Tuesday
            'tuesday_start_time' => $request->tuesday_start_time,
            'tuesday_end_time'   => $request->tuesday_end_time,

            // Wednesday
            'wednesday_start_time' => $request->wednesday_start_time,
            'wednesday_end_time'   => $request->wednesday_end_time,

            // Thursday
            'thursday_start_time' => $request->thursday_start_time,
            'thursday_end_time'   => $request->thursday_end_time,

            // Friday
            'friday_start_time' => $request->friday_start_time,
            'friday_end_time'   => $request->friday_end_time,

            // Saturday
            'saturday_start_time' => $request->saturday_start_time,
            'saturday_end_time'   => $request->saturday_end_time,

            // Sunday
            'sunday_start_time' => $request->sunday_start_time,
            'sunday_end_time'   => $request->sunday_end_time,

            'capacity' => $request->capacity,
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


        // Days configuration
        $days = [
            'monday'    => 'Monday',
            'tuesday'   => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday'  => 'Thursday',
            'friday'    => 'Friday',
            'saturday'  => 'Saturday',
            'sunday'    => 'Sunday',
        ];


        // Format response
        $formattedBatches = $batches->map(function ($batch) use ($days) {

            $schedule = [];

            $dayNames = [];


            foreach ($days as $dayKey => $dayName) {

                $start = $batch->{$dayKey . '_start_time'};
                $end   = $batch->{$dayKey . '_end_time'};


                // Only show day if both times are available
                if ($start && $end) {

                    $formattedStart =
                        date('h:i A', strtotime($start));

                    $formattedEnd =
                        date('h:i A', strtotime($end));


                    $schedule[] = [
                        'day' => $dayName,
                        'start_time' => $formattedStart,
                        'end_time' => $formattedEnd,
                    ];


                    $dayNames[] = $dayName;
                }

            }


            /*
            |--------------------------------------------------------------------------
            | Schedule HTML
            |--------------------------------------------------------------------------
            */

            $scheduleHtml = '';

            foreach ($schedule as $item) {

                $scheduleHtml .= '
                    <div class="mb-1">

                        <span class="badge bg-primary">
                            ' . $item['day'] . '
                        </span>

                        <span class="ms-1">
                            ' . $item['start_time'] . '
                            -
                            ' . $item['end_time'] . '
                        </span>

                    </div>
                ';
            }


            /*
            |--------------------------------------------------------------------------
            | Days Text
            |--------------------------------------------------------------------------
            */

            $daysText =
                !empty($dayNames)
                    ? implode(', ', $dayNames)
                    : '';


            /*
            |--------------------------------------------------------------------------
            | Full Status
            |--------------------------------------------------------------------------
            */

            $isFull = (
                $batch->capacity > 0 &&
                $batch->enrolled_students_count >= $batch->capacity
            );


            /*
            |--------------------------------------------------------------------------
            | Display Text
            |--------------------------------------------------------------------------
            */

            $displaySchedule = '';

            foreach ($schedule as $item) {

                $displaySchedule .=
                    $item['day'] .
                    ' ' .
                    $item['start_time'] .
                    ' - ' .
                    $item['end_time'] .
                    ', ';
            }

            $displaySchedule =
                rtrim($displaySchedule, ', ');


            return [

                'id' =>
                    $batch->id,

                'batch_name' =>
                    $batch->batch_name,


                /*
                |--------------------------------------------------------------------------
                | Weekly Schedule
                |--------------------------------------------------------------------------
                */

                'schedule' =>
                    $schedule,

                'schedule_html' =>
                    $scheduleHtml,

                'days_text' =>
                    $daysText,


                /*
                |--------------------------------------------------------------------------
                | Individual Day Timings
                |--------------------------------------------------------------------------
                */

                'monday_start_time' =>
                    $batch->monday_start_time
                        ? date('h:i A', strtotime($batch->monday_start_time))
                        : '',

                'monday_end_time' =>
                    $batch->monday_end_time
                        ? date('h:i A', strtotime($batch->monday_end_time))
                        : '',


                'tuesday_start_time' =>
                    $batch->tuesday_start_time
                        ? date('h:i A', strtotime($batch->tuesday_start_time))
                        : '',

                'tuesday_end_time' =>
                    $batch->tuesday_end_time
                        ? date('h:i A', strtotime($batch->tuesday_end_time))
                        : '',


                'wednesday_start_time' =>
                    $batch->wednesday_start_time
                        ? date('h:i A', strtotime($batch->wednesday_start_time))
                        : '',

                'wednesday_end_time' =>
                    $batch->wednesday_end_time
                        ? date('h:i A', strtotime($batch->wednesday_end_time))
                        : '',


                'thursday_start_time' =>
                    $batch->thursday_start_time
                        ? date('h:i A', strtotime($batch->thursday_start_time))
                        : '',

                'thursday_end_time' =>
                    $batch->thursday_end_time
                        ? date('h:i A', strtotime($batch->thursday_end_time))
                        : '',


                'friday_start_time' =>
                    $batch->friday_start_time
                        ? date('h:i A', strtotime($batch->friday_start_time))
                        : '',

                'friday_end_time' =>
                    $batch->friday_end_time
                        ? date('h:i A', strtotime($batch->friday_end_time))
                        : '',


                'saturday_start_time' =>
                    $batch->saturday_start_time
                        ? date('h:i A', strtotime($batch->saturday_start_time))
                        : '',

                'saturday_end_time' =>
                    $batch->saturday_end_time
                        ? date('h:i A', strtotime($batch->saturday_end_time))
                        : '',


                'sunday_start_time' =>
                    $batch->sunday_start_time
                        ? date('h:i A', strtotime($batch->sunday_start_time))
                        : '',

                'sunday_end_time' =>
                    $batch->sunday_end_time
                        ? date('h:i A', strtotime($batch->sunday_end_time))
                        : '',


                /*
                |--------------------------------------------------------------------------
                | Capacity
                |--------------------------------------------------------------------------
                */

                'capacity' =>
                    $batch->capacity,

                'enrolled_students' =>
                    $batch->enrolled_students_count,


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                'is_full' =>
                    $isFull,


                /*
                |--------------------------------------------------------------------------
                | Display Text
                |--------------------------------------------------------------------------
                */

                'display_text' =>
                    $batch->batch_name .
                    ($displaySchedule
                        ? ' (' . $displaySchedule . ')'
                        : '') .
                    ($daysText
                        ? ' • ' . $daysText
                        : ''),
            ];

        });


        return response()->json([
            'status' => true,

            'message' =>
                'Batches fetched successfully',

            'batches' =>
                $formattedBatches,
        ]);
    }
}
