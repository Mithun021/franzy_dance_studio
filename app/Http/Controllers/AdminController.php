<?php

namespace App\Http\Controllers;

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

        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $yearStart = Carbon::now()->startOfYear();
        $yearEnd = Carbon::now()->endOfYear();

        /*
        |--------------------------------------------------------------------------
        | Course + Level Wise Enrollment Statistics
        |--------------------------------------------------------------------------
        */

        $courseLevelStats = StudentCourse::with([
            'course:id,course_name',
            'level:id,name',
        ])
        ->select(
            'course_id',
            'level_id'
        )
        ->selectRaw("
            SUM(
                CASE
                    WHEN is_enroll = 1
                    THEN 1
                    ELSE 0
                END
            ) as total_enroll
        ")
        ->selectRaw("
            SUM(
                CASE
                    WHEN is_enroll = 1
                    AND DATE(admission_date) = ?
                    THEN 1
                    ELSE 0
                END
            ) as today_enroll
        ", [Carbon::today()->toDateString()])
        ->selectRaw("
            SUM(
                CASE
                    WHEN is_enroll = 1
                    AND MONTH(admission_date) = ?
                    AND YEAR(admission_date) = ?
                    THEN 1
                    ELSE 0
                END
            ) as monthly_enroll
        ", [
            Carbon::now()->month,
            Carbon::now()->year
        ])
        ->selectRaw("
            SUM(
                CASE
                    WHEN is_enroll = 1
                    AND YEAR(admission_date) = ?
                    THEN 1
                    ELSE 0
                END
            ) as yearly_enroll
        ", [
            Carbon::now()->year
        ])
        ->selectRaw("
            SUM(
                CASE
                    WHEN is_enroll = 0
                    THEN 1
                    ELSE 0
                END
            ) as enroll_pending
        ")
        ->groupBy(
            'course_id',
            'level_id'
        )
        ->orderBy('course_id')
        ->orderBy('level_id')
        ->get();


        //  Studio Stats -------------------------------------------
         $studioCategories = StudioCategory::with('studios')->get();

        $studioStats = $studioCategories->map(function ($category) use (
            $today,
            $monthStart,
            $monthEnd,
            $yearStart,
            $yearEnd
        ) {

            /*
            |--------------------------------------------------------------------------
            | Studios under this Category
            |--------------------------------------------------------------------------
            */

            $studioIds = $category->studios->pluck('id');

            /*
            |--------------------------------------------------------------------------
            | Confirmed / Completed Booking Base Query
            |--------------------------------------------------------------------------
            */

            $confirmedBookings = StudioBooking::whereIn(
                    'studio_id',
                    $studioIds
                )
                ->whereIn('enquiry_status', [
                    'Confirmed',
                    'Completed'
                ]);


            /*
            |--------------------------------------------------------------------------
            | Today's Booking
            |--------------------------------------------------------------------------
            */

            $todayBooking = (clone $confirmedBookings)
                ->whereDate('booking_from_date', $today)
                ->count();


            /*
            |--------------------------------------------------------------------------
            | Monthly Booking
            |--------------------------------------------------------------------------
            */

            $monthlyBooking = (clone $confirmedBookings)
                ->whereBetween('booking_from_date', [
                    $monthStart,
                    $monthEnd
                ])
                ->count();


            /*
            |--------------------------------------------------------------------------
            | Yearly Booking
            |--------------------------------------------------------------------------
            */

            $yearlyBooking = (clone $confirmedBookings)
                ->whereBetween('booking_from_date', [
                    $yearStart,
                    $yearEnd
                ])
                ->count();


            /*
            |--------------------------------------------------------------------------
            | Total Booking
            |--------------------------------------------------------------------------
            */

            $totalBooking = (clone $confirmedBookings)
                ->count();


            /*
            |--------------------------------------------------------------------------
            | Pending / Other Status
            |--------------------------------------------------------------------------
            */

            $pendingBooking = StudioBooking::whereIn(
                    'studio_id',
                    $studioIds
                )
                ->whereNotIn('enquiry_status', [
                    'Confirmed',
                    'Completed'
                ])
                ->count();


            return [
                'id' => $category->id,
                'name' => $category->name,

                'today' => $todayBooking,

                'monthly' => $monthlyBooking,

                'yearly' => $yearlyBooking,

                'total' => $totalBooking,

                'pending' => $pendingBooking,
            ];
        });


        return view('backend.index', compact(
            'user',
            'courseLevelStats',
            'studioStats',
        ));
    }

    public function studentIndex()
    {
        $user = Auth::user();

        return view('student.index', compact('user'));
    }
}
