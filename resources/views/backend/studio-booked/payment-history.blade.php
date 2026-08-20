@extends('backend.partial.master')

@section('title', 'Booking Payment History')

@section('backend-content')

<style>

    /* =========================================================
       SCREEN BASE
    ========================================================== */

    .booking-page {
        padding-bottom: 30px;
    }

    .invoice-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .invoice-paper {
        width: 100%;
        max-width: 1180px;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 8px 35px rgba(15, 23, 42, .10);
        overflow: hidden;
        color: #172033;
        font-family: "Segoe UI", Arial, Helvetica, sans-serif;
    }


    /* =========================================================
       HEADER
    ========================================================== */

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 28px 34px;
        background: linear-gradient(
            135deg,
            #0f172a 0%,
            #1e293b 100%
        );
        color: #ffffff;
    }

    .invoice-brand {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .brand-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.18);
        font-size: 22px;
        overflow: hidden;
    }

    .brand-icon img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .brand-title {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: .7px;
    }

    .brand-subtitle {
        margin-top: 3px;
        font-size: 12px;
        color: rgba(255,255,255,.70);
        letter-spacing: .4px;
    }

    .invoice-meta {
        text-align: right;
    }

    .invoice-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        color: rgba(255,255,255,.60);
    }

    .invoice-number {
        font-size: 22px;
        font-weight: 800;
        margin-top: 2px;
    }

    .invoice-date {
        margin-top: 7px;
        font-size: 11px;
        color: rgba(255,255,255,.72);
    }


    /* =========================================================
       STATUS
    ========================================================== */

    .status-strip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 34px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .status-label {
        font-size: 10px;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 1px;
        margin-right: 8px;
    }

    .status-value {
        font-size: 11px;
        font-weight: 800;
        color: #334155;
    }

    .invoice-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .invoice-status.paid {
        color: #166534;
        background: #dcfce7;
    }

    .invoice-status.partial {
        color: #92400e;
        background: #fef3c7;
    }

    .invoice-status.failed {
        color: #991b1b;
        background: #fee2e2;
    }

    .invoice-status.cancelled {
        color: #475569;
        background: #e2e8f0;
    }

    .invoice-status.pending {
        color: #155e75;
        background: #cffafe;
    }

    .invoice-status.unpaid {
        color: #334155;
        background: #e2e8f0;
    }


    /* =========================================================
       INFORMATION CARDS
    ========================================================== */

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        padding: 22px 34px 0;
    }

    .info-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        background: #ffffff;
    }

    .info-card-header {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .customer-icon {
        background: #eff6ff;
        color: #2563eb;
    }

    .booking-icon {
        background: #ecfdf5;
        color: #059669;
    }

    .info-card-title {
        font-size: 13px;
        font-weight: 800;
        color: #1e293b;
    }

    .info-card-subtitle {
        font-size: 9px;
        color: #94a3b8;
        margin-top: 2px;
    }

    .info-card-body {
        padding: 7px 14px 9px;
    }

    .info-row {
        display: grid;
        grid-template-columns: 115px 1fr;
        gap: 10px;
        padding: 6px 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 11px;
        line-height: 1.4;
    }

    .info-row:last-child {
        border-bottom: 0;
    }

    .info-label {
        color: #64748b;
        font-weight: 600;
    }

    .info-value {
        color: #1e293b;
        font-weight: 600;
        word-break: break-word;
    }

    .email-value {
        word-break: break-all;
    }

    .booking-total-row {
        margin-top: 2px;
        padding-top: 8px;
        border-top: 1px solid #cbd5e1;
        border-bottom: 0;
    }

    .amount-highlight {
        color: #2563eb;
        font-size: 12px;
        font-weight: 800;
    }


    /* =========================================================
       SECTION
    ========================================================== */

    .section-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 34px 10px;
    }

    .section-title {
        font-size: 14px;
        font-weight: 800;
        color: #172033;
    }

    .section-subtitle {
        margin-top: 2px;
        font-size: 9px;
        color: #94a3b8;
    }


    /* =========================================================
       SUMMARY
    ========================================================== */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        padding: 0 34px;
    }

    .summary-card {
        min-height: 74px;
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px;
        border-radius: 9px;
        border: 1px solid;
    }

    .summary-icon {
        width: 36px;
        height: 36px;
        flex: 0 0 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .summary-label {
        font-size: 9px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .summary-value {
        font-size: 15px;
        font-weight: 800;
        line-height: 1.2;
    }

    .booking-summary {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .booking-summary .summary-icon {
        background: #dbeafe;
    }

    .paid-summary {
        background: #ecfdf5;
        border-color: #bbf7d0;
        color: #047857;
    }

    .paid-summary .summary-icon {
        background: #d1fae5;
    }

    .due-summary {
        background: #fff1f2;
        border-color: #fecdd3;
        color: #be123c;
    }

    .due-summary .summary-icon {
        background: #ffe4e6;
    }

    .attempt-summary {
        background: #ecfeff;
        border-color: #a5f3fc;
        color: #0e7490;
    }

    .attempt-summary .summary-icon {
        background: #cffafe;
    }


    /* =========================================================
       PAYMENT TABLE
    ========================================================== */

    .payment-section {
        margin: 4px 34px 0;
    }

    .payment-heading {
        padding-left: 0;
        padding-right: 0;
    }

    .payment-count {
        font-size: 9px;
        font-weight: 700;
        color: #64748b;
        background: #f1f5f9;
        padding: 5px 9px;
        border-radius: 20px;
    }

    .payment-table-wrapper {
        width: 100%;
        overflow: hidden;
        border: 1px solid #dbe3ed;
        border-radius: 8px;
    }

    .payment-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 9px;
    }

    .payment-table th {
        padding: 8px 5px;
        background: #172033;
        color: #ffffff;
        font-size: 8px;
        font-weight: 700;
        text-align: left;
        letter-spacing: .2px;
        border-right: 1px solid rgba(255,255,255,.12);
        white-space: nowrap;
    }

    .payment-table td {
        padding: 7px 5px;
        border-top: 1px solid #e2e8f0;
        color: #334155;
        vertical-align: middle;
        word-break: break-word;
        line-height: 1.3;
    }

    .payment-table tbody tr:nth-child(even) {
        background: #f8fafc;
    }

    .payment-table tbody tr {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .col-no { width: 4%; }
    .col-payment { width: 11%; }
    .col-date { width: 11%; }
    .col-amount { width: 10%; }
    .col-method { width: 10%; }
    .col-transaction { width: 15%; }
    .col-status { width: 10%; }
    .col-received { width: 11%; }
    .col-remarks { width: 18%; }

    .payment-id {
        color: #1d4ed8;
        font-weight: 800;
    }

    .date-main {
        font-weight: 700;
        color: #334155;
    }

    .date-time {
        margin-top: 1px;
        font-size: 8px;
        color: #94a3b8;
    }

    .amount-cell {
        text-align: right;
        font-weight: 800;
        color: #0f172a !important;
        white-space: nowrap;
    }

    .transaction-text {
        font-size: 8px;
        word-break: break-all;
    }

    .table-status {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 3px 5px;
        border-radius: 20px;
        font-size: 7px;
        font-weight: 800;
        white-space: nowrap;
    }

    .table-status.success {
        color: #166534;
        background: #dcfce7;
    }

    .table-status.pending {
        color: #155e75;
        background: #cffafe;
    }

    .table-status.failed {
        color: #991b1b;
        background: #fee2e2;
    }

    .table-status.cancelled {
        color: #475569;
        background: #e2e8f0;
    }

    .table-status.other {
        color: #334155;
        background: #f1f5f9;
    }

    .payment-table tfoot td,
    .payment-table tfoot th {
        padding: 7px 5px;
        border-top: 1px solid #cbd5e1;
        background: #ffffff;
    }

    .total-label {
        text-align: right;
        font-weight: 700;
        color: #64748b;
    }

    .total-value {
        text-align: right;
        font-weight: 800;
        white-space: nowrap;
    }

    .total-value.primary {
        color: #2563eb;
    }

    .total-value.success {
        color: #059669;
    }

    .total-value.danger {
        color: #dc2626;
    }

    .due-total-row td {
        background: #fff7f7 !important;
    }

    .empty-payment {
        padding: 25px !important;
        text-align: center;
        color: #64748b !important;
    }

    .empty-icon {
        font-size: 24px;
        color: #cbd5e1;
        margin-bottom: 7px;
    }


    /* =========================================================
       BOTTOM
    ========================================================== */

    .invoice-bottom {
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 40px;
        margin: 22px 34px 0;
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
    }

    .bottom-title {
        font-size: 10px;
        font-weight: 800;
        color: #334155;
        margin-bottom: 5px;
    }

    .bottom-title i {
        color: #2563eb;
        margin-right: 4px;
    }

    .notes-text {
        max-width: 620px;
        font-size: 9px;
        line-height: 1.5;
        color: #64748b;
    }

    .signature-box {
        text-align: center;
        align-self: end;
        padding-top: 20px;
    }

    .signature-line {
        width: 180px;
        margin: 0 auto 6px;
        border-bottom: 1px solid #334155;
    }

    .signature-title {
        font-size: 9px;
        font-weight: 700;
        color: #334155;
    }


    /* =========================================================
       FOOTER
    ========================================================== */

    .invoice-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 11px 34px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        font-size: 8px;
        color: #94a3b8;
    }

    .invoice-footer strong {
        color: #475569;
    }


    /* =========================================================
       PRINT
    ========================================================== */

    @media print {

        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        html,
        body {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }

        body {
            font-family: "Segoe UI", Arial, Helvetica, sans-serif !important;
            color: #172033 !important;
            overflow: visible !important;
        }

        body > * {
            visibility: hidden;
        }

        #invoiceArea,
        #invoiceArea * {
            visibility: visible !important;
        }

        #invoiceArea {
            position: relative !important;
            display: block !important;
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .invoice-wrapper {
            display: block !important;
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .invoice-paper {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            border: 1px solid #dbe3ed !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
            background: #ffffff !important;
        }

        *,
        *::before,
        *::after {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .info-card,
        .summary-card,
        .payment-section,
        .invoice-bottom {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        .payment-table tr {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        .payment-table thead {
            display: table-header-group !important;
        }

        .payment-table tfoot {
            display: table-footer-group !important;
        }

        .invoice-header,
        .status-strip {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        .invoice-header {
            padding: 18px 20px !important;
        }

        .brand-icon {
            width: 42px !important;
            height: 42px !important;
            font-size: 17px !important;
        }

        .brand-title {
            font-size: 19px !important;
        }

        .brand-subtitle {
            font-size: 9px !important;
        }

        .invoice-label {
            font-size: 8px !important;
        }

        .invoice-number {
            font-size: 17px !important;
        }

        .invoice-date {
            font-size: 8px !important;
        }

        .status-strip {
            padding: 7px 20px !important;
        }

        .status-label {
            font-size: 7px !important;
        }

        .status-value {
            font-size: 8px !important;
        }

        .invoice-status {
            padding: 4px 8px !important;
            font-size: 7px !important;
        }

        .info-grid {
            gap: 10px !important;
            padding: 12px 20px 0 !important;
        }

        .info-card {
            border-radius: 6px !important;
        }

        .info-card-header {
            padding: 7px 9px !important;
        }

        .info-icon {
            width: 26px !important;
            height: 26px !important;
            font-size: 10px !important;
        }

        .info-card-title {
            font-size: 9px !important;
        }

        .info-card-subtitle {
            font-size: 7px !important;
        }

        .info-card-body {
            padding: 3px 9px 5px !important;
        }

        .info-row {
            grid-template-columns: 85px 1fr !important;
            padding: 3px 0 !important;
            gap: 7px !important;
            font-size: 8px !important;
        }

        .amount-highlight {
            font-size: 9px !important;
        }

        .section-title-row {
            padding: 10px 20px 5px !important;
        }

        .section-title {
            font-size: 10px !important;
        }

        .section-subtitle {
            font-size: 7px !important;
        }

        .summary-grid {
            gap: 7px !important;
            padding: 0 20px !important;
        }

        .summary-card {
            min-height: 47px !important;
            padding: 7px !important;
            gap: 7px !important;
            border-radius: 6px !important;
        }

        .summary-icon {
            width: 26px !important;
            height: 26px !important;
            flex-basis: 26px !important;
            border-radius: 6px !important;
            font-size: 9px !important;
        }

        .summary-label {
            font-size: 6.5px !important;
        }

        .summary-value {
            font-size: 9px !important;
        }

        .payment-section {
            margin: 0 20px !important;
        }

        .payment-heading {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .payment-count {
            font-size: 6.5px !important;
            padding: 3px 6px !important;
        }

        .payment-table-wrapper {
            border-radius: 5px !important;
            overflow: visible !important;
        }

        .payment-table {
            width: 100% !important;
            font-size: 6.8px !important;
            table-layout: fixed !important;
        }

        .payment-table th {
            padding: 5px 3px !important;
            font-size: 6.2px !important;
        }

        .payment-table td {
            padding: 4px 3px !important;
            font-size: 6.8px !important;
        }

        .date-time {
            font-size: 5.8px !important;
        }

        .transaction-text {
            font-size: 6px !important;
        }

        .table-status {
            padding: 2px 3px !important;
            font-size: 5.5px !important;
        }

        .payment-table tfoot td,
        .payment-table tfoot th {
            padding: 4px 3px !important;
            font-size: 6.8px !important;
        }

        .invoice-bottom {
            grid-template-columns: 1fr 190px !important;
            gap: 20px !important;
            margin: 12px 20px 0 !important;
            padding-top: 9px !important;
        }

        .bottom-title {
            font-size: 7px !important;
        }

        .notes-text {
            font-size: 6.5px !important;
        }

        .signature-box {
            padding-top: 10px !important;
        }

        .signature-line {
            width: 130px !important;
            margin-bottom: 4px !important;
        }

        .signature-title {
            font-size: 6.5px !important;
        }

        .invoice-footer {
            margin-top: 10px !important;
            padding: 7px 20px !important;
            font-size: 6px !important;
        }

        .no-print {
            display: none !important;
        }

        .container-fluid {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .invoice-paper,
        .payment-table-wrapper,
        .payment-table {
            max-width: 100% !important;
        }
    }


    /* =========================================================
       MOBILE
    ========================================================== */

    @media screen and (max-width: 768px) {

        .invoice-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 18px;
        }

        .invoice-meta {
            text-align: left;
        }

        .status-strip {
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .summary-grid {
            grid-template-columns: 1fr 1fr;
        }

        .invoice-bottom {
            grid-template-columns: 1fr;
        }

        .signature-box {
            text-align: left;
        }

        .signature-line {
            margin-left: 0;
        }

        .payment-table-wrapper {
            overflow-x: auto;
        }

        .payment-table {
            min-width: 900px;
        }
    }


    @media screen and (max-width: 480px) {

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .invoice-header,
        .status-strip {
            padding-left: 18px;
            padding-right: 18px;
        }

        .info-grid {
            padding-left: 18px;
            padding-right: 18px;
        }

        .section-title-row {
            padding-left: 18px;
            padding-right: 18px;
        }

        .summary-grid {
            padding-left: 18px;
            padding-right: 18px;
        }

        .payment-section {
            margin-left: 18px;
            margin-right: 18px;
        }

        .invoice-bottom {
            margin-left: 18px;
            margin-right: 18px;
        }

        .invoice-footer {
            padding-left: 18px;
            padding-right: 18px;
        }
    }

</style>


<div class="container-fluid booking-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="row mb-3 no-print">

        <div class="col-md-6">

            <h4 class="mb-0">

                <i class="fas fa-file-invoice-dollar text-primary"></i>

                Booking Invoice

            </h4>

            <small class="text-muted">

                Booking Payment History

            </small>

        </div>


        <div class="col-md-6 text-end">

            <a href="{{ route('studio-booked.index') }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>

                Back

            </a>


            <button type="button"
                    class="btn btn-primary"
                    onclick="printInvoice()">

                <i class="fas fa-print"></i>

                Print Invoice

            </button>

        </div>

    </div>


    {{-- =========================================================
        INVOICE AREA
    ========================================================== --}}

    <div id="invoiceArea" class="invoice-wrapper">

        <div class="invoice-paper">


            {{-- =================================================
                HEADER
            ================================================== --}}

            <div class="invoice-header">

                <div class="invoice-brand">

                    <div class="brand-icon">

                        <img src="{{ asset('images/logo.png') }}"
                             alt="Logo">

                    </div>


                    <div>

                        <div class="brand-title">

                            FRANZY DANCE STUDIO

                        </div>

                        <div class="brand-subtitle">

                            Booking Payment Invoice

                        </div>

                    </div>

                </div>


                <div class="invoice-meta">

                    <div class="invoice-label">

                        INVOICE

                    </div>


                    <div class="invoice-number">

                        {{ $booking->booking_id }}

                    </div>


                    <div class="invoice-date">

                        <strong>Booking Date:</strong>

                        {{ optional($booking->created_at)->format('d M Y') }}

                    </div>

                </div>

            </div>


            {{-- =================================================
                STATUS
            ================================================== --}}

            <div class="status-strip">

                <div>

                    <span class="status-label">

                        BOOKING STATUS

                    </span>


                    <span class="status-value">

                        {{ strtoupper($booking->booking_status ?? 'Unpaid') }}

                    </span>

                </div>


                <div>

                    @switch(strtolower($booking->booking_status ?? ''))

                        @case('paid')

                            <span class="invoice-status paid">

                                <i class="fas fa-check-circle"></i>

                                Fully Paid

                            </span>

                        @break


                        @case('partial')

                            <span class="invoice-status partial">

                                <i class="fas fa-clock"></i>

                                Partial Paid

                            </span>

                        @break


                        @case('failed')

                            <span class="invoice-status failed">

                                <i class="fas fa-times-circle"></i>

                                Failed

                            </span>

                        @break


                        @case('cancelled')

                            <span class="invoice-status cancelled">

                                <i class="fas fa-ban"></i>

                                Cancelled

                            </span>

                        @break


                        @case('pending')

                            <span class="invoice-status pending">

                                <i class="fas fa-hourglass-half"></i>

                                Pending

                            </span>

                        @break


                        @default

                            <span class="invoice-status unpaid">

                                <i class="fas fa-circle"></i>

                                Unpaid

                            </span>

                    @endswitch

                </div>

            </div>


            {{-- =================================================
                CUSTOMER + BOOKING INFORMATION
            ================================================== --}}

            <div class="info-grid">


                {{-- CUSTOMER INFORMATION --}}

                <div class="info-card">

                    <div class="info-card-header">

                        <div class="info-icon customer-icon">

                            <i class="mdi mdi-account"></i>

                        </div>


                        <div>

                            <div class="info-card-title">

                                Customer Information

                            </div>

                            <div class="info-card-subtitle">

                                Customer contact details

                            </div>

                        </div>

                    </div>


                    <div class="info-card-body">

                        <div class="info-row">

                            <div class="info-label">
                                Name
                            </div>

                            <div class="info-value">
                                {{ $booking->customer_name ?: '-' }}
                            </div>

                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                Phone
                            </div>

                            <div class="info-value">
                                {{ $booking->phone ?: '-' }}
                            </div>

                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                Email
                            </div>

                            <div class="info-value email-value">
                                {{ $booking->email ?: '-' }}
                            </div>

                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                City
                            </div>

                            <div class="info-value">
                                {{ $booking->city ?: '-' }}
                            </div>

                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                State
                            </div>

                            <div class="info-value">
                                {{ $booking->state ?: '-' }}
                            </div>

                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                Address
                            </div>

                            <div class="info-value">
                                {{ $booking->address ?: '-' }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- BOOKING INFORMATION --}}

                <div class="info-card">

                    <div class="info-card-header">

                        <div class="info-icon booking-icon">

                            <i class="mdi mdi-calendar-check"></i>

                        </div>


                        <div>

                            <div class="info-card-title">

                                Booking Information

                            </div>

                            <div class="info-card-subtitle">

                                Studio reservation details

                            </div>

                        </div>

                    </div>


                    <div class="info-card-body">


                        <div class="info-row">

                            <div class="info-label">
                                Booking ID
                            </div>

                            <div class="info-value">
                                {{ $booking->booking_id }}
                            </div>

                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                Studio
                            </div>

                            <div class="info-value">

                                {{ data_get($booking, 'studio.category.name') ?: '-' }}

                            </div>

                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                From
                            </div>

                            <div class="info-value">

                                @if($booking->booking_from_date)

                                    {{ \Carbon\Carbon::parse($booking->booking_from_date)->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                To
                            </div>

                            <div class="info-value">

                                @if($booking->booking_to_date)

                                    {{ \Carbon\Carbon::parse($booking->booking_to_date)->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                Total Days
                            </div>

                            <div class="info-value">

                                {{ $booking->total_days ?? 0 }}

                                {{ ($booking->total_days ?? 0) == 1 ? 'Day' : 'Days' }}

                            </div>

                        </div>


                        <div class="info-row">

                            <div class="info-label">
                                Per Day Rent
                            </div>

                            <div class="info-value">

                                ₹ {{ number_format(
                                    $booking->studio_amount
                                    ?? data_get($booking, 'studio.price_per_day', 0),
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="info-row booking-total-row">

                            <div class="info-label">
                                Booking Amount
                            </div>

                            <div class="info-value amount-highlight">

                                ₹ {{ number_format($bookingAmount, 2) }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                FINANCIAL SUMMARY
            ================================================== --}}

            <div class="section-title-row">

                <div>

                    <div class="section-title">

                        Payment Summary

                    </div>

                    <div class="section-subtitle">

                        Current booking financial overview

                    </div>

                </div>

            </div>


            <div class="summary-grid">


                {{-- BOOKING AMOUNT --}}

                <div class="summary-card booking-summary">

                    <div class="summary-icon">

                        <i class="mdi mdi-file-document-outline"></i>

                    </div>

                    <div>

                        <div class="summary-label">

                            Booking Amount

                        </div>

                        <div class="summary-value">

                            ₹ {{ number_format($bookingAmount, 2) }}

                        </div>

                    </div>

                </div>


                {{-- TOTAL PAID --}}

                <div class="summary-card paid-summary">

                    <div class="summary-icon">

                        <i class="mdi mdi-check-all"></i>

                    </div>

                    <div>

                        <div class="summary-label">

                            Total Paid

                        </div>

                        <div class="summary-value">

                            ₹ {{ number_format($totalPayment, 2) }}

                        </div>

                    </div>

                </div>


                {{-- DUE --}}

                <div class="summary-card due-summary">

                    <div class="summary-icon">

                        <i class="mdi mdi-wallet"></i>

                    </div>

                    <div>

                        <div class="summary-label">

                            Remaining Due

                        </div>

                        <div class="summary-value">

                            ₹ {{ number_format($dueAmount, 2) }}

                        </div>

                    </div>

                </div>


                {{-- PAYMENT ATTEMPTS --}}

                <div class="summary-card attempt-summary">

                    <div class="summary-icon">

                        <i class="mdi mdi-credit-card-outline"></i>

                    </div>

                    <div>

                        <div class="summary-label">

                            Payment Attempts

                        </div>

                        <div class="summary-value">

                            {{ $paymentAttempts }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                PAYMENT HISTORY
            ================================================== --}}

            <div class="payment-section">


                <div class="section-title-row payment-heading">

                    <div>

                        <div class="section-title">

                            Payment History

                        </div>

                        <div class="section-subtitle">

                            Complete payment transaction record

                        </div>

                    </div>


                    <div class="payment-count">

                        {{ $paymentAttempts }}

                        {{ $paymentAttempts == 1 ? 'Transaction' : 'Transactions' }}

                    </div>

                </div>


                <div class="payment-table-wrapper">

                    <table class="payment-table">

                        <thead>

                            <tr>

                                <th class="col-no">#</th>

                                <th class="col-payment">Payment ID</th>

                                <th class="col-date">Date</th>

                                <th class="col-amount">Amount</th>

                                <th class="col-method">Method</th>

                                <th class="col-transaction">Transaction ID</th>

                                <th class="col-status">Status</th>

                                <th class="col-received">Received By</th>

                                <th class="col-remarks">Remarks</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($booking->payments as $payment)

                                @php

                                    $status = strtolower(
                                        trim($payment->payment_status ?? '')
                                    );

                                @endphp


                                <tr>

                                    <td class="text-center">

                                        {{ $loop->iteration }}

                                    </td>


                                    <td>

                                        <strong class="payment-id">

                                            {{ $payment->payment_id ?: '-' }}

                                        </strong>

                                    </td>


                                    <td>

                                        @if($payment->payment_date)

                                            <div class="date-main">

                                                {{ \Carbon\Carbon::parse(
                                                    $payment->payment_date
                                                )->format('d M Y') }}

                                            </div>

                                            <div class="date-time">

                                                {{ \Carbon\Carbon::parse(
                                                    $payment->payment_date
                                                )->format('h:i A') }}

                                            </div>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td class="amount-cell">

                                        ₹ {{ number_format(
                                            $payment->amount ?? 0,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        {{ $payment->payment_method ?: '-' }}

                                    </td>


                                    <td>

                                        <span class="transaction-text">

                                            {{ $payment->transaction_id ?: '-' }}

                                        </span>

                                    </td>


                                    <td class="text-center">

                                        @if($status === 'success')

                                            <span class="table-status success">

                                                <i class="fas fa-check"></i>

                                                Success

                                            </span>

                                        @elseif($status === 'pending')

                                            <span class="table-status pending">

                                                <i class="fas fa-clock"></i>

                                                Pending

                                            </span>

                                        @elseif($status === 'failed')

                                            <span class="table-status failed">

                                                <i class="fas fa-times"></i>

                                                Failed

                                            </span>

                                        @elseif($status === 'cancelled')

                                            <span class="table-status cancelled">

                                                <i class="fas fa-ban"></i>

                                                Cancelled

                                            </span>

                                        @else

                                            <span class="table-status other">

                                                {{ ucfirst(
                                                    $payment->payment_status ?? '-'
                                                ) }}

                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{ optional($payment->creator)->name ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $payment->remarks ?: '-' }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9"
                                        class="empty-payment">

                                        <div class="empty-icon">

                                            <i class="fas fa-wallet"></i>

                                        </div>

                                        <strong>

                                            No Payment History Found

                                        </strong>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>


                        <tfoot>

                            <tr>

                                <td colspan="3"
                                    class="total-label">

                                    Booking Amount

                                </td>

                                <td class="total-value primary">

                                    ₹ {{ number_format(
                                        $bookingAmount,
                                        2
                                    ) }}

                                </td>

                                <td colspan="5"></td>

                            </tr>


                            <tr>

                                <td colspan="3"
                                    class="total-label">

                                    Total Paid

                                </td>

                                <td class="total-value success">

                                    ₹ {{ number_format(
                                        $totalPayment,
                                        2
                                    ) }}

                                </td>

                                <td colspan="5"></td>

                            </tr>


                            <tr class="due-total-row">

                                <td colspan="3"
                                    class="total-label">

                                    Remaining Due

                                </td>

                                <td class="total-value danger">

                                    ₹ {{ number_format(
                                        $dueAmount,
                                        2
                                    ) }}

                                </td>

                                <td colspan="5"></td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>


            {{-- =================================================
                NOTES + SIGNATURE
            ================================================== --}}

            <div class="invoice-bottom">

                <div class="notes-box">

                    <div class="bottom-title">

                        <i class="fas fa-info-circle"></i>

                        Notes

                    </div>


                    <div class="notes-text">

                        {{ $booking->remarks
                            ?: 'Thank you for choosing our studio services.' }}

                    </div>

                </div>


                <div class="signature-box">

                    <div class="signature-line"></div>

                    <div class="signature-title">

                        Authorized Signature

                    </div>

                </div>

            </div>


            {{-- =================================================
                FOOTER
            ================================================== --}}

            <div class="invoice-footer">

                <div>

                    <strong>

                        STUDIO BOOKING

                    </strong>

                    <span>

                        &nbsp;•&nbsp; Payment Invoice

                    </span>

                </div>


                <div>

                    Generated on

                    {{ now()->format('d M Y, h:i A') }}

                </div>

            </div>


        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

    function printInvoice()
    {
        window.print();
    }


    window.addEventListener('beforeprint', function () {

        document.body.classList.add('printing');

    });


    window.addEventListener('afterprint', function () {

        document.body.classList.remove('printing');

    });

</script>

@endpush
