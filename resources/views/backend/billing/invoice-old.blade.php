@extends('backend.partial.master')

@section('title', 'Payment Invoice')

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
    max-width:100%;
}

.amount-paid{
    font-size:15px;
    font-weight:700;
}

.no-print{
    margin-bottom:12px;
}

.invoice-footer{
    border-top:1px solid #ddd;
    margin-top:12px;
    padding-top:8px;
}

.payment-highlight{
    background:#f0fff4;
    border:1px solid #b7e4c7;
    border-radius:4px;
    padding:8px 10px;
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
        max-width:none;
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

    .section-title{
        font-size:12px;
        padding:4px 6px;
        margin:7px 0 5px;
    }

    .payment-highlight{
        padding:6px 8px;
    }

}

</style>


@php

    /*
    |--------------------------------------------------------------------------
    | SAME PAYMENT ID TOTAL
    |--------------------------------------------------------------------------
    */

    $paymentIdTotal = $payments
        ->where('status', 'success')
        ->sum(function ($record) {
            return (float) ($record->amount ?? 0);
        });

    $paymentIdTotal = round($paymentIdTotal, 2);

@endphp


<div class="container-fluid">


{{-- ================================================================
    ACTION BUTTONS
================================================================ --}}

<div class="mb-3 no-print">

    <a
        href="{{ url()->previous() }}"
        class="btn btn-secondary"
    >

        <i class="mdi mdi-arrow-left"></i>

        Back

    </a>


    <button
        type="button"
        onclick="window.print()"
        class="btn btn-primary"
    >

        <i class="mdi mdi-printer"></i>

        Print Invoice

    </button>

</div>



{{-- ================================================================
    INVOICE
================================================================ --}}

<div class="invoice-box">


{{-- ================================================================
    HEADER
================================================================ --}}

<div class="invoice-header">

    <div class="row align-items-center">

        {{-- Logo --}}
        <div class="col-2 text-center">

            <img
                src="{{ asset('images/logo.png') }}"
                class="logo"
                alt="Frenzy Dance Studio"
            >

        </div>


        {{-- Company --}}
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


        {{-- Invoice --}}
        <div class="col-3 text-end">

            <h4 class="invoice-title">

                PAYMENT RECEIPT

            </h4>

            <strong>

                INV-{{ $payment->payment_id }}

            </strong>

            <br>

            <small class="text-muted">

                Computer Generated Receipt

            </small>

        </div>

    </div>

</div>



{{-- ================================================================
    STUDENT INFORMATION
================================================================ --}}

<div class="section-title">

    Student Information

</div>


<table class="table table-bordered">

    <tr>

        <th>
            Name
        </th>

        <td>

            {{ $studentCourse->student->name ?? 'N/A' }}

        </td>


        <th>
            Admission No
        </th>

        <td>

            {{ $studentCourse->student->admission_no ?? 'N/A' }}

        </td>

    </tr>


    <tr>

        <th>
            Mobile
        </th>

        <td>

            {{ $studentCourse->student->phone ?? '-' }}

        </td>


        <th>
            Email
        </th>

        <td>

            {{ $studentCourse->student->email ?? '-' }}

        </td>

    </tr>

</table>



{{-- ================================================================
    COURSE INFORMATION
================================================================ --}}

<div class="section-title">

    Course Information

</div>


<table class="table table-bordered">

    <tr>

        <th>
            Course
        </th>

        <td>

            {{ $studentCourse->course->course_name ?? 'N/A' }}

        </td>


        <th>
            Batch
        </th>

        <td>

            {{ $studentCourse->batch->batch_name ?? '-' }}

        </td>

    </tr>


    <tr>

        <th>
            Level
        </th>

        <td>

            {{ $studentCourse->level->name ?? '-' }}

        </td>


        <th>
            Category
        </th>

        <td>

            {{ $studentCourse->category->name ?? '-' }}

        </td>

    </tr>


    <tr>

        <th>
            Monthly Fee
        </th>

        <td>

            ₹{{ number_format(
                (float) ($studentCourse->monthly_fee ?? 0),
                2
            ) }}

        </td>


        <th>
            Instructor
        </th>

        <td>

            {{ optional(
                $studentCourse->instructor
            )->name ?? '-' }}

        </td>

    </tr>

</table>



{{-- ================================================================
    PAYMENT INFORMATION
================================================================ --}}

<div class="section-title">

    Payment Information

</div>


