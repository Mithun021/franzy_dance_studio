<?php

namespace App\Http\Controllers;

use App\Models\StudentCourse;
use App\Models\StudentPayment;
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

        $todayUsers = User::where('user_type', 'student')
            ->whereDate('created_at', Carbon::today())
            ->count();

        $monthlyUsers = User::where('user_type', 'student')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $yearlyUsers = User::where('user_type', 'student')
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $totalUsers = User::where('user_type', 'student')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Student Enrollment (student_course table)
        |--------------------------------------------------------------------------
        */

        $todayEnroll = StudentCourse::activeEnroll()
            ->whereDate('admission_date', Carbon::today())
            ->count();

        $monthlyEnroll = StudentCourse::activeEnroll()
            ->whereYear('admission_date', Carbon::now()->year)
            ->whereMonth('admission_date', Carbon::now()->month)
            ->count();

        $yearlyEnroll = StudentCourse::activeEnroll()
            ->whereYear('admission_date', Carbon::now()->year)
            ->count();

        $totalEnroll = StudentCourse::activeEnroll()
            ->count();


        $todayOngoing = StudentCourse::where('status', 'ongoing')
            ->whereDate('admission_date', Carbon::today())
            ->count();

        $monthlyOngoing = StudentCourse::where('status', 'ongoing')
            ->whereYear('admission_date', Carbon::now()->year)
            ->whereMonth('admission_date', Carbon::now()->month)
            ->count();

        $yearlyOngoing = StudentCourse::where('status', 'ongoing')
            ->whereYear('admission_date', Carbon::now()->year)
            ->count();

        $totalOngoing = StudentCourse::where('status', 'ongoing')
            ->count();

        $todayCompleted = StudentCourse::where('status', 'completed')
            ->whereDate('admission_date', Carbon::today())
            ->count();

        $monthlyCompleted = StudentCourse::where('status', 'completed')
            ->whereYear('admission_date', Carbon::now()->year)
            ->whereMonth('admission_date', Carbon::now()->month)
            ->count();

        $yearlyCompleted = StudentCourse::where('status', 'completed')
            ->whereYear('admission_date', Carbon::now()->year)
            ->count();

        $totalCompleted = StudentCourse::where('status', 'completed')
            ->count();


        $dailyPayment = StudentPayment::where('status', 'success')
            ->whereDate('payment_date', Carbon::today())
            ->sum('amount');

        $monthlyPayment = StudentPayment::where('status', 'success')
            ->whereYear('payment_date', Carbon::now()->year)
            ->whereMonth('payment_date', Carbon::now()->month)
            ->sum('amount');

        $yearlyPayment = StudentPayment::where('status', 'success')
            ->whereYear('payment_date', Carbon::now()->year)
            ->sum('amount');

        $totalPayment = StudentPayment::where('status', 'success')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Monthly Payment (Current Year)
        |--------------------------------------------------------------------------
        */

        $monthlyPaymentChart = StudentPayment::select(
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'success')
            ->whereYear('payment_date', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(payment_date)'))
            ->orderBy(DB::raw('MONTH(payment_date)'))
            ->pluck('total', 'month')
            ->toArray();

        $monthlyData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $monthlyPaymentChart[$i] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Yearly Payment
        |--------------------------------------------------------------------------
        */

        $yearlyPaymentChart = StudentPayment::select(
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'success')
            ->groupBy(DB::raw('YEAR(payment_date)'))
            ->orderBy(DB::raw('YEAR(payment_date)'))
            ->get();

        $yearLabels = $yearlyPaymentChart->pluck('year');
        $yearData   = $yearlyPaymentChart->pluck('total');

        return view('backend.index', compact(
            'user',
            'todayUsers',
            'monthlyUsers',
            'yearlyUsers',
            'totalUsers',
            'todayEnroll',
            'monthlyEnroll',
            'yearlyEnroll',
            'totalEnroll',

            'todayOngoing',
            'monthlyOngoing',
            'yearlyOngoing',
            'totalOngoing',

            'todayCompleted',
            'monthlyCompleted',
            'yearlyCompleted',
            'totalCompleted',

            'dailyPayment',
            'monthlyPayment',
            'yearlyPayment',
            'totalPayment',
            'monthlyData',
            'yearLabels',
            'yearData'
        ));
    }

    public function studentIndex()
    {
        $user = Auth::user();

        return view('student.index', compact('user'));
    }
}
