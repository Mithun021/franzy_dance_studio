@extends('partials.master')

@section('title','Payment Summary')

@section('content')

@include('component.breadcrumbs')

@php
    use Carbon\Carbon;

    /*
    |--------------------------------------------------------------------------
    | Payment Date
    |--------------------------------------------------------------------------
    */

    $paymentDate = Carbon::today();
    // dump($paymentDate);
    // $paymentDate = Carbon::parse('2026-08-16');

    $day = $paymentDate->day;

    /*
    |--------------------------------------------------------------------------
    | Monthly Fee Calculation
    |--------------------------------------------------------------------------
    */

    /*
    | NOTE:
    | StudentCourse model mein field `monthly_fee` hai.
    | Calculation logic same rakha gaya hai.
    */

    $monthlyFee = (float) $studentCourse->monthly_fee;

    $monthlyPayable = 0;

    $paymentPercentage = 0;

    $feeMonth = $paymentDate->copy();

    $paymentRule = '';

    /*
    |--------------------------------------------------------------------------
    | 1 - 10
    | Full Monthly Fee
    | Current Month
    |--------------------------------------------------------------------------
    */

    if ($day >= 1 && $day <= 10) {

        $monthlyPayable = $monthlyFee;

        $paymentPercentage = 100;

        $feeMonth = $paymentDate->copy();

        $paymentRule = 'Full Monthly Fee';

    }

    /*
    |--------------------------------------------------------------------------
    | 11 - 25
    | Half Monthly Fee
    | Current Month
    |--------------------------------------------------------------------------
    */

    elseif ($day >= 11 && $day <= 25) {

        $monthlyPayable = $monthlyFee * 0.50;

        $paymentPercentage = 50;

        $feeMonth = $paymentDate->copy();

        $paymentRule = '50% Monthly Fee';

    }

    /*
    |--------------------------------------------------------------------------
    | 26 - Month End
    | Full Monthly Fee
    | NEXT Month
    |--------------------------------------------------------------------------
    */

    else {

        $monthlyPayable = $monthlyFee;

        $paymentPercentage = 100;

        $feeMonth = $paymentDate->copy()->addMonth();

        $paymentRule = 'Full Monthly Fee - Next Month';

    }


    /*
    |--------------------------------------------------------------------------
    | Total Payable Before Platform Fee
    |--------------------------------------------------------------------------
    */

    $totalPayable =
        (float) $studentCourse->registration_fee +
        (float) $studentCourse->admission_fee +
        $monthlyPayable;


    /*
    |--------------------------------------------------------------------------
    | Platform Fee - 2%
    |--------------------------------------------------------------------------
    */

    $platformFeePercentage = 2;

    $platformFee = $totalPayable * ($platformFeePercentage / 100);


    /*
    |--------------------------------------------------------------------------
    | Final Total Payable
    |--------------------------------------------------------------------------
    */

    $finalTotalPayable = $totalPayable + $platformFee;

@endphp


{{-- ============================================================
     SUCCESS MESSAGE
============================================================ --}}

@if(session('success'))

    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 shadow-sm">

        <div class="flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <i class="fas fa-check"></i>
            </div>

            <div>
                <p class="font-bold text-emerald-800">
                    Success
                </p>

                <p class="text-sm text-emerald-700">
                    {{ session('success') }}
                </p>
            </div>

        </div>

    </div>

@endif


{{-- ============================================================
     ERROR MESSAGE
============================================================ --}}

@if(session('error'))

    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 shadow-sm">

        <div class="flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

            <div>
                <p class="font-bold text-red-800">
                    Payment Error
                </p>

                <p class="text-sm text-red-700">
                    {{ session('error') }}
                </p>
            </div>

        </div>

    </div>

@endif


{{-- ============================================================
     VALIDATION ERRORS
============================================================ --}}