<table class="table table-bordered">

    <tr>

        <th>
            Payment ID
        </th>

        <td>

            <strong>
                {{ $payment->payment_id }}
            </strong>

        </td>


        <th>
            Payment Date
        </th>

        <td>

            @if($payment->payment_date)

                {{ \Carbon\Carbon::parse(
                    $payment->payment_date
                )->format('d M Y') }}

            @else

                -

            @endif

        </td>

    </tr>


    <tr>

        <th>
            Payment Mode
        </th>

        <td>

            {{ $payment->payment_mode ?? '-' }}

        </td>


        <th>
            Payment Records
        </th>

        <td>

            <span class="badge bg-primary">

                {{ $payments->count() }}

                {{ $payments->count() == 1
                    ? 'Record'
                    : 'Records'
                }}

            </span>

        </td>

    </tr>


    <tr>

        <th>
            Transaction / Reference No.
        </th>

        <td>

            {{ $payment->transaction_id ?: 'N/A' }}

        </td>


        <th>
            Payment Status
        </th>

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
                        {{ ucfirst(
                            $payment->status ?? 'Unknown'
                        ) }}
                    </span>

            @endswitch

        </td>

    </tr>


    <tr>

        <th>
            Receipt No.
        </th>

        <td>

            INV-{{ $payment->payment_id }}

        </td>


        <th>
            Remarks
        </th>

        <td>

            {{ $payment->remarks ?: '-' }}

        </td>

    </tr>

</table>



{{-- ================================================================
    ALL PAYMENT DETAILS
================================================================ --}}

<div class="section-title">

    Payment Details

</div>


<table class="table table-bordered">

    <thead class="table-primary">

        <tr>

            <th width="8%">
                #
            </th>

            <th>
                Payment Date
            </th>

            <th>
                Description
            </th>

            <th>
                Payment Mode
            </th>

            <th>
                Transaction / Reference
            </th>

            <th>
                Status
            </th>

            <th width="18%" class="text-end">
                Amount (₹)
            </th>

        </tr>

    </thead>


    <tbody>

        @forelse($payments as $index => $paymentRecord)

            <tr>

                <td>
                    {{ $index + 1 }}
                </td>


                <td>

                    @if($paymentRecord->payment_date)

                        {{ \Carbon\Carbon::parse(
                            $paymentRecord->payment_date
                        )->format('d M Y') }}

                    @else

                        -

                    @endif

                </td>


                <td>

                    Course Fee Payment

                    <br>

                    <small class="text-muted">

                        Payment received against course billing

                    </small>

                </td>


                <td>

                    {{ $paymentRecord->payment_mode ?? '-' }}

                </td>


                <td>

                    {{ $paymentRecord->transaction_id ?: 'N/A' }}

                </td>


                <td>

                    @if($paymentRecord->status === 'success')

                        <span class="badge bg-success">
                            Success
                        </span>

                    @elseif($paymentRecord->status === 'pending')

                        <span class="badge bg-warning text-dark">
                            Pending
                        </span>

                    @elseif($paymentRecord->status === 'failed')

                        <span class="badge bg-danger">
                            Failed
                        </span>

                    @elseif($paymentRecord->status === 'cancelled')

                        <span class="badge bg-secondary">
                            Cancelled
                        </span>

                    @else

                        <span class="badge bg-dark">
                            {{ ucfirst(
                                $paymentRecord->status ?? 'Unknown'
                            ) }}
                        </span>

                    @endif

                </td>


                <td class="text-end">

                    ₹{{ number_format(
                        (float) ($paymentRecord->amount ?? 0),
                        2
                    ) }}

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7" class="text-center">

                    No payment records found.

                </td>

            </tr>

        @endforelse


        {{-- TOTAL --}}
        <tr class="table-success">

            <th colspan="6" class="text-end">

                Total Paid Amount for Payment ID

            </th>

            <th class="amount-paid text-end">

                ₹{{ number_format(
                    $paymentIdTotal,
                    2
                ) }}

            </th>

        </tr>

    </tbody>

</table>



{{-- ================================================================
    PAYMENT HIGHLIGHT
================================================================ --}}

<div class="payment-highlight mb-2">

    <div class="row align-items-center">

        <div class="col-8">

            <strong>

                Payment Received

            </strong>

            <br>

            <small class="text-muted">

                Total amount received against Payment ID:
                <strong>{{ $payment->payment_id }}</strong>

            </small>

        </div>


        <div class="col-4 text-end">

            <span class="amount-paid">

                ₹{{ number_format(
                    $paymentIdTotal,
                    2
                ) }}

            </span>

        </div>

    </div>

