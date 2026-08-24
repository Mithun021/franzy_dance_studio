@extends('backend.partial.master')

@section('title','Salary Details')

@section('backend-content')

<style>

/* ==========================================================
   SCREEN
========================================================== */

.salary-page {
    width: 100%;
}

.invoice-box {
    max-width: 1000px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 2px solid #222;
    padding-bottom: 15px;
    margin-bottom: 18px;
}

.company-name {
    font-size: 25px;
    font-weight: 700;
}

.invoice-subtitle {
    font-size: 13px;
    color: #6c757d;
}

.invoice-id-box {
    text-align: right;
}

.invoice-id-label {
    font-size: 11px;
    color: #6c757d;
}

.invoice-id {
    font-size: 17px;
    font-weight: 700;
}

.section-title {
    font-size: 14px;
    font-weight: 700;
    border-bottom: 1px solid #ddd;
    padding-bottom: 6px;
    margin-bottom: 8px;
}

.info-table,
.amount-table {
    width: 100%;
    border-collapse: collapse;
}

.info-table th,
.info-table td,
.amount-table th,
.amount-table td {
    border: 1px solid #dee2e6;
    padding: 7px 9px;
    font-size: 12px;
}

.info-table th,
.amount-table th {
    background: #f5f6f7;
    font-weight: 600;
}

.info-table th {
    width: 28%;
}

.snapshot-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.snapshot-box {
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 10px;
}

.snapshot-label {
    font-size: 10px;
    color: #6c757d;
    margin-bottom: 3px;
}

.snapshot-value {
    font-size: 16px;
    font-weight: 700;
}

.calculation-box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 9px 12px;
    margin-top: 10px;
    margin-bottom: 15px;
    font-size: 12px;
}

.amount-table .total-row td,
.amount-table .total-row th {
    font-weight: 700;
}

.paid-text {
    color: #198754;
    font-weight: 700;
}

.due-text {
    color: #dc3545;
    font-weight: 700;
}

