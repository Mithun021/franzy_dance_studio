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

        $today = Carbon::today();
        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;
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

        /*
        |--------------------------------------------------------------------------
        | Expense Overall Stats
        |--------------------------------------------------------------------------
        */

        $expenseStats = Expense::selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN DATE(expense_date) = ?
                    THEN amount
                    ELSE 0
                END
            ), 0) as today_expense
        ", [
            $today->toDateString()
        ])
        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN MONTH(expense_date) = ?
                    AND YEAR(expense_date) = ?
                    THEN amount
                    ELSE 0
                END
            ), 0) as monthly_expense
        ", [
            $month,
            $year
        ])
        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN YEAR(expense_date) = ?
                    THEN amount
                    ELSE 0
                END
            ), 0) as yearly_expense
        ", [
            $year
        ])
        ->selectRaw("
            COALESCE(SUM(amount), 0) as total_expense
        ")
        ->first();


        /*
        |--------------------------------------------------------------------------
        | Expense Payment Method Wise Stats
        |--------------------------------------------------------------------------
        */

        $expensePaymentStats = Expense::select(
            'payment_method'
        )
        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN DATE(expense_date) = ?
                    THEN amount
                    ELSE 0
                END
            ), 0) as today_expense
        ", [
            $today->toDateString()
        ])
        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN MONTH(expense_date) = ?
                    AND YEAR(expense_date) = ?
                    THEN amount
                    ELSE 0
                END
            ), 0) as monthly_expense
        ", [
            $month,
            $year
        ])
        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN YEAR(expense_date) = ?
                    THEN amount
                    ELSE 0
                END
            ), 0) as yearly_expense
        ", [
            $year
        ])
        ->selectRaw("
            COALESCE(SUM(amount), 0) as total_expense
        ")
        ->whereIn('payment_method', [
            'Cash',
            'UPI',
            'Bank Transfer',
            'Cheque',
            'Card',
        ])
        ->groupBy('payment_method')
        ->orderBy('payment_method')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Salary Overall Stats
        |--------------------------------------------------------------------------
        */

        $salaryStats = SalaryManagement::selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN DATE(salary_month) = ?
                    THEN salary_amount
                    ELSE 0
                END
            ), 0) as today_salary
        ", [
            $today->toDateString()
        ])
        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN MONTH(salary_month) = ?
                    AND YEAR(salary_month) = ?
                    THEN salary_amount
                    ELSE 0
                END
            ), 0) as monthly_salary
        ", [
            $month,
            $year
        ])
        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN YEAR(salary_month) = ?
                    THEN salary_amount
                    ELSE 0
                END
            ), 0) as yearly_salary
        ", [
            $year
        ])
        ->selectRaw("
            COALESCE(SUM(salary_amount), 0) as total_salary
        ")
        ->selectRaw("
            COALESCE(SUM(paid_amount), 0) as total_paid
        ")
        ->selectRaw("
            COALESCE(SUM(due_amount), 0) as total_due
        ")
        ->first();


        /*
        |--------------------------------------------------------------------------
        | Monthly Paid Salary
        |--------------------------------------------------------------------------
        */

        $monthlyPaidSalary = SalaryManagement::whereMonth(
            'salary_month',
            $month
        )
        ->whereYear(
            'salary_month',
            $year
        )
        ->sum('paid_amount');


        /*
        |--------------------------------------------------------------------------
        | Monthly Due Salary
        |--------------------------------------------------------------------------
        */

        $monthlyDueSalary = SalaryManagement::whereMonth(
            'salary_month',
            $month
        )
        ->whereYear(
            'salary_month',
            $year
        )
        ->sum('due_amount');


        /*
        |--------------------------------------------------------------------------
        | Payment Method Wise Salary
        |--------------------------------------------------------------------------
        */

        $salaryPaymentStats = SalaryManagement::select(
            'payment_method'
        )
        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN DATE(salary_month) = ?
                    THEN paid_amount
                    ELSE 0
                END
            ), 0) as today_paid
        ", [
            $today->toDateString()
        ])
        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN MONTH(salary_month) = ?
                    AND YEAR(salary_month) = ?
                    THEN paid_amount
                    ELSE 0
                END
            ), 0) as monthly_paid
        ", [
            $month,
            $year
        ])
        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN YEAR(salary_month) = ?
                    THEN paid_amount
                    ELSE 0
                END
            ), 0) as yearly_paid
        ", [
            $year
        ])
        ->selectRaw("
            COALESCE(SUM(paid_amount), 0) as total_paid
        ")
        ->whereIn('payment_method', [
            'Cash',
            'UPI',
            'Bank Transfer',
            'Cheque',
            'Card',
        ])
        ->groupBy('payment_method')
        ->orderBy('payment_method')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Employee Wise Salary
        |--------------------------------------------------------------------------
        */

        $employeeSalaryStats = SalaryManagement::with([
            'employee:id,name'
        ])
        ->select(
            'user_id'
        )
        ->selectRaw('COUNT(*) as salary_records')
        ->selectRaw('COALESCE(SUM(salary_amount),0) as total_salary')
        ->selectRaw('COALESCE(SUM(paid_amount),0) as total_paid')
        ->selectRaw('COALESCE(SUM(due_amount),0) as total_due')
        ->groupBy('user_id')
        ->orderBy('user_id')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Billing Collection Stats
        |--------------------------------------------------------------------------
        |
        | Only SUCCESS payments are counted.
        |
        */

        $courseBillingStats = StudentPayment::where('status', 'success')

            ->selectRaw("
                COALESCE(SUM(
                    CASE
                        WHEN DATE(payment_date) = ?
                        THEN total_amount
                        ELSE 0
                    END
                ), 0) as today_collection
            ", [
                $today->toDateString()
            ])

            ->selectRaw("
                COALESCE(SUM(
                    CASE
                        WHEN MONTH(payment_date) = ?
                        AND YEAR(payment_date) = ?
                        THEN total_amount
                        ELSE 0
                    END
                ), 0) as monthly_collection
            ", [
                $month,
                $year
            ])

            ->selectRaw("
                COALESCE(SUM(
                    CASE
                        WHEN YEAR(payment_date) = ?
                        THEN total_amount
                        ELSE 0
                    END
                ), 0) as yearly_collection
            ", [
                $year
            ])

            ->selectRaw("
                COALESCE(SUM(total_amount), 0) as total_collection
            ")

            ->first();


        /*
        |--------------------------------------------------------------------------
        | Course Billing Student Stats
        |--------------------------------------------------------------------------
        */

        $courseStudentStats = StudentCourse::selectRaw("
            COUNT(*) as total_students
        ")

        ->selectRaw("
            SUM(
                CASE
                    WHEN is_enroll = 1
                    THEN 1
                    ELSE 0
                END
            ) as enrolled_students
        ")

        ->selectRaw("
            SUM(
                CASE
                    WHEN is_enroll = 0
                    THEN 1
                    ELSE 0
                END
            ) as pending_enrollment
        ")

        ->first();


        /*
        |--------------------------------------------------------------------------
        | Total Due
        |--------------------------------------------------------------------------
        |
        | Grand Total - Successful Payment Amount
        |
        */

        $totalCourseBilling = StudentCourse::sum('grand_total');

        $totalSuccessfulCollection = StudentPayment::where(
            'status',
            'success'
        )->sum('total_amount');

        $totalCourseDue = max(
            0,
            $totalCourseBilling - $totalSuccessfulCollection
        );


        /*
        |--------------------------------------------------------------------------
        | Payment Method Wise Collection
        |--------------------------------------------------------------------------
        */

        $coursePaymentMethodStats = StudentPayment::where(
            'status',
            'success'
        )

        ->select('payment_mode')

        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN DATE(payment_date) = ?
                    THEN total_amount
                    ELSE 0
                END
            ), 0) as today_collection
        ", [
            $today->toDateString()
        ])

        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN MONTH(payment_date) = ?
                    AND YEAR(payment_date) = ?
                    THEN total_amount
                    ELSE 0
                END
            ), 0) as monthly_collection
        ", [
            $month,
            $year
        ])

        ->selectRaw("
            COALESCE(SUM(
                CASE
                    WHEN YEAR(payment_date) = ?
                    THEN total_amount
                    ELSE 0
                END
            ), 0) as yearly_collection
        ", [
            $year
        ])

        ->selectRaw("
            COALESCE(SUM(total_amount), 0) as total_collection
        ")

        ->whereIn('payment_mode', [
            'Cash',
            'UPI',
            'Card',
            'Bank Transfer',
            'Cheque',
        ])

        ->groupBy('payment_mode')

        ->orderBy('payment_mode')

        ->get();


        /*
        |--------------------------------------------------------------------------
        | Payment Type Wise Collection
        |--------------------------------------------------------------------------
        */

        $coursePaymentTypeStats = StudentPayment::where(
            'status',
            'success'
        )

        ->select('payment_type')

        ->selectRaw("
            COALESCE(SUM(total_amount), 0) as total_collection
        ")

        ->selectRaw("
            COUNT(*) as payment_count
        ")

        ->whereIn('payment_type', [
            'full',
            'half',
            'next_month',
        ])

        ->groupBy('payment_type')

        ->get();



        /*
        |--------------------------------------------------------------------------
        | 1. Current Year - Monthly Payment Collection
        |--------------------------------------------------------------------------
        */

        $currentYear = Carbon::now()->year;

        $monthlyPayments = StudentPayment::where('status', 'success')
            ->whereYear('payment_date', $currentYear)
            ->selectRaw('MONTH(payment_date) as month')
            ->selectRaw('SUM(total_amount) as total')
            ->groupBy(DB::raw('MONTH(payment_date)'))
            ->orderBy('month')
            ->get();

        $monthlyPaymentData = [];

        for ($month = 1; $month <= 12; $month++) {

            $payment = $monthlyPayments->firstWhere('month', $month);

            $monthlyPaymentData[] = [
                'month' => Carbon::create()
                    ->month($month)
                    ->format('M'),

                'total' => $payment
                    ? (float) $payment->total
                    : 0,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Year Wise Payment Collection
        |--------------------------------------------------------------------------
        */

        $yearlyPayments = StudentPayment::where('status', 'success')
            ->selectRaw('YEAR(payment_date) as year')
            ->selectRaw('SUM(total_amount) as total')
            ->groupBy(DB::raw('YEAR(payment_date)'))
            ->orderBy('year')
            ->get();

        $yearlyPaymentData = $yearlyPayments->map(function ($payment) {

            return [
                'year' => (string) $payment->year,
                'total' => (float) $payment->total,
            ];

        })->values();


        /*
        |--------------------------------------------------------------------------
        | 3. Course Wise Payment Collection
        |--------------------------------------------------------------------------
        */

        $coursePayments = StudentPayment::where('student_payments.status', 'success')
            ->join(
                'student_course',
                'student_payments.student_course_id',
                '=',
                'student_course.id'
            )
            ->join(
                'courses',
                'student_course.course_id',
                '=',
                'courses.id'
            )
            ->select(
                'courses.course_name'
            )
            ->selectRaw('SUM(student_payments.total_amount) as total')
            ->groupBy(
                'courses.id',
                'courses.course_name'
            )
            ->orderByDesc('total')
            ->get();

        $coursePaymentData = $coursePayments->map(function ($payment) {

            return [
                'course' => $payment->course_name,
                'total' => (float) $payment->total,
            ];

        })->values();


        return view('backend.index', compact(
            'user',
            'courseLevelStats',
            'studioStats',
            'expenseStats',
            'expensePaymentStats',
            'salaryStats',
            'monthlyPaidSalary',
            'monthlyDueSalary',
            'salaryPaymentStats',
            'employeeSalaryStats',
            'courseBillingStats',

            'courseStudentStats',

            'totalCourseDue',

            'coursePaymentMethodStats',

            'coursePaymentTypeStats',
            'monthlyPaymentData',
            'yearlyPaymentData',
            'coursePaymentData'
        ));
    }

    public function studentIndex()
    {
        $user = Auth::user();

        return view('student.index', compact('user'));
    }
}
