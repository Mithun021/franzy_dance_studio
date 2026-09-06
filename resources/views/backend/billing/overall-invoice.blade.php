@extends('backend.partial.master')

@section('title','Overall Payment Invoice')

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
    width:210mm;
    max-width:210mm;
    margin:10px auto;
    background:#fff;
    border:1px solid #ddd;
    padding:12px;
    box-sizing:border-box;
}

.invoice-header{
    border-bottom:2px solid #0d6efd;
    padding-bottom:8px;
    margin-bottom:10px;
}

.invoice-title{
    font-size:21px;
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
    width:100%;
    margin-bottom:8px;
}

.table th,
.table td{
    padding:5px 7px !important;
    font-size:10.5px;
    vertical-align:middle;
}

.table th{
    background:#fafafa;
}

.logo{
    max-height:65px;
    max-width:100%;
}

.no-print{
    margin-bottom:12px;
}

.summary-number{
    font-size:16px;
    font-weight:700;
}

.invoice-footer{
    border-top:1px solid #ddd;
    margin-top:12px;
    padding-top:8px;
}

.payment-history-table,
.monthly-details-table,
.fine-details-table{
    page-break-inside:auto;
}

.payment-history-table tr,
.monthly-details-table tr,
.fine-details-table tr{
    page-break-inside:avoid;
    page-break-after:auto;
}

.section-block{
    page-break-inside:auto;
}

.invoice-footer{
    page-break-inside:avoid;
}

.signature-section{
    page-break-inside:avoid;
}

