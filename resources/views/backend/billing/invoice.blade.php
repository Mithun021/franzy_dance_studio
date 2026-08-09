@extends('backend.partial.master')

@section('title','Payment Invoice')

@section('backend-content')

<style>

@page{
    size:A4 portrait;
    margin:8mm;
}

body{
    background:#f5f5f5;
    font-size:12px;
    line-height:1.3;
    color:#222;
}

.invoice-box{
    max-width:210mm;
    margin:10px auto;
    background:#fff;
    border:1px solid #ddd;
    padding:12px;
}

.invoice-header{
    border-bottom:2px solid #0d6efd;
    padding-bottom:8px;
    margin-bottom:10px;
}

.invoice-title{
    font-size:22px;
    font-weight:700;
    color:#0d6efd;
    margin:0;
}

.company-name{
    font-size:20px;
    font-weight:700;
    margin-bottom:2px;
}

.company-info{
    font-size:11px;
    margin:0;
}

.section-title{
    font-size:14px;
    font-weight:700;
    background:#f3f6fb;
    padding:5px 8px;
    margin:10px 0 6px;
    border-left:4px solid #0d6efd;
}

.table{
    margin-bottom:8px;
}

.table th,
.table td{
    padding:5px 8px !important;
    font-size:11px;
    vertical-align:middle;
}

.table th{
    width:20%;
    background:#fafafa;
}

.logo{
    max-height:65px;
}

.no-print{
    margin-bottom:12px;
}

@media print{

    body{
        background:#fff;
        font-size:10px;
    }

    .no-print{
        display:none!important;
    }

    .invoice-box{
        margin:0;
        border:none;
        box-shadow:none;
        padding:0;
        width:100%;
    }

    .table th,
    .table td{
        padding:4px 6px !important;
        font-size:10px;
    }

    .company-name{
        font-size:17px;
    }

    .invoice-title{
        font-size:18px;
    }

}

</style>


<div class="container-fluid">

<div class="mb-3 no-print">

    <a href="{{ url()->previous() }}" class="btn btn-secondary">
        <i class="mdi mdi-arrow-left"></i> Back
    </a>

    <button onclick="window.print()" class="btn btn-primary">
        <i class="mdi mdi-printer"></i> Print Invoice
    </button>

</div>

<div class="invoice-box">

<!-- ================= HEADER ================= -->

<div class="invoice-header">

    <div class="row align-items-center">

        <div class="col-2 text-center">

            {{-- Uncomment if logo available --}}

            {{--
            <img src="{{ asset(setting('logo')) }}" class="logo">
            --}}

        </div>

        <div class="col-7">

            <div class="company-name">
                FRENZY DANCE STUDIO
            </div>

            <p class="company-info mb-1">
                A Complete Performing & Fine Art Center
            </p>

            <small>
                Dance | Music | Art | Fitness
            </small>

        </div>

        <div class="col-3 text-end">

            <h4 class="invoice-title">
                INVOICE
            </h4>

            <strong>
                INV-{{ str_pad($payment->id,6,'0',STR_PAD_LEFT) }}
            </strong>

        </div>

    </div>

</div>


<!-- ================= STUDENT INFORMATION ================= -->

<div class="section-title">
    Student Information
</div>

<table class="table table-bordered">

<tr>

    <th>Name</th>

    <td>
        {{ $payment->studentCourse->student->name }}
    </td>

    <th>Admission No</th>

    <td>
        {{ $payment->studentCourse->admission_no }}
    </td>

</tr>

<tr>

    <th>Mobile</th>

    <td>
        {{ $payment->studentCourse->student->phone }}
    </td>

    <th>Email</th>

    <td>
        {{ $payment->studentCourse->student->email }}
    </td>

</tr>

<tr>

    <th>Payment Date</th>

    <td>
        {{ $payment->payment_date->format('d M Y') }}
    </td>

    <th>Payment Mode</th>

    <td>
        {{ $payment->payment_mode }}
    </td>

</tr>

</table>


<!-- ================= COURSE INFORMATION ================= -->

<div class="section-title">
    Course Information
</div>

<table class="table table-bordered">

<tr>

    <th>Course</th>

    <td>
        {{ $payment->studentCourse->course->course_name }}
    </td>

    <th>Batch</th>

    <td>
        {{ $payment->studentCourse->batch->batch_name ?? '-' }}
    </td>

</tr>

<tr>

    <th>Level</th>

    <td>
        {{ $payment->studentCourse->level->name ?? '-' }}
    </td>

    <th>Category</th>

    <td>
        {{ $payment->studentCourse->category->name ?? '-' }}
    </td>

</tr>

<tr>

    <th>Duration</th>

    <td>
        {{ $payment->studentCourse->course->course_duration }}
        {{ ucfirst($payment->studentCourse->course->duration_type) }}
    </td>

    <th>Instructor</th>

    <td>
        {{ optional($payment->studentCourse->instructor)->name ?? '-' }}
    </td>

</tr>

</table>

{{-- Part 2 starts from here --}}
{{-- ========================================================= --}}
{{-- PAYMENT DETAILS --}}
{{-- ========================================================= --}}

<div class="section-title">
    Payment Details
</div>

