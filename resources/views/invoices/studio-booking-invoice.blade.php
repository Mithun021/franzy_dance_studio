<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>

        Studio Booking Invoice

    </title>

    <style>

        *{

            margin:0;

            padding:0;

            box-sizing:border-box;

        }

        @page{
            margin: 25px 30px;
        }

        body{

            font-family: DejaVu Sans, sans-serif;

            font-size:12px;

            color:#222;

            line-height:1.5;
            margin:0;
            padding:10px;

        }

        .invoice{

            width:100%;

        }

        .header{

            width:100%;

            border-bottom:2px solid #e91e63;

            padding-bottom:15px;

            margin-bottom:20px;

        }

        .header table{

            width:100%;

        }

        .header td{

            vertical-align:top;

        }

        .logo{

            width:80px;

        }

        .company-name{

            font-size:24px;

            font-weight:bold;

            color:#e91e63;

        }

        .company-sub{

            color:#555;

            font-size:11px;

            margin-top:3px;

        }

        .invoice-title{

            font-size:28px;

            font-weight:bold;

            color:#1565C0;

            text-align:right;

        }

        .invoice-number{

            margin-top:8px;

            text-align:right;

            font-size:12px;

        }

        .section-title{

            background:#1565C0;

            color:#fff;

            padding:8px 12px;

            font-size:14px;

            font-weight:bold;

            margin-top:18px;

            margin-bottom:0;

        }

        table{

            width:100%;

            border-collapse:collapse;

        }

        .table{

            border:1px solid #ddd;

        }

        .table td{

            border:1px solid #ddd;

            padding:8px 10px;

        }

        .label{

            width:32%;

            font-weight:bold;

            background:#f8f8f8;

        }

        .text-right{

            text-align:right;

        }

        .text-center{

            text-align:center;

        }

        .mt15{

            margin-top:15px;

        }

        .mt20{

            margin-top:20px;

        }

        .badge{

            display:inline-block;

            padding:4px 10px;

            border-radius:3px;

            background:#fff3cd;

            color:#856404;

            font-size:11px;

        }

    </style>

</head>

<body>

