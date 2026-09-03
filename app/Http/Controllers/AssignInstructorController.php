<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Level;
use App\Models\StudentCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignInstructorController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('course_name')->get();

        $levels = Level::orderBy('name')->get();

        $batches = Batch::with(['course', 'level'])
            ->orderBy('batch_name')
            ->get();

        $instructors = User::where('user_type', 'faculty')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('backend.assign_instuructor.index', compact(
            'courses',
            'levels',
            'batches',
            'instructors'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Search Students
    |--------------------------------------------------------------------------
    */

    public function searchStudents(Request $request)
    {
        $validated = $request->validate([
            'course_id' => [
                'required',
                'exists:courses,id',
            ],

            'batch_id' => [
                'nullable',
                'exists:batches,id',
            ],

            'level_id' => [
                'nullable',
                'exists:levels,id',
            ],
        ]);


        $query = StudentCourse::with([
            'student',
            'course',
            'level',
            'batch',
            'instructor',
        ])
        ->whereHas('student', function ($query) {

            $query->where('user_type', 'student');

        })
        ->where('course_id', $validated['course_id']);


        /*
        |--------------------------------------------------------------------------
        | Batch Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['batch_id'])) {

            $query->where(
                'batch_id',
                $validated['batch_id']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Level Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['level_id'])) {

            $query->where(
                'level_id',
                $validated['level_id']
            );
        }


        $studentCourses = $query
            ->orderBy('id', 'desc')
            ->get();


        return response()->json([
            'status' => true,
            'message' => $studentCourses->count() . ' student(s) found.',
            'students' => $studentCourses,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Assign Instructor
    |--------------------------------------------------------------------------
    */

    public function assignInstructor(Request $request)
    {
        $validated = $request->validate([
            'student_course_ids' => [
                'required',
                'array',
                'min:1'
            ],
            'student_course_ids.*' => [
                'integer',
                'exists:student_course,id'
            ],
            'action' => [
                'required',
                'in:apply,revert'
            ],
            'instructor_id' => [
                'nullable',
                'exists:users,id'
            ],
        ]);

        $action = $validated['action'];

        $instructor = null;

        if ($action === 'apply') {

            if (empty($validated['instructor_id'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please select an instructor/faculty.'
                ], 422);
            }

            $instructor = User::where(
                'id',
                $validated['instructor_id']
            )
            ->where('user_type', 'faculty')
            ->where('is_active', 1)
            ->first();

            if (!$instructor) {
                return response()->json([
                    'status' => false,
                    'message' => 'Selected instructor is not a valid active faculty.'
                ], 422);
            }
        }

        DB::beginTransaction();

        try {

            $studentCourses = StudentCourse::whereIn(
                'id',
                $validated['student_course_ids']
            )
            ->whereHas('student', function ($query) {
                $query->where('user_type', 'student');
            });

            if ($action === 'apply') {

                $updated = $studentCourses->update([
                    'instructor_id' => $instructor->id
                ]);

            } else {

                $updated = $studentCourses->update([
                    'instructor_id' => null
                ]);
            }

            DB::commit();

            if ($action === 'apply') {

                return response()->json([
                    'status' => true,
                    'message' =>
                        $updated .
                        ' student(s) assigned to ' .
                        $instructor->name .
                        ' successfully.',
                    'action' => 'apply',
                    'instructor' => [
                        'id' => $instructor->id,
                        'name' => $instructor->name
                    ]
                ]);
            }

            return response()->json([
                'status' => true,
                'message' =>
                    $updated .
                    ' student(s) instructor assignment reverted successfully.',
                'action' => 'revert',
                'instructor' => null
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating instructor.'
            ], 500);
        }
    }
}
