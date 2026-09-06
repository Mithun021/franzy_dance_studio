<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Course;
use App\Models\CourseMonthRecord;
use App\Models\CoursePaymentRecord;
use App\Models\LateFine;
use App\Models\LateFineRecord;
use App\Models\StudentCourse;
use App\Models\StudentPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. GET STUDENT COURSES
        |--------------------------------------------------------------------------
        */

        $query = StudentCourse::query()
            ->with([
                'student',
                'course',
                'batch',

                'monthRecords' => function ($query) {

                    $query
                        ->where('status', 'paid')
                        ->orderBy('fee_month', 'asc');

                },

                /*
                |--------------------------------------------------------------------------
                | Latest Payment
                |--------------------------------------------------------------------------
                */

                'paymentRecords' => function ($query) {

                    $query
                        ->where('status', 'success')
                        ->latest('payment_date')
                        ->latest('id')
                        ->limit(1);

                },
            ])

            ->withSum(
                'paymentRecords as total_paid_amount',
                'amount'
            )

            ->withCount(
                'paymentRecords as payment_count'
            );


        /*
        |--------------------------------------------------------------------------
        | 2. SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Student Search
                |--------------------------------------------------------------------------
                */

                $q->whereHas('student', function ($studentQuery) use ($search) {

                    $studentQuery
                        ->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'admission_no',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'phone',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        );

                });


                /*
                |--------------------------------------------------------------------------
                | Course Search
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas('course', function ($courseQuery) use ($search) {

                    $courseQuery->where(
                        'course_name',
                        'like',
                        "%{$search}%"
                    );

                });


                /*
                |--------------------------------------------------------------------------
                | Batch Search
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas('batch', function ($batchQuery) use ($search) {

                    $batchQuery->where(
                        'batch_name',
                        'like',
                        "%{$search}%"
                    );

                });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | 3. GET RECORDS
        |--------------------------------------------------------------------------
        */

        $studentCourses = $query
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 4. PREPARE PAID MONTH INFORMATION
        |--------------------------------------------------------------------------
        */

        foreach ($studentCourses as $studentCourse) {

            $paidMonths = $studentCourse->monthRecords
                ->filter(function ($monthRecord) {

                    $payable = round(
                        (float) $monthRecord->payable_amount,
                        2
                    );

                    $paid = round(
                        (float) $monthRecord->paid_amount,
                        2
                    );

                    return $payable > 0 && $paid >= $payable;

                })
                ->sortBy('fee_month')
                ->values();


            /*
            |--------------------------------------------------------------------------
            | No Paid Month
            |--------------------------------------------------------------------------
            */

            if ($paidMonths->isEmpty()) {

                $studentCourse->paid_month_label =
                    'No payment yet';

                $studentCourse->paid_month_count =
                    0;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | First Paid Month
            |--------------------------------------------------------------------------
            */

            $firstPaidMonth = Carbon::parse(
                $paidMonths->first()->fee_month
            )->startOfMonth();


            /*
            |--------------------------------------------------------------------------
            | Last Paid Month
            |--------------------------------------------------------------------------
            */

            $lastPaidMonth = Carbon::parse(
                $paidMonths->last()->fee_month
            )->startOfMonth();


            /*
            |--------------------------------------------------------------------------
            | Count Paid Months
            |--------------------------------------------------------------------------
            */

            $paidMonthCount =
                $paidMonths->count();


            /*
            |--------------------------------------------------------------------------
            | Check Continuous Months
            |--------------------------------------------------------------------------
            */

            $isContinuous = true;

            $expectedMonth =
                $firstPaidMonth->copy();


            foreach ($paidMonths as $monthRecord) {

                $currentMonth = Carbon::parse(
                    $monthRecord->fee_month
                )->startOfMonth();


                if (
                    !$currentMonth->equalTo(
                        $expectedMonth
                    )
                ) {

                    $isContinuous = false;

                    break;
                }


                $expectedMonth->addMonth();
            }


            /*
            |--------------------------------------------------------------------------
            | Generate Display Label
            |--------------------------------------------------------------------------
            */

            if ($isContinuous) {

                if (
                    $firstPaidMonth->equalTo(
                        $lastPaidMonth
                    )
                ) {

                    $paidMonthLabel =
                        $firstPaidMonth->format('M Y');

                } else {

                    $paidMonthLabel =
                        $firstPaidMonth->format('M Y')
                        . ' - '
                        . $lastPaidMonth->format('M Y');
                }

            } else {

                $paidMonthLabel = $paidMonths
                    ->map(function ($monthRecord) {

                        return Carbon::parse(
                            $monthRecord->fee_month
                        )->format('M Y');

                    })
                    ->implode(', ');
            }


            /*
            |--------------------------------------------------------------------------
            | Attach Data
            |--------------------------------------------------------------------------
            */

            $studentCourse->paid_month_label =
                $paidMonthLabel;

            $studentCourse->paid_month_count =
                $paidMonthCount;
        }


        /*
        |--------------------------------------------------------------------------
        | 5. RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.billing.index',
            compact('studentCourses')
        );
    }
    // public function index(Request $request)
    // {
    //     /*
    //     |--------------------------------------------------------------------------
    //     | 1. GET STUDENT COURSES
    //     |--------------------------------------------------------------------------
    //     */

    //     $query = StudentCourse::query()
    //         ->with([
    //             'student',
    //             'course',
    //             'batch',
    //             'monthRecords' => function ($query) {
    //                 $query
    //                     ->where('status', 'paid')
    //                     ->orderBy('fee_month', 'asc');
    //             },
    //         ])
    //         ->withSum(
    //             'paymentRecords as total_paid_amount',
    //             'amount'
    //         )
    //         ->withCount(
    //             'paymentRecords as payment_count'
    //         );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 2. SEARCH
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('search')) {

    //         $search = trim($request->search);

    //         $query->where(function ($q) use ($search) {

    //             /*
    //             | Student Search
    //             */

    //             $q->whereHas('student', function ($studentQuery) use ($search) {

    //                 $studentQuery
    //                     ->where('name', 'like', "%{$search}%")
    //                     ->orWhere(
    //                         'admission_no',
    //                         'like',
    //                         "%{$search}%"
    //                     )
    //                     ->orWhere(
    //                         'phone',
    //                         'like',
    //                         "%{$search}%"
    //                     )
    //                     ->orWhere(
    //                         'email',
    //                         'like',
    //                         "%{$search}%"
    //                     );
    //             });


    //             /*
    //             | Course Search
    //             */

    //             $q->orWhereHas('course', function ($courseQuery) use ($search) {

    //                 $courseQuery->where(
    //                     'course_name',
    //                     'like',
    //                     "%{$search}%"
    //                 );
    //             });


    //             /*
    //             | Batch Search
    //             */

    //             $q->orWhereHas('batch', function ($batchQuery) use ($search) {

    //                 $batchQuery->where(
    //                     'batch_name',
    //                     'like',
    //                     "%{$search}%"
    //                 );
    //             });

    //         });
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 3. GET RECORDS
    //     |--------------------------------------------------------------------------
    //     |
    //     | DataTables is already being used on the page.
    //     | Therefore get() is better than Laravel paginate()
    //     | if DataTables handles pagination/search.
    //     |
    //     */

    //     $studentCourses = $query
    //         ->latest('id')
    //         ->get();


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 4. PREPARE PAID MONTH INFORMATION
    //     |--------------------------------------------------------------------------
    //     |
    //     | Example:
    //     |
    //     | Jun 2026 - Sep 2026
    //     | 4 Months
    //     |
    //     */

    //     foreach ($studentCourses as $studentCourse) {

    //         $paidMonths = $studentCourse->monthRecords
    //             ->filter(function ($monthRecord) {

    //                 $payable = round(
    //                     (float) $monthRecord->payable_amount,
    //                     2
    //                 );

    //                 $paid = round(
    //                     (float) $monthRecord->paid_amount,
    //                     2
    //                 );

    //                 /*
    //                 | Fully paid month
    //                 */

    //                 return $payable > 0 && $paid >= $payable;
    //             })
    //             ->sortBy('fee_month')
    //             ->values();


    //         /*
    //         |--------------------------------------------------------------------------
    //         | No Paid Month
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($paidMonths->isEmpty()) {

    //             $studentCourse->paid_month_label =
    //                 'No payment yet';

    //             $studentCourse->paid_month_count =
    //                 0;

    //             continue;
    //         }


    //         /*
    //         |--------------------------------------------------------------------------
    //         | First Paid Month
    //         |--------------------------------------------------------------------------
    //         */

    //         $firstPaidMonth = Carbon::parse(
    //             $paidMonths->first()->fee_month
    //         )->startOfMonth();


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Last Paid Month
    //         |--------------------------------------------------------------------------
    //         */

    //         $lastPaidMonth = Carbon::parse(
    //             $paidMonths->last()->fee_month
    //         )->startOfMonth();


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Count Paid Months
    //         |--------------------------------------------------------------------------
    //         */

    //         $paidMonthCount = $paidMonths->count();


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Check Continuous Months
    //         |--------------------------------------------------------------------------
    //         |
    //         | Example:
    //         |
    //         | Jun, Jul, Aug, Sep
    //         |
    //         | => Jun 2026 - Sep 2026
    //         |
    //         */

    //         $isContinuous = true;

    //         $expectedMonth = $firstPaidMonth->copy();


    //         foreach ($paidMonths as $monthRecord) {

    //             $currentMonth = Carbon::parse(
    //                 $monthRecord->fee_month
    //             )->startOfMonth();


    //             if (!$currentMonth->equalTo($expectedMonth)) {

    //                 $isContinuous = false;

    //                 break;
    //             }


    //             $expectedMonth->addMonth();
    //         }


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Generate Display Label
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($isContinuous) {

    //             if ($firstPaidMonth->equalTo($lastPaidMonth)) {

    //                 $paidMonthLabel =
    //                     $firstPaidMonth->format('M Y');

    //             } else {

    //                 $paidMonthLabel =
    //                     $firstPaidMonth->format('M Y')
    //                     . ' - '
    //                     . $lastPaidMonth->format('M Y');
    //             }

    //         } else {

    //             /*
    //             | Non-continuous months
    //             |
    //             | Example:
    //             | Jun 2026, Aug 2026, Sep 2026
    //             */

    //             $paidMonthLabel = $paidMonths
    //                 ->map(function ($monthRecord) {

    //                     return Carbon::parse(
    //                         $monthRecord->fee_month
    //                     )->format('M Y');

    //                 })
    //                 ->implode(', ');
    //         }


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Attach Data To Model
    //         |--------------------------------------------------------------------------
    //         */

    //         $studentCourse->paid_month_label =
    //             $paidMonthLabel;

    //         $studentCourse->paid_month_count =
    //             $paidMonthCount;
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 5. RETURN VIEW
    //     |--------------------------------------------------------------------------
    //     */

    //     return view(
    //         'backend.billing.index',
    //         compact('studentCourses')
    //     );
    // }

    public function paymentDetails($studentCourseId)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. LOAD STUDENT COURSE
        |--------------------------------------------------------------------------
        */

        $studentCourse = StudentCourse::query()
            ->with([
                'student',
                'course',
                'level',
                'category',
                'batch',
            ])
            ->findOrFail($studentCourseId);


        /*
        |--------------------------------------------------------------------------
        | 2. PAYMENT HISTORY
        |--------------------------------------------------------------------------
        */

        $payments = CoursePaymentRecord::query()
            ->where(
                'student_course_id',
                $studentCourse->id
            )
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 3. MONTHLY BILLING RECORDS
        |--------------------------------------------------------------------------
        */

        $monthRecords = CourseMonthRecord::query()
            ->where(
                'student_course_id',
                $studentCourse->id
            )
            ->orderBy('fee_month', 'asc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 4. LATE FINES / PENALTIES
        |--------------------------------------------------------------------------
        */

        $lateFines = LateFineRecord::query()
            ->where(
                'student_course_id',
                $studentCourse->id
            )
            ->orderByDesc('fine_date')
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 5. TOTAL PAYMENT
        |--------------------------------------------------------------------------
        */

        $totalPaid = round(
            (float) $payments->sum('amount'),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 6. TOTAL COURSE FEE
        |--------------------------------------------------------------------------
        */

        $totalCourseFee = round(
            (float) $monthRecords->sum('payable_amount'),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 7. TOTAL LATE FINE / PENALTY
        |--------------------------------------------------------------------------
        */

        $totalFine = round(
            (float) $lateFines->sum('fine_amount'),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 8. REGISTRATION + ADMISSION
        |--------------------------------------------------------------------------
        |
        | These are charged only on first billing.
        |
        */

        $registrationFee = round(
            (float) ($studentCourse->registration_fee ?? 0),
            2
        );

        $admissionFee = round(
            (float) ($studentCourse->admission_fee ?? 0),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 9. FIRST PAYMENT CHECK
        |--------------------------------------------------------------------------
        */

        $hasPayment = $payments->isNotEmpty();


        $totalRegistrationAdmission = $hasPayment
            ? $registrationFee + $admissionFee
            : 0;


        /*
        |--------------------------------------------------------------------------
        | 10. TOTAL BILLING
        |--------------------------------------------------------------------------
        */

        $totalBilling = round(
            $totalCourseFee
            + $totalRegistrationAdmission
            + $totalFine,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 11. DUE
        |--------------------------------------------------------------------------
        */

        $totalDue = max(
            0,
            round(
                $totalBilling - $totalPaid,
                2
            )
        );


        /*
        |--------------------------------------------------------------------------
        | 12. PAID MONTHS
        |--------------------------------------------------------------------------
        */

        $paidMonths = $monthRecords
            ->filter(function ($record) {

                return
                    (float) $record->payable_amount > 0
                    &&
                    (float) $record->paid_amount
                        >=
                    (float) $record->payable_amount;
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | 13. PAYMENT MONTH RANGE
        |--------------------------------------------------------------------------
        */

        $paidMonthLabel = 'No payment yet';
        $paidMonthCount = $paidMonths->count();

        if ($paidMonths->isNotEmpty()) {

            $firstMonth = Carbon::parse(
                $paidMonths->first()->fee_month
            )->startOfMonth();

            $lastMonth = Carbon::parse(
                $paidMonths->last()->fee_month
            )->startOfMonth();


            if ($firstMonth->equalTo($lastMonth)) {

                $paidMonthLabel =
                    $firstMonth->format('M Y');

            } else {

                $paidMonthLabel =
                    $firstMonth->format('M Y')
                    . ' - '
                    . $lastMonth->format('M Y');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 14. RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.billing.payments-detail',
            compact(
                'studentCourse',
                'payments',
                'monthRecords',
                'lateFines',
                'totalPaid',
                'totalCourseFee',
                'totalFine',
                'registrationFee',
                'admissionFee',
                'totalBilling',
                'totalDue',
                'paidMonthLabel',
                'paidMonthCount'
            )
        );
    }

    public function create()
    {
        $students = User::where('user_type', 'student')
            ->where('is_active', 'yes')
            ->orderBy('name')
            ->get();

        return view(
            'backend.billing.create',
            compact('students')
        );
    }

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. VALIDATE REQUEST
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'student_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'student_course_id' => [
                'required',
                'integer',
                'exists:student_course,id',
            ],

            'billing_from' => [
                'required',
                'date',
            ],

            'billing_to' => [
                'required',
                'date',
                'after_or_equal:billing_from',
            ],

            /*
            |--------------------------------------------------------------------------
            | Payment Entries
            |--------------------------------------------------------------------------
            */

            'payment_date' => [
                'required',
                'array',
                'min:1',
            ],

            'payment_date.*' => [
                'required',
                'date',
            ],

            'payment_mode' => [
                'required',
                'array',
                'min:1',
            ],

            'payment_mode.*' => [
                'required',
                'in:Cash,UPI,Card,Bank Transfer,Cheque',
            ],

            'amount' => [
                'required',
                'array',
                'min:1',
            ],

            'amount.*' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'transaction_id' => [
                'nullable',
                'array',
            ],

            'transaction_id.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'array',
            ],

            'remarks.*' => [
                'nullable',
                'string',
                'max:1000',
            ],

            /*
            |--------------------------------------------------------------------------
            | Displayed Billing Values
            |--------------------------------------------------------------------------
            |
            | These are NOT trusted blindly.
            | They are only used as submitted calculation information.
            |
            */

            'late_fine' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'course_penalty_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fine_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'fine_current_month' => [
                'nullable',
                'string',
                'max:50',
            ],

            'total_course_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_billing_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 2. LOAD STUDENT COURSE
        |--------------------------------------------------------------------------
        */

        $studentCourse = StudentCourse::query()
            ->with([
                'student',
                'course',
                'level',
                'category',
                'batch',
            ])
            ->where('id', $validated['student_course_id'])
            ->where('user_id', $validated['student_id'])
            ->first();

        if (!$studentCourse) {

            throw ValidationException::withMessages([
                'student_course_id' =>
                    'The selected course does not belong to the selected student.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 3. DATE OBJECTS
        |--------------------------------------------------------------------------
        */

        $billingFrom = Carbon::parse(
            $validated['billing_from']
        )->startOfDay();

        $billingTo = Carbon::parse(
            $validated['billing_to']
        )->startOfDay();


        if ($billingTo->lt($billingFrom)) {

            throw ValidationException::withMessages([
                'billing_to' =>
                    'Billing To date cannot be before Billing From date.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 4. PAYMENT DATA
        |--------------------------------------------------------------------------
        */

        $paymentDates = $validated['payment_date'] ?? [];
        $paymentModes = $validated['payment_mode'] ?? [];
        $paymentAmounts = $validated['amount'] ?? [];
        $transactionIds = $validated['transaction_id'] ?? [];
        $paymentRemarks = $validated['remarks'] ?? [];


        /*
        |--------------------------------------------------------------------------
        | 5. NORMALIZE PAYMENT ROWS
        |--------------------------------------------------------------------------
        */

        $payments = [];

        foreach ($paymentAmounts as $index => $amount) {

            $amount = round((float) $amount, 2);

            if ($amount <= 0) {
                continue;
            }

            $mode = $paymentModes[$index] ?? null;

            $transactionId =
                isset($transactionIds[$index])
                    ? trim((string) $transactionIds[$index])
                    : null;

            $remark =
                isset($paymentRemarks[$index])
                    ? trim((string) $paymentRemarks[$index])
                    : null;


            /*
            |--------------------------------------------------------------------------
            | Cash does not require transaction ID
            |--------------------------------------------------------------------------
            */

            if ($mode !== 'Cash' && empty($transactionId)) {

                throw ValidationException::withMessages([
                    "transaction_id.$index" =>
                        "Transaction / Reference No is required for {$mode} payment.",
                ]);
            }


            $payments[] = [

                'payment_date' =>
                    $paymentDates[$index] ?? now()->toDateString(),

                'payment_mode' =>
                    $mode,

                'amount' =>
                    $amount,

                'transaction_id' =>
                    $transactionId ?: null,

                'remarks' =>
                    $remark ?: null,
            ];
        }


        if (empty($payments)) {

            throw ValidationException::withMessages([
                'amount' =>
                    'At least one valid payment entry is required.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 6. TOTAL PAYMENT
        |--------------------------------------------------------------------------
        */

        $totalPayment = round(
            collect($payments)->sum('amount'),
            2
        );


        if ($totalPayment <= 0) {

            throw ValidationException::withMessages([
                'amount' =>
                    'Total payment amount must be greater than zero.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 7. GENERATE BILLING MONTHS
        |--------------------------------------------------------------------------
        */

        $billingMonths = [];

        $monthCursor = $billingFrom->copy()->startOfMonth();

        $lastMonth = $billingTo->copy()->startOfMonth();

        while ($monthCursor->lte($lastMonth)) {

            $billingMonths[] = $monthCursor->copy();

            $monthCursor->addMonth();
        }


        if (empty($billingMonths)) {

            throw ValidationException::withMessages([
                'billing_from' =>
                    'Unable to generate billing months.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 8. CHECK EXISTING MONTH RECORDS
        |--------------------------------------------------------------------------
        |
        | We do not create duplicate fully-paid month records.
        |
        */

        $existingMonths = CourseMonthRecord::query()
            ->where('student_course_id', $studentCourse->id)
            ->whereIn(
                'fee_month',
                collect($billingMonths)
                    ->map(fn ($month) => $month->format('Y-m-01'))
                    ->values()
                    ->all()
            )
            ->get()
            ->keyBy(
                fn ($record) =>
                    Carbon::parse($record->fee_month)
                        ->format('Y-m')
            );


        /*
        |--------------------------------------------------------------------------
        | 9. FIRST PAYMENT DETECTION
        |--------------------------------------------------------------------------
        */

        $hasPreviousPayment = CoursePaymentRecord::query()
            ->where(
                'student_course_id',
                $studentCourse->id
            )
            ->exists();


        $isFirstPayment = !$hasPreviousPayment;


        /*
        |--------------------------------------------------------------------------
        | 10. FIRST PAYMENT RULE
        |--------------------------------------------------------------------------
        |
        | 1 - 15  => 100%
        | 16 - 25 => 50%
        | 26-End  => 100%, but next month
        |
        */

        $firstPaymentDate = Carbon::parse(
            $payments[0]['payment_date']
        )->startOfDay();

        $firstPaymentMultiplier = 1;

        $firstPaymentRule = null;

        $actualBillingMonths = $billingMonths;


        if ($isFirstPayment) {

            $day = $firstPaymentDate->day;


            /*
            |--------------------------------------------------------------------------
            | 1 - 15
            |--------------------------------------------------------------------------
            */

            if ($day >= 1 && $day <= 15) {

                $firstPaymentMultiplier = 1;

                $firstPaymentRule =
                    '1-15: Full Course Fee';


            /*
            |--------------------------------------------------------------------------
            | 16 - 25
            |--------------------------------------------------------------------------
            */

            } elseif ($day >= 16 && $day <= 25) {

                $firstPaymentMultiplier = 0.50;

                $firstPaymentRule =
                    '16-25: 50% Course Fee';


            /*
            |--------------------------------------------------------------------------
            | 26 - Month End
            |--------------------------------------------------------------------------
            */

            } else {

                $firstPaymentMultiplier = 1;

                $firstPaymentRule =
                    '26-End: Full Course Fee, Next Month Billing';


                /*
                | Move billing to next month.
                */

                $nextMonth =
                    $firstPaymentDate
                        ->copy()
                        ->addMonth()
                        ->startOfMonth();


                $actualBillingMonths = [
                    $nextMonth,
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 11. CALCULATE COURSE FEE
        |--------------------------------------------------------------------------
        */

        $monthlyFee = round(
            (float) $studentCourse->monthly_fee,
            2
        );


        $registrationFee = round(
            (float) ($studentCourse->registration_fee ?? 0),
            2
        );


        $admissionFee = round(
            (float) ($studentCourse->admission_fee ?? 0),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | First payment:
        |
        | Only FIRST monthly record gets the first-payment multiplier.
        |
        | Example:
        |
        | Monthly Fee = 1000
        |
        | First payment 20th
        |
        | Month 1 = 500
        |
        |--------------------------------------------------------------------------
        */

        $monthPayables = [];

        foreach ($actualBillingMonths as $index => $month) {

            $fee = $monthlyFee;


            if ($isFirstPayment && $index === 0) {

                $fee =
                    round(
                        $monthlyFee *
                        $firstPaymentMultiplier,
                        2
                    );
            }


            $monthPayables[] = [

                'month' =>
                    $month->copy(),

                'monthly_fee' =>
                    $monthlyFee,

                'payable_amount' =>
                    $fee,

                'payment_rule' =>
                    $isFirstPayment
                        ? $firstPaymentRule
                        : 'Regular Monthly Fee',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | 12. TOTAL COURSE FEE
        |--------------------------------------------------------------------------
        */

        $totalCourseFee = round(
            collect($monthPayables)
                ->sum('payable_amount'),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 13. LATE FINE / PENALTY
        |--------------------------------------------------------------------------
        |
        | At this stage these values come from the calculation already
        | performed by your billing calculation AJAX.
        |
        | IMPORTANT:
        | Ideally the exact same calculation should be moved into a
        | shared BillingService and called here as well.
        |
        */

        $lateFine = round(
            (float) ($validated['late_fine'] ?? 0),
            2
        );


        $penaltyFee = round(
            (float) ($validated['course_penalty_fee'] ?? 0),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 14. TOTAL BILLING
        |--------------------------------------------------------------------------
        |
        | Registration + Admission are charged only on first payment.
        |--------------------------------------------------------------------------
        */

        $billingRegistrationFee =
            $isFirstPayment
                ? $registrationFee
                : 0;


        $billingAdmissionFee =
            $isFirstPayment
                ? $admissionFee
                : 0;


        $totalBillingAmount = round(

            $totalCourseFee
            + $billingRegistrationFee
            + $billingAdmissionFee
            + $lateFine
            + $penaltyFee,

            2
        );


        /*
        |--------------------------------------------------------------------------
        | 15. PAYMENT MUST MATCH BILLING
        |--------------------------------------------------------------------------
        */

        if (
            abs(
                $totalPayment -
                $totalBillingAmount
            ) > 0.009
        ) {

            throw ValidationException::withMessages([
                'amount' =>
                    'Total payment ₹' .
                    number_format($totalPayment, 2) .
                    ' does not match billing amount ₹' .
                    number_format($totalBillingAmount, 2) .
                    '.',
            ]);
        }

        $paymentId =
            'PAY-' .
            now()->format('Ymd') .
            '-' .
            strtoupper(Str::random(6));


        /*
        |--------------------------------------------------------------------------
        | 16. DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | A. CREATE / UPDATE MONTH RECORDS
            |--------------------------------------------------------------------------
            */

            $monthRecords = [];


            foreach ($monthPayables as $monthData) {

                $month =
                    $monthData['month'];


                $monthKey =
                    $month->format('Y-m');


                /*
                |--------------------------------------------------------------------------
                | Existing Record
                |--------------------------------------------------------------------------
                */

                $monthRecord =
                    $existingMonths->get($monthKey);


                if ($monthRecord) {

                    /*
                    |--------------------------------------------------------------------------
                    | Prevent duplicate payment for already-paid month
                    |--------------------------------------------------------------------------
                    */

                    $existingPayable =
                        round(
                            (float) $monthRecord->payable_amount,
                            2
                        );


                    $existingPaid =
                        round(
                            (float) $monthRecord->paid_amount,
                            2
                        );


                    if (
                        $existingPayable > 0 &&
                        $existingPaid >= $existingPayable
                    ) {

                        throw ValidationException::withMessages([
                            'billing_from' =>
                                'The billing month ' .
                                $month->format('F Y') .
                                ' is already fully paid.',
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Use existing record
                    |--------------------------------------------------------------------------
                    */

                    $monthRecords[] = $monthRecord;

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Due Date
                |--------------------------------------------------------------------------
                |
                | Monthly fee due date = billing month date.
                | You can change this to your actual studio due-date rule.
                |
                */

                $dueDate =
                    $month->copy()->endOfMonth();


                /*
                |--------------------------------------------------------------------------
                | Create Month Record
                |--------------------------------------------------------------------------
                */

                $monthRecord =
                    CourseMonthRecord::create([

                        'student_course_id' =>
                            $studentCourse->id,

                        'fee_month' =>
                            $month->format('Y-m-01'),

                        'monthly_fee' =>
                            $monthData['monthly_fee'],

                        'waiver_amount' =>
                            0,

                        'payable_amount' =>
                            $monthData['payable_amount'],

                        'paid_amount' =>
                            0,

                        'due_date' =>
                            $dueDate->format('Y-m-d'),

                        'paid_date' =>
                            null,

                        'payment_percentage' =>
                            0,

                        'payment_rule' =>
                            $monthData['payment_rule'],

                        'status' =>
                            'unpaid',

                        'remarks' =>
                            $isFirstPayment
                                ? 'First payment billing'
                                : 'Regular billing',
                    ]);


                $monthRecords[] =
                    $monthRecord;
            }


            /*
            |--------------------------------------------------------------------------
            | B. ALLOCATE COURSE FEE FROM PAYMENT
            |--------------------------------------------------------------------------
            |
            | Registration / Admission / Fine / Penalty are not added
            | to monthly paid_amount.
            |
            | First the monthly course fee portion is allocated.
            |--------------------------------------------------------------------------
            */

            $remainingCourseFee =
                $totalCourseFee;


            foreach ($monthRecords as $monthRecord) {

                if ($remainingCourseFee <= 0) {
                    break;
                }


                $payable =
                    round(
                        (float) $monthRecord->payable_amount,
                        2
                    );


                $alreadyPaid =
                    round(
                        (float) $monthRecord->paid_amount,
                        2
                    );


                $outstanding =
                    max(
                        0,
                        $payable - $alreadyPaid
                    );


                if ($outstanding <= 0) {
                    continue;
                }


                $allocated =
                    min(
                        $remainingCourseFee,
                        $outstanding
                    );


                $newPaid =
                    round(
                        $alreadyPaid + $allocated,
                        2
                    );


                $percentage =
                    $payable > 0
                        ? round(
                            ($newPaid / $payable) * 100,
                            2
                        )
                        : 0;


                $isFullyPaid =
                    $newPaid >= $payable;


                $monthRecord->update([

                    'paid_amount' =>
                        $newPaid,

                    'payment_percentage' =>
                        min(100, $percentage),

                    'paid_date' =>
                        $isFullyPaid
                            ? $payments[0]['payment_date']
                            : null,

                    'status' =>
                        $isFullyPaid
                            ? 'paid'
                            : 'partial',
                ]);


                $remainingCourseFee =
                    round(
                        $remainingCourseFee -
                        $allocated,
                        2
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | C. SAVE LATE FINE
            |--------------------------------------------------------------------------
            */

            if ($lateFine > 0) {

                /*
                |--------------------------------------------------------------------------
                | Find month to which fine belongs.
                |--------------------------------------------------------------------------
                */

                $fineMonth = null;


                if (!empty($validated['fine_current_month'])) {

                    try {

                        $fineMonth =
                            Carbon::parse(
                                $validated['fine_current_month'] . '-01'
                            )->startOfMonth();

                    } catch (\Throwable $e) {

                        $fineMonth =
                            $actualBillingMonths[0] ?? null;
                    }

                } else {

                    $fineMonth =
                        $actualBillingMonths[0] ?? null;
                }


                $fineMonthRecord = null;


                if ($fineMonth) {

                    $fineMonthRecord =
                        CourseMonthRecord::query()
                            ->where(
                                'student_course_id',
                                $studentCourse->id
                            )
                            ->whereDate(
                                'fee_month',
                                $fineMonth->format('Y-m-01')
                            )
                            ->first();
                }


                /*
                |--------------------------------------------------------------------------
                | Due Date
                |--------------------------------------------------------------------------
                */

                $fineDueDate =
                    $fineMonthRecord?->due_date
                    ?? (
                        $fineMonth
                            ? $fineMonth->copy()->endOfMonth()
                            : $billingFrom
                    );


                LateFineRecord::create([

                    'student_course_id' =>
                        $studentCourse->id,

                    'course_month_record_id' =>
                        $fineMonthRecord?->id,

                    'fine_date' =>
                        now()->toDateString(),

                    'due_date' =>
                        $fineDueDate,

                    'fine_amount' =>
                        $lateFine,

                    'paid_amount' =>
                        $lateFine,

                    'waived_amount' =>
                        0,

                    'status' =>
                        'paid',

                    'remarks' =>
                        $validated['fine_type']
                            ?? 'Late Fine',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | D. SAVE COURSE PENALTY
            |--------------------------------------------------------------------------
            |
            | Your model does not have a separate CoursePenaltyRecord table.
            | Therefore penalty is stored in LateFineRecord.
            |
            */

            if ($penaltyFee > 0) {

                $penaltyMonth =
                    $actualBillingMonths[0] ?? null;


                $penaltyMonthRecord = null;


                if ($penaltyMonth) {

                    $penaltyMonthRecord =
                        CourseMonthRecord::query()
                            ->where(
                                'student_course_id',
                                $studentCourse->id
                            )
                            ->whereDate(
                                'fee_month',
                                $penaltyMonth->format('Y-m-01')
                            )
                            ->first();
                }


                LateFineRecord::create([

                    'student_course_id' =>
                        $studentCourse->id,

                    'course_month_record_id' =>
                        $penaltyMonthRecord?->id,

                    'fine_date' =>
                        now()->toDateString(),

                    'due_date' =>
                        $penaltyMonthRecord?->due_date
                        ?? now()->toDateString(),

                    'fine_amount' =>
                        $penaltyFee,

                    'paid_amount' =>
                        $penaltyFee,

                    'waived_amount' =>
                        0,

                    'status' =>
                        'paid',

                    'remarks' =>
                        'Course Penalty Fee',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | E. SAVE PAYMENT ENTRIES
            |--------------------------------------------------------------------------
            */

            foreach ($payments as $payment) {

                CoursePaymentRecord::create([

                    'payment_id' =>
                        $paymentId,

                    'student_course_id' =>
                        $studentCourse->id,

                    'user_id' =>
                        $studentCourse->user_id,

                    'payment_date' =>
                        $payment['payment_date'],

                    'payment_mode' =>
                        $payment['payment_mode'],

                    'amount' =>
                        $payment['amount'],

                    /*
                    |--------------------------------------------------------------------------
                    | No platform fee for now
                    |--------------------------------------------------------------------------
                    */

                    'platform_fee_percentage' =>
                        0,

                    'platform_fee_amount' =>
                        0,

                    'transaction_id' =>
                        $payment['transaction_id'],

                    'payment_proof' =>
                        null,

                    'status' =>
                        'success',

                    'remarks' =>
                        $payment['remarks'],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | F. COMMIT
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('billing.invoice',$paymentId)
                ->with(
                    'success',
                    'Billing saved successfully. Total amount ₹' .
                    number_format($totalBillingAmount, 2)
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ROLLBACK ON ERROR
        |--------------------------------------------------------------------------
        */

        catch (ValidationException $e) {

            DB::rollBack();

            throw $e;
        }


        catch (\Throwable $e) {

            DB::rollBack();

            Log::error('BILLING STORE ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['_token']),
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Billing Save Error: ' . $e->getMessage()
                );
        }
    }

    public function manage(StudentCourse $student_course)
    {
        $student_course->load([
            'student',
            'course',
            'batch',
            'level',
            'category'
        ]);

        $payments = StudentPayment::where(
                'student_course_id',
                $student_course->id
            )
            ->orderBy('payment_date')
            ->orderBy('id')
            ->get();

        $totalPaid = $payments->sum('amount');

        $remainingAmount = max(
            0,
            $student_course->grand_total - $totalPaid
        );

        $paymentCount = $payments->count();

        /*
        |--------------------------------------------------------------------------
        | Next Payable
        |--------------------------------------------------------------------------
        */

        if ($paymentCount == 0) {

            $nextPayable =
                $student_course->registration_fee +
                $student_course->admission_fee +
                $student_course->course_fee;

        } else {

            $nextPayable = min(
                $student_course->course_fee,
                $remainingAmount
            );

        }

        return view(
            'backend.billing.manage',
            compact(
                'student_course',
                'payments',
                'totalPaid',
                'remainingAmount',
                'paymentCount',
                'nextPayable'
            )
        );
    }

    public function update(Request $request, StudentCourse $student_course)
    {
        $request->validate([

            'payment_date.*' => 'required|date',

            'payment_mode.*' => 'required|string',

            'amount.*' => 'required|numeric|min:0.01',

            'transaction_id.*' => 'nullable|string|max:255',

            'remarks.*' => 'nullable|string|max:500',

        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Update Existing Payments
            |--------------------------------------------------------------------------
            */

            if($request->filled('payment_id')){

                foreach($request->payment_id as $key=>$paymentId){

                    $payment = StudentPayment::find($paymentId);

                    if(!$payment){
                        continue;
                    }

                    $payment->update([

                        'payment_date' => $request->old_payment_date[$key],

                        'payment_mode' => $request->old_payment_mode[$key],

                        'amount' => $request->old_amount[$key],

                        'transaction_id' =>

                            $request->old_payment_mode[$key]=='Cash'
                            ? null
                            : $request->old_transaction_id[$key],

                        'remarks' => $request->old_remarks[$key],

                    ]);

                }

            }

            /*
            |--------------------------------------------------------------------------
            | Current Total Paid
            |--------------------------------------------------------------------------
            */

            $totalPaid = StudentPayment::where(
                    'student_course_id',
                    $student_course->id
                )
                ->sum('amount');

            /*
            |--------------------------------------------------------------------------
            | New Payment Total
            |--------------------------------------------------------------------------
            */

            $newPayment = 0;

            if($request->filled('amount')){

                $newPayment = array_sum($request->amount);

            }

            $remaining = $student_course->grand_total - $totalPaid;

            if($newPayment > $remaining){

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Payment cannot exceed remaining due.'
                    );

            }

            /*
            |--------------------------------------------------------------------------
            | Registration & Admission Fee
            |--------------------------------------------------------------------------
            */

            $paymentCount = StudentPayment::where(
                'student_course_id',
                $student_course->id
            )->count();

            if($paymentCount==0){

                $registrationFee =
                    $student_course->registration_fee;

                $admissionFee =
                    $student_course->admission_fee;

            }else{

                $registrationFee = 0;

                $admissionFee = 0;

            }

            /*
            |--------------------------------------------------------------------------
            | Insert New Payments
            |--------------------------------------------------------------------------
            */

            if($request->filled('payment_mode')){

                foreach($request->payment_mode as $key=>$mode){

                    if(
                        empty($mode)
                        ||
                        empty($request->amount[$key])
                        ||
                        $request->amount[$key]<=0
                    ){
                        continue;
                    }

                    StudentPayment::create([

                        'student_course_id'=>$student_course->id,

                        'user_id'=>$student_course->user_id,

                        'registration_fee'=>$registrationFee,

                        'admission_fee'=>$admissionFee,

                        'course_fee'=>$student_course->course_fee,

                        'payment_date'=>$request->payment_date[$key],

                        'payment_mode'=>$mode,

                        'amount'=>$request->amount[$key],

                        'transaction_id'=>

                            $mode=='Cash'
                            ? null
                            : $request->transaction_id[$key],

                        'remarks'=>$request->remarks[$key],

                        'status'=>'success',

                    ]);

                    $registrationFee = 0;

                    $admissionFee = 0;

                }

            }

            DB::commit();

            return redirect()

                ->route('billing.manage',$student_course->id)

                ->with(
                    'success',
                    'Billing updated successfully.'
                );

        }catch(\Exception $e){

            DB::rollBack();

            return back()

                ->withInput()

                ->with('error',$e->getMessage());

        }

    }

    public function deletePayment($paymentId)
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. FIND PAYMENT
            |--------------------------------------------------------------------------
            */

            $payment = CoursePaymentRecord::query()
                ->findOrFail($paymentId);


            /*
            |--------------------------------------------------------------------------
            | 2. STUDENT COURSE
            |--------------------------------------------------------------------------
            */

            $studentCourse = StudentCourse::query()
                ->findOrFail(
                    $payment->student_course_id
                );


            /*
            |--------------------------------------------------------------------------
            | 3. DELETE PAYMENT
            |--------------------------------------------------------------------------
            */

            $payment->delete();


            /*
            |--------------------------------------------------------------------------
            | 4. GET REMAINING PAYMENTS
            |--------------------------------------------------------------------------
            */

            $remainingPayments = CoursePaymentRecord::query()
                ->where(
                    'student_course_id',
                    $studentCourse->id
                )
                ->get();


            $totalRemainingPayment = round(
                (float) $remainingPayments->sum('amount'),
                2
            );


            /*
            |--------------------------------------------------------------------------
            | 5. GET MONTH RECORDS
            |--------------------------------------------------------------------------
            */

            $monthRecords = CourseMonthRecord::query()
                ->where(
                    'student_course_id',
                    $studentCourse->id
                )
                ->orderBy('fee_month', 'asc')
                ->get();


            /*
            |--------------------------------------------------------------------------
            | 6. TOTAL FINE / PENALTY
            |--------------------------------------------------------------------------
            */

            $totalFine = round(
                (float) LateFineRecord::query()
                    ->where(
                        'student_course_id',
                        $studentCourse->id
                    )
                    ->sum('fine_amount'),
                2
            );


            /*
            |--------------------------------------------------------------------------
            | 7. FIRST PAYMENT FEES
            |--------------------------------------------------------------------------
            */

            $registrationFee = round(
                (float) ($studentCourse->registration_fee ?? 0),
                2
            );

            $admissionFee = round(
                (float) ($studentCourse->admission_fee ?? 0),
                2
            );


            /*
            |--------------------------------------------------------------------------
            | 8. NON COURSE PAYMENT
            |--------------------------------------------------------------------------
            */

            $nonCourseAmount = 0;

            /*
            | Registration + Admission are applicable
            | only when at least one payment exists.
            */

            if ($remainingPayments->isNotEmpty()) {

                $nonCourseAmount +=
                    $registrationFee +
                    $admissionFee;
            }


            /*
            | Fine / penalty is already paid in the
            | existing billing structure.
            */

            $nonCourseAmount += $totalFine;


            /*
            |--------------------------------------------------------------------------
            | 9. COURSE FEE AVAILABLE FOR MONTH RECORDS
            |--------------------------------------------------------------------------
            */

            $remainingCoursePayment = max(
                0,
                round(
                    $totalRemainingPayment
                    - $nonCourseAmount,
                    2
                )
            );


            /*
            |--------------------------------------------------------------------------
            | 10. RESET MONTH RECORDS
            |--------------------------------------------------------------------------
            */

            foreach ($monthRecords as $monthRecord) {

                $payable = round(
                    (float) $monthRecord->payable_amount,
                    2
                );


                if ($remainingCoursePayment <= 0) {

                    $monthRecord->update([

                        'paid_amount' =>
                            0,

                        'payment_percentage' =>
                            0,

                        'paid_date' =>
                            null,

                        'status' =>
                            'unpaid',
                    ]);

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Allocate remaining payment
                |--------------------------------------------------------------------------
                */

                $allocated = min(
                    $remainingCoursePayment,
                    $payable
                );


                $percentage = $payable > 0
                    ? round(
                        ($allocated / $payable) * 100,
                        2
                    )
                    : 0;


                $isFullyPaid =
                    $allocated >= $payable;


                /*
                |--------------------------------------------------------------------------
                | Update Month
                |--------------------------------------------------------------------------
                */

                $monthRecord->update([

                    'paid_amount' =>
                        round($allocated, 2),

                    'payment_percentage' =>
                        min(100, $percentage),

                    'paid_date' =>
                        $isFullyPaid
                            ? now()->toDateString()
                            : null,

                    'status' =>
                        $isFullyPaid
                            ? 'paid'
                            : (
                                $allocated > 0
                                    ? 'partial'
                                    : 'unpaid'
                            ),
                ]);


                /*
                |--------------------------------------------------------------------------
                | Reduce Remaining
                |--------------------------------------------------------------------------
                */

                $remainingCoursePayment =
                    round(
                        $remainingCoursePayment
                        - $allocated,
                        2
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | 11. COMMIT
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | 12. SUCCESS
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'billing.payments',
                    $studentCourse->id
                )
                ->with(
                    'success',
                    'Payment deleted successfully and billing records recalculated.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION ERROR
        |--------------------------------------------------------------------------
        */

        catch (ValidationException $e) {

            DB::rollBack();

            throw $e;
        }


        /*
        |--------------------------------------------------------------------------
        | OTHER ERROR
        |--------------------------------------------------------------------------
        */

        catch (\Throwable $e) {

            DB::rollBack();


            Log::error(
                'BILLING PAYMENT DELETE ERROR',
                [
                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),

                    'payment_id' =>
                        $paymentId,
                ]
            );


            return back()
                ->with(
                    'error',
                    'Payment delete failed: '
                    . $e->getMessage()
                );
        }
    }

    public function confirmPayment(CoursePaymentRecord $payment)
    {
        // dd($payment);
        /*
        |--------------------------------------------------------------------------
        | Already Successful
        |--------------------------------------------------------------------------
        */

        if ($payment->status === 'success') {

            return back()->with(
                'info',
                'This payment is already confirmed.'
            );
        }


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Update Payment Status
            |--------------------------------------------------------------------------
            */

            $payment->update([
                'status' => 'success',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            return back()->with(
                'success',
                'Payment confirmed successfully.'
            );
        }


        catch (\Throwable $e) {

            DB::rollBack();


            Log::error(
                'PAYMENT CONFIRM ERROR',
                [
                    'payment_id' => $payment->id,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );


            return back()->with(
                'error',
                'Unable to confirm payment: ' .
                $e->getMessage()
            );
        }
    }

    public function invoice($paymentId)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. LOAD ALL PAYMENTS WITH SAME PAYMENT ID
        |--------------------------------------------------------------------------
        */

        $payments = CoursePaymentRecord::query()
            ->with([
                'studentCourse.student',
                'studentCourse.course',
                'studentCourse.level',
                'studentCourse.category',
                'studentCourse.batch',
                'studentCourse.instructor',
            ])
            ->where('payment_id', $paymentId)
            ->orderBy('id', 'asc')
            ->get();

        if ($payments->isEmpty()) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | 2. MAIN PAYMENT
        |--------------------------------------------------------------------------
        |
        | Same payment_id ke records mein se first record ko
        | student/course ki common information ke liye use kar rahe hain.
        |
        */

        $payment = $payments->first();

        $studentCourse = $payment->studentCourse;


        /*
        |--------------------------------------------------------------------------
        | 3. MONTH RECORDS
        |--------------------------------------------------------------------------
        */

        $monthRecords = CourseMonthRecord::query()
            ->where(
                'student_course_id',
                $studentCourse->id
            )
            ->orderBy('fee_month', 'asc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 4. TOTAL PAID
        |--------------------------------------------------------------------------
        |
        | Is course ke saare successful payments ka cumulative total.
        |
        */

        $totalPaid = round(
            (float) CoursePaymentRecord::query()
                ->where(
                    'student_course_id',
                    $studentCourse->id
                )
                ->where('status', 'success')
                ->sum('amount'),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 5. COURSE FEE
        |--------------------------------------------------------------------------
        */

        $totalCourseFee = round(
            (float) $monthRecords->sum('payable_amount'),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 6. LATE FINE / PENALTY
        |--------------------------------------------------------------------------
        */

        $totalFine = round(
            (float) LateFineRecord::query()
                ->where(
                    'student_course_id',
                    $studentCourse->id
                )
                ->sum('fine_amount'),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 7. REGISTRATION / ADMISSION
        |--------------------------------------------------------------------------
        */

        $registrationFee = round(
            (float) ($studentCourse->registration_fee ?? 0),
            2
        );

        $admissionFee = round(
            (float) ($studentCourse->admission_fee ?? 0),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 8. TOTAL BILLING
        |--------------------------------------------------------------------------
        */

        $totalBilling = round(
            $totalCourseFee
            + $registrationFee
            + $admissionFee
            + $totalFine,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | 9. REMAINING
        |--------------------------------------------------------------------------
        */

        $remaining = max(
            0,
            round(
                $totalBilling - $totalPaid,
                2
            )
        );


        /*
        |--------------------------------------------------------------------------
        | 10. PAID MONTHS
        |--------------------------------------------------------------------------
        */

        $paidMonths = $monthRecords
            ->filter(function ($record) {

                return
                    (float) $record->payable_amount > 0
                    &&
                    (float) $record->paid_amount >=
                    (float) $record->payable_amount;

            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | 11. PAID MONTH LABEL
        |--------------------------------------------------------------------------
        */

        $paidMonthLabel = 'No payment yet';

        $paidMonthCount = $paidMonths->count();


        if ($paidMonths->isNotEmpty()) {

            $firstMonth = Carbon::parse(
                $paidMonths->first()->fee_month
            )->startOfMonth();

            $lastMonth = Carbon::parse(
                $paidMonths->last()->fee_month
            )->startOfMonth();


            if ($firstMonth->equalTo($lastMonth)) {

                $paidMonthLabel =
                    $firstMonth->format('M Y');

            } else {

                $paidMonthLabel =
                    $firstMonth->format('M Y')
                    . ' - '
                    . $lastMonth->format('M Y');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 12. RETURN INVOICE
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.billing.invoice',
            compact(
                'payment',
                'payments',
                'studentCourse',
                'monthRecords',
                'totalPaid',
                'totalCourseFee',
                'registrationFee',
                'admissionFee',
                'totalFine',
                'totalBilling',
                'remaining',
                'paidMonthLabel',
                'paidMonthCount'
            )
        );
    }

    // public function invoice($paymentId)
    // {
    //     /*
    //     |--------------------------------------------------------------------------
    //     | 1. LOAD SINGLE PAYMENT
    //     |--------------------------------------------------------------------------
    //     */

    //     $payment = CoursePaymentRecord::query()
    //         ->with([
    //             'studentCourse.student',
    //             'studentCourse.course',
    //             'studentCourse.level',
    //             'studentCourse.category',
    //             'studentCourse.batch',
    //             'studentCourse.instructor',
    //         ])
    //         ->findOrFail($paymentId);


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 2. STUDENT COURSE
    //     |--------------------------------------------------------------------------
    //     */

    //     $studentCourse = $payment->studentCourse;


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 3. MONTH RECORDS
    //     |--------------------------------------------------------------------------
    //     |
    //     | These are used only for calculating billing summary.
    //     | They are NOT displayed as individual payment records.
    //     |
    //     */

    //     $monthRecords = CourseMonthRecord::query()
    //         ->where(
    //             'student_course_id',
    //             $studentCourse->id
    //         )
    //         ->orderBy('fee_month', 'asc')
    //         ->get();


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 4. TOTAL PAID
    //     |--------------------------------------------------------------------------
    //     |
    //     | Cumulative total paid for this course.
    //     |
    //     */

    //     $totalPaid = round(
    //         (float) CoursePaymentRecord::query()
    //             ->where(
    //                 'student_course_id',
    //                 $studentCourse->id
    //             )
    //             ->where('status', 'success')
    //             ->sum('amount'),
    //         2
    //     );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 5. COURSE FEE
    //     |--------------------------------------------------------------------------
    //     */

    //     $totalCourseFee = round(
    //         (float) $monthRecords->sum('payable_amount'),
    //         2
    //     );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 6. LATE FINE / PENALTY
    //     |--------------------------------------------------------------------------
    //     */

    //     $totalFine = round(
    //         (float) LateFineRecord::query()
    //             ->where(
    //                 'student_course_id',
    //                 $studentCourse->id
    //             )
    //             ->sum('fine_amount'),
    //         2
    //     );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 7. REGISTRATION / ADMISSION
    //     |--------------------------------------------------------------------------
    //     */

    //     $registrationFee = round(
    //         (float) ($studentCourse->registration_fee ?? 0),
    //         2
    //     );

    //     $admissionFee = round(
    //         (float) ($studentCourse->admission_fee ?? 0),
    //         2
    //     );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 8. TOTAL BILLING
    //     |--------------------------------------------------------------------------
    //     */

    //     $totalBilling = round(

    //         $totalCourseFee
    //         + $registrationFee
    //         + $admissionFee
    //         + $totalFine,

    //         2
    //     );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 9. REMAINING
    //     |--------------------------------------------------------------------------
    //     */

    //     $remaining = max(
    //         0,
    //         round(
    //             $totalBilling - $totalPaid,
    //             2
    //         )
    //     );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 10. PAID MONTHS
    //     |--------------------------------------------------------------------------
    //     */

    //     $paidMonths = $monthRecords
    //         ->filter(function ($record) {

    //             return
    //                 (float) $record->payable_amount > 0
    //                 &&
    //                 (float) $record->paid_amount >=
    //                 (float) $record->payable_amount;

    //         })
    //         ->values();


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 11. PAID MONTH LABEL
    //     |--------------------------------------------------------------------------
    //     */

    //     $paidMonthLabel = 'No payment yet';

    //     $paidMonthCount = $paidMonths->count();


    //     if ($paidMonths->isNotEmpty()) {

    //         $firstMonth = Carbon::parse(
    //             $paidMonths->first()->fee_month
    //         )->startOfMonth();


    //         $lastMonth = Carbon::parse(
    //             $paidMonths->last()->fee_month
    //         )->startOfMonth();


    //         if ($firstMonth->equalTo($lastMonth)) {

    //             $paidMonthLabel =
    //                 $firstMonth->format('M Y');

    //         } else {

    //             $paidMonthLabel =
    //                 $firstMonth->format('M Y')
    //                 . ' - '
    //                 . $lastMonth->format('M Y');
    //         }
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 12. RETURN SINGLE PAYMENT INVOICE
    //     |--------------------------------------------------------------------------
    //     */

    //     return view(
    //         'backend.billing.invoice',
    //         compact(
    //             'payment',
    //             'studentCourse',
    //             'monthRecords',
    //             'totalPaid',
    //             'totalCourseFee',
    //             'registrationFee',
    //             'admissionFee',
    //             'totalFine',
    //             'totalBilling',
    //             'remaining',
    //             'paidMonthLabel',
    //             'paidMonthCount'
    //         )
    //     );
    // }

    public function overallInvoice($payment)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. LOAD PAYMENT
        |--------------------------------------------------------------------------
        */

        $payment = CoursePaymentRecord::query()

            ->with([
                'studentCourse.student',
                'studentCourse.course',
                'studentCourse.batch',
                'studentCourse.level',
                'studentCourse.category',
                'studentCourse.instructor',
            ])

            ->findOrFail($payment);


        /*
        |--------------------------------------------------------------------------
        | 2. STUDENT COURSE
        |--------------------------------------------------------------------------
        */

        $studentCourse =
            $payment->studentCourse;


        /*
        |--------------------------------------------------------------------------
        | 3. ALL SUCCESSFUL PAYMENTS
        |--------------------------------------------------------------------------
        */

        $payments = CoursePaymentRecord::query()

            ->where(
                'student_course_id',
                $studentCourse->id
            )

            ->where(
                'status',
                'success'
            )

            ->orderBy(
                'payment_date',
                'asc'
            )

            ->orderBy(
                'id',
                'asc'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | 4. MONTH RECORDS
        |--------------------------------------------------------------------------
        */

        $monthRecords = CourseMonthRecord::query()

            ->where(
                'student_course_id',
                $studentCourse->id
            )

            ->orderBy(
                'fee_month',
                'asc'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | 5. PAID MONTHS
        |--------------------------------------------------------------------------
        */

        $paidMonths = $monthRecords

            ->filter(function ($record) {

                $payable = round(
                    (float) $record->payable_amount,
                    2
                );

                $paid = round(
                    (float) $record->paid_amount,
                    2
                );

                return $payable > 0
                    && $paid >= $payable;

            })

            ->sortBy('fee_month')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | 6. PAID MONTH LABEL
        |--------------------------------------------------------------------------
        */

        $paidMonthLabel =
            'No payment yet';

        $paidMonthCount =
            $paidMonths->count();


        if ($paidMonths->isNotEmpty()) {

            $firstPaidMonth = Carbon::parse(
                $paidMonths->first()->fee_month
            )->startOfMonth();


            $lastPaidMonth = Carbon::parse(
                $paidMonths->last()->fee_month
            )->startOfMonth();


            /*
            |--------------------------------------------------------------------------
            | Check continuous months
            |--------------------------------------------------------------------------
            */

            $isContinuous = true;

            $expectedMonth =
                $firstPaidMonth->copy();


            foreach ($paidMonths as $monthRecord) {

                $currentMonth = Carbon::parse(
                    $monthRecord->fee_month
                )->startOfMonth();


                if (
                    !$currentMonth->equalTo(
                        $expectedMonth
                    )
                ) {

                    $isContinuous = false;

                    break;
                }


                $expectedMonth->addMonth();
            }


            if ($isContinuous) {

                if (
                    $firstPaidMonth->equalTo(
                        $lastPaidMonth
                    )
                ) {

                    $paidMonthLabel =
                        $firstPaidMonth->format('M Y');

                } else {

                    $paidMonthLabel =
                        $firstPaidMonth->format('M Y')
                        . ' - '
                        . $lastPaidMonth->format('M Y');
                }

            } else {

                $paidMonthLabel =
                    $paidMonths
                        ->map(function ($record) {

                            return Carbon::parse(
                                $record->fee_month
                            )->format('M Y');

                        })
                        ->implode(', ');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 7. TOTAL COURSE FEE
        |--------------------------------------------------------------------------
        */

        $totalCourseFee = round(

            (float) $monthRecords->sum(
                'payable_amount'
            ),

            2
        );


        /*
        |--------------------------------------------------------------------------
        | 8. REGISTRATION FEE
        |--------------------------------------------------------------------------
        */

        $registrationFee = round(

            (float) (
                $studentCourse->registration_fee
                ?? 0
            ),

            2
        );


        /*
        |--------------------------------------------------------------------------
        | 9. ADMISSION FEE
        |--------------------------------------------------------------------------
        */

        $admissionFee = round(

            (float) (
                $studentCourse->admission_fee
                ?? 0
            ),

            2
        );


        /*
        |--------------------------------------------------------------------------
        | 10. LATE FINE / PENALTY
        |--------------------------------------------------------------------------
        */

        $lateFines = LateFineRecord::query()

            ->where(
                'student_course_id',
                $studentCourse->id
            )

            ->orderBy(
                'fine_date',
                'asc'
            )

            ->orderBy(
                'id',
                'asc'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | 11. TOTAL FINE
        |--------------------------------------------------------------------------
        */

        $totalFine = round(

            (float) $lateFines->sum(
                'fine_amount'
            ),

            2
        );


        /*
        |--------------------------------------------------------------------------
        | 12. TOTAL BILLING
        |--------------------------------------------------------------------------
        */

        $totalBilling = round(

            $totalCourseFee
            + $registrationFee
            + $admissionFee
            + $totalFine,

            2
        );


        /*
        |--------------------------------------------------------------------------
        | 13. TOTAL PAID
        |--------------------------------------------------------------------------
        */

        $totalPaid = round(

            (float) $payments->sum(
                'amount'
            ),

            2
        );


        /*
        |--------------------------------------------------------------------------
        | 14. REMAINING
        |--------------------------------------------------------------------------
        */

        $remaining = max(

            0,

            round(
                $totalBilling - $totalPaid,
                2
            )

        );


        /*
        |--------------------------------------------------------------------------
        | 15. PAYMENT COUNT
        |--------------------------------------------------------------------------
        */

        $paymentCount =
            $payments->count();


        /*
        |--------------------------------------------------------------------------
        | 16. AMOUNT IN WORDS
        |--------------------------------------------------------------------------
        */

        $amountInWords = '';

        try {

            $formatter =
                \NumberFormatter::create(
                    'en',
                    \NumberFormatter::SPELLOUT
                );


            $amountInWords =
                $formatter->format(
                    $totalPaid
                );

        } catch (\Throwable $e) {

            $amountInWords = '';

        }


        /*
        |--------------------------------------------------------------------------
        | 17. RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.billing.overall-invoice',
            compact(
                'payment',
                'studentCourse',
                'payments',
                'monthRecords',
                'paidMonths',
                'paidMonthLabel',
                'paidMonthCount',
                'lateFines',
                'totalCourseFee',
                'registrationFee',
                'admissionFee',
                'totalFine',
                'totalBilling',
                'totalPaid',
                'remaining',
                'paymentCount',
                'amountInWords'
            )
        );
    }

    public function studentCourses(Request $request)
    {
        $courses = StudentCourse::with('course')
            ->where('user_id', $request->student_id)
            ->where('status', 'ongoing')
            // ->where('is_enroll', 1)
            ->get();

        return response()->json([
            'status' => true,
            'courses' => $courses
        ]);
    }

    public function courseDetails(Request $request)
    {
        $request->validate([
            'student_course_id' => [
                'required',
                'exists:student_course,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Student Course
        |--------------------------------------------------------------------------
        */

        $studentCourse = StudentCourse::with([
            'course',
            'level',
            'category',
            'batch',
        ])->find($request->student_course_id);


        if (!$studentCourse) {

            return response()->json([
                'status' => false,
                'message' => 'Student course not found.',
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Fee Total
        |--------------------------------------------------------------------------
        |
        | Registration Fee
        | + Admission Fee
        | + Monthly Fee
        |
        */

        $totalFee =
            (float) $studentCourse->registration_fee +
            (float) $studentCourse->admission_fee +
            (float) $studentCourse->monthly_fee;


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'data' => [

                /*
                |--------------------------------------------------------------------------
                | Student Course
                |--------------------------------------------------------------------------
                */

                'student_course_id' => $studentCourse->id,

                'admission_no' => $studentCourse->admission_no,

                'admission_date' => optional(
                    $studentCourse->admission_date
                )->format('d-m-Y'),


                /*
                |--------------------------------------------------------------------------
                | Course Information
                |--------------------------------------------------------------------------
                */

                'course_duration' => $studentCourse->course_duration,

                'duration_type' => $studentCourse->duration_type,

                'course_name' => optional(
                    $studentCourse->course
                )->course_name,

                'level' => optional(
                    $studentCourse->level
                )->name,

                'category' => optional(
                    $studentCourse->category
                )->name,

                'batch' => optional(
                    $studentCourse->batch
                )->batch_name,


                /*
                |--------------------------------------------------------------------------
                | Fee Summary
                |--------------------------------------------------------------------------
                */

                'registration_fee' => (float) $studentCourse->registration_fee,

                'admission_fee' => (float) $studentCourse->admission_fee,

                'monthly_fee' => (float) $studentCourse->monthly_fee,

                'total_fee' => $totalFee,

            ],

        ]);
    }

    public function calculateLateFine(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'student_id'        => 'required|integer',
            'student_course_id' => 'required|integer',
            'billing_from'      => 'required|date',
            'billing_to'        => 'required|date|after_or_equal:billing_from',
        ]);


        /*
        |--------------------------------------------------------------------------
        | BASIC DATA
        |--------------------------------------------------------------------------
        */

        $studentId = (int) $request->student_id;

        $studentCourseId = (int) $request->student_course_id;

        $billingFrom = Carbon::parse(
            $request->billing_from
        )->startOfDay();

        $billingTo = Carbon::parse(
            $request->billing_to
        )->startOfDay();


        /*
        |--------------------------------------------------------------------------
        | STUDENT COURSE
        |--------------------------------------------------------------------------
        */

        $studentCourse = StudentCourse::where('id', $studentCourseId)
            ->where('user_id', $studentId)
            ->first();

        if (!$studentCourse) {

            return response()->json([
                'status'  => false,
                'apply'   => false,
                'message' => 'Student course not found.',
            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | COURSE FEES
        |--------------------------------------------------------------------------
        */

        $monthlyFee = (float) $studentCourse->monthly_fee;

        $registrationFee = (float) ($studentCourse->registration_fee ?? 0);

        $admissionFee = (float) ($studentCourse->admission_fee ?? 0);


        /*
        |--------------------------------------------------------------------------
        | BILLING MONTH RANGE
        |
        | Example:
        |
        | 2026-09-01 -> 2026-12-31
        |
        | September, October, November, December
        | = 4 months
        |--------------------------------------------------------------------------
        */

        $billingMonthStart = $billingFrom->copy()->startOfMonth();

        $billingMonthEnd = $billingTo->copy()->startOfMonth();

        $billingMonthCount =
            (($billingMonthEnd->year - $billingMonthStart->year) * 12)
            + ($billingMonthEnd->month - $billingMonthStart->month)
            + 1;


        /*
        |--------------------------------------------------------------------------
        | LATE FINE CONFIGURATION
        |--------------------------------------------------------------------------
        */

        $lateFineConfig = LateFine::first();

        $dueDay = $lateFineConfig
            ? (int) $lateFineConfig->due_date
            : 5;

        $sameMonthLateFee = $lateFineConfig
            ? (float) $lateFineConfig->same_month_late_fee
            : 0;

        $nextMonthLateFee = $lateFineConfig
            ? (float) $lateFineConfig->next_month_late_fee
            : 0;

        $absentPercentage = $lateFineConfig
            ? (float) $lateFineConfig->absent_charge_percentage
            : 0;


        /*
        |--------------------------------------------------------------------------
        | ADMISSION DATE
        |--------------------------------------------------------------------------
        */

        $admissionDate = $studentCourse->admission_date
            ? Carbon::parse($studentCourse->admission_date)->startOfDay()
            : null;


        /*
        |--------------------------------------------------------------------------
        | GET ALL COURSE MONTH RECORDS
        |
        | We use fee_month to know:
        |
        | Which month has already been billed/paid.
        |--------------------------------------------------------------------------
        */

        $allMonthRecords = CourseMonthRecord::where(
            'student_course_id',
            $studentCourseId
        )
        ->whereNotNull('fee_month')
        ->orderBy('fee_month')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | HELPER
        |--------------------------------------------------------------------------
        */

        $monthKey = function ($date) {

            return Carbon::parse($date)
                ->startOfMonth()
                ->format('Y-m');
        };


        /*
        |--------------------------------------------------------------------------
        | PAID MONTHS
        |--------------------------------------------------------------------------
        */

        $paidMonths = [];

        foreach ($allMonthRecords as $record) {

            $paidAmount = (float) ($record->paid_amount ?? 0);

            $payableAmount = (float) ($record->payable_amount ?? 0);

            if ($payableAmount <= 0) {

                $payableAmount =
                    (float) ($record->monthly_fee ?? $monthlyFee);
            }

            if (
                $paidAmount > 0 &&
                $payableAmount > 0 &&
                $paidAmount >= $payableAmount
            ) {

                $paidMonths[$monthKey($record->fee_month)] = [
                    'record_id' => $record->id,
                    'fee_month' => Carbon::parse(
                        $record->fee_month
                    )->startOfMonth(),
                    'paid_amount' => $paidAmount,
                    'payable_amount' => $payableAmount,
                    'paid_date' => $record->paid_date
                        ? Carbon::parse($record->paid_date)
                        : null,
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DETERMINE FIRST PAYMENT
        |
        | Agar koi fully paid month nahi hai:
        |
        | FIRST PAYMENT
        |--------------------------------------------------------------------------
        */

        $isFirstPayment = count($paidMonths) === 0;


        /*
        |--------------------------------------------------------------------------
        | SELECTED BILLING MONTHS
        |--------------------------------------------------------------------------
        */

        $selectedMonths = [];

        $cursor = $billingMonthStart->copy();

        while ($cursor->lte($billingMonthEnd)) {

            /*
            |--------------------------------------------------------------
            | Admission month se pehle ka month ignore
            |--------------------------------------------------------------
            */

            if (
                $admissionDate &&
                $cursor->lt(
                    $admissionDate->copy()->startOfMonth()
                )
            ) {

                $cursor->addMonth();

                continue;
            }

            $selectedMonths[] = [
                'key' => $cursor->format('Y-m'),
                'month' => $cursor->copy(),
                'name' => $cursor->format('F Y'),
            ];

            $cursor->addMonth();
        }


        /*
        |--------------------------------------------------------------------------
        | NO VALID BILLING MONTH
        |--------------------------------------------------------------------------
        */

        if (count($selectedMonths) === 0) {

            return response()->json([
                'status' => true,
                'apply' => false,

                'is_first_payment' => $isFirstPayment,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' => 0,

                'course_fee' => 0,

                'registration_fee' => 0,

                'admission_fee' => 0,

                'late_fine' => 0,

                'course_penalty_fee' => 0,

                'total_course_fee' => 0,

                'total_billing_amount' => 0,

                'paid_months' => [],

                'pending_months' => [],

                'already_paid_months' => [],

                'fine_type' => null,

                'fine_heading' => 'No Fine',

                'message' =>
                    'No valid billing month found for the selected billing period.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | FIND ALREADY PAID / PENDING MONTHS
        |--------------------------------------------------------------------------
        */

        $alreadyPaidMonths = [];

        $pendingMonths = [];

        foreach ($selectedMonths as $selectedMonth) {

            $key = $selectedMonth['key'];

            if (isset($paidMonths[$key])) {

                $alreadyPaidMonths[] = [
                    'month' => $key,
                    'month_name' => $selectedMonth['name'],
                    'paid_amount' =>
                        $paidMonths[$key]['paid_amount'],
                    'paid_date' =>
                        $paidMonths[$key]['paid_date']
                            ? $paidMonths[$key]['paid_date']
                                ->format('Y-m-d')
                            : null,
                ];

            } else {

                $pendingMonths[] = [
                    'month' => $key,
                    'month_name' => $selectedMonth['name'],
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | COURSE FEE FOR CURRENT BILLING REQUEST
        |
        | Already paid months dobara count nahi honge.
        |--------------------------------------------------------------------------
        */

        $pendingMonthCount = count($pendingMonths);

        $totalCourseFee =
            $pendingMonthCount * $monthlyFee;


        /*
        |--------------------------------------------------------------------------
        | FIRST PAYMENT
        |
        | FIRST PAYMENT:
        |
        | Registration Fee
        | Admission Fee
        | Course Fee
        |
        | NO LATE FINE
        | NO COURSE PENALTY
        |--------------------------------------------------------------------------
        */

        if ($isFirstPayment) {

            $totalBillingAmount =
                $registrationFee
                + $admissionFee
                + $totalCourseFee;


            return response()->json([

                'status' => true,

                'apply' => false,

                'is_first_payment' => true,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' =>
                    $pendingMonthCount,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    $pendingMonths,

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    round($totalCourseFee, 2),

                'registration_fee' =>
                    round($registrationFee, 2),

                'admission_fee' =>
                    round($admissionFee, 2),

                'late_fine' =>
                    0,

                'course_penalty_fee' =>
                    0,

                'fine_type' =>
                    null,

                'fine_heading' =>
                    'First Payment',

                'total_billing_amount' =>
                    round($totalBillingAmount, 2),

                'message' =>
                    'This is the student\'s first payment. Registration Fee and Admission Fee are applicable. No late fine or course penalty is applied.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | IF ALL SELECTED MONTHS ARE ALREADY PAID
        |--------------------------------------------------------------------------
        */

        if ($pendingMonthCount === 0) {

            return response()->json([

                'status' => true,

                'apply' => false,

                'is_first_payment' => false,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' => 0,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    [],

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    0,

                'registration_fee' =>
                    0,

                'admission_fee' =>
                    0,

                'late_fine' =>
                    0,

                'course_penalty_fee' =>
                    0,

                'fine_type' =>
                    null,

                'fine_heading' =>
                    'Already Paid',

                'total_billing_amount' =>
                    0,

                'message' =>
                    'All selected billing months are already fully paid. No additional amount is applicable.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT BILLING MONTH
        |
        | IMPORTANT:
        |
        | Billing Date From determines the current billing month.
        |--------------------------------------------------------------------------
        */

        $currentBillingMonth =
            $billingFrom
                ->copy()
                ->startOfMonth();

        $currentBillingMonthKey =
            $currentBillingMonth->format('Y-m');

        $currentBillingMonthName =
            $currentBillingMonth->format('F Y');


        /*
        |--------------------------------------------------------------------------
        | FIND PREVIOUS PAID MONTH
        |
        | Current billing month se pehle ka latest fully paid month.
        |--------------------------------------------------------------------------
        */

        $previousPaidMonthRecord = null;

        foreach ($paidMonths as $key => $record) {

            if ($record['fee_month']->lt($currentBillingMonth)) {

                $previousPaidMonthRecord = $record;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | NO PREVIOUS PAID MONTH
        |
        | This situation can happen when old month records exist but
        | current sequence does not have a previous paid month.
        |
        | Treat it as no late fine.
        |--------------------------------------------------------------------------
        */

        if (!$previousPaidMonthRecord) {

            return response()->json([

                'status' => true,

                'apply' => false,

                'is_first_payment' => false,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' =>
                    $pendingMonthCount,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    $pendingMonths,

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    round($totalCourseFee, 2),

                'registration_fee' =>
                    0,

                'admission_fee' =>
                    0,

                'late_fine' =>
                    0,

                'course_penalty_fee' =>
                    0,

                'fine_type' =>
                    null,

                'fine_heading' =>
                    'No Fine',

                'total_billing_amount' =>
                    round($totalCourseFee, 2),

                'previous_paid_month' =>
                    null,

                'previous_payment_date' =>
                    null,

                'month_difference' =>
                    0,

                'message' =>
                    'No previous paid month found. No late fine or course penalty is applied.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PREVIOUS PAID MONTH DATA
        |--------------------------------------------------------------------------
        */

        $previousPaidMonth =
            $previousPaidMonthRecord['fee_month'];

        $previousPaidMonthName =
            $previousPaidMonth->format('F Y');

        $previousPaymentDate =
            $previousPaidMonthRecord['paid_date']
                ? $previousPaidMonthRecord['paid_date']->format('Y-m-d')
                : null;


        /*
        |--------------------------------------------------------------------------
        | MONTH DIFFERENCE
        |--------------------------------------------------------------------------
        */

        $previousMonthNumber =
            ($previousPaidMonth->year * 12)
            + $previousPaidMonth->month;

        $currentMonthNumber =
            ($currentBillingMonth->year * 12)
            + $currentBillingMonth->month;

        $monthDifference =
            $currentMonthNumber - $previousMonthNumber;


        /*
        |--------------------------------------------------------------------------
        | ADVANCE PAYMENT
        |
        | Example:
        |
        | August paid
        | User selects September -> December
        |
        | These are future months.
        |
        | NO FINE.
        |--------------------------------------------------------------------------
        */

        if ($monthDifference <= 0) {

            return response()->json([

                'status' => true,

                'apply' => false,

                'is_first_payment' => false,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' =>
                    $pendingMonthCount,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    $pendingMonths,

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    round($totalCourseFee, 2),

                'registration_fee' =>
                    0,

                'admission_fee' =>
                    0,

                'late_fine' =>
                    0,

                'course_penalty_fee' =>
                    0,

                'fine_type' =>
                    null,

                'fine_heading' =>
                    'Advance Payment',

                'total_billing_amount' =>
                    round($totalCourseFee, 2),

                'previous_paid_month' =>
                    $previousPaidMonthName,

                'previous_payment_date' =>
                    $previousPaymentDate,

                'month_difference' =>
                    $monthDifference,

                'message' =>
                    'This is an advance payment. No late fine or course penalty is applicable.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK WHETHER INTERMEDIATE MONTHS WERE MISSED
        |
        | Example:
        |
        | June paid
        | July unpaid
        | August current
        |
        | Difference = 2
        |
        | July is a missed month.
        |--------------------------------------------------------------------------
        */

        $missedMonths = [];

        $gapCursor =
            $previousPaidMonth
                ->copy()
                ->addMonth()
                ->startOfMonth();

        while ($gapCursor->lt($currentBillingMonth)) {

            $gapKey =
                $gapCursor->format('Y-m');

            if (!isset($paidMonths[$gapKey])) {

                $missedMonths[] = [
                    'key' =>
                        $gapKey,

                    'month' =>
                        $gapCursor->copy(),

                    'name' =>
                        $gapCursor->format('F Y'),
                ];
            }

            $gapCursor->addMonth();
        }


        /*
        |--------------------------------------------------------------------------
        | COURSE PENALTY CHECK
        |
        | Rule:
        |
        | Previous unpaid month(s) mein attendance completely absent hai
        | to current payment par Course Penalty Fee lagegi.
        |
        | Example:
        |
        | June paid
        | July absent
        | August absent
        | September absent
        | October payment
        |
        | October course fee + 50% penalty
        |--------------------------------------------------------------------------
        */

        $coursePenaltyFee = 0;

        $coursePenaltyMonth = null;

        $coursePenaltyAttendanceCount = 0;

        $coursePenaltyPresentCount = 0;

        $coursePenaltyStatus = null;


        /*
        |--------------------------------------------------------------------------
        | ONLY CHECK MISSED MONTHS
        |--------------------------------------------------------------------------
        */

        foreach ($missedMonths as $missedMonth) {

            $attendanceStart =
                $missedMonth['month']
                    ->copy()
                    ->startOfMonth();

            $attendanceEnd =
                $missedMonth['month']
                    ->copy()
                    ->endOfMonth();


            $attendanceRecords =
                Attendance::where('user_id', $studentId)
                    ->where(
                        'course_id',
                        $studentCourse->course_id
                    )
                    ->where(
                        'batch_id',
                        $studentCourse->batch_id
                    )
                    ->whereBetween(
                        'attendance_date',
                        [
                            $attendanceStart->format('Y-m-d'),
                            $attendanceEnd->format('Y-m-d'),
                        ]
                    )
                    ->get();


            $attendanceCount =
                $attendanceRecords->count();


            $presentCount =
                $attendanceRecords
                    ->filter(function ($attendance) {

                        return strtolower(
                            trim(
                                (string) $attendance->status
                            )
                        ) === 'present';
                    })
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | ABSENT MONTH
            |
            | Attendance record ho aur ek bhi Present nahi.
            |
            | Ya attendance records hi nahi hain.
            |--------------------------------------------------------------------------
            */

            if ($presentCount === 0) {

                $coursePenaltyMonth =
                    $missedMonth['name'];

                $coursePenaltyAttendanceCount =
                    $attendanceCount;

                $coursePenaltyPresentCount =
                    $presentCount;

                $coursePenaltyStatus =
                    $attendanceCount === 0
                        ? 'No Attendance Record'
                        : 'Absent';


                /*
                |--------------------------------------------------------------
                | IMPORTANT
                |
                | Penalty is based on ONE course fee.
                | It does NOT multiply for every absent month.
                |--------------------------------------------------------------
                */

                $coursePenaltyFee =
                    (
                        $monthlyFee *
                        $absentPercentage
                    ) / 100;


                /*
                |--------------------------------------------------------------
                | Once penalty month found, stop.
                |--------------------------------------------------------------
                */

                break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | EXACTLY ONE MONTH GAP
        |
        | June paid
        | July current
        |
        | Difference = 1
        |--------------------------------------------------------------------------
        */

        if ($monthDifference === 1) {

            /*
            |--------------------------------------------------------------------------
            | CURRENT BILLING MONTH DUE DATE
            |--------------------------------------------------------------------------
            */

            $actualDueDay =
                min(
                    $dueDay,
                    $currentBillingMonth
                        ->copy()
                        ->endOfMonth()
                        ->day
                );

            $dueDate =
                $currentBillingMonth
                    ->copy()
                    ->day($actualDueDay)
                    ->startOfDay();


            /*
            |--------------------------------------------------------------------------
            | BEFORE DUE DATE
            |
            | 1 - 4 => no fine
            | 5+    => same_month_late_fee
            |--------------------------------------------------------------------------
            */

            if ($billingFrom->lt($dueDate)) {

                return response()->json([

                    'status' => true,

                    'apply' => false,

                    'is_first_payment' => false,

                    'billing_from' =>
                        $billingFrom->format('Y-m-d'),

                    'billing_to' =>
                        $billingTo->format('Y-m-d'),

                    'billing_month_count' =>
                        count($selectedMonths),

                    'pending_month_count' =>
                        $pendingMonthCount,

                    'already_paid_month_count' =>
                        count($alreadyPaidMonths),

                    'billing_months' =>
                        collect($selectedMonths)
                            ->pluck('name')
                            ->values()
                            ->all(),

                    'already_paid_months' =>
                        $alreadyPaidMonths,

                    'pending_months' =>
                        $pendingMonths,

                    'course_fee' =>
                        $monthlyFee,

                    'total_course_fee' =>
                        round($totalCourseFee, 2),

                    'registration_fee' =>
                        0,

                    'admission_fee' =>
                        0,

                    'late_fine' =>
                        0,

                    'course_penalty_fee' =>
                        round($coursePenaltyFee, 2),

                    'fine_type' =>
                        $coursePenaltyFee > 0
                            ? 'course_penalty_fee'
                            : null,

                    'fine_heading' =>
                        $coursePenaltyFee > 0
                            ? 'Course Penalty Fee'
                            : 'No Fine',

                    'total_billing_amount' =>
                        round(
                            $totalCourseFee
                            + $coursePenaltyFee,
                            2
                        ),

                    'previous_paid_month' =>
                        $previousPaidMonthName,

                    'previous_payment_date' =>
                        $previousPaymentDate,

                    'current_billing_month' =>
                        $currentBillingMonthName,

                    'due_date' =>
                        $dueDate->format('Y-m-d'),

                    'month_difference' =>
                        $monthDifference,

                    'attendance_month' =>
                        $coursePenaltyMonth,

                    'attendance_status' =>
                        $coursePenaltyStatus,

                    'attendance_count' =>
                        $coursePenaltyAttendanceCount,

                    'present_count' =>
                        $coursePenaltyPresentCount,

                    'message' =>
                        $coursePenaltyFee > 0
                            ? "Payment is before the due date. However, {$coursePenaltyMonth} has no Present attendance, so Course Penalty Fee is applicable."
                            : "Payment is being made before the {$dueDate->format('d F Y')} due date. No late fine is applicable.",
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | DUE DATE OR AFTER
            |
            | User specifically said:
            |
            | 5 ya uske baad => same_month_late_fee
            |--------------------------------------------------------------------------
            */

            $lateFine =
                $sameMonthLateFee;


            $totalBillingAmount =
                $totalCourseFee
                + $lateFine
                + $coursePenaltyFee;


            return response()->json([

                'status' => true,

                'apply' =>
                    $lateFine > 0 ||
                    $coursePenaltyFee > 0,

                'is_first_payment' => false,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' =>
                    $pendingMonthCount,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    $pendingMonths,

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    round($totalCourseFee, 2),

                'registration_fee' =>
                    0,

                'admission_fee' =>
                    0,

                'late_fine' =>
                    round($lateFine, 2),

                'course_penalty_fee' =>
                    round($coursePenaltyFee, 2),

                'fine_type' =>
                    $coursePenaltyFee > 0
                        ? 'course_penalty_fee'
                        : 'same_month_late_fee',

                'fine_heading' =>
                    $coursePenaltyFee > 0
                        ? 'Course Penalty Fee'
                        : 'Same Month Late Fee',

                'total_billing_amount' =>
                    round($totalBillingAmount, 2),

                'previous_paid_month' =>
                    $previousPaidMonthName,

                'previous_payment_date' =>
                    $previousPaymentDate,

                'current_billing_month' =>
                    $currentBillingMonthName,

                'due_date' =>
                    $dueDate->format('Y-m-d'),

                'month_difference' =>
                    $monthDifference,

                'attendance_month' =>
                    $coursePenaltyMonth,

                'attendance_status' =>
                    $coursePenaltyStatus,

                'attendance_count' =>
                    $coursePenaltyAttendanceCount,

                'present_count' =>
                    $coursePenaltyPresentCount,

                'message' =>
                    $coursePenaltyFee > 0
                        ? "Payment is on/after the due date. Same Month Late Fee of ₹{$lateFine} and Course Penalty Fee of ₹" . round($coursePenaltyFee, 2) . " are applicable."
                        : "Payment is on/after the {$dueDate->format('d F Y')} due date. Same Month Late Fee of ₹{$lateFine} is applicable.",
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | MORE THAN ONE MONTH GAP
        |
        | Example:
        |
        | June paid
        | July unpaid
        | August current
        |
        | => next_month_late_fee
        |
        | User pays:
        |
        | July + August Course Fee
        | + ₹200 Next Month Late Fee
        |--------------------------------------------------------------------------
        */

        if ($monthDifference > 1) {

            /*
            |--------------------------------------------------------------------------
            | IF COURSE PENALTY EXISTS
            |
            | Course Penalty is separate from late fine.
            |
            | Example:
            |
            | July absent
            | August absent
            | September payment
            |
            | Course Penalty Fee applies.
            |
            | But we don't stack absent penalty and next month late fee
            | for the same missed situation.
            |--------------------------------------------------------------------------
            */

            if ($coursePenaltyFee > 0) {

                $totalBillingAmount =
                    $totalCourseFee
                    + $coursePenaltyFee;


                return response()->json([

                    'status' => true,

                    'apply' => true,

                    'is_first_payment' => false,

                    'billing_from' =>
                        $billingFrom->format('Y-m-d'),

                    'billing_to' =>
                        $billingTo->format('Y-m-d'),

                    'billing_month_count' =>
                        count($selectedMonths),

                    'pending_month_count' =>
                        $pendingMonthCount,

                    'already_paid_month_count' =>
                        count($alreadyPaidMonths),

                    'billing_months' =>
                        collect($selectedMonths)
                            ->pluck('name')
                            ->values()
                            ->all(),

                    'already_paid_months' =>
                        $alreadyPaidMonths,

                    'pending_months' =>
                        $pendingMonths,

                    'course_fee' =>
                        $monthlyFee,

                    'total_course_fee' =>
                        round($totalCourseFee, 2),

                    'registration_fee' =>
                        0,

                    'admission_fee' =>
                        0,

                    'late_fine' =>
                        0,

                    'course_penalty_fee' =>
                        round($coursePenaltyFee, 2),

                    'fine_type' =>
                        'course_penalty_fee',

                    'fine_heading' =>
                        'Course Penalty Fee',

                    'total_billing_amount' =>
                        round($totalBillingAmount, 2),

                    'previous_paid_month' =>
                        $previousPaidMonthName,

                    'previous_payment_date' =>
                        $previousPaymentDate,

                    'current_billing_month' =>
                        $currentBillingMonthName,

                    'month_difference' =>
                        $monthDifference,

                    'attendance_month' =>
                        $coursePenaltyMonth,

                    'attendance_status' =>
                        $coursePenaltyStatus,

                    'attendance_count' =>
                        $coursePenaltyAttendanceCount,

                    'present_count' =>
                        $coursePenaltyPresentCount,

                    'absent_percentage' =>
                        $absentPercentage,

                    'message' =>
                        "Payment gap is {$monthDifference} month(s). "
                        . "The missed course month {$coursePenaltyMonth} has no Present attendance. "
                        . "Therefore Course Penalty Fee of "
                        . "{$absentPercentage}% of monthly course fee is applied. "
                        . "No separate late fine is added.",
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | NORMAL NEXT MONTH LATE FEE
            |--------------------------------------------------------------------------
            */

            $lateFine =
                $nextMonthLateFee;


            $totalBillingAmount =
                $totalCourseFee
                + $lateFine;


            return response()->json([

                'status' => true,

                'apply' =>
                    $lateFine > 0,

                'is_first_payment' => false,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' =>
                    $pendingMonthCount,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    $pendingMonths,

                'missed_months' =>
                    collect($missedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    round($totalCourseFee, 2),

                'registration_fee' =>
                    0,

                'admission_fee' =>
                    0,

                'late_fine' =>
                    round($lateFine, 2),

                'course_penalty_fee' =>
                    0,

                'fine_type' =>
                    'next_month_late_fee',

                'fine_heading' =>
                    'Next Month Late Fee',

                'total_billing_amount' =>
                    round($totalBillingAmount, 2),

                'previous_paid_month' =>
                    $previousPaidMonthName,

                'previous_payment_date' =>
                    $previousPaymentDate,

                'current_billing_month' =>
                    $currentBillingMonthName,

                'month_difference' =>
                    $monthDifference,

                'attendance_month' =>
                    null,

                'attendance_status' =>
                    'Payment gap greater than one month',

                'attendance_count' =>
                    0,

                'present_count' =>
                    0,

                'message' =>
                    "Previous paid month was {$previousPaidMonthName}. "
                    . "Current billing month is {$currentBillingMonthName}. "
                    . "There is a {$monthDifference}-month payment gap. "
                    . "Next Month Late Fee of ₹{$lateFine} is applied. "
                    . "Only the applicable late fee is added; billing months are charged separately.",
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'apply' => false,

            'is_first_payment' => false,

            'billing_from' =>
                $billingFrom->format('Y-m-d'),

            'billing_to' =>
                $billingTo->format('Y-m-d'),

            'billing_month_count' =>
                count($selectedMonths),

            'pending_month_count' =>
                $pendingMonthCount,

            'already_paid_month_count' =>
                count($alreadyPaidMonths),

            'billing_months' =>
                collect($selectedMonths)
                    ->pluck('name')
                    ->values()
                    ->all(),

            'already_paid_months' =>
                $alreadyPaidMonths,

            'pending_months' =>
                $pendingMonths,

            'course_fee' =>
                $monthlyFee,

            'total_course_fee' =>
                round($totalCourseFee, 2),

            'registration_fee' =>
                0,

            'admission_fee' =>
                0,

            'late_fine' =>
                0,

            'course_penalty_fee' =>
                0,

            'fine_type' =>
                null,

            'fine_heading' =>
                'No Fine',

            'total_billing_amount' =>
                round($totalCourseFee, 2),

            'message' =>
                'No late fine or course penalty is applicable.',
        ]);
    }

    public function paymentHistory(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. PAYMENT QUERY
        |--------------------------------------------------------------------------
        */

        $query = CoursePaymentRecord::query()
            ->with([
                'studentCourse.student',
                'studentCourse.course',
                'studentCourse.batch',
                'studentCourse.level',
                'studentCourse.category',
            ]);

        /*
        |--------------------------------------------------------------------------
        | 2. STUDENT FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('student_id')) {

            $query->whereHas('studentCourse', function ($q) use ($request) {
                $q->where('user_id', $request->student_id);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | 3. COURSE FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('course_id')) {

            $query->whereHas('studentCourse', function ($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | 4. BATCH FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('batch_id')) {

            $query->whereHas('studentCourse', function ($q) use ($request) {
                $q->where('batch_id', $request->batch_id);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | 5. FROM DATE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'payment_date',
                '>=',
                $request->from_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. TO DATE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('to_date')) {

            $query->whereDate(
                'payment_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. PAYMENT STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 8. PAYMENT MODE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_mode')) {

            $query->where(
                'payment_mode',
                $request->payment_mode
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 9. SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                /*
                | Student
                */

                $q->whereHas(
                    'studentCourse.student',
                    function ($studentQuery) use ($search) {

                        $studentQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'user_id',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'phone',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            );
                    }
                );

                /*
                | Course
                */

                $q->orWhereHas(
                    'studentCourse.course',
                    function ($courseQuery) use ($search) {

                        $courseQuery->where(
                            'course_name',
                            'like',
                            "%{$search}%"
                        );
                    }
                );

                /*
                | Batch
                */

                $q->orWhereHas(
                    'studentCourse.batch',
                    function ($batchQuery) use ($search) {

                        $batchQuery->where(
                            'batch_name',
                            'like',
                            "%{$search}%"
                        );
                    }
                );

                /*
                | Transaction
                */

                $q->orWhere(
                    'transaction_id',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | 10. GET RAW PAYMENT RECORDS
        |--------------------------------------------------------------------------
        */

        $paymentRecords = $query
            ->latest('payment_date')
            ->latest('created_at')
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 11. GET ACTUAL STATUS DIRECTLY FROM DATABASE
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Do not depend on $record->status here.
        |
        | We are fetching status directly from the table so that any
        | accessor/mutator/cast in CoursePaymentRecord model cannot
        | change the status value.
        |
        */

        $recordIds = $paymentRecords
            ->pluck('id')
            ->filter()
            ->values();

        $actualStatuses = collect();

        if ($recordIds->count()) {

            $actualStatuses = DB::table('course_payment_records')
                ->whereIn('id', $recordIds)
                ->pluck('status', 'id')
                ->map(function ($status) {

                    return strtolower(
                        trim((string) $status)
                    );

                });
        }

        /*
        |--------------------------------------------------------------------------
        | 12. ATTACH ACTUAL STATUS TO EACH RECORD
        |--------------------------------------------------------------------------
        */

        $paymentRecords->each(function ($record) use ($actualStatuses) {

            $record->actual_status = $actualStatuses->get(
                $record->id
            );

        });

        /*
        |--------------------------------------------------------------------------
        | 13. GROUP PAYMENT RECORDS
        |--------------------------------------------------------------------------
        |
        | Same student course + same created_at second
        | = ONE PAYMENT GROUP
        |
        */

        $payments = $paymentRecords
                ->groupBy(function ($payment) {

                    return $payment->payment_id
                        ?: (
                            $payment->student_course_id
                            . '|'
                            . (
                                $payment->created_at
                                    ? $payment->created_at->format('Y-m-d H:i:s')
                                    : 'no-created-at'
                            )
                        );
                })
            ->map(function ($records) {

                /*
                |--------------------------------------------------------------------------
                | MAIN / REPRESENTATIVE RECORD
                |--------------------------------------------------------------------------
                */

                $payment = $records->first();

                /*
                |--------------------------------------------------------------------------
                | KEEP ALL ORIGINAL RECORDS
                |--------------------------------------------------------------------------
                */

                $payment->grouped_payment_records = $records->values();

                /*
                |--------------------------------------------------------------------------
                | TOTAL AMOUNT
                |--------------------------------------------------------------------------
                */

                $payment->total_amount = $records->sum(
                    fn ($record) => (float) $record->amount
                );

                /*
                |--------------------------------------------------------------------------
                | PAYMENT MODE BREAKDOWN
                |--------------------------------------------------------------------------
                */

                $payment->payment_breakdown = $records
                    ->groupBy(function ($record) {

                        return $record->payment_mode ?: 'Other';

                    })
                    ->map(function ($modeRecords) {

                        return [
                            'amount' => $modeRecords->sum(
                                fn ($record) => (float) $record->amount
                            ),

                            'records' => $modeRecords->values(),
                        ];

                    });

                /*
                |--------------------------------------------------------------------------
                | TRANSACTION / REFERENCE BREAKDOWN
                |--------------------------------------------------------------------------
                */

                $payment->transaction_breakdown = $records
                    ->map(function ($record) {

                        return [
                            'payment_mode' => $record->payment_mode ?: 'Other',

                            'transaction_id' => $record->transaction_id,

                            'amount' => (float) $record->amount,

                            /*
                            | VERY IMPORTANT
                            | Use actual DB status
                            */

                            'status' => $record->actual_status,
                        ];

                    })
                    ->values();

                /*
                |--------------------------------------------------------------------------
                | OVERALL STATUS
                |--------------------------------------------------------------------------
                |
                | Use ACTUAL DB status only.
                |
                */

                $statuses = $records
                    ->map(function ($record) {

                        return $record->actual_status;

                    })
                    ->filter()
                    ->values();

                /*
                | All success
                */

                if (
                    $statuses->count() > 0 &&
                    $statuses->every(
                        fn ($status) => $status === 'success'
                    )
                ) {

                    $payment->status = 'success';

                /*
                | Any pending
                */

                } elseif ($statuses->contains('pending')) {

                    $payment->status = 'pending';

                /*
                | Any failed
                */

                } elseif ($statuses->contains('failed')) {

                    $payment->status = 'failed';

                /*
                | Any cancelled
                */

                } elseif ($statuses->contains('cancelled')) {

                    $payment->status = 'cancelled';

                /*
                | Any refunded
                */

                } elseif ($statuses->contains('refunded')) {

                    $payment->status = 'refunded';

                /*
                | Fallback
                */

                } else {

                    $payment->status = $statuses->first() ?? null;
                }

                /*
                |--------------------------------------------------------------------------
                | COMBINED REMARKS
                |--------------------------------------------------------------------------
                */

                $payment->combined_remarks = $records
                    ->pluck('remarks')
                    ->filter()
                    ->unique()
                    ->implode(' | ');

                return $payment;

            })
            ->sortByDesc(function ($payment) {

                return $payment->created_at
                    ? $payment->created_at->timestamp
                    : 0;

            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | 14. FILTER DATA
        |--------------------------------------------------------------------------
        */

        $students = User::query()
            ->whereHas('studentCourses')
            ->orderBy('name')
            ->get([
                'id',
                'user_id',
                'name',
                'email',
                'phone',
            ]);

        $courses = Course::query()
            ->orderBy('course_name')
            ->get([
                'id',
                'course_name',
            ]);

        $batches = Batch::query()
            ->orderBy('batch_name')
            ->get([
                'id',
                'batch_name',
            ]);

        /*
        |--------------------------------------------------------------------------
        | 15. PAYMENT MODES
        |--------------------------------------------------------------------------
        */

        $paymentModes = CoursePaymentRecord::query()
            ->whereNotNull('payment_mode')
            ->where('payment_mode', '!=', '')
            ->distinct()
            ->orderBy('payment_mode')
            ->pluck('payment_mode');

        /*
        |--------------------------------------------------------------------------
        | 16. SUMMARY
        |--------------------------------------------------------------------------
        */

        $successfulPayments = $payments->where(
            'status',
            'success'
        );

        $totalPayments = $payments->count();

        $successfulAmount = $successfulPayments->sum(
            'total_amount'
        );

        /*
        |--------------------------------------------------------------------------
        | 17. RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.payment-history.course-payment',
            compact(
                'payments',
                'students',
                'courses',
                'batches',
                'paymentModes',
                'totalPayments',
                'successfulAmount'
            )
        );
    }

    // public function paymentHistory(Request $request)
    // {
    //     /*
    //     |--------------------------------------------------------------------------
    //     | 1. PAYMENT QUERY
    //     |--------------------------------------------------------------------------
    //     */

    //     $query = CoursePaymentRecord::query()
    //         ->with([
    //             'studentCourse.student',
    //             'studentCourse.course',
    //             'studentCourse.batch',
    //             'studentCourse.level',
    //             'studentCourse.category',
    //         ]);


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 2. STUDENT FILTER
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('student_id')) {

    //         $query->whereHas('studentCourse', function ($q) use ($request) {

    //             $q->where('user_id', $request->student_id);

    //         });
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 3. COURSE FILTER
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('course_id')) {

    //         $query->whereHas('studentCourse', function ($q) use ($request) {

    //             $q->where('course_id', $request->course_id);

    //         });
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 4. BATCH FILTER
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('batch_id')) {

    //         $query->whereHas('studentCourse', function ($q) use ($request) {

    //             $q->where('batch_id', $request->batch_id);

    //         });
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 5. FROM DATE
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('from_date')) {

    //         $query->whereDate(
    //             'payment_date',
    //             '>=',
    //             $request->from_date
    //         );
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 6. TO DATE
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('to_date')) {

    //         $query->whereDate(
    //             'payment_date',
    //             '<=',
    //             $request->to_date
    //         );
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 7. PAYMENT STATUS
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('status')) {

    //         $query->where(
    //             'status',
    //             $request->status
    //         );
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 8. PAYMENT MODE
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->filled('payment_mode')) {

    //         $query->where(
    //             'payment_mode',
    //             $request->payment_mode
    //         );
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 9. SEARCH
    //     |--------------------------------------------------------------------------
    //     |
    //     | Search:
    //     | - Student Name
    //     | - Student User ID
    //     | - Student Phone
    //     | - Student Email
    //     | - Course
    //     | - Batch
    //     | - Transaction ID
    //     |
    //     */

    //     if ($request->filled('search')) {

    //         $search = trim($request->search);

    //         $query->where(function ($q) use ($search) {

    //             /*
    //             | Student
    //             */

    //             $q->whereHas(
    //                 'studentCourse.student',
    //                 function ($studentQuery) use ($search) {

    //                     $studentQuery
    //                         ->where(
    //                             'name',
    //                             'like',
    //                             "%{$search}%"
    //                         )
    //                         ->orWhere(
    //                             'user_id',
    //                             'like',
    //                             "%{$search}%"
    //                         )
    //                         ->orWhere(
    //                             'phone',
    //                             'like',
    //                             "%{$search}%"
    //                         )
    //                         ->orWhere(
    //                             'email',
    //                             'like',
    //                             "%{$search}%"
    //                         );
    //                 }
    //             );


    //             /*
    //             | Course
    //             */

    //             $q->orWhereHas(
    //                 'studentCourse.course',
    //                 function ($courseQuery) use ($search) {

    //                     $courseQuery->where(
    //                         'course_name',
    //                         'like',
    //                         "%{$search}%"
    //                     );
    //                 }
    //             );


    //             /*
    //             | Batch
    //             */

    //             $q->orWhereHas(
    //                 'studentCourse.batch',
    //                 function ($batchQuery) use ($search) {

    //                     $batchQuery->where(
    //                         'batch_name',
    //                         'like',
    //                         "%{$search}%"
    //                     );
    //                 }
    //             );


    //             /*
    //             | Transaction
    //             */

    //             $q->orWhere(
    //                 'transaction_id',
    //                 'like',
    //                 "%{$search}%"
    //             );

    //         });
    //     }


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 10. GET PAYMENTS
    //     |--------------------------------------------------------------------------
    //     */

    //     $payments = $query
    //         ->latest('payment_date')
    //         ->latest('id')
    //         ->get();


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 11. FILTER DATA
    //     |--------------------------------------------------------------------------
    //     */

    //     $students = User::query()
    //         ->whereHas('studentCourses')
    //         ->orderBy('name')
    //         ->get([
    //             'id',
    //             'user_id',
    //             'name',
    //             'email',
    //             'phone',
    //         ]);


    //     $courses = Course::query()
    //         ->orderBy('course_name')
    //         ->get([
    //             'id',
    //             'course_name',
    //         ]);


    //     $batches = Batch::query()
    //         ->orderBy('batch_name')
    //         ->get([
    //             'id',
    //             'batch_name',
    //         ]);


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 12. PAYMENT MODES
    //     |--------------------------------------------------------------------------
    //     */

    //     $paymentModes = CoursePaymentRecord::query()
    //         ->whereNotNull('payment_mode')
    //         ->where('payment_mode', '!=', '')
    //         ->distinct()
    //         ->orderBy('payment_mode')
    //         ->pluck('payment_mode');


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 13. SUMMARY
    //     |--------------------------------------------------------------------------
    //     */

    //     $successfulPayments = $payments->where(
    //         'status',
    //         'success'
    //     );

    //     $totalPayments = $payments->count();

    //     $successfulAmount = $successfulPayments->sum(
    //         'amount'
    //     );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | 14. RETURN VIEW
    //     |--------------------------------------------------------------------------
    //     */

    //     return view(
    //         'backend.payment-history.course-payment',
    //         compact(
    //             'payments',
    //             'students',
    //             'courses',
    //             'batches',
    //             'paymentModes',
    //             'totalPayments',
    //             'successfulAmount'
    //         )
    //     );
    // }

    public function destroy(StudentCourse $studentCourse)
    {
        /*
        |--------------------------------------------------------------------------
        | DELETE COMPLETE BILLING / PAYMENT HISTORY
        |--------------------------------------------------------------------------
        |
        | This will remove:
        |
        | 1. Course Payment Records
        | 2. Late Fine Records
        | 3. Course Month Records
        |
        | StudentCourse itself will NOT be deleted.
        |
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. DELETE PAYMENT RECORDS
            |--------------------------------------------------------------------------
            */

            CoursePaymentRecord::query()
                ->where(
                    'student_course_id',
                    $studentCourse->id
                )
                ->delete();


            /*
            |--------------------------------------------------------------------------
            | 2. DELETE LATE FINE / PENALTY RECORDS
            |--------------------------------------------------------------------------
            */

            LateFineRecord::query()
                ->where(
                    'student_course_id',
                    $studentCourse->id
                )
                ->delete();


            /*
            |--------------------------------------------------------------------------
            | 3. DELETE MONTHLY BILLING RECORDS
            |--------------------------------------------------------------------------
            */

            CourseMonthRecord::query()
                ->where(
                    'student_course_id',
                    $studentCourse->id
                )
                ->delete();


            /*
            |--------------------------------------------------------------------------
            | 4. COMMIT
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | 5. SUCCESS
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('billing.index')
                ->with(
                    'success',
                    'Complete billing and payment history deleted successfully.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ROLLBACK
        |--------------------------------------------------------------------------
        */

        catch (\Throwable $e) {

            DB::rollBack();


            Log::error(
                'COMPLETE BILLING DELETE ERROR',
                [
                    'student_course_id' => $studentCourse->id,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );


            return redirect()
                ->route('billing.index')
                ->with(
                    'error',
                    'Unable to delete billing history: ' .
                    $e->getMessage()
                );
        }
    }
}