<div class="invoice">

    {{-- ===================================================== --}}
    {{-- Header --}}
    {{-- ===================================================== --}}

    <div class="header">

        <table>

            <tr>

                <td width="70%">

                    {{-- Company Logo --}}
                    {{-- Replace with your logo path --}}

                    {{-- <img src="{{ public_path('images/logo.png') }}" class="logo"> --}}

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

                <td width="30%">

                    <div class="invoice-title">

                        INVOICE

                    </div>

                    <div class="invoice-number">

                        <strong>Invoice No :</strong>

                        {{ $payment->payment_id }}

                    </div>

                    <div class="invoice-number">

                        <strong>Date :</strong>

                        {{ $payment->payment_date->format('d M Y') }}

                    </div>

                </td>

            </tr>

        </table>

    </div>

    {{-- ===================================================== --}}
    {{-- Customer Information --}}
    {{-- ===================================================== --}}

    <div class="section-title">

        Customer Information

    </div>

    <table class="table">

        <tr>

            <td class="label">

                Customer Name

            </td>

            <td>

                {{ $payment->booking->customer_name }}

            </td>

            <td class="label">

                Mobile Number

            </td>

            <td>

                {{ $payment->booking->phone }}

            </td>

        </tr>

        <tr>

            <td class="label">

                Email Address

            </td>

            <td>

                {{ $payment->booking->email }}

            </td>

            <td class="label">

                Booking ID

            </td>

            <td>

                {{ $payment->booking->booking_id }}

            </td>

        </tr>

        <tr>

            <td class="label">

                Address

            </td>

            <td colspan="3">

                {{ $payment->booking->address }}

            </td>

        </tr>

    </table>
        {{-- ===================================================== --}}
    {{-- Studio Information --}}
    {{-- ===================================================== --}}

    <div class="section-title">

        Studio Information

    </div>

    <table class="table">

        <tr>

            <td class="label">

                Studio Category

            </td>

            <td>

                {{ $payment->booking->studio->category->name }}

            </td>

            <td class="label">

                Price Per Day

            </td>

            <td>

                ₹ {{ number_format($payment->booking->studio_amount,2) }}

            </td>

        </tr>

        <tr>

            <td class="label">

                Booking From

            </td>

            <td>

                {{ \Carbon\Carbon::parse($payment->booking->booking_from_date)->format('d M Y') }}

            </td>

            <td class="label">

                Booking To

            </td>

            <td>

                @if($payment->booking->booking_to_date)

                    {{ \Carbon\Carbon::parse($payment->booking->booking_to_date)->format('d M Y') }}

                @else

                    Same Day Booking

                @endif

            </td>

        </tr>

        <tr>

            <td class="label">

                Total Booking Days

            </td>

            <td>

                {{ $days }} Day{{ $days > 1 ? 's' : '' }}

            </td>

            <td class="label">

                Booking Status

            </td>

            <td>

                {{ $payment->booking->enquiry_status }}

            </td>

        </tr>

    </table>

    {{-- ===================================================== --}}
    {{-- Invoice Summary --}}
    {{-- ===================================================== --}}

    <div class="section-title">

        Invoice Summary

    </div>

    <table class="table">

        <thead>

            <tr style="background:#f5f5f5;font-weight:bold;">

                <td class="text-center">

                    #

                </td>

                <td>

                    Description

                </td>

                <td class="text-center">

                    Qty

                </td>

                <td class="text-right">

                    Rate

                </td>

                <td class="text-right">

                    Total

                </td>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td class="text-center">

                    1

                </td>

                <td>

                    Studio Booking Charge

                </td>

                <td class="text-center">

                    {{ $days }}

                </td>

                <td class="text-right">

                    ₹ {{ number_format($payment->booking->studio_amount,2) }}

                </td>

                <td class="text-right">

                    ₹ {{ number_format($payment->amount,2) }}

                </td>

            </tr>

        </tbody>

    </table>

    <br>

    <table width="100%">

        <tr>

            <td width="55%">

            </td>

            <td width="45%">

                <table class="table">

                    <tr>

                        <td class="label">

                            Booking Days

                        </td>

                        <td class="text-right">

                            {{ $days }}

                        </td>

                    </tr>

                    <tr>

                        <td class="label">

                            Price / Day

                        </td>

                        <td class="text-right">

                            ₹ {{ number_format($payment->booking->studio_amount,2) }}

                        </td>

                    </tr>

                    <tr style="font-size:14px;font-weight:bold;background:#f8f8f8;">

                        <td>

                            Grand Total

                        </td>

                        <td class="text-right">

                            ₹ {{ number_format($payment->amount,2) }}

                        </td>

                    </tr>

                </table>

            </td>

        </tr>

    </table>
        {{-- ===================================================== --}}
    {{-- Payment Information --}}
    {{-- ===================================================== --}}

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

                {{ $payment->payment_date->format('d M Y h:i A') }}

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

                Uploaded Proof

            </td>

            <td colspan="3">

                @if($payment->payment_proof)

                    Payment proof uploaded successfully and is awaiting verification.

                @else

                    No payment proof uploaded.

                @endif

            </td>

        </tr>

    </table>

    {{-- ===================================================== --}}
    {{-- Additional Information --}}
    {{-- ===================================================== --}}

    <div class="section-title">

        Additional Information

    </div>

    <table class="table">

        <tr>

            <td class="label">

                Customer Remarks

            </td>

            <td>

                {{ $payment->remarks ?? 'N/A' }}

            </td>

        </tr>

    </table>

    {{-- ===================================================== --}}
    {{-- Amount Summary --}}
    {{-- ===================================================== --}}

    <div class="section-title">

        Amount Summary

    </div>

    <table class="table">

        <tr>

            <td class="label">

                Total Booking Days

            </td>

            <td>

                {{ $days }} Day{{ $days > 1 ? 's' : '' }}

            </td>

        </tr>

        <tr>

            <td class="label">

                Studio Charge Per Day

            </td>

            <td>

                ₹ {{ number_format($payment->booking->studio_amount,2) }}

            </td>

        </tr>

        <tr>

            <td class="label">

                Total Amount Paid

            </td>

            <td style="font-size:16px;font-weight:bold;color:#1565C0;">

                ₹ {{ number_format($payment->amount,2) }}

            </td>

        </tr>

    </table>

    <br>

    <table width="100%">

        <tr>

            <td style="font-size:11px;color:#666;line-height:20px;">

                <strong>Note :</strong><br>

                This invoice is generated electronically after submission of your studio booking payment request.
                Payment verification is pending and your booking will be confirmed after successful verification by the accounts team.
                Please keep this invoice safely for future reference.

            </td>

        </tr>

    </table>
        {{-- ===================================================== --}}
    {{-- Terms & Conditions --}}
    {{-- ===================================================== --}}

    <div class="section-title">

        Terms & Conditions

    </div>

    <table class="table">

        <tr>

            <td style="line-height:22px;">

                <strong>1.</strong> This invoice is automatically generated by the Franzy Dance Studio.<br><br>

                <strong>2.</strong> Submission of payment proof does not confirm your booking. Booking will be confirmed only after payment verification by our Accounts Team.<br><br>

                <strong>3.</strong> In case of any payment discrepancy, kindly contact our support team with your <strong>Payment ID</strong> and <strong>Booking ID</strong>.<br><br>

                <strong>4.</strong> Please preserve this invoice for future reference. It may be required during booking verification or check-in.<br><br>

                {{-- <strong>5.</strong> This is a computer-generated invoice and does not require a physical signature. --}}

            </td>

        </tr>

    </table>

    <br><br>

    {{-- ===================================================== --}}
    {{-- Signature Section --}}
    {{-- ===================================================== --}}

    <table width="100%">

        <tr>

            <td width="50%" valign="top">

                <strong>

                    Customer Signature

                </strong>

                <br><br>

                ___________________________

            </td>

            <td width="50%" align="right" valign="top">

                <strong>

                    Authorized Signature

                </strong>

                <br><br>

                ___________________________

            </td>

        </tr>

    </table>

    <br><br>

    {{-- ===================================================== --}}
    {{-- Thank You --}}
    {{-- ===================================================== --}}

    <table width="100%">

        <tr>

            <td align="center">

                <h2 style="color:#1565C0;">

                    Thank You For Choosing Us!

                </h2>

                <p style="margin-top:10px;color:#666;">

                    We appreciate your trust in our studio services.

                </p>

                <p style="margin-top:6px;color:#888;font-size:11px;">

                    For any queries regarding your booking, please contact our support team.

                </p>

            </td>

        </tr>

    </table>

    <br>

    {{-- ===================================================== --}}
    {{-- Footer --}}
    {{-- ===================================================== --}}

    <hr>

    <table width="100%" style="margin-top:10px;">

        <tr>

            <td style="font-size:10px;color:#777;">

                Generated On :

                {{ now()->format('d M Y h:i A') }}

            </td>

            <td align="right" style="font-size:10px;color:#777;">

                Studio Booking Invoice |
                Page 1 of 1

            </td>

        </tr>

    </table>

</div>

</body>

</html>
