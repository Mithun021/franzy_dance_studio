@extends('backend.partial.master')

@section('title', 'Payment Invoice')

@section('backend-content')

<style>

@page{
    size:A5 portrait;
    margin:5mm;
}

body{
    background:#f5f5f5;
    font-size:11px;
    line-height:1.3;
    color:#222;
}

.invoice-box{
    width:148mm;
    min-height:200mm;
    margin:12px auto;
    padding:10px;
    background:#fff;
    border:1px solid #d9d9d9;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}

.invoice-header{
    border-bottom:2px solid #0d6efd;
    padding-bottom:7px;
    margin-bottom:8px;
}

.logo{
    max-width:55px;
    max-height:55px;
}

.company-name{
    font-size:17px;
    font-weight:700;
    margin-bottom:1px;
    letter-spacing:.2px;
}

.company-info{
    font-size:9px;
    margin:0;
}

.invoice-title{
    font-size:14px;
    font-weight:700;
    color:#0d6efd;
    margin:0 0 2px;
}

.invoice-number{
    font-size:11px;
    font-weight:600;
}

.invoice-note{
    font-size:8px;
    color:#777;
}

.section-title{
    font-size:11px;
    font-weight:700;
    background:#f3f6fb;
    padding:4px 6px;
    margin:7px 0 4px;
    border-left:3px solid #0d6efd;
    color:#222;
}

.table{
    margin-bottom:5px;
}

.table th,
.table td{
    padding:4px 5px !important;
    font-size:9.5px;
    vertical-align:middle;
}

.table th{
    background:#fafafa;
    font-weight:600;
}

.payment-table{
    table-layout:fixed;
    width:100%;
}

.payment-table th,
.payment-table td{
    word-break:break-word;
}

.payment-table th:nth-child(1){
    width:5%;
}

.payment-table th:nth-child(2){
    width:14%;
}

.payment-table th:nth-child(3){
    width:20%;
}

.payment-table th:nth-child(4){
    width:13%;
}

.payment-table th:nth-child(5){
    width:19%;
}

.payment-table th:nth-child(6){
    width:11%;
}

.payment-table th:nth-child(7){
    width:18%;
}

.payment-description{
    line-height:1.2;
}

.payment-description small{
    font-size:7.5px;
}

.amount-paid{
    font-size:15px;
    font-weight:700;
}

.payment-highlight{
    background:#f0fff4;
    border:1px solid #b7e4c7;
    border-radius:4px;
    padding:7px 9px;
    margin-top:6px;
}

.amount-words{
    font-size:9px;
    margin:6px 0;
}

.signature-area{
    margin-top:12px;
}

.signature-line{
    height:25px;
    border-bottom:1px solid #555;
    margin-bottom:3px;
}

.signature-title{
    font-size:9px;
    font-weight:600;
}

.invoice-footer{
    border-top:1px solid #ddd;
    margin-top:8px;
    padding-top:6px;
    font-size:8px;
    color:#555;
}

.invoice-footer strong{
    color:#222;
}

.no-print{
    margin-bottom:12px;
}

@media print{

    @page{
        size:A5 portrait;
        margin:5mm;
    }

    html,
    body{
        width:148mm;
        min-height:210mm;
        margin:0 !important;
        padding:0 !important;
        background:#fff !important;
    }

    body{
        font-size:9px;
    }

    body *{
        visibility:hidden;
    }

    .invoice-box,
    .invoice-box *{
        visibility:visible;
    }

    .invoice-box{
        position:absolute;
        left:0;
        top:0;
        width:100%;
        min-height:0;
        margin:0;
        padding:0;
        border:none;
        box-shadow:none;
        background:#fff;
    }

    .no-print{
        display:none !important;
    }

    .invoice-header{
        padding-bottom:5px;
        margin-bottom:5px;
    }

    .logo{
        max-width:48px;
        max-height:48px;
    }

    .company-name{
        font-size:14px;
    }

    .company-info{
        font-size:8px;
    }

    .invoice-title{
        font-size:12px;
    }

    .invoice-number{
        font-size:9px;
    }

    .invoice-note{
        font-size:7px;
    }

    .section-title{
        font-size:9px;
        padding:3px 5px;
        margin:5px 0 3px;
    }

    .table{
        margin-bottom:4px;
    }

    .table th,
    .table td{
        padding:3px 4px !important;
        font-size:8px;
    }

    .payment-table th,
    .payment-table td{
        font-size:7.5px;
    }

    .payment-description small{
        font-size:6.5px;
    }

    .payment-highlight{
        padding:5px 7px;
        margin-top:4px;
    }

    .amount-paid{
        font-size:13px;
    }

    .amount-words{
        font-size:8px;
        margin:5px 0;
    }

    .signature-area{
        margin-top:8px;
    }

    .signature-line{
        height:20px;
    }

    .signature-title{
        font-size:8px;
    }

    .invoice-footer{
        margin-top:5px;
        padding-top:4px;
        font-size:7px;
    }

}

</style>


@php

$paymentIdTotal = $payments
    ->where('status', 'success')
    ->sum(function ($record) {
        return (float) ($record->amount ?? 0);
    });

$paymentIdTotal = round($paymentIdTotal, 2);

try {

    $formatter = \NumberFormatter::create(
        'en',
        \NumberFormatter::SPELLOUT
    );

    $amountInWords = $formatter->format($paymentIdTotal);

} catch (\Throwable $e) {

    $amountInWords = '';

}

@endphp


<div class="container-fluid">


    {{-- ACTION BUTTONS --}}

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


    {{-- ==========================================================
         INVOICE
    =========================================================== --}}

    <div class="invoice-box">


        {{-- ======================================================
             HEADER
        ======================================================= --}}

        <div class="invoice-header">

            <div class="row align-items-center">

                <div class="col-2 text-center">

                    <img
                        src="{{ asset('images/logo.png') }}"
                        class="logo"
                        alt="Frenzy Dance Studio"
                    >

                </div>

                <div class="col-6">

                    <div class="company-name">
                        FRENZY DANCE STUDIO
                    </div>

                    <p class="company-info">
                        A Complete Performing & Fine Art Center
                    </p>

                    <small>
                        Dance | Music | Art | Fitness
                    </small>

                </div>

                <div class="col-4 text-end">

                    <div class="invoice-title">
                        PAYMENT RECEIPT
                    </div>

                    <div class="invoice-number">
                        INV-{{ $payment->payment_id }}
                    </div>

                    <div class="invoice-note">
                        Computer Generated Receipt
                    </div>

                </div>

            </div>

        </div>


        {{-- ======================================================
             STUDENT INFORMATION
        ======================================================= --}}

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
                    {{ $studentCourse->student->user_id ?? 'N/A' }}
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


        {{-- ======================================================
             COURSE INFORMATION
        ======================================================= --}}

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
                    {{ optional($studentCourse->instructor)->name ?? '-' }}
                </td>

            </tr>

        </table>


        {{-- ======================================================
             PAYMENT DETAILS
        ======================================================= --}}

        <div class="section-title">
            Payment Details
        </div>

        <table class="table table-bordered payment-table">

            <thead class="table-primary">

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Date
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Mode
                    </th>

                    <th>
                        Transaction / Reference
                    </th>

                    <th>
                        Status
                    </th>

                    <th class="text-end">
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

                        <td class="payment-description">
                            Course Fee Payment
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

                        <td
                            colspan="7"
                            class="text-center"
                        >
                            No payment records found.
                        </td>

                    </tr>

                @endforelse


                {{-- TOTAL --}}

                <tr class="table-success">

                    <th
                        colspan="6"
                        class="text-end"
                    >
                        Total Paid Amount
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


        {{-- ======================================================
             AMOUNT IN WORDS
        ======================================================= --}}

        <div class="amount-words">

            <strong>
                Amount in Words:
            </strong>

            {{ ucwords($amountInWords) }}

            Rupees Only

        </div>


        {{-- ======================================================
             SIGNATURE
        ======================================================= --}}

        <div class="row signature-area">

            <div class="col-6 text-center">

                <div class="signature-line"></div>

                <div class="signature-title">
                    Student Signature
                </div>

            </div>

            <div class="col-6 text-center">

                <div class="signature-line"></div>

                <div class="signature-title">
                    Authorized Signature
                </div>

            </div>

        </div>


        {{-- ======================================================
             FOOTER
        ======================================================= --}}

        <div class="invoice-footer">

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

                    <br>

                    FRENZY DANCE STUDIO

                </div>

            </div>

        </div>


    </div>


    {{-- ==========================================================
         BOTTOM BUTTONS
    =========================================================== --}}

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

});

</script>

@endpush