<table class="table table-bordered">

    <thead class="table-primary">

        <tr>

            <th width="8%">#</th>

            <th>Description</th>

            <th width="20%">Amount (₹)</th>

        </tr>

    </thead>

    <tbody>

        @php $i = 1; @endphp

        @if($payment->registration_fee > 0)

        <tr>

            <td>{{ $i++ }}</td>

            <td>Registration Fee</td>

            <td>{{ number_format($payment->registration_fee,2) }}</td>

        </tr>

        @endif


        @if($payment->admission_fee > 0)

        <tr>

            <td>{{ $i++ }}</td>

            <td>Admission Fee</td>

            <td>{{ number_format($payment->admission_fee,2) }}</td>

        </tr>

        @endif


        <tr>

            <td>{{ $i++ }}</td>

            <td>Monthly Course Fee</td>

            <td>{{ number_format($payment->course_fee,2) }}</td>

        </tr>

        <tr class="table-success">

            <th colspan="2" class="text-end">
                Paid Amount
            </th>

            <th>
                ₹ {{ number_format($payment->amount,2) }}
            </th>

        </tr>

    </tbody>

</table>


{{-- ========================================================= --}}
{{-- BILLING SUMMARY + TRANSACTION INFO (COMPACT) --}}
{{-- ========================================================= --}}

@php

$studentCourse = $payment->studentCourse;

$totalPaid = \App\Models\StudentPayment::where(
    'student_course_id',
    $studentCourse->id
)->sum('amount');

$remaining = $studentCourse->grand_total - $totalPaid;

@endphp


<div class="section-title">
    Billing & Transaction Summary
</div>

<table class="table table-bordered">

<tr>

    <th>Grand Total</th>

    <td>
        ₹ {{ number_format($studentCourse->grand_total,2) }}
    </td>

    <th>Total Paid</th>

    <td>
        ₹ {{ number_format($totalPaid,2) }}
    </td>

</tr>

<tr>

    <th>Remaining</th>

    <td>

        @if($remaining<=0)

            <span class="badge bg-success">
                Fully Paid
            </span>

        @else

            ₹ {{ number_format($remaining,2) }}

        @endif

    </td>

    <th>Status</th>

    <td>

        @switch($payment->status)

            @case('success')

                <span class="badge bg-success">
                    Success
                </span>

                @break

            @case('pending')

                <span class="badge bg-warning text-dark">
                    Pending
                </span>

                @break

            @case('failed')

                <span class="badge bg-danger">
                    Failed
                </span>

                @break

            @case('cancelled')

                <span class="badge bg-secondary">
                    Cancelled
                </span>

                @break

            @default

                <span class="badge bg-dark">
                    Unknown
                </span>

        @endswitch

    </td>

</tr>

<tr>

    <th>Payment Mode</th>

    <td>
        {{ $payment->payment_mode }}
    </td>

    <th>Transaction No.</th>

    <td>
        {{ $payment->transaction_id ?: 'N/A' }}
    </td>

</tr>

<tr>

    <th>Receipt No.</th>

    <td>
        INV-{{ str_pad($payment->id,6,'0',STR_PAD_LEFT) }}
    </td>

    <th>Remarks</th>

    <td>
        {{ $payment->remarks ?: '-' }}
    </td>

</tr>

</table>


{{-- ========================================================= --}}
{{-- AMOUNT IN WORDS --}}
{{-- ========================================================= --}}

<p class="mb-2">

<strong>Amount in Words :</strong>

{{ ucwords(\NumberFormatter::create('en', \NumberFormatter::SPELLOUT)->format($payment->amount)) }}
Rupees Only

</p>

{{-- Part 3 starts from here --}}
{{-- ========================================================= --}}
{{-- SIGNATURE SECTION --}}
{{-- ========================================================= --}}

<div class="row mt-3">

    <div class="col-6 text-center">

        <div style="height:35px;"></div>

        <hr class="mb-1">

        <strong style="font-size:11px;">
            Student Signature
        </strong>

    </div>

    <div class="col-6 text-center">

        <div style="height:35px;"></div>

        <hr class="mb-1">

        <strong style="font-size:11px;">
            Authorized Signature
        </strong>

    </div>

</div>


{{-- ========================================================= --}}
{{-- FOOTER --}}
{{-- ========================================================= --}}

<div class="border-top mt-3 pt-2" style="font-size:10px;">

    <div class="row align-items-center">

        <div class="col-8">

            <strong>Terms & Conditions</strong>

            <div>
                • Fees once paid are non-refundable.
            </div>

            <div>
                • Preserve this receipt for future reference.
            </div>

            <div>
                • Computer generated receipt.
            </div>

        </div>

        <div class="col-4 text-end">

            <strong class="text-success">
                Thank You!
            </strong>

            <div>
                FRENZY DANCE STUDIO
            </div>

        </div>

    </div>

</div>

</div> {{-- invoice-box --}}


{{-- ========================================================= --}}
{{-- PRINT BUTTON --}}
{{-- ========================================================= --}}

<div class="text-center mt-3 no-print">

    <button
        type="button"
        class="btn btn-primary"
        onclick="window.print();">

        <i class="mdi mdi-printer"></i>

        Print Invoice

    </button>

    <a href="{{ route('billing.index') }}"
       class="btn btn-secondary">

        <i class="mdi mdi-arrow-left"></i>

        Back

    </a>

</div>

</div>

@endsection


@push('scripts')

<script>

$(function () {

    // Auto Print
    // window.print();

});

</script>

@endpush