.payment-status {
    display: inline-block;
    padding: 4px 9px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.status-paid {
    background: #d1e7dd;
    color: #0f5132;
}

.status-partial {
    background: #fff3cd;
    color: #664d03;
}

.status-pending {
    background: #f8d7da;
    color: #842029;
}

.status-none {
    background: #e2e3e5;
    color: #41464b;
}

.signature-section {
    display: flex;
    justify-content: space-between;
    margin-top: 45px;
}

.signature {
    width: 220px;
    font-size: 11px;
}

.signature-right {
    text-align: right;
}

.invoice-footer {
    text-align: center;
    border-top: 1px solid #ddd;
    margin-top: 25px;
    padding-top: 8px;
    font-size: 10px;
    color: #777;
}


/* ==========================================================
   PRINT
========================================================== */

@media print {

    @page {
        size: A4 portrait;
        margin: 8mm;
    }

    html,
    body {
        width: 210mm;
        height: 297mm;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    body {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 10px !important;
        color: #000 !important;
    }

    /*
    Hide entire application
    */

    body * {
        visibility: hidden !important;
    }

    /*
    Show only invoice
    */

    #invoice-area,
    #invoice-area * {
        visibility: visible !important;
    }

    /*
    Remove Bootstrap/container effects
    */

    #invoice-area {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;

        width: 100% !important;
        max-width: none !important;

        margin: 0 !important;
        padding: 0 !important;
    }

    .invoice-box {
        width: 100% !important;
        max-width: none !important;

        margin: 0 !important;
        padding: 5mm !important;

        border: 1px solid #999 !important;
        border-radius: 0 !important;

        box-shadow: none !important;

        box-sizing: border-box !important;
    }

    /*
    Header
    */

    .invoice-header {
        padding-bottom: 8px !important;
        margin-bottom: 10px !important;
        border-bottom: 1.5px solid #000 !important;
    }

    .company-name {
        font-size: 19px !important;
    }

    .invoice-subtitle {
        font-size: 9px !important;
    }

    .invoice-id-label {
        font-size: 8px !important;
    }

    .invoice-id {
        font-size: 13px !important;
    }

    /*
    Section
    */

    .section-title {
        font-size: 10px !important;
        padding-bottom: 3px !important;
        margin-bottom: 5px !important;
    }

    /*
    Tables
    */

    .info-table,
    .amount-table {
        width: 100% !important;
        page-break-inside: avoid !important;
    }

    .info-table th,
    .info-table td,
    .amount-table th,
    .amount-table td {
        padding: 4px 6px !important;
        font-size: 9px !important;
        line-height: 1.25 !important;
    }

    /*
    Snapshot
    */

    .snapshot-grid {
        gap: 6px !important;
    }

    .snapshot-box {
        padding: 6px !important;
        border-radius: 3px !important;
    }

    .snapshot-label {
        font-size: 8px !important;
    }

    .snapshot-value {
        font-size: 12px !important;
    }

    /*
    Calculation
    */

    .calculation-box {
        padding: 5px 7px !important;
        margin-top: 6px !important;
        margin-bottom: 8px !important;
        font-size: 9px !important;
    }

    /*
    Signature
    */

    .signature-section {
        margin-top: 28px !important;
    }

    .signature {
        width: 180px !important;
        font-size: 8px !important;
    }

    /*
    Footer
    */

    .invoice-footer {
        margin-top: 12px !important;
        padding-top: 5px !important;
        font-size: 7px !important;
    }

    /*
    Never split invoice
    */

    .invoice-box,
    .invoice-header,
    .snapshot-grid,
    .calculation-box,
    .amount-table,
    .info-table,
    .signature-section {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    /*
    Remove unwanted Bootstrap spacing
    */

    .row,
    .col,
    [class*="col-"] {
        margin: 0 !important;
        padding: 0 !important;
    }

    /*
    Hide buttons
    */

    .no-print {
        display: none !important;
    }

}

</style>


<div class="salary-page">

    {{-- =====================================================
        ACTION BUTTONS
    ====================================================== --}}

    <div class="mb-3 no-print">

        <a
            href="{{ route('salary-management.index') }}"
            class="btn btn-secondary">

            <i class="fa fa-arrow-left"></i>
            Back

        </a>


        <a
            href="{{ route('salary-management.edit', $salary->id) }}"
            class="btn btn-warning">

            <i class="fa fa-edit"></i>
            Edit

        </a>


        <button
            type="button"
            onclick="window.print()"
            class="btn btn-primary">

            <i class="fa fa-print"></i>
            Print

        </button>

    </div>


    {{-- =====================================================
        INVOICE
    ====================================================== --}}

    <div id="invoice-area">

        <div class="invoice-box">


            {{-- =================================================
                HEADER
            ================================================== --}}

            <div class="invoice-header">

                <div>

                    <div class="company-name">

                        FRANZY DANCE STUDIO

                    </div>

                    <div class="invoice-subtitle">

                        Faculty Salary Statement

                    </div>

                </div>


                <div class="invoice-id-box">

                    <div class="invoice-id-label">

                        SALARY ID

                    </div>

                    <div class="invoice-id">

                        {{ $salary->salary_id }}

                    </div>

                </div>

            </div>


            {{-- =================================================
                EMPLOYEE INFORMATION
            ================================================== --}}

            <div>

                <div class="section-title">

                    EMPLOYEE INFORMATION

                </div>


                <table class="info-table">

                    <tr>

                        <th>Employee ID</th>

                        <td>
                            {{ optional($salary->employee)->user_id ?? '-' }}
                        </td>

                        <th>Employee Name</th>

                        <td>
                            {{ optional($salary->employee)->name ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>Email</th>

                        <td>
                            {{ optional($salary->employee)->email ?? '-' }}
                        </td>

                        <th>Phone</th>

                        <td>
                            {{ optional($salary->employee)->phone ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>Salary Month</th>

                        <td colspan="3">

                            @if($salary->salary_month)

                                {{ \Carbon\Carbon::parse(
                                    $salary->salary_month
                                )->format('F Y') }}

                            @else

                                -

                            @endif

                        </td>

                    </tr>

                </table>

            </div>


            <br>


            {{-- =================================================
                SNAPSHOT
            ================================================== --}}

            <div>

                <div class="section-title">

                    SALARY CALCULATION SNAPSHOT

                </div>


                <div class="snapshot-grid">


                    <div class="snapshot-box">

                        <div class="snapshot-label">

                            ASSIGNED STUDENTS

                        </div>

                        <div class="snapshot-value">

                            {{ number_format(
                                $salary->assigned_student ?? 0
                            ) }}

                        </div>

                    </div>


                    <div class="snapshot-box">

                        <div class="snapshot-label">

                            PAYMENT BEFORE CALCULATION

                        </div>

                        <div class="snapshot-value">

                            ₹ {{ number_format(
                                $salary->payment_before_calculate ?? 0,
                                2
                            ) }}

                        </div>

                    </div>


                    <div class="snapshot-box">

                        <div class="snapshot-label">

                            DIVIDED PERCENTAGE

                        </div>

                        <div class="snapshot-value">

                            {{ number_format(
                                $salary->divided_percentage ?? 0,
                                2
                            ) }}%

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                CALCULATION
            ================================================== --}}

            <div class="calculation-box">

                <strong>Calculation:</strong>

                ₹ {{ number_format(
                    $salary->payment_before_calculate ?? 0,
                    2
                ) }}

                ×

                {{ number_format(
                    $salary->divided_percentage ?? 0,
                    2
                ) }}%

                =

                <strong>

                    ₹ {{ number_format(
                        $salary->salary_amount ?? 0,
                        2
                    ) }}

                </strong>

            </div>


            {{-- =================================================
                PAYMENT DETAILS
            ================================================== --}}

            <div>

                <div class="section-title">

                    SALARY PAYMENT

                </div>


                <table class="amount-table">

                    <thead>

                        <tr>

                            <th>
                                Description
                            </th>

                            <th width="180" class="text-end">
                                Amount
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td>
                                Total Earning Amount
                            </td>

                            <td class="text-end">

                                ₹ {{ number_format(
                                    $salary->salary_amount ?? 0,
                                    2
                                ) }}

                            </td>

                        </tr>


                        <tr>

                            <td class="paid-text">

                                Paid Amount

                            </td>

                            <td class="text-end paid-text">

                                ₹ {{ number_format(
                                    $salary->paid_amount ?? 0,
                                    2
                                ) }}

                            </td>

                        </tr>


                        <tr>

                            <td class="due-text">

                                Due Amount

                            </td>

                            <td class="text-end due-text">

                                ₹ {{ number_format(
                                    $salary->due_amount ?? 0,
                                    2
                                ) }}

                            </td>

                        </tr>


                        <tr>

                            <th>

                                Payment Method

                            </th>

                            <td class="text-end">

                                {{ $salary->payment_method ?: '-' }}

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            <br>


            {{-- =================================================
                PAYMENT STATUS
            ================================================== --}}

            @php

                $salaryAmount =
                    (float) ($salary->salary_amount ?? 0);

                $paidAmount =
                    (float) ($salary->paid_amount ?? 0);

                $dueAmount =
                    (float) ($salary->due_amount ?? 0);

            @endphp


            <div>

                <div class="section-title">

                    PAYMENT STATUS

                </div>


                @if($salaryAmount <= 0)

                    <span class="payment-status status-none">

                        No Earning

                    </span>

                @elseif($dueAmount <= 0)

                    <span class="payment-status status-paid">

                        Fully Paid

                    </span>

                @elseif($paidAmount > 0)

                    <span class="payment-status status-partial">

                        Partially Paid

                    </span>

                @else

                    <span class="payment-status status-pending">

                        Payment Pending

                    </span>

                @endif

            </div>


            <br>


            {{-- =================================================
                ADDITIONAL INFORMATION
            ================================================== --}}

            <div>

                <div class="section-title">

                    ADDITIONAL INFORMATION

                </div>


                <table class="info-table">

                    <tr>

                        <th>

                            Remarks

                        </th>

                        <td>

                            {{ $salary->description ?: '-' }}

                        </td>

                    </tr>


                    <tr>

                        <th>

                            Created By

                        </th>

                        <td>

                            {{ optional($salary->creator)->name ?? '-' }}

                        </td>

                    </tr>


                    <tr>

                        <th>

                            Created At

                        </th>

                        <td>

                            @if($salary->created_at)

                                {{ $salary->created_at->format(
                                    'd M Y h:i A'
                                ) }}

                            @else

                                -

                            @endif

                        </td>

                    </tr>

                </table>

            </div>


            {{-- =================================================
                SIGNATURE
            ================================================== --}}

            <div class="signature-section">

                <div class="signature">

                    __________________________

                    <br>

                    Employee Signature

                </div>


                <div class="signature signature-right">

                    __________________________

                    <br>

                    Authorized Signature

                </div>

            </div>


            {{-- =================================================
                FOOTER
            ================================================== --}}

            <div class="invoice-footer">

                This is a system-generated salary statement
                from FRANZY DANCE STUDIO.

            </div>


        </div>

    </div>

</div>

@endsection
