<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Holiday;
use App\Models\StudentCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendance = Attendance::with([
                'student',
                'course',
                'batch'
            ])
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('attendance_date', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('attendance_date', '<=', $request->to_date);
            })
            ->when($request->filled('course_id'), function ($query) use ($request) {
                $query->where('course_id', $request->course_id);
            })
            ->orderBy('attendance_date', 'desc')
            ->get();

        $courses = Course::orderBy('course_name')->get();

        return view('backend.attendance.index', compact(
            'attendance',
            'courses'
        ));
    }
    public function create()
    {
        $courses = Course::orderBy('course_name')->get();

        $holiday = Holiday::whereDate(
            'holiday_date',
            Carbon::today()
        )->first();

        return view('backend.attendance.create', compact(
            'courses',
            'holiday'
        ));
    }

    public function fetchBatches(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $batches = Batch::where('course_id', $request->course_id)
            ->whereHas('studentCourses', function ($query) {
                $query->where('is_enroll', 1)
                    ->where('status', 'ongoing');
            })
            ->orderBy('batch_name')
            ->get();

        return response()->json($batches);
    }


    public function fetchStudents(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_id'  => 'required|exists:batches,id',
            'attendance_date' => 'required|date',
        ]);

        $students = StudentCourse::with([
                'student',
                'course',
                'batch',
            ])
            ->where('course_id', $request->course_id)
            ->where('batch_id', $request->batch_id)
            ->where('is_enroll', 1)
            ->where('status', 'ongoing')
            ->whereHas('student', function ($query) {
                $query->where('user_type', 'student');
            })
            ->orderBy('admission_no')
            ->get();

        $holiday = Holiday::whereDate('holiday_date', $request->attendance_date)->first();

        return view('backend.attendance.render-student-list', [
            'students'        => $students,
            'course_id'       => $request->course_id,
            'batch_id'        => $request->batch_id,
            'attendance_date' => $request->attendance_date,
            'holiday'         => $holiday,
        ])->render();
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'attendance_date' => 'required|date',
            'course_id'       => 'required|exists:courses,id',
            'batch_id'        => 'required|exists:batches,id',
            'attendance'      => 'required|array',
        ]);

        foreach ($request->attendance as $userId => $status) {

            Attendance::updateOrCreate(

                [
                    'user_id' => $userId,
                    'course_id' => $request->course_id,
                    'batch_id' => $request->batch_id,
                    'attendance_date' => $request->attendance_date,
                ],

                [
                    'status' => $status,
                    'remarks' => $request->remarks[$userId] ?? null,
                    'created_by' => auth()->id(),
                ]

            );
        }

        return redirect()->route('attendance.create')
            ->with('success', 'Attendance Saved Successfully.');
    }

    public function edit($attendance_date,$course_id,$batch_id)
    {
        $courses = Course::orderBy('course_name')->get();

        $batches = Batch::where('course_id',$course_id)
                        ->orderBy('batch_name')
                        ->get();

        $students = StudentCourse::with([
                'student',
                'course',
                'batch'
            ])
            ->where('course_id',$course_id)
            ->where('batch_id',$batch_id)
            ->where('is_enroll',1)
            ->where('status','ongoing')
            ->orderBy('admission_no')
            ->get();

        $attendance = Attendance::whereDate('attendance_date',$attendance_date)
                        ->where('course_id',$course_id)
                        ->where('batch_id',$batch_id)
                        ->get()
                        ->keyBy('user_id');

        return view('backend.attendance.edit',compact(
            'courses',
            'batches',
            'students',
            'attendance',
            'attendance_date',
            'course_id',
            'batch_id'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'attendance_date'=>'required|date',
            'course_id'=>'required',
            'batch_id'=>'required',
            'attendance'=>'required|array'
        ]);

        foreach($request->attendance as $userId=>$status){

            Attendance::updateOrCreate(

                [
                    'user_id'=>$userId,
                    'course_id'=>$request->course_id,
                    'batch_id'=>$request->batch_id,
                    'attendance_date'=>$request->attendance_date,
                ],

                [
                    'status'=>$status,
                    'remarks'=>$request->remarks[$userId] ?? null,
                    'created_by' => auth()->id(),
                ]

            );

        }

        return redirect()
                ->route('attendance.index')
                ->with('success','Attendance Updated Successfully.');
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->delete();

        return redirect()
            ->route('attendance.index')
            ->with('success', 'Attendance deleted successfully.');
    }

}
