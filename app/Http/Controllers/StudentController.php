<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\CourseMonthRecord;
use App\Models\CoursePaymentRecord;
use App\Models\LateFine;
use App\Models\MembershipPlan;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('student.index', compact('user'));
    }

    public function studentProfile(){
        if (!Auth::check() || Auth::user()->user_type != 'student') {
            abort(403, 'Unauthorized Access');
        }

        $student = Auth::user();
        return view('student.profile', compact('student'));
    }

    public function editProfile()
    {
        if (!Auth::check() || Auth::user()->user_type != 'student') {

            abort(403);

        }

        $student = Auth::user();

        return view('student.edit-profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check() || Auth::user()->user_type != 'student') {

            abort(403);

        }

        $student = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',

            'email' => 'nullable|email|max:255|unique:users,email,' . $student->id,

            'phone' => 'required|string|max:15',

            'whatsapp_no' => 'nullable|string|max:15',

            'date_of_birth' => 'nullable|date',

            'gender' => 'nullable|in:Male,Female,Other',

            'religion' => 'nullable|string|max:100',

            'mother_tongue' => 'nullable|string|max:100',

            'occupation' => 'nullable|string|max:150',

            'qualification' => 'nullable|string|max:150',

            'guardian_name' => 'nullable|string|max:255',

            'guardian_contact' => 'nullable|string|max:20',

            'guardian_occupation' => 'nullable|string|max:255',

            'local_guardian_name' => 'nullable|string|max:255',

            'local_guardian_relation' => 'nullable|string|max:255',

            'address' => 'nullable|string',

            'city' => 'nullable|string|max:100',

            'state' => 'nullable|string|max:100',

            'country' => 'nullable|string|max:100',

            'pincode' => 'nullable|string|max:20',

            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'signature' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        if ($validator->fails()) {

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();

        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Profile Image Upload
            |--------------------------------------------------------------------------
            */

            $profileImage = $student->profile_image;

            if ($request->hasFile('profile_image')) {

                if (
                    $profileImage &&
                    Storage::disk('public')->exists($profileImage)
                ) {

                    Storage::disk('public')->delete($profileImage);

                }

                $profileImage = $request
                    ->file('profile_image')
                    ->store('students', 'public');

            }

            /*
            |--------------------------------------------------------------------------
            | Signature Upload
            |--------------------------------------------------------------------------
            */

            $signature = $student->signature;

            if ($request->hasFile('signature')) {

                if (
                    $signature &&
                    Storage::disk('public')->exists($signature)
                ) {

                    Storage::disk('public')->delete($signature);

                }

                $signature = $request
                    ->file('signature')
                    ->store('students/signatures', 'public');

            }

            /*
            |--------------------------------------------------------------------------
            | Update Profile
            |--------------------------------------------------------------------------
            */

            $student->update([

                'name' => $request->name,

                'email' => $request->email,

                'phone' => $request->phone,

                'whatsapp_no' => $request->whatsapp_no,

                'date_of_birth' => $request->date_of_birth,

                'gender' => $request->gender,

                'religion' => $request->religion,

                'mother_tongue' => $request->mother_tongue,

                'occupation' => $request->occupation,

                'qualification' => $request->qualification,

                'guardian_name' => $request->guardian_name,

                'guardian_contact' => $request->guardian_contact,

                'guardian_occupation' => $request->guardian_occupation,

                'local_guardian_name' => $request->local_guardian_name,

                'local_guardian_relation' => $request->local_guardian_relation,

                'address' => $request->address,

                'city' => $request->city,

                'state' => $request->state,

                'country' => $request->country,

                'pincode' => $request->pincode,

                'profile_image' => $profileImage,

                'signature' => $signature,

            ]);

            DB::commit();

            return redirect()
                ->route('student.profile')
                ->with('success', 'Profile updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    public function studentIdCard()
    {
        $student = Auth::user();

        if (!$student || $student->user_type != 'student') {
            abort(403);
        }

        $course = StudentCourse::with([
            'course',
            'level',
            'batch',
            'category'
        ])
        ->where('user_id', $student->id)
        ->where('status', 'ongoing')
        ->where('is_enroll', 1)
        ->latest()
        ->first();

        return view(
            'student.id-card',
            compact('student', 'course')
        );
    }

    public function myCourses()
    {
        $student = Auth::user();

        $courses = StudentCourse::with([
            'course',
            'level',
            'category',
            'batch',
            'instructor',
            'paymentRecords' => function ($query) {
                $query->latest('id');
            }
        ])
        ->where('user_id', $student->id)
        ->latest()
        ->get();

        return view(
            'student.my-courses',
            compact('student', 'courses')
        );
    }

    public function courseDetails(StudentCourse $studentCourse)
    {
        // Security
        if ($studentCourse->user_id != Auth::id()) {
            abort(403);
        }

        $studentCourse->load([
            'course',
            'level',
            'category',
            'student',
            'instructor',
            'batch' => function ($query) {
                $query->withCount([
                    'studentCourses as enrolled_students_count' => function ($q) {
                        $q->activeEnroll();
                    }
                ]);
            }
        ]);

        return view(
            'student.course-details',
            compact('studentCourse')
        );
    }

    public function certificate()
    {
        if (!Auth::check() || Auth::user()->user_type != 'student') {
            abort(403);
        }

        $certificates = Certificate::with('course','level')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('student.certificate', compact('certificates'));
    }

    public function payments()
    {
        /*
        |--------------------------------------------------------------------------
        | Logged In Student
        |--------------------------------------------------------------------------
        */

        $student = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Get All Payment Attempts
        |--------------------------------------------------------------------------
        |
        | Every payment record is treated as a separate payment attempt.
        |
        */

        $payments = CoursePaymentRecord::with([
            'studentCourse.course',
            'studentCourse.level',
            'studentCourse.category',
            'studentCourse.batch',
        ])
        ->where('user_id', $student->id)
        ->latest('id')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'student.payments',
            compact(
                'student',
                'payments'
            )
        );
    }

    public function paymentInvoice($id)
    {
        /*
        |--------------------------------------------------------------------------
        | Logged In Student
        |--------------------------------------------------------------------------
        */

        $student = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Find Payment
        |--------------------------------------------------------------------------
        | Only allow the logged-in student's payment record.
        |--------------------------------------------------------------------------
        */

        $payment = CoursePaymentRecord::with([
            'student',
            'studentCourse.course',
            'studentCourse.level',
            'studentCourse.category',
            'studentCourse.batch',
            'studentCourse.instructor',
        ])
        ->where('id', $id)
        ->where('user_id', $student->id)
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Invoice Only For Successful Payment
        |--------------------------------------------------------------------------
        */

        if (strtolower($payment->status) !== 'success') {

            return redirect()
                ->route('student.payments')
                ->with(
                    'error',
                    'Invoice is available only for successful payments.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Related Student Course
        |--------------------------------------------------------------------------
        */

        $studentCourse = $payment->studentCourse;


        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        */

        if (!$studentCourse) {

            return redirect()
                ->route('student.payment-invoice')
                ->with(
                    'error',
                    'Course information not found for this payment.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Return Invoice View
        |--------------------------------------------------------------------------
        */

        return view(
            'student.payment-invoice',
            compact(
                'student',
                'payment',
                'studentCourse'
            )
        );
    }


    public function customMonthlyFeePay()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Only Student
        |--------------------------------------------------------------------------
        */

        if (!$user || $user->user_type !== 'student') {
            abort(403, 'Unauthorized access.');
        }

        /*
        |--------------------------------------------------------------------------
        | Fetch Enrolled Courses
        |--------------------------------------------------------------------------
        */

        $studentCourses = StudentCourse::with([
            'course',
            'level',
            'category',
            'batch',
        ])
            ->where('user_id', $user->id)
            ->where('is_enroll', 1)
            ->orderBy('id', 'desc')
            ->get();

        return view(
            'student.monthly-fee-pay',
            compact('studentCourses')
        );
    }

    public function customMonthlyFeePayDetails(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | 1. Student Authentication
        |--------------------------------------------------------------------------
        */

        if (!$user || $user->user_type !== 'student') {

            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'from_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'to_date' => [
                'required',
                'date',
                'after_or_equal:from_date',
            ],

            'student_course_id' => [
                'required',
                'integer',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | 3. Get Student Course
        |--------------------------------------------------------------------------
        */

        $studentCourse = StudentCourse::with([
            'course',
            'level',
            'category',
            'batch',
        ])
            ->where('id', $validated['student_course_id'])
            ->where('user_id', $user->id)
            ->where('is_enroll', 1)
            ->first();

        if (!$studentCourse) {

            return response()->json([
                'status' => false,
                'message' => 'Invalid course selected.'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Dates
        |--------------------------------------------------------------------------
        */

        $requestedFromDate = Carbon::parse(
            $validated['from_date']
        )->startOfMonth();

        $requestedToDate = Carbon::parse(
            $validated['to_date']
        )->startOfMonth();

        $today = Carbon::today();

        $todayMonth = $today->copy()->startOfMonth();

        /*
        |--------------------------------------------------------------------------
        | 5. Monthly Fee
        |--------------------------------------------------------------------------
        */

        $monthlyFee = (float) $studentCourse->monthly_fee;

        /*
        |--------------------------------------------------------------------------
        | 6. Existing Paid Records
        |--------------------------------------------------------------------------
        */

        $paidRecords = CourseMonthRecord::where(
            'student_course_id',
            $studentCourse->id
        )
            ->where('status', 'paid')
            ->get();

        $hasPaidRecord = $paidRecords->isNotEmpty();

        /*
        |--------------------------------------------------------------------------
        | Paid Month Keys
        |--------------------------------------------------------------------------
        */

        $paidMonths = $paidRecords
            ->map(function ($record) {

                return Carbon::parse(
                    $record->fee_month
                )
                    ->startOfMonth()
                    ->format('Y-m');
            })
            ->unique()
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | 7. Late Fine Settings
        |--------------------------------------------------------------------------
        */

        $lateFine = LateFine::first();

        /*
        |--------------------------------------------------------------------------
        | 8. Last Paid Month Before Requested Period
        |--------------------------------------------------------------------------
        */

        $lastPaidBeforePeriod = CourseMonthRecord::where(
            'student_course_id',
            $studentCourse->id
        )
            ->where('status', 'paid')
            ->whereDate(
                'fee_month',
                '<',
                $requestedFromDate
            )
            ->orderBy(
                'fee_month',
                'desc'
            )
            ->first();

        $lastPaidMonth = null;

        if ($lastPaidBeforePeriod) {

            $lastPaidMonth = Carbon::parse(
                $lastPaidBeforePeriod->fee_month
            )
                ->startOfMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | 9. Generate Requested Months
        |--------------------------------------------------------------------------
        */

        $months = [];

        $currentMonth =
            $requestedFromDate->copy();

        /*
        |--------------------------------------------------------------------------
        | Overall Late Fine Debug
        |--------------------------------------------------------------------------
        */

        $lateFineDecision = 'NO_LATE_FINE';

        $lateFineReason = 'No late fine applicable.';

        $lateFineAmountOverall = 0;

        $attendanceCheck = [];

        while ($currentMonth->lte($requestedToDate)) {

            $feeMonth =
                $currentMonth->copy();

            $monthKey =
                $feeMonth->format('Y-m');

            /*
            |--------------------------------------------------------------------------
            | Already Paid
            |--------------------------------------------------------------------------
            */

            if (in_array($monthKey, $paidMonths)) {

                $months[] = [

                    'month' =>
                        $monthKey,

                    'month_name' =>
                        $feeMonth->format('F Y'),

                    'monthly_fee' =>
                        $monthlyFee,

                    'late_fine' =>
                        0,

                    'payable_amount' =>
                        0,

                    'status' =>
                        'paid',

                    'message' =>
                        'This month fee is already paid.',

                    'gap' =>
                        0,

                    'months_without_payment' =>
                        0,

                    'advance_payment' =>
                        false,

                    'arrear' =>
                        false,

                    'late_fine_decision' =>
                        'NO_LATE_FINE',

                ];

                $currentMonth->addMonth();

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Future / Advance Payment
            |--------------------------------------------------------------------------
            */

            if ($feeMonth->gt($todayMonth)) {

                $months[] = [

                    'month' =>
                        $monthKey,

                    'month_name' =>
                        $feeMonth->format('F Y'),

                    'monthly_fee' =>
                        $monthlyFee,

                    'late_fine' =>
                        0,

                    'payable_amount' =>
                        $monthlyFee,

                    'status' =>
                        'unpaid',

                    'message' =>
                        'Advance payment. No late fine applicable.',

                    'gap' =>
                        0,

                    'months_without_payment' =>
                        0,

                    'advance_payment' =>
                        true,

                    'arrear' =>
                        false,

                    'late_fine_decision' =>
                        'ADVANCE_PAYMENT',

                ];

                $currentMonth->addMonth();

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | 10. Previous Paid Month
            |--------------------------------------------------------------------------
            */

            $previousPaid = CourseMonthRecord::where(
                'student_course_id',
                $studentCourse->id
            )
                ->where('status', 'paid')
                ->whereDate(
                    'fee_month',
                    '<',
                    $feeMonth->copy()->startOfMonth()
                )
                ->orderBy(
                    'fee_month',
                    'desc'
                )
                ->first();

            /*
            |--------------------------------------------------------------------------
            | First Payment
            |--------------------------------------------------------------------------
            */

            if (!$previousPaid) {

                $months[] = [

                    'month' =>
                        $monthKey,

                    'month_name' =>
                        $feeMonth->format('F Y'),

                    'monthly_fee' =>
                        $monthlyFee,

                    'late_fine' =>
                        0,

                    'payable_amount' =>
                        $monthlyFee,

                    'status' =>
                        'first_payment',

                    'message' =>
                        'First payment for this course.',

                    'gap' =>
                        0,

                    'months_without_payment' =>
                        0,

                    'advance_payment' =>
                        false,

                    'arrear' =>
                        false,

                    'late_fine_decision' =>
                        'FIRST_PAYMENT',

                ];

                $currentMonth->addMonth();

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Previous Month
            |--------------------------------------------------------------------------
            */

            $previousMonth = Carbon::parse(
                $previousPaid->fee_month
            )
                ->startOfMonth();

            /*
            |--------------------------------------------------------------------------
            | Calendar Difference
            |--------------------------------------------------------------------------
            |
            | July -> August   = 1
            | July -> September = 2
            | July -> October   = 3
            |
            |--------------------------------------------------------------------------
            */

            $monthDiff = (int) $previousMonth->diffInMonths(
                $feeMonth->copy()->startOfMonth()
            );

            /*
            |--------------------------------------------------------------------------
            | Actual Skipped Months
            |--------------------------------------------------------------------------
            |
            | July -> August:
            | 0 skipped
            |
            | July -> September:
            | August = 1 skipped
            |
            | July -> October:
            | August + September = 2 skipped
            |
            |--------------------------------------------------------------------------
            */

            $gapMonths = max(
                0,
                $monthDiff - 1
            );

            $lateFineAmount = 0;

            $monthLateFineDecision =
                'NO_LATE_FINE';

            $monthLateFineReason =
                null;

            $monthAttendanceDebug = [];

            /*
            |--------------------------------------------------------------------------
            | 11. LATE FINE CALCULATION
            |--------------------------------------------------------------------------
            */

            if ($lateFine) {

                /*
                |--------------------------------------------------------------------------
                | CASE 1
                | Immediate Next Month
                |--------------------------------------------------------------------------
                |
                | July Paid -> August Paying
                |
                | Due date passed:
                | same_month_late_fee
                |
                |--------------------------------------------------------------------------
                */

                if ($gapMonths === 0) {

                    if (
                        $today->day >
                        (int) $lateFine->due_date
                    ) {

                        $lateFineAmount =
                            (float) $lateFine->same_month_late_fee;

                        $monthLateFineDecision =
                            'SAME_MONTH_LATE_FEE';

                        $monthLateFineReason =
                            'Due date has passed. Same month late fee applied.';
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | CASE 2
                | Exactly One Skipped Month
                |--------------------------------------------------------------------------
                |
                | July Paid
                | August Skipped
                | September Paying
                |
                | ALWAYS next_month_late_fee.
                |
                | Attendance is NOT checked.
                |
                |--------------------------------------------------------------------------
                */

                elseif ($gapMonths === 1) {

                    $lateFineAmount =
                        (float) $lateFine->next_month_late_fee;

                    $monthLateFineDecision =
                        'NEXT_MONTH_LATE_FEE';

                    $monthLateFineReason =
                        'One month payment gap found. Next month late fee applied.';
                }

                /*
                |--------------------------------------------------------------------------
                | CASE 3
                | Two Or More Skipped Months
                |--------------------------------------------------------------------------
                */

                elseif ($gapMonths >= 2) {

                    $hasPresentInSkippedMonth = false;

                    /*
                    |--------------------------------------------------------------------------
                    | Check Every Skipped Month
                    |--------------------------------------------------------------------------
                    */

                    for (
                        $i = 1;
                        $i <= $gapMonths;
                        $i++
                    ) {

                        $skippedMonth =
                            $previousMonth
                                ->copy()
                                ->addMonths($i)
                                ->startOfMonth();

                        $skippedMonthStart =
                            $skippedMonth
                                ->copy()
                                ->startOfMonth();

                        $skippedMonthEnd =
                            $skippedMonth
                                ->copy()
                                ->endOfMonth();

                        $attendanceRecords =
                            Attendance::query()
                                ->where(
                                    'user_id',
                                    $user->id
                                )
                                ->where(
                                    'course_id',
                                    $studentCourse->course_id
                                )
                                ->whereBetween(
                                    'attendance_date',
                                    [
                                        $skippedMonthStart->toDateString(),
                                        $skippedMonthEnd->toDateString(),
                                    ]
                                )
                                ->get();

                        $hasPresent =
                            $attendanceRecords->contains(
                                function ($record) {

                                    return trim(
                                        (string) $record->status
                                    ) === 'Present';
                                }
                            );

                        $monthAttendanceDebug[] = [

                            'month' =>
                                $skippedMonth->format('Y-m'),

                            'attendance_records' =>
                                $attendanceRecords->count(),

                            'has_present' =>
                                $hasPresent,

                        ];

                        $attendanceCheck[] = [

                            'month' =>
                                $skippedMonth->format('F Y'),

                            'month_key' =>
                                $skippedMonth->format('Y-m'),

                            'attendance_records' =>
                                $attendanceRecords->count(),

                            'has_present' =>
                                $hasPresent,

                        ];

                        if ($hasPresent) {

                            $hasPresentInSkippedMonth =
                                true;
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | ANY PRESENT FOUND
                    |--------------------------------------------------------------------------
                    */

                    if ($hasPresentInSkippedMonth) {

                        $lateFineAmount =
                            (float) $lateFine->next_month_late_fee;

                        $monthLateFineDecision =
                            'NEXT_MONTH_LATE_FEE_PRESENT_FOUND';

                        $monthLateFineReason =
                            'Present attendance found in skipped month. Next month late fee applied.';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | ALL ABSENT / NO RECORD
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $lateFineAmount =
                            $monthlyFee *
                            (
                                (float)
                                $lateFine->absent_charge_percentage
                                / 100
                            );

                        $monthLateFineDecision =
                            'ABSENT_CHARGE_PERCENTAGE';

                        $monthLateFineReason =
                            'All skipped months are absent/no attendance. Absent charge percentage applied.';
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Track Overall Fine
            |--------------------------------------------------------------------------
            */

            if ($lateFineAmount > 0) {

                $lateFineAmountOverall +=
                    $lateFineAmount;

                $lateFineDecision =
                    $monthLateFineDecision;

                $lateFineReason =
                    $monthLateFineReason;
            }

            /*
            |--------------------------------------------------------------------------
            | Payable
            |--------------------------------------------------------------------------
            */

            $payableAmount =
                $monthlyFee +
                $lateFineAmount;

            /*
            |--------------------------------------------------------------------------
            | Add Month
            |--------------------------------------------------------------------------
            */

            $months[] = [

                'month' =>
                    $monthKey,

                'month_name' =>
                    $feeMonth->format('F Y'),

                'monthly_fee' =>
                    $monthlyFee,

                'late_fine' =>
                    round(
                        $lateFineAmount,
                        2
                    ),

                'payable_amount' =>
                    round(
                        $payableAmount,
                        2
                    ),

                'status' =>
                    'unpaid',

                'message' =>
                    $monthLateFineReason,

                'gap' =>
                    $monthDiff,

                'months_without_payment' =>
                    $gapMonths,

                'advance_payment' =>
                    false,

                'arrear' =>
                    false,

                'late_fine_decision' =>
                    $monthLateFineDecision,

                'late_fine_reason' =>
                    $monthLateFineReason,

                'attendance_debug' =>
                    $monthAttendanceDebug,

            ];

            $currentMonth->addMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | 12. Unpaid Months
        |--------------------------------------------------------------------------
        */

        $unpaidMonths = collect($months)
            ->whereIn(
                'status',
                [
                    'unpaid',
                    'first_payment',
                ]
            )
            ->values();

        $unpaidMonthCount =
            $unpaidMonths->count();

        /*
        |--------------------------------------------------------------------------
        | 13. Membership Plans
        |--------------------------------------------------------------------------
        */

        $membershipPlans =
            MembershipPlan::where(
                'is_active',
                1
            )
                ->orderBy('duration')
                ->get();

        $discount = 0;

        $discountPlan = null;

        $discountLabel = null;

        /*
        |--------------------------------------------------------------------------
        | Duration To Months
        |--------------------------------------------------------------------------
        */

        $durationToMonths = function ($plan) {

            $duration =
                (float) $plan->duration;

            $type =
                strtolower(
                    trim(
                        (string) $plan->duration_type
                    )
                );

            if ($type === 'year') {

                return $duration * 12;
            }

            if ($type === 'day') {

                return $duration / 30;
            }

            return $duration;
        };

        /*
        |--------------------------------------------------------------------------
        | 14. Applicable Membership Plan
        |--------------------------------------------------------------------------
        */

        if ($unpaidMonthCount >= 3) {

            $applicablePlans =
                $membershipPlans
                    ->filter(
                        function ($plan) use (
                            $unpaidMonthCount,
                            $durationToMonths
                        ) {

                            return
                                $durationToMonths($plan)
                                <= $unpaidMonthCount;
                        }
                    )
                    ->sortByDesc(
                        function ($plan) use (
                            $durationToMonths
                        ) {

                            return
                                $durationToMonths($plan);
                        }
                    );

            $discountPlan =
                $applicablePlans->first();
        }

        /*
        |--------------------------------------------------------------------------
        | 15. Membership Discount Calculation
        |--------------------------------------------------------------------------
        */

        if ($discountPlan) {

            $discountType =
                strtolower(
                    trim(
                        (string) $discountPlan->discount_type
                    )
                );

            $discountValue =
                (float) $discountPlan->discount_value;

            /*
            |--------------------------------------------------------------------------
            | FLAT
            |--------------------------------------------------------------------------
            */

            if ($discountType === 'flat') {

                $discount =
                    $discountValue;

                $discountLabel =
                    '₹' .
                    number_format(
                        $discountValue,
                        2
                    ) .
                    ' Flat Discount';
            }

            /*
            |--------------------------------------------------------------------------
            | PERCENTAGE
            |--------------------------------------------------------------------------
            |
            | Discount only on monthly course fee.
            | Late fine is NOT discounted.
            |
            |--------------------------------------------------------------------------
            */

            elseif ($discountType === 'percentage') {

                $monthlyFeeTotal =
                    $unpaidMonths->sum(
                        'monthly_fee'
                    );

                $discount =
                    $monthlyFeeTotal *
                    (
                        $discountValue / 100
                    );

                $discountLabel =
                    number_format(
                        $discountValue,
                        2
                    ) .
                    '% Discount';
            }

            /*
            |--------------------------------------------------------------------------
            | MONTH
            |--------------------------------------------------------------------------
            |
            | 6 Month = 1 Month Free
            | 12 Month = 2 Months Free
            |
            |--------------------------------------------------------------------------
            */

            elseif ($discountType === 'month') {

                $planMonths =
                    (int) round(
                        $durationToMonths(
                            $discountPlan
                        )
                    );

                $freeMonths = 0;

                if (
                    $planMonths >= 6 &&
                    $planMonths < 12
                ) {

                    $freeMonths = 1;
                }

                elseif ($planMonths >= 12) {

                    $freeMonths = 2;
                }

                $freeMonths =
                    min(
                        $freeMonths,
                        $unpaidMonthCount
                    );

                $discount =
                    $monthlyFee *
                    $freeMonths;

                $discountLabel =
                    $freeMonths .
                    ' Month' .
                    (
                        $freeMonths > 1
                            ? 's'
                            : ''
                    ) .
                    ' Free';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 16. Totals
        |--------------------------------------------------------------------------
        */

        $totalMonthlyFee =
            $unpaidMonths->sum(
                'monthly_fee'
            );

        $totalLateFine =
            $unpaidMonths->sum(
                'late_fine'
            );

        $grossPayable =
            $totalMonthlyFee +
            $totalLateFine;

        /*
        |--------------------------------------------------------------------------
        | Discount Cannot Exceed Course Fee
        |--------------------------------------------------------------------------
        */

        $totalDiscount =
            min(
                $discount,
                $totalMonthlyFee
            );

        /*
        |--------------------------------------------------------------------------
        | Final Payable
        |--------------------------------------------------------------------------
        */

        $finalPayable =
            max(
                0,
                $grossPayable -
                $totalDiscount
            );

        /*
        |--------------------------------------------------------------------------
        | 17. Arrears
        |--------------------------------------------------------------------------
        */

        $arrearMonths =
            collect($months)
                ->where(
                    'arrear',
                    true
                )
                ->values();

        $arrearMonthCount =
            $arrearMonths->count();

        /*
        |--------------------------------------------------------------------------
        | 18. COMPLETE Late Fine Debug
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Blade directly uses these values.
        |
        |--------------------------------------------------------------------------
        */

        $lateFineDebug = [

            'enabled' =>
                $lateFine !== null,

            'today' =>
                $today->toDateString(),

            'today_month' =>
                $todayMonth->format('Y-m'),

            'requested_from_date' =>
                $validated['from_date'],

            'requested_from_month' =>
                $requestedFromDate->format('Y-m'),

            'requested_to_date' =>
                $validated['to_date'],

            'requested_to_month' =>
                $requestedToDate->format('Y-m'),

            'last_paid_month' =>
                $lastPaidMonth
                    ? $lastPaidMonth->format('Y-m')
                    : null,

            'due_date' =>
                $lateFine
                    ? (int) $lateFine->due_date
                    : null,

            'same_month_late_fee' =>
                $lateFine
                    ? (float) $lateFine->same_month_late_fee
                    : 0,

            'next_month_late_fee' =>
                $lateFine
                    ? (float) $lateFine->next_month_late_fee
                    : 0,

            'absent_charge_percentage' =>
                $lateFine
                    ? (float) $lateFine->absent_charge_percentage
                    : 0,

            /*
            |----------------------------------------------------------------------
            | These were missing before
            |----------------------------------------------------------------------
            */

            'decision' =>
                $lateFineDecision,

            'late_fine_amount' =>
                round(
                    $totalLateFine,
                    2
                ),

            'late_fine_reason' =>
                $lateFineReason,

            'attendance_check' =>
                $attendanceCheck,

            /*
            |----------------------------------------------------------------------
            | Membership information
            |----------------------------------------------------------------------
            */

            'membership_month_count' =>
                $unpaidMonthCount,

            'membership_plan' =>
                $discountPlan?->plan_name,

            'membership_discount' =>
                round(
                    $totalDiscount,
                    2
                ),
        ];

        /*
        |--------------------------------------------------------------------------
        | 19. Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' =>
                true,

            'first_payment' =>
                !$hasPaidRecord,

            'payment_page_url' =>
                route(
                    'student.payment-page',
                    $studentCourse->id
                ),

            /*
            |--------------------------------------------------------------------------
            | Course
            |--------------------------------------------------------------------------
            */

            'course' => [

                'id' =>
                    $studentCourse->id,

                'name' =>
                    $studentCourse->course?->course_name,

                'monthly_fee' =>
                    $monthlyFee,
            ],

            /*
            |--------------------------------------------------------------------------
            | Membership Discount
            |--------------------------------------------------------------------------
            */

            'discount' => [

                'applicable' =>
                    $totalDiscount > 0,

                'plan_name' =>
                    $discountPlan?->plan_name,

                'duration' =>
                    $discountPlan?->duration,

                'duration_type' =>
                    $discountPlan?->duration_type,

                'discount_type' =>
                    $discountPlan?->discount_type,

                'discount_value' =>
                    $discountPlan
                        ? (float)
                        $discountPlan->discount_value
                        : 0,

                'amount' =>
                    round(
                        $totalDiscount,
                        2
                    ),

                'label' =>
                    $discountLabel,

                'selected_months' =>
                    $unpaidMonthCount,
            ],

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            'summary' => [

                'selected_months' =>
                    $unpaidMonthCount,

                'arrear_months' =>
                    $arrearMonthCount,

                'monthly_fee' =>
                    round(
                        $totalMonthlyFee,
                        2
                    ),

                'late_fine' =>
                    round(
                        $totalLateFine,
                        2
                    ),

                'gross_payable' =>
                    round(
                        $grossPayable,
                        2
                    ),

                'discount' =>
                    round(
                        $totalDiscount,
                        2
                    ),

                'final_payable' =>
                    round(
                        $finalPayable,
                        2
                    ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Late Fine Setting
            |--------------------------------------------------------------------------
            */

            'late_fine_setting' =>
                $lateFine
                    ? [

                        'due_date' =>
                            $lateFine->due_date,

                        'same_month_late_fee' =>
                            (float)
                            $lateFine->same_month_late_fee,

                        'next_month_late_fee' =>
                            (float)
                            $lateFine->next_month_late_fee,

                        'absent_charge_percentage' =>
                            (float)
                            $lateFine->absent_charge_percentage,

                    ]
                    : null,

            /*
            |--------------------------------------------------------------------------
            | Debug
            |--------------------------------------------------------------------------
            */

            'late_fine_debug' =>
                $lateFineDebug,

            /*
            |--------------------------------------------------------------------------
            | Months
            |--------------------------------------------------------------------------
            */

            'months' =>
                $months,

            /*
            |--------------------------------------------------------------------------
            | Arrears
            |--------------------------------------------------------------------------
            */

            'arrears' =>
                $arrearMonths->values(),

        ]);
    }

}
