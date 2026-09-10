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
            size: A4 portrait;
            margin: 12mm 14mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8.5px;
            color: #222;
            line-height: 1.2;
            margin: 0;
            padding: 20px;
        }

        .invoice {
            width: 100%;
        }

        /* =========================
           HEADER
        ========================= */

        .header {
            width: 100%;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }

        .header table {
            width: 100%;
        }

        .header td {
            vertical-align: top;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #e91e63;
        }

        .company-tagline {
            font-size: 8px;
            color: #444;
            font-weight: bold;
            margin-top: 1px;
        }

        .company-sub {
            color: #555;
            font-size: 7.5px;
            margin-top: 1px;
        }

        .invoice-title {
            font-size: 21px;
            font-weight: bold;
            color: #1565C0;
            text-align: right;
        }

        .invoice-number {
            margin-top: 2px;
            text-align: right;
            font-size: 8px;
        }

        /* =========================
           SECTION
        ========================= */

        .section-title {
            background: #1565C0;
            color: #fff;
            padding: 4px 7px;
            font-size: 9px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table {
            border: 1px solid #d5d5d5;
        }

        .table td {
            border: 1px solid #d5d5d5;
            padding: 3px 5px;
            vertical-align: middle;
        }

        .label {
            width: 20%;
            font-weight: bold;
            background: #f7f7f7;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* =========================
           BADGES
        ========================= */

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 2px;
            background: #fff3cd;
            color: #856404;
            font-size: 7.5px;
            font-weight: bold;
        }

        .badge-blue {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 2px;
            background: #e3f2fd;
            color: #1565C0;
            font-size: 7.5px;
            font-weight: bold;
        }

        .badge-green {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 2px;
            background: #e8f5e9;
            color: #2e7d32;
            font-size: 7.5px;
            font-weight: bold;
        }

        .amount-highlight {
            font-size: 10px;
            font-weight: bold;
            color: #1565C0;
        }

        .current-payment {
            font-size: 10px;
            font-weight: bold;
            color: #2e7d32;
        }

        .small {
            font-size: 7px;
            color: #666;
        }

        /* =========================
           SUMMARY
        ========================= */

        .summary-table th {
            border: 1px solid #d5d5d5;
            padding: 3px 4px;
            background: #f1f3f5;
            font-size: 7.5px;
            font-weight: bold;
        }

        .summary-table td {
            border: 1px solid #d5d5d5;
            padding: 3px 4px;
            font-size: 7.8px;
        }

        .summary-total {
            background: #f5f7fa;
            font-weight: bold;
        }

        .grand-total {
            background: #e3f2fd;
            font-size: 10px !important;
            font-weight: bold;
            color: #1565C0;
        }

        /* =========================
           NOTE / TERMS
        ========================= */

        .note-box {
            font-size: 7px;
            color: #666;
            line-height: 1.3;
            padding-top: 4px;
        }

        .terms {
            font-size: 7px;
            line-height: 1.3;
        }

        /* =========================
           FOOTER
        ========================= */

        .footer {
            font-size: 6.5px;
            color: #777;
        }

        .signature {
            font-size: 7.5px;
        }

        .signature-line {
            margin-top: 8px;
        }

        .thank-you {
            text-align: center;
            padding-top: 4px;
        }

        .thank-you-title {
            color: #1565C0;
            font-size: 11px;
            font-weight: bold;
        }

        .thank-you-text {
            margin-top: 2px;
            color: #666;
            font-size: 7px;
        }

        .no-break {
            page-break-inside: avoid;
        }

    </style>

</head>

<body>

@php

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

    $totalAmount = $booking->studio_amount ?? 0;

    $currentPayment = $payment->amount ?? 0;

@endphp


<div class="invoice">


    <!-- ================= HEADER ================= -->

    <div class="header">

        <table>

            <tr>

                <td width="65%">

                    <div class="company-name">
                        FRENZY DANCE STUDIO
                    </div>

                    <div class="company-tagline">
                        A Complete Performing & Fine Art Center
                    </div>

                    <div class="company-sub">
                        Dance • Music • Art • Fitness
                    </div>

                    <div class="company-sub">
                        📍 Chaputoli Chowk, Argora, Ranchi – 834004
                    </div>

                    <div class="company-sub">
                        📞 +91 8294755348
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        ✉ frenzydancestudio@gmail.com
                    </div>

                </td>

                <td width="35%">

                    <div class="invoice-title">
                        INVOICE
                    </div>

                    <div class="invoice-number">
                        <strong>Invoice No:</strong>
                        {{ $payment->payment_id }}
                    </div>

                    <div class="invoice-number">
                        <strong>Date:</strong>
                        {{ $payment->payment_date
                            ? $payment->payment_date->format('d M Y')
                            : now()->format('d M Y')
                        }}
                    </div>

                </td>

            </tr>

        </table>

    </div>


    <!-- ================= CUSTOMER ================= -->

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


    <!-- ================= STUDIO ================= -->

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


    <!-- ================= INVOICE SUMMARY ================= -->

    <div class="section-title">
        Invoice Summary
    </div>

    <table class="summary-table">

        <thead>

            <tr>

                <th width="6%" class="text-center">
                    #
                </th>

                <th width="44%">
                    Description
                </th>

                <th width="14%" class="text-center">
                    Qty
                </th>

                <th width="18%" class="text-right">
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


    <!-- ================= PAYMENT ================= -->

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

    </table>


    <!-- ================= AMOUNT SUMMARY ================= -->

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


    <!-- ================= REMARKS ================= -->

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


    <!-- ================= NOTE ================= -->

    <div class="note-box">

        <strong>Note:</strong>

        This invoice is generated electronically after submission of your
        studio booking payment request. Payment verification is pending and
        the booking will be confirmed after successful verification by the
        accounts team.

    </div>


    <!-- ================= TERMS ================= -->

    <div class="section-title">
        Terms & Conditions
    </div>

    <table class="table">

        <tr>

            <td class="terms">

                <strong>1.</strong>
                This invoice is automatically generated by FRENZY DANCE STUDIO.

                &nbsp;&nbsp;

                <strong>2.</strong>
                Submission of payment proof does not confirm your booking.
                Booking will be confirmed only after payment verification.

                &nbsp;&nbsp;

                <strong>3.</strong>
                For payment discrepancies, contact our support team with your
                Payment ID and Booking ID.

                &nbsp;&nbsp;

                <strong>4.</strong>
                Please preserve this invoice for future reference.

            </td>

        </tr>

    </table>


    <!-- ================= SIGNATURE ================= -->

    <table width="100%" class="signature" style="margin-top:7px;">

        <tr>

            <td width="50%" valign="top">

                <strong>
                    Customer Signature
                </strong>

                <div class="signature-line">
                    __________________________
                </div>

            </td>

            <td width="50%" align="right" valign="top">

                <strong>
                    Authorized Signature
                </strong>

                <div class="signature-line">
                    __________________________
                </div>

            </td>

        </tr>

    </table>


    <!-- ================= THANK YOU ================= -->

    <div class="thank-you">

        <div class="thank-you-title">
            Thank You For Choosing FRENZY DANCE STUDIO!
        </div>

        <div class="thank-you-text">
            We appreciate your trust in our studio services.
        </div>

    </div>


    <!-- ================= FOOTER ================= -->

    <hr style="border:0;border-top:1px solid #ddd;margin-top:5px;">

    <table width="100%" style="margin-top:3px;">

        <tr>

            <td class="footer">

                Generated On:
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
