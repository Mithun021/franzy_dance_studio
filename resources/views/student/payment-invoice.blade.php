@extends('partials.master')

@section('title','Payment Invoice')

@section('content')

<style>

/* ============================================================
   INVOICE NORMAL VIEW
============================================================ */

.invoice-box {
    background: #ffffff !important;
    color: #111827 !important;
}

.invoice-box * {
    color: #111827;
}

.invoice-box .bg-pink-600,
.invoice-box .bg-blue-600,
.invoice-box .bg-green-600,
.invoice-box .bg-indigo-600,
.invoice-box .bg-gray-800 {
    color: white !important;
}

.invoice-box .bg-pink-600 *,
.invoice-box .bg-blue-600 *,
.invoice-box .bg-green-600 *,
.invoice-box .bg-indigo-600 *,
.invoice-box .bg-gray-800 * {
    color: white !important;
}


/* ============================================================
   A4 PRINT
============================================================ */

@page {
    size: A4 portrait;
    margin: 5mm;
}


@media print {

    body * {
        visibility: hidden !important;
    }

    .invoice-box,
    .invoice-box * {
        visibility: visible !important;
    }

    html,
    body {
        background: #fff !important;
        width: 210mm !important;
        height: 297mm !important;
        margin: 0 !important;
        padding: 0 !important;

        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .invoice-box {

        position: absolute !important;

        top: 0;
        left: 0;

        width: 200mm !important;

        padding: 5mm !important;

        margin: 0 !important;

        background: white !important;

        border: none !important;

        border-radius: 0 !important;

        box-shadow: none !important;

        font-size: 9px !important;

        line-height: 1.25 !important;
    }


    .no-print {
        display: none !important;
    }


    /* Header */

    .invoice-box img {
        width: 55px !important;
        height: 55px !important;
    }

    .invoice-box h1 {
        font-size: 20px !important;
        line-height: 22px !important;
    }

    .invoice-box h2 {
        font-size: 15px !important;
        line-height: 18px !important;
    }

    .invoice-box h3 {
        font-size: 11px !important;
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }

    .invoice-box p {
        font-size: 9px !important;
        margin: 2px 0 !important;
    }


    /* Grid */

    .invoice-box .grid {
        display: grid !important;
    }

    .invoice-box .md\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .invoice-box .gap-6 {
        gap: 10px !important;
    }


    /* Spacing */

    .invoice-box .mt-8 {
        margin-top: 10px !important;
    }

    .invoice-box .mt-12 {
        margin-top: 12px !important;
    }

    .invoice-box .mt-4 {
        margin-top: 5px !important;
    }

    .invoice-box .pb-6 {
        padding-bottom: 8px !important;
    }

    .invoice-box .p-8 {
        padding: 10px !important;
    }

    .invoice-box .px-4 {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    .invoice-box .py-2 {
        padding-top: 4px !important;
        padding-bottom: 4px !important;
    }


    /* Table */

    .invoice-box table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .invoice-box th,
    .invoice-box td {
        padding: 3px 5px !important;
        font-size: 9px !important;
        line-height: 11px !important;
    }

    .invoice-box tr {
        height: auto !important;
    }

    .invoice-box .py-3 {
        padding-top: 3px !important;
        padding-bottom: 3px !important;
    }


    /* Badge */

    .invoice-box .rounded-full {
        padding: 2px 6px !important;
        font-size: 8px !important;
    }


    /* Terms */

    .invoice-box ul {
        margin-top: 5px !important;
    }

    .invoice-box li {
        font-size: 8px !important;
        margin-bottom: 2px !important;
    }


    /* Colors */

    .invoice-box .bg-pink-600 {
        background: #db2777 !important;
    }

    .invoice-box .bg-blue-600 {
        background: #2563eb !important;
    }

    .invoice-box .bg-green-600 {
        background: #16a34a !important;
    }

    .invoice-box .bg-indigo-600 {
        background: #4f46e5 !important;
    }

    .invoice-box .bg-gray-800 {
        background: #1f2937 !important;
    }

}

</style>


<section class="bg-blue py-10 min-h-screen">

    <div class="max-w-5xl mx-auto">


        {{-- ============================================================
            BUTTONS
        ============================================================= --}}

        <div class="flex justify-between mb-6 no-print">

            <a
                href="{{ route('student.payments') }}"
                class="px-5 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-800">

                ← Back

            </a>


            <button
                type="button"
                onclick="printInvoice()"
                class="px-5 py-2 rounded-lg bg-pink-600 text-white hover:bg-pink-700">

                Print Invoice

            </button>

        </div>



        {{-- ============================================================
            CALCULATIONS
        ============================================================= --}}

        @php

            /*
            |--------------------------------------------------------------------------
            | All Successful Payments
            |--------------------------------------------------------------------------
            */

            $successfulPayments = $studentCourse->paymentRecords
                ->filter(function ($record) {

                    return strtolower($record->status ?? '') === 'success';

                });


            /*
            |--------------------------------------------------------------------------
            | Total Paid
            |--------------------------------------------------------------------------
            */

            $totalPaid = $successfulPayments->sum('amount');


            /*
            |--------------------------------------------------------------------------
            | Current Payment
            |--------------------------------------------------------------------------
            */

            $currentPayment = (float) $payment->amount;


            /*
            |--------------------------------------------------------------------------
            | Monthly Billing
            |--------------------------------------------------------------------------
            */

            $monthlyBilling = $studentCourse->monthRecords
                ->sum('payable_amount');


            /*
            |--------------------------------------------------------------------------
            | Registration + Admission
            |--------------------------------------------------------------------------
            */

            $registrationFee = (float) $studentCourse->registration_fee;

            $admissionFee = (float) $studentCourse->admission_fee;


            /*
            |--------------------------------------------------------------------------
            | Total Course Billing
            |--------------------------------------------------------------------------
            */

            $courseBillingTotal =
                $registrationFee
                + $admissionFee
                + (float) $monthlyBilling;


            /*
            |--------------------------------------------------------------------------
            | Remaining
            |--------------------------------------------------------------------------
            */

            $remainingAmount = max(
                0,
                $courseBillingTotal - (float) $totalPaid
            );


            /*
            |--------------------------------------------------------------------------
            | Invoice Number
            |--------------------------------------------------------------------------
            */

            $invoiceNo =
                'INV-' .
                str_pad($payment->id, 6, '0', STR_PAD_LEFT);

        @endphp



        {{-- ============================================================
            INVOICE
        ============================================================= --}}

        <div
            class="invoice-box bg-white rounded-xl shadow-xl border border-gray-200 p-8 text-gray-900">


            {{-- ========================================================
                HEADER
            ========================================================= --}}

            <div
                class="flex justify-between items-center border-b border-gray-300 pb-6">


                <div class="flex items-center gap-4">

                    <img
                        src="{{ asset('images/logo.png') }}"
                        class="w-20 h-20 object-contain"
                        alt="Frenzy Dance Studio">


                    <div>

                        <h2 class="text-3xl font-bold text-gray-900">

                            FRENZY DANCE STUDIO

                        </h2>

                        <p class="text-gray-600">

                            A Complete Performing & Fine Art Center

                        </p>

                        <p class="text-sm text-gray-500">

                            Dance • Music • Art • Fitness

                        </p>

                    </div>

                </div>


                <div class="text-right">

                    <h1 class="text-4xl font-bold text-pink-600">

                        INVOICE

                    </h1>


                    <p class="font-semibold text-gray-900 mt-2">

                        {{ $invoiceNo }}

                    </p>


                    <p class="text-sm text-gray-600 mt-1">

                        Payment Receipt

                    </p>

                </div>

            </div>



            {{-- ========================================================
                STUDENT INFORMATION
            ========================================================= --}}

            <div class="mt-8">

                <h3
                    class="bg-pink-600 text-white px-4 py-2 rounded font-semibold">

                    Student Information

                </h3>


                <div class="grid md:grid-cols-2 gap-6 mt-4">


                    {{-- Student --}}

                    <table class="w-full text-gray-900">

                        <tr>

                            <td class="py-2 font-semibold text-gray-800 w-40">

                                Student

                            </td>

                            <td class="text-gray-900">

                                {{ $payment->student->name ?? 'N/A' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Student ID

                            </td>

                            <td class="text-gray-900">

                                {{ str_pad(
                                    $payment->student->id ?? 0,
                                    4,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Mobile

                            </td>

                            <td class="text-gray-900">

                                {{ $payment->student->phone ?? '-' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Email

                            </td>

                            <td class="text-gray-900">

                                {{ $payment->student->email ?? '-' }}

                            </td>

                        </tr>

                    </table>



                    {{-- Payment --}}

                    <table class="w-full text-gray-900">

                        <tr>

                            <td class="py-2 font-semibold text-gray-800 w-40">

                                Payment Date

                            </td>

                            <td class="text-gray-900">

                                {{ $payment->payment_date
                                    ? $payment->payment_date->format('d M Y')
                                    : '-' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Payment Mode

                            </td>

                            <td class="text-gray-900">

                                {{ $payment->payment_mode
                                    ? ucfirst($payment->payment_mode)
                                    : '-' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Transaction ID

                            </td>

                            <td class="text-gray-900 break-all">

                                {{ $payment->transaction_id ?: '-' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Status

                            </td>

                            <td>

                                <span
                                    class="px-3 py-1 rounded-full bg-green-600 text-white text-sm">

                                    {{ ucfirst($payment->status) }}

                                </span>

                            </td>

                        </tr>

                    </table>

                </div>

            </div>



            {{-- ========================================================
                COURSE INFORMATION
            ========================================================= --}}

            <div class="mt-8">

                <h3
                    class="bg-blue-600 text-white px-4 py-2 rounded font-semibold">

                    Course Information

                </h3>


                <div class="grid md:grid-cols-2 gap-6 mt-4">


                    <table class="w-full text-gray-900">

                        <tr>

                            <td class="py-2 font-semibold text-gray-800 w-40">

                                Course

                            </td>

                            <td class="text-gray-900">

                                {{ optional($studentCourse->course)->course_name ?? 'N/A' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Admission No

                            </td>

                            <td class="text-gray-900">

                                {{ $studentCourse->admission_no ?? 'N/A' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Admission Date

                            </td>

                            <td class="text-gray-900">

                                {{ $studentCourse->admission_date
                                    ? $studentCourse->admission_date->format('d M Y')
                                    : '-' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Level

                            </td>

                            <td class="text-gray-900">

                                {{ optional($studentCourse->level)->name ?? 'N/A' }}

                            </td>

                        </tr>

                    </table>



                    <table class="w-full text-gray-900">

                        <tr>

                            <td class="py-2 font-semibold text-gray-800 w-40">

                                Category

                            </td>

                            <td class="text-gray-900">

                                {{ optional($studentCourse->category)->name ?? 'N/A' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Batch

                            </td>

                            <td class="text-gray-900">

                                {{ optional($studentCourse->batch)->batch_name ?? 'Not Assigned' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Instructor

                            </td>

                            <td class="text-gray-900">

                                {{ optional($studentCourse->instructor)->name ?? 'Not Assigned' }}

                            </td>

                        </tr>


                        <tr>

                            <td class="py-2 font-semibold text-gray-800">

                                Monthly Fee

                            </td>

                            <td class="text-gray-900">

                                ₹ {{ number_format(
                                    (float) $studentCourse->monthly_fee,
                                    2
                                ) }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>



            {{-- ========================================================
                PAYMENT DETAILS
            ========================================================= --}}

            <div class="mt-8">

                <h3
                    class="bg-green-600 text-white px-4 py-2 rounded font-semibold">

                    Payment Details

                </h3>


                <div class="overflow-x-auto mt-4">

                    <table
                        class="w-full border border-gray-300 text-gray-900">


                        <thead class="bg-gray-100">

                            <tr>

                                <th
                                    class="border border-gray-300 px-4 py-3 text-left">

                                    #

                                </th>

                                <th
                                    class="border border-gray-300 px-4 py-3 text-left">

                                    Description

                                </th>

                                <th
                                    class="border border-gray-300 px-4 py-3 text-right">

                                    Amount (₹)

                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                <td
                                    class="border border-gray-300 px-4 py-3">

                                    1

                                </td>

                                <td
                                    class="border border-gray-300 px-4 py-3">

                                    Course Payment

                                    @if($studentCourse->monthly_fee)

                                        <span class="text-xs text-gray-500">

                                            — Monthly Fee ₹
                                            {{ number_format(
                                                (float) $studentCourse->monthly_fee,
                                                2
                                            ) }}

                                        </span>

                                    @endif

                                </td>

                                <td
                                    class="border border-gray-300 px-4 py-3 text-right">

                                    ₹ {{ number_format(
                                        $currentPayment,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            @if($payment->platform_fee_amount > 0)

                                <tr>

                                    <td
                                        class="border border-gray-300 px-4 py-3">

                                    </td>

                                    <td
                                        class="border border-gray-300 px-4 py-3">

                                        Platform Fee

                                    </td>

                                    <td
                                        class="border border-gray-300 px-4 py-3 text-right">

                                        ₹ {{ number_format(
                                            (float) $payment->platform_fee_amount,
                                            2
                                        ) }}

                                    </td>

                                </tr>

                            @endif


                            <tr class="bg-green-100 font-bold">

                                <td
                                    colspan="2"
                                    class="border border-gray-300 px-4 py-3 text-right">

                                    Paid Amount

                                </td>

                                <td
                                    class="border border-gray-300 px-4 py-3 text-right text-green-700">

                                    ₹ {{ number_format(
                                        $currentPayment,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>



            {{-- ========================================================
                BILLING SUMMARY
            ========================================================= --}}

            <div class="mt-8">

                <h3
                    class="bg-indigo-600 text-white px-4 py-2 rounded font-semibold">

                    Billing Summary

                </h3>


                <div class="overflow-x-auto mt-4">

                    <table
                        class="w-full border border-gray-300 text-gray-900">

                        <tbody>


                            {{-- Registration Fee --}}

                            <tr>

                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Registration Fee

                                </th>

                                <td
                                    class="border border-gray-300 px-4 py-3">

                                    ₹ {{ number_format(
                                        $registrationFee,
                                        2
                                    ) }}

                                </td>


                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Admission Fee

                                </th>

                                <td
                                    class="border border-gray-300 px-4 py-3">

                                    ₹ {{ number_format(
                                        $admissionFee,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            {{-- Monthly Billing --}}

                            <tr>

                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Generated Monthly Billing

                                </th>

                                <td
                                    class="border border-gray-300 px-4 py-3">

                                    ₹ {{ number_format(
                                        (float) $monthlyBilling,
                                        2
                                    ) }}

                                </td>


                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Total Course Billing

                                </th>

                                <td
                                    class="border border-gray-300 px-4 py-3 font-bold">

                                    ₹ {{ number_format(
                                        $courseBillingTotal,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            {{-- Total Paid --}}

                            <tr>

                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Total Paid

                                </th>

                                <td
                                    class="border border-gray-300 px-4 py-3 text-green-700 font-bold">

                                    ₹ {{ number_format(
                                        (float) $totalPaid,
                                        2
                                    ) }}

                                </td>


                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Remaining

                                </th>

                                <td
                                    class="border border-gray-300 px-4 py-3">

                                    @if($remainingAmount <= 0)

                                        <span
                                            class="bg-green-600 text-white px-3 py-1 rounded">

                                            Fully Paid

                                        </span>

                                    @else

                                        <span class="font-bold">

                                            ₹ {{ number_format(
                                                $remainingAmount,
                                                2
                                            ) }}

                                        </span>

                                    @endif

                                </td>

                            </tr>


                            {{-- Payment Status --}}

                            <tr>

                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Payment Status

                                </th>

                                <td
                                    class="border border-gray-300 px-4 py-3">

                                    <span
                                        class="bg-green-600 text-white px-3 py-1 rounded">

                                        {{ ucfirst($payment->status) }}

                                    </span>

                                </td>


                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Payment Mode

                                </th>

                                <td
                                    class="border border-gray-300 px-4 py-3">

                                    {{ $payment->payment_mode
                                        ? ucfirst($payment->payment_mode)
                                        : '-' }}

                                </td>

                            </tr>


                            {{-- Transaction --}}

                            <tr>

                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Receipt No.

                                </th>

                                <td
                                    class="border border-gray-300 px-4 py-3">

                                    {{ $invoiceNo }}

                                </td>


                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Transaction ID

                                </th>

                                <td
                                    class="border border-gray-300 px-4 py-3 break-all">

                                    {{ $payment->transaction_id ?: '-' }}

                                </td>

                            </tr>


                            {{-- Remarks --}}

                            <tr>

                                <th
                                    class="border border-gray-300 bg-gray-50 px-4 py-3 text-left">

                                    Remarks

                                </th>

                                <td
                                    colspan="3"
                                    class="border border-gray-300 px-4 py-3">

                                    {{ $payment->remarks ?: '-' }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>



            {{-- ========================================================
                CURRENT PAYMENT HIGHLIGHT
            ========================================================= --}}

            <div
                class="mt-8 p-5 bg-green-100 rounded-lg border border-green-300">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm text-gray-600">

                            This Receipt Payment

                        </p>

                        <p class="text-xl font-bold text-gray-900">

                            {{ $invoiceNo }}

                        </p>

                    </div>


                    <div class="text-right">

                        <p class="text-sm text-gray-600">

                            Amount Received

                        </p>

                        <p class="text-3xl font-bold text-green-700">

                            ₹ {{ number_format(
                                $currentPayment,
                                2
                            ) }}

                        </p>

                    </div>

                </div>

            </div>



            {{-- ========================================================
                AMOUNT IN WORDS
            ========================================================= --}}

            <div
                class="mt-8 p-4 bg-gray-100 rounded-lg border border-gray-300">

                <span class="font-bold text-gray-900">

                    Amount In Words :

                </span>

                <span class="text-gray-900">

                    {{ ucwords(
                        \NumberFormatter::create(
                            'en',
                            \NumberFormatter::SPELLOUT
                        )->format($currentPayment)
                    ) }}

                    Rupees Only

                </span>

            </div>



            {{-- ========================================================
                TERMS
            ========================================================= --}}

            <div class="mt-12">

                <h3
                    class="bg-gray-800 text-white px-4 py-2 rounded font-semibold">

                    Terms & Conditions

                </h3>


                <ul
                    class="list-disc ml-6 mt-4 text-sm text-gray-700 space-y-2">

                    <li>

                        This receipt is generated by
                        Frenzy Dance Studio.

                    </li>


                    <li>

                        Fees once paid are non-refundable.

                    </li>


                    <li>

                        Please keep this invoice for future reference.

                    </li>


                    <li>

                        Any dispute regarding payment should be
                        reported within 7 days.

                    </li>

                </ul>

            </div>



            {{-- ========================================================
                THANK YOU
            ========================================================= --}}

            <div class="mt-12 text-center">

                <h2
                    class="text-2xl font-bold text-pink-600">

                    Thank You!

                </h2>


                <p class="text-gray-600 mt-2">

                    Thank you for choosing

                    <strong class="text-gray-900">

                        FRENZY DANCE STUDIO

                    </strong>

                </p>

            </div>


        </div>



        {{-- ============================================================
            BUTTONS
        ============================================================= --}}

        <div
            class="flex justify-center gap-4 mt-8 no-print">


            <button
                type="button"
                onclick="printInvoice()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold">

                🖨 Print Invoice

            </button>


            <a
                href="{{ route('student.payments') }}"
                class="bg-pink-600 hover:bg-pink-700 text-white px-8 py-3 rounded-lg font-semibold">

                ← Back To Payments

            </a>

        </div>


    </div>

</section>



<script>

function printInvoice()
{
    const invoice = document.querySelector('.invoice-box');

    invoice.style.transform = 'scale(0.96)';
    invoice.style.transformOrigin = 'top center';

    setTimeout(function () {

        window.print();

        setTimeout(function () {

            invoice.style.transform = 'scale(1)';

        }, 300);

    }, 150);
}

</script>

@endsection
