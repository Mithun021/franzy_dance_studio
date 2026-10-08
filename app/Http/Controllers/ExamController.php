<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamStudentAmount;
use App\Models\Level;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Exam List + Add Exam Form
     */
    public function index()
    {
        $exams = Exam::latest('id')->get();

        return view('backend.exam.index', compact('exams'));
    }

    /**
     * Store Exam
     */
    public function store(Request $request)
    {
        $request->validate([
            'exam_name' => 'required|string|max:255',
            'status'    => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {

            // If new exam is Active,
            // make all existing exams Inactive
            if ((int) $request->status === 1) {
                Exam::where('status', 1)->update([
                    'status' => 0,
                ]);
            }

            Exam::create([
                'exam_name' => $request->exam_name,
                'status'    => $request->status,
            ]);
        });

        return redirect()
            ->route('exam.index')
            ->with('success', 'Exam added successfully.');
    }

    /**
     * Edit Exam
     */
    public function edit($id)
    {
        $exam = Exam::findOrFail($id);

        return view('backend.exam.edit', compact('exam'));
    }

    /**
     * Update Exam
     */
    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $request->validate([
            'exam_name' => 'required|string|max:255',
            'status'    => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $exam) {

            // If this exam is being made Active,
            // make all other exams Inactive
            if ((int) $request->status === 1) {

                Exam::where('id', '!=', $exam->id)
                    ->where('status', 1)
                    ->update([
                        'status' => 0,
                    ]);
            }

            $exam->update([
                'exam_name' => $request->exam_name,
                'status'    => $request->status,
            ]);
        });

        return redirect()
            ->route('exam.index')
            ->with('success', 'Exam updated successfully.');
    }

    /**
     * Delete Exam
     */
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);

        $exam->delete();

        return redirect()
            ->route('exam.index')
            ->with('success', 'Exam deleted successfully.');
    }

    public function studentAmount(Request $request)
    {
        // Levels for dropdown
        $levels = Level::orderBy('name', 'asc')->get();

        $students = collect();

        /*
        |--------------------------------------------------------------------------
        | Fetch Students
        |--------------------------------------------------------------------------
        */

        if ($request->filled('level_id') || $request->filled('admission_no')) {

            $query = StudentCourse::with([
                'student',
                'level'
            ])
            ->activeEnroll();

            // Level Filter
            if ($request->filled('level_id')) {
                $query->where('level_id', $request->level_id);
            }

            // Admission No Filter
            if ($request->filled('admission_no')) {

                $admissionNo = trim($request->admission_no);

                $query->where('admission_no', 'like', '%' . $admissionNo . '%');
            }

            $students = $query
                ->orderBy('admission_no', 'asc')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Existing Exam Fee
            |--------------------------------------------------------------------------
            */

            $studentIds = $students
                ->pluck('user_id')
                ->unique()
                ->values();

            $levelIds = $students
                ->pluck('level_id')
                ->unique()
                ->values();

            $existingAmounts = ExamStudentAmount::whereIn('student_id', $studentIds)
                ->whereIn('level_id', $levelIds)
                ->get()
                ->keyBy(function ($item) {
                    return $item->student_id . '_' . $item->level_id;
                });

            /*
            |--------------------------------------------------------------------------
            | Attach Existing Exam Fee
            |--------------------------------------------------------------------------
            */

            $students->each(function ($student) use ($existingAmounts) {

                $key = $student->user_id . '_' . $student->level_id;

                $student->existing_exam_fee =
                    $existingAmounts[$key]->exam_fee ?? null;
            });
        }

        return view(
            'backend.exam.student-amount',
            compact(
                'levels',
                'students'
            )
        );
    }


    /**
     * Save Exam Amount For Selected Students
     */
    public function saveStudentAmount(Request $request)
    {
        $request->validate([
            'students' => 'required|array|min:1',
            'students.*.student_id' => 'required|exists:users,id',
            'students.*.level_id'   => 'required|exists:levels,id',
            'students.*.exam_fee'   => 'required|numeric|min:0',
        ]);

        foreach ($request->students as $student) {

            ExamStudentAmount::updateOrCreate(
                [
                    'student_id' => $student['student_id'],
                    'level_id'   => $student['level_id'],
                ],
                [
                    'exam_fee' => $student['exam_fee'],
                ]
            );
        }

        return redirect()
            ->back()
            ->with('success', 'Exam fee saved successfully.');
    }
}
