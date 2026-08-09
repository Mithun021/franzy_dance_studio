@extends('partials.master')

@section('title','Payment Invoice')

@section('content')
<style>


/* ==========================================
   NORMAL INVOICE TEXT FIX
========================================== */


.invoice-box{

    background:#ffffff !important;
    color:#111827 !important;

}


.invoice-box *{

    color:#111827;

}



.invoice-box .bg-pink-600,
.invoice-box .bg-blue-600,
.invoice-box .bg-green-600,
.invoice-box .bg-indigo-600,
.invoice-box .bg-gray-800{

    color:white !important;

}


.invoice-box .bg-pink-600 *,
.invoice-box .bg-blue-600 *,
.invoice-box .bg-green-600 *,
.invoice-box .bg-indigo-600 *,
.invoice-box .bg-gray-800 *{

    color:white !important;

}







/* ==========================================
        A4 PRINT
========================================== */


@page{

    size:A4 portrait;

    margin:5mm;

}





@media print{


/* REMOVE OUTSIDE */

body *{

    visibility:hidden !important;

}



.invoice-box,
.invoice-box *{

    visibility:visible !important;

}





html,
body{

    background:#fff !important;

    width:210mm !important;

    height:297mm !important;

    margin:0 !important;

    padding:0 !important;

    -webkit-print-color-adjust:exact !important;

    print-color-adjust:exact !important;

}





/* ONLY INVOICE */


.invoice-box{


    position:absolute !important;

    top:0;

    left:0;


    width:200mm !important;


    padding:5mm !important;


    margin:0 !important;


    background:white !important;


    border:none !important;


    border-radius:0 !important;


    box-shadow:none !important;


    font-size:9px !important;


    line-height:1.25 !important;


}






/* HIDE BUTTON */


.no-print{

    display:none !important;

}






/* ==========================================
        HEADER COMPACT
========================================== */


.invoice-box img{

    width:55px !important;

    height:55px !important;

}



.invoice-box h1{

    font-size:20px !important;

    line-height:22px !important;

}



.invoice-box h2{

    font-size:15px !important;

    line-height:18px !important;

}



.invoice-box h3{

    font-size:11px !important;

    padding-top:5px !important;

    padding-bottom:5px !important;

}





.invoice-box p{

    font-size:9px !important;

    margin:2px 0 !important;

}







/* ==========================================
        GRID
========================================== */


.invoice-box .grid{

    display:grid !important;

}


.invoice-box .md\:grid-cols-2{

    grid-template-columns:repeat(2,minmax(0,1fr)) !important;

}




.invoice-box .gap-6{

    gap:10px !important;

}





/* ==========================================
        ALL SPACING REDUCE
========================================== */


.invoice-box .mt-8{

    margin-top:10px !important;

}



.invoice-box .mt-12{

    margin-top:12px !important;

}



.invoice-box .mt-4{

    margin-top:5px !important;

}



.invoice-box .pb-6{

    padding-bottom:8px !important;

}



.invoice-box .p-8{

    padding:10px !important;

}



.invoice-box .px-4{

    padding-left:8px !important;

    padding-right:8px !important;

}



.invoice-box .py-2{

    padding-top:4px !important;

    padding-bottom:4px !important;

}








/* ==========================================
        TABLE COMPACT
========================================== */


.invoice-box table{

    width:100% !important;

    border-collapse:collapse !important;

}



.invoice-box th,
.invoice-box td{


    padding:3px 5px !important;


    font-size:9px !important;


    line-height:11px !important;


}





.invoice-box tr{

    height:auto !important;

}




.invoice-box .py-3{


    padding-top:3px !important;

    padding-bottom:3px !important;


}





.invoice-box .px-4{


    padding-left:5px !important;

    padding-right:5px !important;


}





/* ==========================================
        BADGE
========================================== */


.invoice-box .rounded-full{


    padding:2px 6px !important;

    font-size:8px !important;


}





/* ==========================================
        TERMS
========================================== */


.invoice-box ul{

    margin-top:5px !important;

}



.invoice-box li{

    font-size:8px !important;

    margin-bottom:2px !important;

}







/* ==========================================
        COLORS
========================================== */


.invoice-box .bg-pink-600{

    background:#db2777 !important;

}


.invoice-box .bg-blue-600{

    background:#2563eb !important;

}


.invoice-box .bg-green-600{

    background:#16a34a !important;

}


.invoice-box .bg-indigo-600{

    background:#4f46e5 !important;

}


.invoice-box .bg-gray-800{

    background:#1f2937 !important;

}





}