</div>



{{-- ================================================================
    BILLING SUMMARY
================================================================ --}}

<div class="section-title">

    Billing Summary

</div>


<table class="table table-bordered">

    <tr>

        <th>
            Total Course Fee
        </th>

        <td>

            ₹{{ number_format(
                (float) $totalCourseFee,
                2
            ) }}

        </td>


        <th>
            Registration Fee
        </th>

        <td>

            ₹{{ number_format(
                (float) $registrationFee,
                2
            ) }}

        </td>

    </tr>


    <tr>

        <th>
            Admission Fee
        </th>

        <td>

            ₹{{ number_format(
                (float) $admissionFee,
                2
            ) }}

        </td>


        <th>
            Late Fine / Penalty
        </th>

        <td>

            ₹{{ number_format(
                (float) $totalFine,
                2
            ) }}

        </td>

    </tr>


    <tr class="table-light">

        <th>
            Total Billing
        </th>

        <td>

            <strong>

                ₹{{ number_format(
                    (float) $totalBilling,
                    2
                ) }}

            </strong>

        </td>


        <th>
            Total Paid Till Date
        </th>

        <td class="text-success">

            <strong>

                ₹{{ number_format(
                    (float) $totalPaid,
                    2
                ) }}

            </strong>

        </td>

    </tr>


    <tr>

        <th>
            Remaining Due
        </th>

        <td>

            @if($remaining <= 0)

                <span class="badge bg-success">

                    Fully Paid

                </span>

            @else

                <strong class="text-danger">

                    ₹{{ number_format(
                        (float) $remaining,
                        2
                    ) }}

                </strong>

            @endif

        </td>


        <th>
            Payment Status
        </th>

        <td>

            @if($payment->status === 'success')

                <span class="badge bg-success">

                    Successful Payment

                </span>

            @elseif($payment->status === 'pending')

                <span class="badge bg-warning text-dark">

                    Payment Pending

                </span>

            @elseif($payment->status === 'failed')

                <span class="badge bg-danger">

                    Payment Failed

                </span>

            @else

                <span class="badge bg-secondary">

                    {{ ucfirst(
                        $payment->status ?? 'Unknown'
                    ) }}

                </span>

            @endif

        </td>

    </tr>

</table>



{{-- ================================================================
    MONTH COVERAGE
================================================================ --}}

@if(isset($paidMonthCount) && $paidMonthCount > 0)

<div class="section-title">

    Course Payment Coverage

</div>


<table class="table table-bordered">

    <tr>

        <th>
            Paid Months
        </th>

        <td>

            <strong>

                {{ $paidMonthLabel }}

            </strong>

        </td>


        <th>
            Total Paid Months
        </th>

        <td>

            <span class="badge bg-success">

                {{ $paidMonthCount }}

                {{ $paidMonthCount == 1
                    ? 'Month'
                    : 'Months'
                }}

            </span>

        </td>

    </tr>

</table>

@endif



{{-- ================================================================
    AMOUNT IN WORDS
================================================================ --}}

<p class="mb-2">

    <strong>
        Amount in Words:
    </strong>

    @php

        try {

            $formatter = \NumberFormatter::create(
                'en',
                \NumberFormatter::SPELLOUT
            );

            $amountInWords = $formatter->format(
                $paymentIdTotal
            );

        } catch (\Throwable $e) {

            $amountInWords = '';

        }

    @endphp


    {{ ucwords($amountInWords) }}

    Rupees Only

</p>



{{-- ================================================================
    SIGNATURE SECTION
================================================================ --}}

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



{{-- ================================================================
    FOOTER
================================================================ --}}

<div
    class="invoice-footer"
    style="font-size:10px;"
>

    <div class="row align-items-center">

        <div class="col-8">

            <strong>

                Terms & Conditions

            </strong>


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


</div>



{{-- ================================================================
    BOTTOM BUTTONS
================================================================ --}}

<div class="text-center mt-3 no-print">

    <button
        type="button"
        class="btn btn-primary"
        onclick="window.print();"
    >

        <i class="mdi mdi-printer"></i>

        Print Invoice

    </button>


    <a
        href="{{ route(
            'billing.payments',
            $studentCourse->id
        ) }}"
        class="btn btn-secondary"
    >

        <i class="mdi mdi-arrow-left"></i>

        Back to Payments

    </a>

</div>


</div>

@endsection


@push('scripts')

<script>

$(function () {

    // Auto print if required
    // window.print();

});

</script>

@endpush