@media print{

    @page{
        size:A4 portrait;
        margin:8mm;
    }

    html,
    body{
        width:210mm;
        margin:0 !important;
        padding:0 !important;
        background:#fff !important;
    }

    body{
        font-size:10px;
        color:#222;
    }

    /*
    |--------------------------------------------------------------------------
    | HIDE EVERYTHING
    |--------------------------------------------------------------------------
    */

    body *{
        visibility:hidden;
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW ONLY INVOICE
    |--------------------------------------------------------------------------
    */

    .invoice-box,
    .invoice-box *{
        visibility:visible;
    }

    /*
    |--------------------------------------------------------------------------
    | PRINT ONLY INVOICE AREA
    |--------------------------------------------------------------------------
    */

    .invoice-box{
        position:absolute;
        left:0;
        top:0;
        width:100%;
        max-width:none;
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

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    .invoice-header{
        padding-bottom:7px;
        margin-bottom:8px;
    }

    .logo{
        max-height:58px;
        max-width:100%;
    }

    .company-name{
        font-size:17px;
    }

    .company-info{
        font-size:10px;
    }

    .invoice-title{
        font-size:17px;
    }

    /*
    |--------------------------------------------------------------------------
    | SECTIONS
    |--------------------------------------------------------------------------
    */

    .section-title{
        font-size:11px;
        padding:4px 6px;
        margin:7px 0 5px;
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .table{
        width:100%;
        margin-bottom:7px;
    }

    .table th,
    .table td{
        padding:4px 5px !important;
        font-size:9.5px;
    }

    /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

    .summary-number{
        font-size:14px;
    }

    /*
    |--------------------------------------------------------------------------
    | FOOTER
    |--------------------------------------------------------------------------
    */

    .invoice-footer{
        margin-top:10px;
        padding-top:7px;
        font-size:9px !important;
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE BREAK CONTROL
    |--------------------------------------------------------------------------
    */

    .section-title{
        page-break-after:avoid;
    }

    .table thead{
        display:table-header-group;
    }

    .table tfoot{
        display:table-footer-group;
    }

    tr{
        page-break-inside:avoid;
    }

    .signature-section{
        page-break-inside:avoid;
    }

    .invoice-footer{
        page-break-inside:avoid;
    }

}

</style>


<div class="container-fluid">


{{-- ====================================================
    TOP BUTTONS
===================================================== --}}

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
        class="btn btn-primary"
        onclick="window.print()"
    >

        <i class="mdi mdi-printer"></i>

        Print Invoice

    </button>

</div>



{{-- ====================================================
    INVOICE BOX
===================================================== --}}

<div class="invoice-box">


{{-- ====================================================
    HEADER
===================================================== --}}

<div class="invoice-header">

    <div class="row align-items-center">


        <div class="col-2 text-center">

            <img
                src="{{ asset('images/logo.png') }}"
                class="logo"
                alt="Frenzy Dance Studio"
            >

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

                OVERALL INVOICE

            </h4>

            <strong>

                INV-{{ str_pad(
                    $studentCourse->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                ) }}

            </strong>

            <br>

            <small class="text-muted">

                Complete Payment Statement

            </small>

        </div>

    </div>

</div>



{{-- ====================================================
    STUDENT INFORMATION
===================================================== --}}

<div class="section-title">

    Student Information

</div>


<table class="table table-bordered">

    <tr>

        <th width="20%">
            Student Name
        </th>

        <td>
            {{ $studentCourse->student->name ?? 'N/A' }}
        </td>


        <th width="20%">
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



{{-- ====================================================
    COURSE INFORMATION
===================================================== --}}

<div class="section-title">

    Course Information

</div>


<table class="table table-bordered">

    <tr>

        <th width="20%">
            Course
        </th>

        <td>
            {{ $studentCourse->course->course_name ?? 'N/A' }}
        </td>


        <th width="20%">
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



{{-- ====================================================
    BILLING SUMMARY
===================================================== --}}

<div class="section-title">

    Billing Summary

</div>


<table class="table table-bordered">

    <tr>

        <th width="20%">
            Monthly Course Fee
        </th>

        <td>

            ₹{{ number_format(
                $totalCourseFee,
                2
            ) }}

        </td>


        <th width="20%">
            Registration Fee
        </th>

        <td>

            ₹{{ number_format(
                $registrationFee,
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
                $admissionFee,
                2
            ) }}

        </td>


        <th>
            Late Fine / Penalty
        </th>

        <td>

            ₹{{ number_format(
                $totalFine,
                2
            ) }}

        </td>

    </tr>


    <tr class="table-primary">

        <th>
            Total Billing
        </th>

        <td>

            <strong class="summary-number">

                ₹{{ number_format(
                    $totalBilling,
                    2
                ) }}

            </strong>

        </td>


        <th>
            Total Paid
        </th>

        <td>

            <strong
                class="summary-number text-success"
            >

                ₹{{ number_format(
                    $totalPaid,
                    2
                ) }}

            </strong>

        </td>

    </tr>


    <tr>

        <th>
            Remaining
        </th>

        <td>

            @if($remaining <= 0)

                <span class="badge bg-success">

                    Fully Paid

                </span>

            @else

                <strong class="text-danger">

                    ₹{{ number_format(
                        $remaining,
                        2
                    ) }}

                </strong>

            @endif

        </td>


        <th>
            Total Payments
        </th>

        <td>

            <span class="badge bg-primary">

                {{ $paymentCount }}

                {{ $paymentCount == 1
                    ? 'Payment'
                    : 'Payments'
                }}

            </span>

        </td>

    </tr>

</table>



{{-- ====================================================
    PAYMENT COVERAGE
===================================================== --}}

<div class="section-title">

    Payment Coverage

</div>


<table class="table table-bordered">

    <tr>

        <th width="20%">
            Paid Months
        </th>

        <td>

            @if($paidMonthCount > 0)

                <strong>

                    {{ $paidMonthLabel }}

                </strong>

                <br>

                <small class="text-muted">

                    {{ $paidMonthCount }}

                    {{ $paidMonthCount == 1
                        ? 'Month'
                        : 'Months'
                    }}

                    Paid

                </small>

            @else

                No payment yet

            @endif

        </td>


        <th width="20%">
            Payment Status
        </th>

        <td>

            @if($remaining <= 0)

                <span class="badge bg-success">

                    FULLY PAID

                </span>

            @elseif($totalPaid > 0)

                <span class="badge bg-warning text-dark">

                    PARTIALLY PAID

                </span>

            @else

                <span class="badge bg-danger">

                    UNPAID

                </span>

            @endif

        </td>

    </tr>

</table>



{{-- ====================================================
    ALL PAYMENT HISTORY
===================================================== --}}

<div class="section-title">

    Complete Payment History

</div>


<table class="table table-bordered payment-history-table">

    <thead class="table-primary">

        <tr>

            <th width="6%">
                #
            </th>

            <th width="16%">
                Payment Date
            </th>

            <th width="16%">
                Payment Mode
            </th>

            <th>
                Transaction / Reference
            </th>

            <th width="13%">
                Status
            </th>

            <th
                width="18%"
                class="text-end"
            >
                Amount (₹)
            </th>

        </tr>

    </thead>


    <tbody>

        @forelse($payments as $paymentRecord)

            <tr>

                <td>
                    {{ $loop->iteration }}
                </td>


                <td>

                    {{ \Carbon\Carbon::parse(
                        $paymentRecord->payment_date
                    )->format('d M Y') }}

                </td>


                <td>

                    {{ $paymentRecord->payment_mode }}

                </td>


                <td>

                    {{ $paymentRecord->transaction_id ?: '-' }}

                </td>


                <td>

                    @if(
                        $paymentRecord->status === 'success'
                    )

                        <span class="badge bg-success">

                            Success

                        </span>

                    @elseif(
                        $paymentRecord->status === 'pending'
                    )

                        <span class="badge bg-warning text-dark">

                            Pending

                        </span>

                    @else

                        <span class="badge bg-secondary">

                            {{ ucfirst(
                                $paymentRecord->status
                            ) }}

                        </span>

                    @endif

                </td>


                <td class="text-end">

                    ₹{{ number_format(
                        (float) $paymentRecord->amount,
                        2
                    ) }}

                </td>

            </tr>

        @empty

            <tr>

                <td
                    colspan="6"
                    class="text-center"
                >

                    No payment records found.

                </td>

            </tr>

        @endforelse


        {{-- TOTAL --}}

        <tr class="table-success">

            <th
                colspan="5"
                class="text-end"
            >

                Total Paid

            </th>

            <th class="text-end">

                ₹{{ number_format(
                    $totalPaid,
                    2
                ) }}

            </th>

        </tr>

    </tbody>

</table>



{{-- ====================================================
    MONTHLY FEE DETAILS
===================================================== --}}

@if($monthRecords->count() > 0)

<div class="section-title">

    Monthly Billing Details

</div>


<table class="table table-bordered monthly-details-table">

    <thead class="table-light">

        <tr>

            <th width="8%">
                #
            </th>

            <th>
                Fee Month
            </th>

            <th>
                Monthly Fee
            </th>

            <th>
                Payable
            </th>

            <th>
                Paid
            </th>

            <th>
                Status
            </th>

        </tr>

    </thead>


    <tbody>

        @foreach($monthRecords as $month)

            <tr>

                <td>
                    {{ $loop->iteration }}
                </td>


                <td>

                    {{ \Carbon\Carbon::parse(
                        $month->fee_month
                    )->format('M Y') }}

                </td>


                <td>

                    ₹{{ number_format(
                        (float) $month->monthly_fee,
                        2
                    ) }}

                </td>


                <td>

                    ₹{{ number_format(
                        (float) $month->payable_amount,
                        2
                    ) }}

                </td>


                <td class="text-success">

                    ₹{{ number_format(
                        (float) $month->paid_amount,
                        2
                    ) }}

                </td>


                <td>

                    @if($month->status === 'paid')

                        <span class="badge bg-success">

                            Paid

                        </span>

                    @elseif($month->status === 'partial')

                        <span class="badge bg-warning text-dark">

                            Partial

                        </span>

                    @else

                        <span class="badge bg-danger">

                            Unpaid

                        </span>

                    @endif

                </td>

            </tr>

        @endforeach

    </tbody>

</table>

@endif



{{-- ====================================================
    FINE / PENALTY DETAILS
===================================================== --}}

@if($lateFines->count() > 0)

<div class="section-title">

    Late Fine / Penalty Details

</div>


<table class="table table-bordered fine-details-table">

    <thead class="table-light">

        <tr>

            <th width="7%">
                #
            </th>

            <th>
                Date
            </th>

            <th>
                Due Date
            </th>

            <th>
                Description
            </th>

            <th>
                Status
            </th>

            <th
                width="18%"
                class="text-end"
            >
                Amount (₹)
            </th>

        </tr>

    </thead>


    <tbody>

        @foreach($lateFines as $fine)

            <tr>

                <td>
                    {{ $loop->iteration }}
                </td>


                <td>

                    {{ $fine->fine_date
                        ? \Carbon\Carbon::parse(
                            $fine->fine_date
                        )->format('d M Y')
                        : '-'
                    }}

                </td>


                <td>

                    {{ $fine->due_date
                        ? \Carbon\Carbon::parse(
                            $fine->due_date
                        )->format('d M Y')
                        : '-'
                    }}

                </td>


                <td>

                    {{ $fine->remarks
                        ?: 'Late Fine'
                    }}

                </td>


                <td>

                    @if(
                        $fine->status === 'paid'
                    )

                        <span class="badge bg-success">

                            Paid

                        </span>

                    @else

                        <span class="badge bg-warning text-dark">

                            {{ ucfirst(
                                $fine->status
                            ) }}

                        </span>

                    @endif

                </td>


                <td class="text-end">

                    ₹{{ number_format(
                        (float) $fine->fine_amount,
                        2
                    ) }}

                </td>

            </tr>

        @endforeach


        <tr class="table-light">

            <th
                colspan="5"
                class="text-end"
            >

                Total Fine / Penalty

            </th>

            <th class="text-end">

                ₹{{ number_format(
                    $totalFine,
                    2
                ) }}

            </th>

        </tr>

    </tbody>

</table>

@endif



{{-- ====================================================
    FINAL SUMMARY
===================================================== --}}

<div class="section-title">

    Final Payment Summary

</div>


<table class="table table-bordered">

    <tr>

        <th width="30%">
            Total Billing Amount
        </th>

        <td>

            <strong>

                ₹{{ number_format(
                    $totalBilling,
                    2
                ) }}

            </strong>

        </td>

    </tr>


    <tr>

        <th>
            Total Amount Paid
        </th>

        <td class="text-success">

            <strong>

                ₹{{ number_format(
                    $totalPaid,
                    2
                ) }}

            </strong>

        </td>

    </tr>


    <tr>

        <th>
            Balance / Remaining
        </th>

        <td>

            @if($remaining <= 0)

                <span class="badge bg-success">

                    FULLY PAID

                </span>

            @else

                <strong class="text-danger">

                    ₹{{ number_format(
                        $remaining,
                        2
                    ) }}

                </strong>

            @endif

        </td>

    </tr>

</table>



{{-- ====================================================
    AMOUNT IN WORDS
===================================================== --}}

@if($amountInWords)

<p class="mb-2">

    <strong>
        Total Paid in Words:
    </strong>

    {{ ucwords($amountInWords) }}

    Rupees Only

</p>

@endif



{{-- ====================================================
    SIGNATURE
===================================================== --}}

<div class="row mt-3 signature-section">

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



{{-- ====================================================
    FOOTER
===================================================== --}}

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
                • Preserve this invoice for future reference.
            </div>

            <div>
                • Computer generated document.
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



{{-- ====================================================
    BOTTOM BUTTONS
===================================================== --}}

<div class="text-center mt-3 no-print">

    <button
        type="button"
        class="btn btn-primary"
        onclick="window.print()"
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
