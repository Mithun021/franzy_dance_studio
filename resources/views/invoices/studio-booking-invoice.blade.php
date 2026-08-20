<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Studio Booking Invoice</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 18px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
            line-height: 1.35;
            margin: 0;
            padding: 5px;
        }

        .invoice {
            width: 100%;
        }

        /* ==============================
           Header
        ============================== */

        .header {
            width: 100%;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .header table {
            width: 100%;
        }

        .header td {
            vertical-align: top;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #e91e63;
        }

        .company-sub {
            color: #555;
            font-size: 9px;
            margin-top: 2px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #1565C0;
            text-align: right;
        }

        .invoice-number {
            margin-top: 4px;
            text-align: right;
            font-size: 9px;
        }

        /* ==============================
           Section
        ============================== */

        .section-title {
            background: #1565C0;
            color: #fff;
            padding: 6px 9px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table {
            border: 1px solid #d9d9d9;
        }

        .table td {
            border: 1px solid #d9d9d9;
            padding: 5px 7px;
            vertical-align: middle;
        }

        .label {
            width: 23%;
            font-weight: bold;
            background: #f7f7f7;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ==============================
           Badges
        ============================== */

        .badge {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 3px;
            background: #fff3cd;
            color: #856404;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-blue {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 3px;
            background: #e3f2fd;
            color: #1565C0;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-green {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 3px;
            background: #e8f5e9;
            color: #2e7d32;
            font-size: 9px;
            font-weight: bold;
        }

        .amount-highlight {
            font-size: 13px;
            font-weight: bold;
            color: #1565C0;
        }

        .current-payment {
            font-size: 13px;
            font-weight: bold;
            color: #2e7d32;
        }

        .small {
            font-size: 8px;
            color: #666;
        }

        /* ==============================
           Invoice Summary
        ============================== */

        .summary-table th {
            border: 1px solid #d9d9d9;
            padding: 5px 6px;
            background: #f1f3f5;
            font-size: 9px;
            font-weight: bold;
        }

        .summary-table td {
            border: 1px solid #d9d9d9;
            padding: 5px 6px;
            font-size: 9px;
        }

        .summary-total {
            background: #f5f7fa;
            font-weight: bold;
        }

        .grand-total {
            background: #e3f2fd;
            font-size: 12px !important;
            font-weight: bold;
            color: #1565C0;
        }

        /* ==============================
           Notes / Terms
        ============================== */

        .note-box {
            font-size: 8.5px;
            color: #666;
            line-height: 1.5;
            padding-top: 7px;
        }

        .terms {
            font-size: 8.5px;
            line-height: 1.5;
        }

        /* ==============================
           Footer
        ============================== */

        .footer {
            font-size: 8px;
            color: #777;
        }

        .signature {
            font-size: 9px;
        }

    </style>

</head>

<body>

@php

    /*
    |--------------------------------------------------------------------------
    | Booking Variables
    |--------------------------------------------------------------------------
    */

    $booking = $payment->booking;

    $isHourly = $booking->booking_type === 'hour';

    $bookingTypeLabel = $isHourly
        ? 'Per Hour'
        : 'Per Day';

    $rateLabel = $isHourly
        ? 'Price Per Hour'
        : 'Price Per Day';

    $durationLabel = $isHourly
        ? 'Total Booking Hours'
        : 'Total Booking Days';

    $duration = $booking->booking_duration ?? 0;

    $durationUnit = $isHourly
        ? ($duration > 1 ? 'Hours' : 'Hour')
        : ($duration > 1 ? 'Days' : 'Day');

    $rate = $booking->rate ?? 0;

    /*
    |--------------------------------------------------------------------------
    | Booking Total
    |--------------------------------------------------------------------------
    */

    $totalAmount = $booking->studio_amount ?? 0;

    /*
    |--------------------------------------------------------------------------
    | Current Payment
    |--------------------------------------------------------------------------
    */

    $currentPayment = $payment->amount ?? 0;

@endphp


<div class="invoice">


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="header">

        <table>

            <tr>

                <td width="68%">

                    <div class="company-name">
                        YOUR COMPANY NAME
                    </div>

                    <div class="company-sub">
                        Ranchi, Jharkhand - India
                    </div>

                    <div class="company-sub">
                        Mobile : +91 XXXXX XXXXX
                    </div>

                    <div class="company-sub">
                        Email : info@example.com
                    </div>

                    <div class="company-sub">
                        Website : www.example.com
                    </div>

                </td>

                <td width="32%">

                    <div class="invoice-title">
                        INVOICE
                    </div>

                    <div class="invoice-number">
                        <strong>Invoice No :</strong>
                        {{ $payment->payment_id }}
                    </div>

                    <div class="invoice-number">

                        <strong>Date :</strong>

                        {{ $payment->payment_date
                            ? $payment->payment_date->format('d M Y')
                            : now()->format('d M Y')
                        }}

                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- ========================================================= --}}
    {{-- CUSTOMER INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="section-title">
        Customer Information
    </div>

    <table class="table">

        <tr>

            <td class="label">
                Customer Name
            </td>

            <td>
                {{ $booking->customer_name }}
            </td>

            <td class="label">
                Mobile Number
            </td>

            <td>
                {{ $booking->phone }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Email Address
            </td>

            <td>
                {{ $booking->email ?? 'N/A' }}
            </td>

            <td class="label">
                Booking ID
            </td>

            <td>
                {{ $booking->booking_id }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Address
            </td>

            <td colspan="3">
                {{ $booking->address ?? 'N/A' }}
            </td>

        </tr>

    </table>


    {{-- ========================================================= --}}
    {{-- STUDIO INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="section-title">
        Studio Information
    </div>

    <table class="table">

        <tr>

            <td class="label">
                Studio Category
            </td>

            <td>
                {{ $booking->studio->category->name }}
            </td>

            <td class="label">
                Booking Type
            </td>

            <td>

                <span class="badge-blue">
                    {{ $bookingTypeLabel }}
                </span>

            </td>

        </tr>

        <tr>

            <td class="label">
                {{ $rateLabel }}
            </td>

            <td>
                ₹ {{ number_format($rate, 2) }}
            </td>

            <td class="label">
                {{ $durationLabel }}
            </td>

            <td>

                {{ number_format($duration, 2) }}

                {{ $durationUnit }}

            </td>

        </tr>

        <tr>

            <td class="label">
                Booking From
            </td>

            <td>

                {{ $booking->booking_from_date
                    ? \Carbon\Carbon::parse($booking->booking_from_date)->format('d M Y')
                    : '-'
                }}

                @if($booking->booking_from_time)

                    <br>

                    <span class="small">

                        {{ \Carbon\Carbon::parse($booking->booking_from_time)->format('h:i A') }}

                    </span>

                @endif

            </td>

            <td class="label">
                Booking To
            </td>

            <td>

                @if($booking->booking_to_date)

                    {{ \Carbon\Carbon::parse($booking->booking_to_date)->format('d M Y') }}

                @else

                    Same Day

                @endif

                @if($booking->booking_to_time)

                    <br>

                    <span class="small">

                        {{ \Carbon\Carbon::parse($booking->booking_to_time)->format('h:i A') }}

                    </span>

                @endif

            </td>

        </tr>

        <tr>

            <td class="label">
                Booking Status
            </td>

            <td>

                {{ $booking->enquiry_status }}

            </td>

            <td class="label">
                Booking Duration
            </td>

            <td>

                {{ number_format($duration, 2) }}

                {{ $durationUnit }}

            </td>

        </tr>

    </table>


    {{-- ========================================================= --}}
    {{-- INVOICE SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="section-title">
        Invoice Summary
    </div>

    <table class="summary-table">

        <thead>

            <tr>

                <th width="7%" class="text-center">
                    #
                </th>

                <th width="43%">
                    Description
                </th>

                <th width="15%" class="text-center">
                    Qty
                </th>

                <th width="17%" class="text-right">
                    Rate
                </th>

                <th width="18%" class="text-right">
                    Amount
                </th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td class="text-center">
                    1
                </td>

                <td>

                    <strong>Studio Booking</strong>

                    <br>

                    <span class="small">
                        {{ $bookingTypeLabel }} Studio Rental
                    </span>

                </td>

                <td class="text-center">

                    {{ number_format($duration, 2) }}

                    <br>

                    <span class="small">
                        {{ $durationUnit }}
                    </span>

                </td>

                <td class="text-right">

                    ₹ {{ number_format($rate, 2) }}

                    <br>

                    <span class="small">
                        {{ $isHourly ? '/ Hour' : '/ Day' }}
                    </span>

                </td>

                <td class="text-right">

                    <strong>
                        ₹ {{ number_format($totalAmount, 2) }}
                    </strong>

                </td>

            </tr>


            {{-- Calculation Row --}}

            <tr class="summary-total">

                <td colspan="4" class="text-right">

                    {{ number_format($rate, 2) }}

                    ×

                    {{ number_format($duration, 2) }}

                </td>

                <td class="text-right">

                    ₹ {{ number_format($totalAmount, 2) }}

                </td>

            </tr>


            {{-- Grand Total --}}

            <tr>

                <td colspan="4" class="text-right grand-total">

                    GRAND TOTAL

                </td>

                <td class="text-right grand-total">

                    ₹ {{ number_format($totalAmount, 2) }}

                </td>

            </tr>

        </tbody>

    </table>


    {{-- ========================================================= --}}
    {{-- PAYMENT INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="section-title">
        Payment Information
    </div>

    <table class="table">

        <tr>

            <td class="label">
                Payment ID
            </td>

            <td>
                {{ $payment->payment_id }}
            </td>

            <td class="label">
                Payment Date
            </td>

            <td>

                {{ $payment->payment_date
                    ? $payment->payment_date->format('d M Y h:i A')
                    : 'N/A'
                }}

            </td>

        </tr>

        <tr>

            <td class="label">
                Payment Method
            </td>

            <td>
                {{ $payment->payment_method }}
            </td>

            <td class="label">
                Payment Type
            </td>

            <td>
                {{ $payment->payment_type }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Transaction ID
            </td>

            <td>
                {{ $payment->transaction_id ?? 'N/A' }}
            </td>

            <td class="label">
                Payment Status
            </td>

            <td>

                <span class="badge">
                    {{ strtoupper($payment->payment_status) }}
                </span>

            </td>

        </tr>

        <tr>

            <td class="label">
                Payment Proof
            </td>

            <td colspan="3">

                @if($payment->payment_proof)

                    Uploaded successfully and awaiting verification.

                @else

                    No payment proof uploaded.

                @endif

            </td>

        </tr>

    </table>


    {{-- ========================================================= --}}
    {{-- AMOUNT SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="section-title">
        Amount Summary
    </div>

    <table class="table">

        <tr>

            <td class="label">
                Booking Type
            </td>

            <td>
                {{ $bookingTypeLabel }}
            </td>

            <td class="label">
                {{ $durationLabel }}
            </td>

            <td>

                {{ number_format($duration, 2) }}

                {{ $durationUnit }}

            </td>

        </tr>

        <tr>

            <td class="label">
                {{ $rateLabel }}
            </td>

            <td>
                ₹ {{ number_format($rate, 2) }}
            </td>

            <td class="label">
                Total Booking Amount
            </td>

            <td class="amount-highlight">

                ₹ {{ number_format($totalAmount, 2) }}

            </td>

        </tr>

        <tr>

            <td class="label">
                Current Payment
            </td>

            <td class="current-payment">

                ₹ {{ number_format($currentPayment, 2) }}

            </td>

            <td class="label">
                Payment Status
            </td>

            <td>

                <span class="badge-green">

                    {{ strtoupper($payment->payment_status) }}

                </span>

            </td>

        </tr>

    </table>


    {{-- ========================================================= --}}
    {{-- ADDITIONAL INFORMATION --}}
    {{-- ========================================================= --}}

    @if($payment->remarks)

        <div class="section-title">
            Additional Information
        </div>

        <table class="table">

            <tr>

                <td class="label">
                    Customer Remarks
                </td>

                <td>
                    {{ $payment->remarks }}
                </td>

            </tr>

        </table>

    @endif


    {{-- ========================================================= --}}
    {{-- NOTE --}}
    {{-- ========================================================= --}}

    <div class="note-box">

        <strong>Note :</strong>

        This invoice is generated electronically after submission of your
        studio booking payment request. Payment verification is pending
        and the booking will be confirmed after successful verification
        by the accounts team.

    </div>


    {{-- ========================================================= --}}
    {{-- TERMS & CONDITIONS --}}
    {{-- ========================================================= --}}

    <div class="section-title">
        Terms & Conditions
    </div>

    <table class="table">

        <tr>

            <td class="terms">

                <strong>1.</strong>
                This invoice is automatically generated by the Franzy Dance Studio.

                <br>

                <strong>2.</strong>
                Submission of payment proof does not confirm your booking.
                Booking will be confirmed only after payment verification by our Accounts Team.

                <br>

                <strong>3.</strong>
                In case of any payment discrepancy, kindly contact our support team
                with your <strong>Payment ID</strong> and <strong>Booking ID</strong>.

                <br>

                <strong>4.</strong>
                Please preserve this invoice for future reference.

            </td>

        </tr>

    </table>


    {{-- ========================================================= --}}
    {{-- SIGNATURE --}}
    {{-- ========================================================= --}}

    <br>

    <table width="100%" class="signature">

        <tr>

            <td width="50%" valign="top">

                <strong>
                    Customer Signature
                </strong>

                <br><br>

                ______________________

            </td>

            <td width="50%" align="right" valign="top">

                <strong>
                    Authorized Signature
                </strong>

                <br><br>

                ______________________

            </td>

        </tr>

    </table>


    {{-- ========================================================= --}}
    {{-- THANK YOU --}}
    {{-- ========================================================= --}}

    <br>

    <table width="100%">

        <tr>

            <td align="center">

                <h2 style="color:#1565C0;font-size:15px;">

                    Thank You For Choosing Us!

                </h2>

                <p style="margin-top:4px;color:#666;font-size:9px;">

                    We appreciate your trust in our studio services.

                </p>

                <p style="margin-top:3px;color:#888;font-size:8px;">

                    For any queries regarding your booking,
                    please contact our support team.

                </p>

            </td>

        </tr>

    </table>


    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <br>

    <hr style="border:0;border-top:1px solid #ddd;">

    <table width="100%" style="margin-top:6px;">

        <tr>

            <td class="footer">

                Generated On :
                {{ now()->format('d M Y h:i A') }}

            </td>

            <td align="right" class="footer">

                Studio Booking Invoice | Page 1 of 1

            </td>

        </tr>

    </table>


</div>

</body>

</html>
