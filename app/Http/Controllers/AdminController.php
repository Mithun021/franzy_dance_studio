<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\SalaryManagement;
use App\Models\StudentCourse;
use App\Models\StudentPayment;
use App\Models\StudioBooking;
use App\Models\StudioCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ------------------ Due Amount Records ------------------
        $currentMonth = Carbon::today()->startOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Due Course Payments
        |--------------------------------------------------------------------------
        | Only:
        | user_type = student
        | is_enroll = 1
        | status = ongoing
        |
        | Admission/enrollment month se current month tak
        | paid months ko skip karke due months nikale jayenge.
        |--------------------------------------------------------------------------
        */

        $enrolledCourses = StudentCourse::query()
            ->with([
                'student:id,user_id,name',
                'course:id,course_name',
                'batch:id,batch_name',
                'monthRecords',
            ])
            ->where('is_enroll', 1)
            ->where('status', 'ongoing')
            ->whereHas('student', function ($query) {
                $query->where('user_type', 'student');
            })
            ->get();

        $dueCoursePayments = collect();

        foreach ($enrolledCourses as $studentCourse) {

            /*
            |--------------------------------------------------------------------------
            | Enrollment Month
            |--------------------------------------------------------------------------
            */

            if (!$studentCourse->admission_date) {
                continue;
            }

            $startMonth = Carbon::parse(
                $studentCourse->admission_date
            )->startOfMonth();

            /*
            |--------------------------------------------------------------------------
            | Don't check future months
            |--------------------------------------------------------------------------
            */

            if ($startMonth->gt($currentMonth)) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Paid Month Keys
            |--------------------------------------------------------------------------
            */

            $paidMonths = $studentCourse->monthRecords
                ->filter(function ($record) {
                    return strtolower(
                        trim((string) $record->status)
                    ) === 'paid';
                })
                ->map(function ($record) {
                    return Carbon::parse(
                        $record->fee_month
                    )
                        ->startOfMonth()
                        ->format('Y-m');
                })
                ->unique()
                ->values();

            /*
            |--------------------------------------------------------------------------
            | Find Due Months
            |--------------------------------------------------------------------------
            */

            $dueMonths = [];

            $totalDueAmount = 0;

            $month = $startMonth->copy();

            while ($month->lte($currentMonth)) {

                $monthKey = $month->format('Y-m');

                /*
                |--------------------------------------------------------------------------
                | Already Paid
                |--------------------------------------------------------------------------
                */

                if ($paidMonths->contains($monthKey)) {
                    $month->addMonth();
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Existing Month Record
                |--------------------------------------------------------------------------
                |
                | Agar month record exist karta hai but paid nahi hai,
                | to actual outstanding amount use karenge.
                |
                */

                $monthRecord = $studentCourse->monthRecords
                    ->first(function ($record) use ($monthKey) {

                        return Carbon::parse(
                            $record->fee_month
                        )
                            ->startOfMonth()
                            ->format('Y-m') === $monthKey;
                    });

                if ($monthRecord) {

                    $outstandingAmount = max(
                        0,
                        (float) $monthRecord->payable_amount
                        - (float) $monthRecord->paid_amount
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Partial/Unpaid record
                    |--------------------------------------------------------------------------
                    */

                    if ($outstandingAmount > 0) {

                        $totalDueAmount += $outstandingAmount;

                        $dueMonths[] = [
                            'key' => $monthKey,
                            'name' => $month->format('F Y'),
                        ];
                    }

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | No Record
                    |--------------------------------------------------------------------------
                    |
                    | Enrollment month se current month tak record hi nahi bana,
                    | iska matlab monthly fee due hai.
                    |
                    */

                    $monthlyFee = (float) $studentCourse->monthly_fee;

                    if ($monthlyFee > 0) {

                        $totalDueAmount += $monthlyFee;

                        $dueMonths[] = [
                            'key' => $monthKey,
                            'name' => $month->format('F Y'),
                        ];
                    }
                }

                $month->addMonth();
            }

            /*
            |--------------------------------------------------------------------------
            | Only students having due payment
            |--------------------------------------------------------------------------
            */

            if (count($dueMonths) > 0) {

                $dueCoursePayments->push([
                    'student_course_id' =>
                        $studentCourse->id,

                    'student_id' =>
                        $studentCourse->student?->id,

                    'user_id' =>
                        $studentCourse->student?->user_id,

                    'student_name' =>
                        $studentCourse->student?->name ?? 'N/A',

                    'course_name' =>
                        $studentCourse->course?->course_name ?? 'N/A',

                    'batch_name' =>
                        $studentCourse->batch?->batch_name ?? 'N/A',

                    'course_amount' =>
                        round($totalDueAmount, 2),

                    'due_months' =>
                        collect($dueMonths)
                            ->pluck('name')
                            ->values()
                            ->toArray(),

                    'due_month_count' =>
                        count($dueMonths),
                ]);
            }
        }

        return view('backend.index',
        compact('user','dueCoursePayments'));
    }

    public function studentIndex()
    {
        $user = Auth::user();

        return view('student.index', compact('user'));
    }
}
