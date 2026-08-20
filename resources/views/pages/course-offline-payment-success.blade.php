@extends('partials.master')

@section('title', 'Payment Submitted Successfully')

@section('content')

@include('component.breadcrumbs')


<div class="max-w-5xl mx-auto py-8 px-4">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">


        {{-- ================================================================
        | Success Header
        |================================================================ --}}

        <div class="bg-gradient-to-r from-pink-600 to-blue-600 p-8 text-center">

            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-white">

                <svg
                    class="h-10 w-10 text-green-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7">
                    </path>

                </svg>

            </div>


            <h2 class="text-3xl font-bold text-white">
                Payment Submitted Successfully
            </h2>


            <p class="mt-2 text-pink-100">

                Your payment details have been submitted successfully.

            </p>

        </div>



        <div class="p-8">


            {{-- ============================================================
            | Payment Status Notice
            |============================================================= --}}

            @if($payment->status === 'pending')

                <div class="mb-8 rounded-xl border border-yellow-300 bg-yellow-50 px-5 py-4">

                    <div class="flex items-start gap-3">

                        <svg
                            class="w-6 h-6 text-yellow-600 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0">
                            </path>

                        </svg>


                        <div>

                            <h4 class="font-bold text-yellow-800">

                                Payment Verification Pending

                            </h4>


                            <p class="text-sm text-yellow-700 mt-1">

                                Your payment proof has been submitted.
                                The payment will be verified by the administration.

                            </p>

                        </div>

                    </div>

                </div>

            @elseif($payment->status === 'success')

                <div class="mb-8 rounded-xl border border-green-300 bg-green-50 px-5 py-4">

                    <div class="flex items-start gap-3">

                        <svg
                            class="w-6 h-6 text-green-600 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7">
                            </path>

                        </svg>


                        <div>

                            <h4 class="font-bold text-green-800">

                                Payment Successful

                            </h4>


                            <p class="text-sm text-green-700 mt-1">

                                Your payment has been successfully recorded.

                            </p>

                        </div>

                    </div>

                </div>

            @else

                <div class="mb-8 rounded-xl border border-red-300 bg-red-50 px-5 py-4">

                    <div class="flex items-start gap-3">

                        <svg
                            class="w-6 h-6 text-red-600 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>

                        </svg>


                        <div>

                            <h4 class="font-bold text-red-800">

                                Payment Status:
                                {{ ucfirst($payment->status) }}

                            </h4>


                            <p class="text-sm text-red-700 mt-1">

                                Please contact the administration if you have any
                                questions regarding this payment.

                            </p>

                        </div>

                    </div>

                </div>

            @endif



            {{-- ============================================================
            | Transaction Details
            |============================================================= --}}

            <div class="mb-8">

                <h3 class="text-xl font-bold text-pink-500 mb-5">

                    Transaction Details

                </h3>


                <div class="grid md:grid-cols-2 gap-6">


                    {{-- Payment ID --}}

                    <div class="rounded-xl bg-gray-50 p-5">

                        <p class="text-sm text-gray-500">

                            Payment ID

                        </p>


                        <h4 class="mt-1 font-bold text-gray-800">

                            #{{ $payment->id }}

                        </h4>

                    </div>



                    {{-- Transaction ID --}}

                    <div class="rounded-xl bg-gray-50 p-5">

                        <p class="text-sm text-gray-500">

                            Transaction ID

                        </p>


                        <h4 class="mt-1 font-bold text-gray-800 break-all">

                            {{ $payment->transaction_id ?? 'Not Provided' }}

                        </h4>

                    </div>



                    {{-- Payment Date --}}

                    <div class="rounded-xl bg-gray-50 p-5">

                        <p class="text-sm text-gray-500">

                            Payment Date

                        </p>


                        <h4 class="mt-1 font-bold text-gray-800">

                            {{ $payment->payment_date?->format('d M Y') }}

                        </h4>

                    </div>



                    {{-- Payment Mode --}}

                    <div class="rounded-xl bg-gray-50 p-5">

                        <p class="text-sm text-gray-500">

                            Payment Mode

                        </p>


                        <h4 class="mt-1 font-bold text-gray-800">

                            {{ $payment->payment_mode }}

                        </h4>

                    </div>



                    {{-- Status --}}

                    <div class="rounded-xl bg-gray-50 p-5">

                        <p class="text-sm text-gray-500">

                            Payment Status

                        </p>


                        <h4 class="mt-1">

                            @if($payment->status === 'pending')

                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">

                                    Pending

                                </span>

                            @elseif($payment->status === 'success')

                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">

                                    Success

                                </span>

                            @elseif($payment->status === 'failed')

                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">

                                    Failed

                                </span>

                            @elseif($payment->status === 'cancelled')

                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">

                                    Cancelled

                                </span>

                            @elseif($payment->status === 'refunded')

                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-700">

                                    Refunded

                                </span>

                            @endif

                        </h4>

                    </div>



                    {{-- Student --}}

                    <div class="rounded-xl bg-gray-50 p-5">

                        <p class="text-sm text-gray-500">

                            Student

                        </p>


                        <h4 class="mt-1 font-bold text-gray-800">

                            {{ $payment->student?->name ?? '-' }}

                        </h4>

                    </div>



                    {{-- Admission Number --}}

                    <div class="rounded-xl bg-gray-50 p-5">

                        <p class="text-sm text-gray-500">

                            Admission No.

                        </p>


                        <h4 class="mt-1 font-bold text-pink-600">

                            {{ $payment->studentCourse?->admission_no ?? '-' }}

                        </h4>

                    </div>

                </div>

            </div>



            {{-- ============================================================
            | Course Details
            |============================================================= --}}

            <div class="mb-8">

                <h3 class="text-xl font-bold text-pink-500 mb-5">

                    Course Details

                </h3>


                <div class="grid md:grid-cols-2 gap-6">


                    {{-- Course --}}

                    <div>

                        <p class="text-sm text-gray-500">

                            Course

                        </p>


                        <p class="font-semibold text-gray-800">

                            {{ $payment->studentCourse?->course?->course_name ?? '-' }}

                        </p>

                    </div>



                    {{-- Level --}}

                    <div>

                        <p class="text-sm text-gray-500">

                            Level

                        </p>


                        <p class="font-semibold text-gray-800">

                            {{ $payment->studentCourse?->level?->name ?? '-' }}

                        </p>

                    </div>



                    {{-- Category --}}

                    <div>

                        <p class="text-sm text-gray-500">

                            Category

                        </p>


                        <p class="font-semibold text-gray-800">

                            {{ $payment->studentCourse?->category?->name ?? '-' }}

                        </p>

                    </div>



                    {{-- Batch --}}

                    <div>

                        <p class="text-sm text-gray-500">

                            Batch

                        </p>


                        <p class="font-semibold text-gray-800">

                            {{ $payment->studentCourse?->batch?->batch_name ?? '-' }}

                        </p>

                    </div>

                </div>

            </div>



            <hr class="my-8">



            {{-- ============================================================
            | Monthly Fee Details
            |============================================================= --}}

            @if($monthRecord)

                <div class="mb-8">

                    <h3 class="text-xl font-bold text-pink-500 mb-5">

                        Monthly Fee Details

                    </h3>


                    <div class="grid md:grid-cols-2 gap-6">


                        {{-- Fee Month --}}

                        <div class="rounded-xl bg-gray-50 p-5">

                            <p class="text-sm text-gray-500">

                                Fee Month

                            </p>


                            <h4 class="mt-1 font-bold text-gray-800">

                                {{ $monthRecord->fee_month?->format('F Y') ?? '-' }}

                            </h4>

                        </div>



                        {{-- Monthly Fee --}}

                        <div class="rounded-xl bg-gray-50 p-5">

                            <p class="text-sm text-gray-500">

                                Monthly Fee

                            </p>


                            <h4 class="mt-1 font-bold text-gray-800">

                                ₹ {{ number_format((float) $monthRecord->monthly_fee, 2) }}

                            </h4>

                        </div>



                        {{-- Payment Percentage --}}

                        <div class="rounded-xl bg-gray-50 p-5">

                            <p class="text-sm text-gray-500">

                                Payment Percentage

                            </p>


                            <h4 class="mt-1 font-bold text-gray-800">

                                {{ number_format((float) $monthRecord->payment_percentage, 2) }}%

                            </h4>

                        </div>



                        {{-- Payment Rule --}}

                        <div class="rounded-xl bg-gray-50 p-5">

                            <p class="text-sm text-gray-500">

                                Payment Rule

                            </p>


                            <h4 class="mt-1 font-bold text-gray-800">

                                {{ $monthRecord->payment_rule ?? '-' }}

                            </h4>

                        </div>



                        {{-- Payable Amount --}}

                        <div class="rounded-xl bg-gray-50 p-5">

                            <p class="text-sm text-gray-500">

                                Payable Amount

                            </p>


                            <h4 class="mt-1 font-bold text-gray-800">

                                ₹ {{ number_format((float) $monthRecord->payable_amount, 2) }}

                            </h4>

                        </div>



                        {{-- Paid Amount --}}

                        <div class="rounded-xl bg-gray-50 p-5">

                            <p class="text-sm text-gray-500">

                                Paid Amount

                            </p>


                            <h4 class="mt-1 font-bold text-green-600">

                                ₹ {{ number_format((float) $monthRecord->paid_amount, 2) }}

                            </h4>

                        </div>

                    </div>

                </div>

            @endif



            <hr class="my-8">



            {{-- ============================================================
            | Payment Summary
            |============================================================= --}}

            <div>

                <h3 class="text-xl font-bold text-pink-500 mb-5">

                    Payment Summary

                </h3>


                <div class="space-y-4">


                    {{-- Base Payment Amount --}}

                    <div class="flex justify-between items-center">

                        <span class="text-gray-600">

                            Payment Amount

                        </span>


                        <span class="font-semibold text-gray-800">

                            ₹ {{ number_format((float) $payment->amount, 2) }}

                        </span>

                    </div>



                    {{-- Platform Fee --}}

                    @if((float) $payment->platform_fee_amount > 0)

                        <div class="flex justify-between items-center">

                            <span class="text-gray-600">

                                Platform Fee
                                ({{ number_format((float) $payment->platform_fee_percentage, 2) }}%)

                            </span>


                            <span class="font-semibold text-gray-800">

                                ₹ {{ number_format((float) $payment->platform_fee_amount, 2) }}

                            </span>

                        </div>

                    @endif



                    {{-- Total --}}

                    <div class="border-t pt-4 flex justify-between items-center">

                        <span class="text-lg font-bold text-pink-500">

                            Total Amount

                        </span>


                        <span class="text-2xl font-bold text-pink-600">

                            ₹ {{ number_format((float) $payment->amount, 2) }}

                        </span>

                    </div>

                </div>

            </div>



            {{-- ============================================================
            | Payment Proof
            |============================================================= --}}

            @if($payment->payment_proof)

                <div class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-5">

                    <div class="flex items-center justify-between gap-4">


                        <div>

                            <h4 class="font-bold text-gray-800">

                                Payment Proof

                            </h4>


                            <p class="text-sm text-gray-500 mt-1">

                                Your uploaded payment proof

                            </p>

                        </div>


                        <a
                            href="{{ asset('storage/' . $payment->payment_proof) }}"
                            target="_blank"
                            class="px-5 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">

                            View Proof

                        </a>

                    </div>

                </div>

            @endif



            {{-- ============================================================
            | Remarks
            |============================================================= --}}

            @if($payment->remarks)

                <div class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-5">

                    <h4 class="font-bold text-gray-800">

                        Remarks

                    </h4>


                    <p class="text-sm text-gray-600 mt-2">

                        {{ $payment->remarks }}

                    </p>

                </div>

            @endif



            {{-- ============================================================
            | Action Buttons
            |============================================================= --}}

            <div class="mt-10 flex flex-col sm:flex-row justify-end gap-3">


                <a
                    href="{{ route('student.payment-page', $payment->student_course_id) }}"
                    class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold text-center hover:bg-gray-50">

                    Back to Payment

                </a>


                <a
                    href="{{ url('/') }}"
                    class="px-6 py-3 rounded-xl bg-pink-600 text-white font-bold text-center hover:bg-pink-700">

                    Go to Home

                </a>

            </div>


        </div>

    </div>

</div>

@endsection