@if($errors->any())

    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 shadow-sm">

        <div class="flex gap-3">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <i class="fas fa-exclamation-circle"></i>
            </div>

            <div>

                <p class="font-bold text-red-800">
                    Please check the following errors
                </p>

                <ul class="mt-2 list-disc pl-5 text-sm text-red-700">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    </div>

@endif


{{-- ============================================================
     MAIN PAYMENT CARD
============================================================ --}}

<div class="relative overflow-hidden rounded-[28px] border border-gray-100 bg-white shadow-[0_20px_60px_rgba(0,0,0,0.08)] my-5">

    {{-- Decorative Background --}}

    <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-pink-100/60 blur-3xl"></div>

    <div class="pointer-events-none absolute -left-24 bottom-0 h-72 w-72 rounded-full bg-blue-100/50 blur-3xl"></div>


    {{-- ========================================================
         PREMIUM HEADER
    ========================================================= --}}

    <div class="relative overflow-hidden bg-gradient-to-br from-pink-600 via-fuchsia-600 to-blue-600 px-6 py-8 md:px-10 md:py-10">

        {{-- Header Pattern --}}

        <div class="absolute inset-0 opacity-10">

            <div class="absolute -right-10 -top-16 h-56 w-56 rounded-full border-[30px] border-white"></div>

            <div class="absolute -bottom-20 left-1/3 h-56 w-56 rounded-full border-[25px] border-white"></div>

        </div>


        <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">

            <div class="flex items-center gap-4">

                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-white shadow-lg ring-1 ring-white/20 backdrop-blur-sm">

                    <i class="fas fa-file-invoice-dollar text-2xl"></i>

                </div>

                <div>

                    <p class="mb-1 text-xs font-semibold uppercase tracking-[0.2em] text-pink-100">
                        Payment Summary
                    </p>

                    <h2 class="text-2xl font-black tracking-tight text-white md:text-3xl">
                        Admission Summary
                    </h2>

                    <p class="mt-1 text-sm text-pink-100 md:text-base">
                        Review your admission details before completing payment.
                    </p>

                </div>

            </div>


            {{-- Admission Badge --}}

            <div class="w-fit rounded-2xl border border-white/20 bg-white/10 px-5 py-3 backdrop-blur-md">

                <p class="text-[10px] font-semibold uppercase tracking-wider text-pink-100">
                    Admission No.
                </p>

                <p class="mt-1 text-lg font-black text-white">
                    {{ $studentCourse->admission_no }}
                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================
         CONTENT
    ========================================================= --}}

    <div class="relative p-5 md:p-8 lg:p-10">


        {{-- ====================================================
             ADMISSION INFORMATION
        ===================================================== --}}

        <div class="mb-10">

            <div class="mb-5 flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-50 text-pink-600">
                    <i class="fas fa-user-graduate"></i>
                </div>

                <div>

                    <h3 class="text-lg font-extrabold text-gray-900">
                        Admission Information
                    </h3>

                    <p class="text-sm text-gray-500">
                        Your registered student and course details
                    </p>

                </div>

            </div>


            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">


                {{-- Admission No --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Admission No.
                    </p>

                    <p class="truncate font-bold text-gray-900">
                        {{ $studentCourse->admission_no }}
                    </p>

                </div>


                {{-- Admission Date --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Admission Date
                    </p>

                    <p class="font-bold text-gray-900">

                        {{ $studentCourse->admission_date?->format('d M Y') ?? '—' }}

                    </p>

                </div>


                {{-- Student --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Student
                    </p>

                    <p class="truncate font-bold text-gray-900">

                        {{ $studentCourse->student?->name ?? '—' }}

                    </p>

                </div>


                {{-- Phone --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Phone
                    </p>

                    <p class="font-bold text-gray-900">

                        {{ $studentCourse->student?->phone ?? '—' }}

                    </p>

                </div>


                {{-- Course --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Course
                    </p>

                    <p class="truncate font-bold text-gray-900">

                        {{ $studentCourse->course?->course_name ?? '—' }}

                    </p>

                </div>


                {{-- Course Duration --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Course Duration
                    </p>

                    <p class="font-bold text-gray-900">

                        @if($studentCourse->course_duration)

                            {{ $studentCourse->course_duration }}

                            {{ $studentCourse->duration_type ?? '' }}

                        @else

                            —

                        @endif

                    </p>

                </div>


                {{-- Level --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Level
                    </p>

                    <p class="font-bold text-gray-900">

                        {{ $studentCourse->level?->name ?? '—' }}

                    </p>

                </div>


                {{-- Category --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Category
                    </p>

                    <p class="font-bold text-gray-900">

                        {{ $studentCourse->category?->name ?? '—' }}

                    </p>

                </div>


                {{-- Batch --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Batch
                    </p>

                    <p class="truncate font-bold text-gray-900">

                        {{ $studentCourse->batch?->batch_name ?? '—' }}

                    </p>

                </div>


                {{-- Instructor --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Instructor
                    </p>

                    <p class="truncate font-bold text-gray-900">

                        {{ $studentCourse->instructor?->name ?? 'Not Assigned' }}

                    </p>

                </div>


                {{-- Enrollment Status --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Enrollment Status
                    </p>

                    @if($studentCourse->is_enroll)

                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700">

                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                            Enrolled

                        </span>

                    @else

                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-700">

                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                            Payment Pending

                        </span>

                    @endif

                </div>


                {{-- Course Status --}}

                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5 transition hover:border-pink-200 hover:bg-pink-50/40">

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Course Status
                    </p>

                    <p class="font-bold capitalize text-gray-900">

                        {{ $studentCourse->status ?? '—' }}

                    </p>

                </div>

            </div>

        </div>


        {{-- Divider --}}

        <div class="mb-10 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>


        {{-- ====================================================
             FEE DETAILS
        ===================================================== --}}

        <div class="grid gap-8 lg:grid-cols-5">


            {{-- LEFT SIDE --}}

            <div class="lg:col-span-3">

                <div class="mb-5 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="fas fa-receipt"></i>
                    </div>

                    <div>

                        <h3 class="text-lg font-extrabold text-gray-900">
                            Fee Details
                        </h3>

                        <p class="text-sm text-gray-500">
                            Detailed breakdown of your payment
                        </p>

                    </div>

                </div>


                <div class="overflow-hidden rounded-2xl border border-gray-100">

                    <div class="divide-y divide-gray-100">


                        {{-- Registration Fee --}}

                        <div class="flex items-center justify-between gap-4 px-5 py-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-pink-50 text-pink-600">
                                    <i class="fas fa-clipboard-check text-sm"></i>
                                </div>

                                <span class="font-medium text-gray-700">
                                    Registration Fee
                                </span>

                            </div>

                            <span class="whitespace-nowrap font-bold text-gray-900">

                                ₹ {{ number_format($studentCourse->registration_fee, 2) }}

                            </span>

                        </div>


                        {{-- Admission Fee --}}

                        <div class="flex items-center justify-between gap-4 px-5 py-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                    <i class="fas fa-user-plus text-sm"></i>
                                </div>

                                <span class="font-medium text-gray-700">
                                    Admission Fee
                                </span>

                            </div>

                            <span class="whitespace-nowrap font-bold text-gray-900">

                                ₹ {{ number_format($studentCourse->admission_fee, 2) }}

                            </span>

                        </div>


                        {{-- Monthly Fee --}}

                        <div class="flex items-center justify-between gap-4 px-5 py-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                                    <i class="fas fa-calendar-alt text-sm"></i>
                                </div>

                                <span class="font-medium text-gray-700">
                                    Monthly Fee
                                </span>

                            </div>

                            <span class="whitespace-nowrap font-bold text-gray-900">

                                ₹ {{ number_format($monthlyFee, 2) }}

                            </span>

                        </div>


                    </div>

                </div>


                {{-- =================================================
                     MONTHLY FEE CALCULATION CARD
                ================================================== --}}

                <div class="mt-5 overflow-hidden rounded-2xl border border-pink-100 bg-gradient-to-br from-pink-50 to-white">

                    <div class="border-b border-pink-100 px-5 py-4">

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                            <div>

                                <p class="font-bold text-gray-900">
                                    Monthly Fee Payment Rule
                                </p>

                                <p class="mt-1 text-xs text-gray-500">

                                    Payment Date:

                                    <span class="font-semibold text-gray-700">
                                        {{ $paymentDate->format('d M Y') }}
                                    </span>

                                </p>

                            </div>


                            <div class="rounded-xl bg-white px-4 py-2 text-center shadow-sm ring-1 ring-pink-100">

                                <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                                    Applicable Rule
                                </p>

                                <p class="mt-1 font-black text-pink-600">
                                    {{ $paymentPercentage }}%
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="grid gap-4 p-5 sm:grid-cols-2">

                        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">

                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Payment Rule
                            </p>

                            <p class="mt-2 font-bold text-gray-800">
                                {{ $paymentRule }}
                            </p>

                        </div>


                        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">

                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Applicable Fee
                            </p>

                            <p class="mt-2 font-bold text-gray-800">
                                ₹ {{ number_format($monthlyPayable, 2) }}
                            </p>

                        </div>


                        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">

                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Fee Month
                            </p>

                            <p class="mt-2 font-bold text-pink-600">
                                {{ $feeMonth->format('F Y') }}
                            </p>

                        </div>


                        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">

                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Monthly Percentage
                            </p>

                            <p class="mt-2 font-bold text-blue-600">
                                {{ $paymentPercentage }}%
                            </p>

                        </div>

                    </div>


                    {{-- Rule Explanation --}}

                    <div class="border-t border-pink-100 px-5 py-4">

                        <div class="flex gap-3">

                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-pink-100 text-pink-600">

                                <i class="fas fa-info text-xs"></i>

                            </div>

                            <div class="text-sm leading-6 text-gray-600">

                                @if($day >= 1 && $day <= 10)

                                    <p>
                                        Payment made between
                                        <strong class="text-gray-800">1st and 10th</strong>
                                        requires the full monthly fee.
                                    </p>

                                @elseif($day >= 11 && $day <= 25)

                                    <p>
                                        Payment made between
                                        <strong class="text-gray-800">11th and 25th</strong>
                                        requires 50% of the monthly fee.
                                    </p>

                                @else

                                    <p>
                                        Payment made from
                                        <strong class="text-gray-800">26th to the end of the month</strong>
                                        requires the full monthly fee, but the payment will be counted for
                                        <strong class="text-pink-600">
                                            {{ $feeMonth->format('F Y') }}
                                        </strong>.
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RIGHT SIDE - PAYMENT SUMMARY
            ================================================== --}}

            <div class="lg:col-span-2">

                <div class="sticky top-6 overflow-hidden rounded-3xl border border-gray-100 bg-gray-50 shadow-sm">


                    {{-- Summary Header --}}

                    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-6 py-6">

                        <div class="flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/10">

                                <i class="fas fa-wallet"></i>

                            </div>

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Payment
                                </p>

                                <h3 class="text-xl font-black text-white">
                                    Order Summary
                                </h3>

                            </div>

                        </div>

                    </div>


                    <div class="p-6">


                        {{-- Monthly Fee Payable --}}

                        <div class="flex items-center justify-between gap-4 py-3">

                            <span class="text-sm text-gray-500">
                                Monthly Fee Payable
                            </span>

                            <span class="font-bold text-gray-800">
                                ₹ {{ number_format($monthlyPayable, 2) }}
                            </span>

                        </div>


                        {{-- Registration Fee --}}

                        <div class="flex items-center justify-between gap-4 py-3">

                            <span class="text-sm text-gray-500">
                                Registration Fee
                            </span>

                            <span class="font-bold text-gray-800">
                                ₹ {{ number_format($studentCourse->registration_fee, 2) }}
                            </span>

                        </div>


                        {{-- Admission Fee --}}

                        <div class="flex items-center justify-between gap-4 py-3">

                            <span class="text-sm text-gray-500">
                                Admission Fee
                            </span>

                            <span class="font-bold text-gray-800">
                                ₹ {{ number_format($studentCourse->admission_fee, 2) }}
                            </span>

                        </div>


                        {{-- Subtotal --}}

                        <div class="my-3 border-t border-dashed border-gray-200"></div>

                        <div class="flex items-center justify-between gap-4 py-3">

                            <span class="text-sm font-semibold text-gray-700">
                                Total Before Platform Fee
                            </span>

                            <span class="font-extrabold text-gray-900">
                                ₹ {{ number_format($totalPayable, 2) }}
                            </span>

                        </div>


                        {{-- Platform Fee --}}

                        <div class="mt-2 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-4">

                            <div class="flex items-start justify-between gap-4">

                                <div class="flex items-start gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">

                                        <i class="fas fa-shield-alt text-sm"></i>

                                    </div>

                                    <div>

                                        <p class="font-bold text-blue-900">
                                            Platform Fee
                                        </p>

                                        <p class="mt-0.5 text-xs text-blue-600">
                                            {{ $platformFeePercentage }}% of total payable
                                        </p>

                                    </div>

                                </div>

                                <span class="whitespace-nowrap font-bold text-blue-700">
                                    ₹ {{ number_format($platformFee, 2) }}
                                </span>

                            </div>

                        </div>


                        {{-- Final Total --}}

                        <div class="mt-5 rounded-2xl bg-gradient-to-br from-pink-600 via-fuchsia-600 to-blue-600 p-[1px] shadow-lg">

                            <div class="rounded-[15px] bg-white px-5 py-5">

                                <div class="flex items-center justify-between gap-4">

                                    <div>

                                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                            Total Payable
                                        </p>

                                        <p class="mt-1 text-sm font-medium text-gray-500">
                                            Including {{ $platformFeePercentage }}% platform fee
                                        </p>

                                    </div>

                                    <div class="text-right">

                                        <p class="text-2xl font-black text-pink-600 md:text-3xl">
                                            ₹ {{ number_format($finalTotalPayable, 2) }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Payment Notice --}}

                        <div class="mt-5 rounded-2xl border border-amber-100 bg-amber-50 p-4">

                            <div class="flex gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">

                                    <i class="fas fa-info-circle text-sm"></i>

                                </div>

                                <div>

                                    <p class="text-sm font-bold text-amber-900">
                                        Payment Summary
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-amber-700">

                                        ₹ {{ number_format($monthlyPayable, 2) }}
                                        monthly fee will be counted for

                                        <strong>
                                            {{ $feeMonth->format('F Y') }}
                                        </strong>.

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             PAYMENT FORM
                        ================================================== --}}

                        <form
                            action="{{ route('student.payment.store', $studentCourse->id) }}"
                            method="POST"
                            enctype="multipart/form-data"
                        >

                            @csrf


                            {{-- Payment Method --}}

                            <div class="mt-10">

                                <h3 class="text-xl font-bold text-pink-500 mb-5">
                                    Select Payment Method
                                </h3>

                                <div class="space-y-4">


                                    {{-- Online Payment --}}

                                    <label class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white px-5 py-4 cursor-pointer hover:border-pink-500 transition">

                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="online"
                                            class="paymentMethod w-5 h-5 accent-pink-500"
                                            checked
                                        >

                                        <div>

                                            <h4 class="font-semibold text-gray-800">
                                                Online Payment
                                            </h4>

                                            <p class="text-gray-500 text-sm">
                                                Secure payment using Payment Gateway
                                            </p>

                                        </div>

                                    </label>


                                    {{-- QR Payment --}}

                                    <label class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white px-5 py-4 cursor-pointer hover:border-pink-500 transition">

                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="qr"
                                            class="paymentMethod w-5 h-5 accent-pink-500"
                                        >

                                        <div>

                                            <h4 class="font-semibold text-gray-800">
                                                QR Payment
                                            </h4>

                                            <p class="text-gray-500 text-sm">
                                                Scan QR Code and upload payment proof
                                            </p>

                                        </div>

                                    </label>


                                    {{-- Direct Bank Transfer --}}

                                    <label class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white px-5 py-4 cursor-pointer hover:border-pink-500 transition">

                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="bank_transfer"
                                            class="paymentMethod w-5 h-5 accent-pink-500"
                                        >

                                        <div>

                                            <h4 class="font-semibold text-gray-800">
                                                Direct Bank Transfer
                                            </h4>

                                            <p class="text-gray-500 text-sm">
                                                Transfer directly to our bank account
                                            </p>

                                        </div>

                                    </label>

                                </div>


                                {{-- QR Section --}}

                                <div id="qrSection" class="hidden mt-6">

                                    <div class="rounded-2xl border border-pink-100 bg-pink-50 p-6">

                                        <h4 class="text-xl font-semibold text-gray-800 mb-5">
                                            Scan QR Code
                                        </h4>

                                        <div class="text-center">

                                            <img
                                                src="{{ asset('images/qr.jpg') }}"
                                                alt="Payment QR Code"
                                                class="w-56 h-56 object-contain mx-auto rounded-xl bg-white p-3 shadow"
                                            >

                                        </div>

                                    </div>

                                </div>


                                {{-- Bank Transfer Section --}}

                                <div id="bankSection" class="hidden mt-6">

                                    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-6">

                                        <h4 class="text-xl font-semibold text-gray-800 mb-4">
                                            Bank Transfer Details
                                        </h4>

                                        <div class="space-y-2 text-gray-700">

                                            <p>
                                                <strong>Bank Name:</strong>
                                                Your Bank Name
                                            </p>

                                            <p>
                                                <strong>Account Name:</strong>
                                                Your Account Name
                                            </p>

                                            <p>
                                                <strong>Account Number:</strong>
                                                XXXXXXXX
                                            </p>

                                            <p>
                                                <strong>IFSC Code:</strong>
                                                XXXXXXXX
                                            </p>

                                        </div>

                                    </div>

                                </div>


                                {{-- Payment Proof --}}

                                <div id="paymentProofSection" class="hidden mt-6">

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">

                                        Payment Proof

                                        <span class="text-red-500">*</span>

                                    </label>

                                    <input
                                        type="file"
                                        name="payment_proof"
                                        accept="image/*,.pdf"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3"
                                    >

                                    <p class="text-sm text-gray-500 mt-2">
                                        Upload screenshot, receipt or transaction proof.
                                    </p>

                                </div>

                            </div>


                            {{-- Submit --}}

                            <div class="mt-6">

                                <button
                                    id="paymentBtn"
                                    type="submit"
                                    class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-pink-600 via-fuchsia-600 to-blue-600 px-6 py-4 text-base font-extrabold text-white shadow-lg shadow-pink-200 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-pink-300 focus:outline-none focus:ring-4 focus:ring-pink-200"
                                >

                                    <span>
                                        Proceed To Checkout
                                    </span>

                                    <span class="rounded-lg bg-white/15 px-2 py-1 text-sm backdrop-blur-sm">

                                        ₹ {{ number_format($finalTotalPayable, 2) }}

                                    </span>

                                    <i class="fas fa-arrow-right text-sm transition-transform duration-300 group-hover:translate-x-1"></i>

                                </button>

                            </div>

                        </form>


                        {{-- Secure Payment Text --}}

                        <div class="mt-4 flex items-center justify-center gap-2 text-xs text-gray-400">

                            <i class="fas fa-lock text-emerald-500"></i>

                            <span>
                                Secure & protected payment
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ====================================================
             BOTTOM PAYMENT NOTICE
        ===================================================== --}}

        <div class="mt-10 rounded-2xl border border-gray-100 bg-gradient-to-r from-gray-50 to-white p-5">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                    <i class="fas fa-check-circle"></i>

                </div>

                <div>

                    <p class="font-bold text-gray-900">
                        Ready to complete your payment?
                    </p>

                    <p class="mt-1 text-sm text-gray-500">

                        Please review the payment breakdown above.
                        The final amount includes the applicable
                        {{ $platformFeePercentage }}% platform fee.

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
     PAYMENT METHOD SCRIPT
============================================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const paymentMethods =
        document.querySelectorAll('.paymentMethod');

    const qrSection =
        document.getElementById('qrSection');

    const bankSection =
        document.getElementById('bankSection');

    const paymentProofSection =
        document.getElementById('paymentProofSection');

    const paymentBtn =
        document.getElementById('paymentBtn');

    const paymentProof =
        document.querySelector(
            'input[name="payment_proof"]'
        );


    function togglePaymentSection() {

        const selected =
            document.querySelector(
                '.paymentMethod:checked'
            ).value;


        /*
        |--------------------------------------------------------------------------
        | Reset
        |--------------------------------------------------------------------------
        */

        qrSection.classList.add('hidden');

        bankSection.classList.add('hidden');

        paymentProofSection.classList.add('hidden');

        paymentProof.removeAttribute('required');


        /*
        |--------------------------------------------------------------------------
        | Online Payment
        |--------------------------------------------------------------------------
        */

        if (selected === 'online') {

            paymentBtn.innerHTML = `
                <span>
                    Proceed To Secure Payment
                </span>

                <span class="rounded-lg bg-white/15 px-2 py-1 text-sm backdrop-blur-sm">
                    ₹ {{ number_format($finalTotalPayable, 2) }}
                </span>

                <i class="fas fa-arrow-right text-sm transition-transform duration-300 group-hover:translate-x-1"></i>
            `;

        }


        /*
        |--------------------------------------------------------------------------
        | QR Payment
        |--------------------------------------------------------------------------
        */

        else if (selected === 'qr') {

            qrSection.classList.remove('hidden');

            paymentProofSection.classList.remove('hidden');

            paymentProof.setAttribute(
                'required',
                true
            );

            paymentBtn.innerHTML = `
                <span>
                    Submit Payment Proof
                </span>

                <span class="rounded-lg bg-white/15 px-2 py-1 text-sm backdrop-blur-sm">
                    ₹ {{ number_format($finalTotalPayable, 2) }}
                </span>

                <i class="fas fa-arrow-right text-sm transition-transform duration-300 group-hover:translate-x-1"></i>
            `;

        }


        /*
        |--------------------------------------------------------------------------
        | Bank Transfer
        |--------------------------------------------------------------------------
        */

        else if (selected === 'bank_transfer') {

            bankSection.classList.remove('hidden');

            paymentProofSection.classList.remove('hidden');

            paymentProof.setAttribute(
                'required',
                true
            );

            paymentBtn.innerHTML = `
                <span>
                    Submit Payment Proof
                </span>

                <span class="rounded-lg bg-white/15 px-2 py-1 text-sm backdrop-blur-sm">
                    ₹ {{ number_format($finalTotalPayable, 2) }}
                </span>

                <i class="fas fa-arrow-right text-sm transition-transform duration-300 group-hover:translate-x-1"></i>
            `;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Payment Method Change
    |--------------------------------------------------------------------------
    */

    paymentMethods.forEach(function (item) {

        item.addEventListener(
            'change',
            togglePaymentSection
        );

    });


    /*
    |--------------------------------------------------------------------------
    | Initial State
    |--------------------------------------------------------------------------
    */

    togglePaymentSection();

});

</script>

@endsection