</style>

<section class="bg-blue py-10 min-h-screen">

    <div class="max-w-5xl mx-auto">

        {{-- Buttons --}}
        <div class="flex justify-between mb-6 no-print">

            <a href="{{ route('student.payments') }}"
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



        {{-- ================= Invoice ================= --}}

        <div class="invoice-box bg-white rounded-xl shadow-xl border border-gray-200 p-8 text-gray-900">

            {{-- ================= Header ================= --}}

            <div class="flex justify-between items-center border-b border-gray-300 pb-6">

                <div class="flex items-center gap-4">

                    <img
                        src="{{ asset('images/logo.png') }}"
                        class="w-20 h-20 object-contain">

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
                        INV-{{ $payment->studentCourse->admission_no }}
                    </p>

                </div>

            </div>



            {{-- ================= Student Information ================= --}}

            <div class="mt-8">

                <h3 class="bg-pink-600 text-white px-4 py-2 rounded font-semibold">
                    Student Information
                </h3>

                <div class="grid md:grid-cols-2 gap-6 mt-4">

                                        <table class="w-full text-gray-900">

                        <tr>
                            <td class="py-2 font-semibold text-gray-800 w-40">
                                Student
                            </td>
                            <td class="text-gray-900">
                                {{ $payment->student->name }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 font-semibold text-gray-800">
                                Student ID
                            </td>
                            <td class="text-gray-900">
                                {{ str_pad($payment->student->id,4,'0',STR_PAD_LEFT) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 font-semibold text-gray-800">
                                Mobile
                            </td>
                            <td class="text-gray-900">
                                {{ $payment->student->phone }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 font-semibold text-gray-800">
                                Email
                            </td>
                            <td class="text-gray-900">
                                {{ $payment->student->email }}
                            </td>
                        </tr>

                    </table>


                    <table class="w-full text-gray-900">

                        <tr>
                            <td class="py-2 font-semibold text-gray-800 w-40">
                                Payment Date
                            </td>
                            <td class="text-gray-900">
                                {{ $payment->payment_date->format('d M Y') }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 font-semibold text-gray-800">
                                Payment Mode
                            </td>
                            <td class="text-gray-900">
                                {{ ucfirst($payment->payment_mode) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 font-semibold text-gray-800">
                                Transaction ID
                            </td>
                            <td class="text-gray-900">
                                {{ $payment->transaction_id ?: '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 font-semibold text-gray-800">
                                Status
                            </td>
                            <td>

                                <span class="px-3 py-1 rounded-full bg-green-600 text-white text-sm">
                                    {{ ucfirst($payment->status) }}
                                </span>

                            </td>
                        </tr>

                    </table>

                </div>

            </div>



            {{-- ================= Course Information ================= --}}

            <div class="mt-8">

                <h3 class="bg-blue-600 text-white px-4 py-2 rounded font-semibold">
                    Course Information
                </h3>

                <div class="grid md:grid-cols-2 gap-6 mt-4">

                    <table class="w-full text-gray-900">

                        <tr>
                            <td class="py-2 font-semibold text-gray-800 w-40">
                                Course
                            </td>
                            <td class="text-gray-900">
                                {{ $payment->studentCourse->course->course_name }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 font-semibold text-gray-800">
                                Batch
                            </td>
                            <td class="text-gray-900">
                                {{ optional($payment->studentCourse->batch)->batch_name }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 font-semibold text-gray-800">
                                Level
                            </td>
                            <td class="text-gray-900">
                                {{ optional($payment->studentCourse->level)->name }}
                            </td>
                        </tr>

                    </table>

                    <table class="w-full text-gray-900">

                        <tr>
                            <td class="py-2 font-semibold text-gray-800 w-40">
                                Category
                            </td>
                            <td class="text-gray-900">
                                {{ optional($payment->studentCourse->category)->name }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 font-semibold text-gray-800">
                                Instructor
                            </td>
                            <td class="text-gray-900">
                                {{ optional($payment->studentCourse->instructor)->name }}
                            </td>
                        </tr>

                    </table>

                </div>

            </div>



            {{-- ================= PAYMENT DETAILS ================= --}}

            {{-- ================= PAYMENT DETAILS ================= --}}

            <div class="mt-8">

                <h3 class="bg-green-600 text-white px-4 py-2 rounded font-semibold">
                    Payment Details
                </h3>

                <div class="overflow-x-auto mt-4">

                    <table class="w-full border border-gray-300 text-gray-900">

                        <thead class="bg-gray-100">

                            <tr>

                                <th class="border border-gray-300 px-4 py-3 text-left text-gray-900">
                                    #
                                </th>

                                <th class="border border-gray-300 px-4 py-3 text-left text-gray-900">
                                    Description
                                </th>

                                <th class="border border-gray-300 px-4 py-3 text-right text-gray-900">
                                    Amount (₹)
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @php
                                $i = 1;
                            @endphp


                            @if($payment->registration_fee > 0)

                            <tr>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    {{ $i++ }}
                                </td>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    Registration Fee
                                </td>

                                <td class="border border-gray-300 px-4 py-3 text-right text-gray-900">
                                    ₹ {{ number_format($payment->registration_fee,2) }}
                                </td>

                            </tr>

                            @endif



                            @if($payment->admission_fee > 0)

                            <tr>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    {{ $i++ }}
                                </td>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    Admission Fee
                                </td>

                                <td class="border border-gray-300 px-4 py-3 text-right text-gray-900">
                                    ₹ {{ number_format($payment->admission_fee,2) }}
                                </td>

                            </tr>

                            @endif



                            @if($payment->course_fee > 0)

                            <tr>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    {{ $i++ }}
                                </td>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    Monthly Course Fee
                                </td>

                                <td class="border border-gray-300 px-4 py-3 text-right text-gray-900">
                                    ₹ {{ number_format($payment->course_fee,2) }}
                                </td>

                            </tr>

                            @endif



                            <tr class="bg-green-100 font-bold">

                                <td colspan="2"
                                    class="border border-gray-300 px-4 py-3 text-right text-gray-900">

                                    Paid Amount

                                </td>

                                <td class="border border-gray-300 px-4 py-3 text-right text-green-700">

                                    ₹ {{ number_format($payment->amount,2) }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>



            @php

                $studentCourse = $payment->studentCourse;

                $totalPaid = \App\Models\StudentPayment::where(
                        'student_course_id',
                        $studentCourse->id
                    )
                    ->where('status','success')
                    ->sum('amount');

                $remaining = $studentCourse->grand_total - $totalPaid;

            @endphp



            {{-- ================= BILLING SUMMARY ================= --}}

            <div class="mt-8">

                <h3 class="bg-indigo-600 text-white px-4 py-2 rounded font-semibold">
                    Billing Summary
                </h3>

                <div class="overflow-x-auto mt-4">

                    <table class="w-full border border-gray-300 text-gray-900">

                        <tbody>

                            <tr>

                                <th class="border border-gray-300 bg-gray-50 px-4 py-3 text-left text-gray-900">
                                    Grand Total
                                </th>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    ₹ {{ number_format($studentCourse->grand_total,2) }}
                                </td>

                                <th class="border border-gray-300 bg-gray-50 px-4 py-3 text-left text-gray-900">
                                    Total Paid
                                </th>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    ₹ {{ number_format($totalPaid,2) }}
                                </td>

                            </tr>

                            <tr>

                                <th class="border border-gray-300 bg-gray-50 px-4 py-3 text-left text-gray-900">
                                    Remaining
                                </th>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">

                                    @if($remaining<=0)

                                        <span class="bg-green-600 text-white px-3 py-1 rounded">
                                            Fully Paid
                                        </span>

                                    @else

                                        ₹ {{ number_format($remaining,2) }}

                                    @endif

                                </td>

                                <th class="border border-gray-300 bg-gray-50 px-4 py-3 text-left text-gray-900">
                                    Payment Status
                                </th>

                                <td class="border border-gray-300 px-4 py-3">

                                    <span class="bg-green-600 text-white px-3 py-1 rounded">
                                        {{ ucfirst($payment->status) }}
                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <th class="border border-gray-300 bg-gray-50 px-4 py-3 text-left text-gray-900">
                                    Payment Mode
                                </th>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    {{ ucfirst($payment->payment_mode) }}
                                </td>

                                <th class="border border-gray-300 bg-gray-50 px-4 py-3 text-left text-gray-900">
                                    Transaction ID
                                </th>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    {{ $payment->transaction_id ?: '-' }}
                                </td>

                            </tr>

                            <tr>

                                <th class="border border-gray-300 bg-gray-50 px-4 py-3 text-left text-gray-900">
                                    Receipt No.
                                </th>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    INV-{{ str_pad($payment->id,6,'0',STR_PAD_LEFT) }}
                                </td>

                                <th class="border border-gray-300 bg-gray-50 px-4 py-3 text-left text-gray-900">
                                    Remarks
                                </th>

                                <td class="border border-gray-300 px-4 py-3 text-gray-900">
                                    {{ $payment->remarks ?: '-' }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>



           {{-- ================= AMOUNT IN WORDS ================= --}}

        <div class="mt-8 p-4 bg-gray-100 rounded-lg border border-gray-300">

            <span class="font-bold text-gray-900">
                Amount In Words :
            </span>

            <span class="text-gray-900">
                {{ ucwords(\NumberFormatter::create('en', \NumberFormatter::SPELLOUT)->format($payment->amount)) }}
                Rupees Only
            </span>

        </div>

        {{-- ================= TERMS ================= --}}

        <div class="mt-12">

            <h3 class="bg-gray-800 text-white px-4 py-2 rounded font-semibold">
                Terms & Conditions
            </h3>

            <ul class="list-disc ml-6 mt-4 text-sm text-gray-700 space-y-2">

                <li>
                    This receipt is generated by Frenzy Dance Studio.
                </li>

                <li>
                    Fees once paid are non-refundable.
                </li>

                <li>
                    Please keep this invoice for future reference.
                </li>

                <li>
                    Any dispute regarding payment should be reported within 7 days.
                </li>

            </ul>

        </div>



        {{-- ================= THANK YOU ================= --}}

        <div class="mt-12 text-center">

            <h2 class="text-2xl font-bold text-pink-600">
                Thank You!
            </h2>

            <p class="text-gray-600 mt-2">
                Thank you for choosing
                <strong class="text-gray-900">FRENZY DANCE STUDIO</strong>
            </p>

        </div>

        </div>



        {{-- ================= BUTTONS ================= --}}

        <div class="flex justify-center gap-4 mt-8 no-print">

            <button
                type="button"
                onclick="printInvoice()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold">

                🖨 Print Invoice

            </button>

            <a href="{{ route('student.payments') }}"
            class="bg-pink-600 hover:bg-pink-700 text-white px-8 py-3 rounded-lg font-semibold">

                ← Back To Payments

            </a>

        </div>

        </div>

        </section>



        <script>

        function printInvoice() {

            const invoice = document.querySelector('.invoice-box');

            invoice.style.transform = 'scale(0.96)';
            invoice.style.transformOrigin = 'top center';

            setTimeout(() => {

                window.print();

                setTimeout(() => {

                    invoice.style.transform = 'scale(1)';

                }, 300);

            }, 150);

        }

        </script>

@endsection
