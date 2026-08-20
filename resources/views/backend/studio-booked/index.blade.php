@extends('backend.partial.master')

@section('title', 'Studio Bookings')

@section('backend-content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="row mb-3">

        <div class="col-md-6">

            <h4 class="mb-0">

                <i class="fas fa-calendar-check text-primary"></i>

                Studio Bookings

            </h4>

            <small class="text-muted">

                Manage all studio bookings & payments

            </small>

        </div>

    </div>


    {{-- =========================================================
        MAIN CARD
    ========================================================== --}}
    <div class="card shadow-sm">

        {{-- =====================================================
            CARD HEADER
        ====================================================== --}}
        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="fas fa-list"></i>

                Booking List

            </h5>

            <span class="badge bg-primary">

                {{ $bookings->count() }}

                Booking{{ $bookings->count() != 1 ? 's' : '' }}

            </span>

        </div>


        {{-- =====================================================
            CARD BODY
        ====================================================== --}}
        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle"
                    id="responsive-datatable"
                    style="width:100%;">

                    {{-- =================================================
                        TABLE HEAD
                    ================================================== --}}
                    <thead class="table-dark text-center">

                        <tr>

                            <th>#</th>

                            <th>Booking</th>

                            <th>Customer</th>

                            <th>Studio</th>

                            <th>Booking Schedule</th>

                            <th>Duration</th>

                            <th>Rate</th>

                            <th>Total Amount</th>

                            <th>Paid</th>

                            <th>Due</th>

                            <th>Payments</th>

                            <th>Status</th>

                            <th width="130">
                                Action
                            </th>

                        </tr>

                    </thead>


                    {{-- =================================================
                        TABLE BODY
                    ================================================== --}}
                    <tbody>

                        @forelse($bookings as $key => $booking)

                            @php

                                /*
                                |--------------------------------------------------------------------------
                                | Booking Type
                                |--------------------------------------------------------------------------
                                */

                                $bookingType = strtolower(
                                    trim($booking->booking_type ?? 'day')
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | Hourly / Daily
                                |--------------------------------------------------------------------------
                                */

                                $isHourly = $bookingType === 'hour';


                                /*
                                |--------------------------------------------------------------------------
                                | Booking Type Label
                                |--------------------------------------------------------------------------
                                */

                                $bookingTypeLabel = $isHourly
                                    ? 'Per Hour'
                                    : 'Per Day';


                                /*
                                |--------------------------------------------------------------------------
                                | Rate
                                |--------------------------------------------------------------------------
                                |
                                | Studio model:
                                |
                                | price_per_day
                                | price_per_hour
                                |
                                */

                                if ($isHourly) {

                                    $rate = $booking->studio->price_per_hour ?? 0;

                                    $rateLabel = 'Per Hour';

                                    $durationUnit =
                                        ($booking->booking_duration ?? 0) > 1
                                            ? 'Hours'
                                            : 'Hour';

                                } else {

                                    $rate = $booking->studio->price_per_day ?? 0;

                                    $rateLabel = 'Per Day';

                                    $durationUnit =
                                        ($booking->booking_duration ?? 0) > 1
                                            ? 'Days'
                                            : 'Day';

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Duration
                                |--------------------------------------------------------------------------
                                */

                                $duration = $booking->booking_duration ?? 0;


                                /*
                                |--------------------------------------------------------------------------
                                | Total Booking Amount
                                |--------------------------------------------------------------------------
                                |
                                | booking_amount is the final booking amount.
                                |
                                */

                                $totalAmount = $booking->booking_amount ?? 0;


                                /*
                                |--------------------------------------------------------------------------
                                | Paid Amount
                                |--------------------------------------------------------------------------
                                */

                                $paidAmount = $booking->total_paid ?? 0;


                                /*
                                |--------------------------------------------------------------------------
                                | Due Amount
                                |--------------------------------------------------------------------------
                                */

                                $dueAmount = $booking->due_amount ?? 0;


                                /*
                                |--------------------------------------------------------------------------
                                | Payments
                                |--------------------------------------------------------------------------
                                */

                                $payments = $booking->payments ?? collect();


                                /*
                                |--------------------------------------------------------------------------
                                | Successful Payments
                                |--------------------------------------------------------------------------
                                */

                                $successfulPayments = $payments->filter(
                                    function ($payment) {

                                        return strtolower(
                                            trim($payment->payment_status ?? '')
                                        ) === 'success';

                                    }
                                );

                            @endphp


                            {{-- =================================================
                                BOOKING ROW
                            ================================================== --}}
                            <tr>


                                {{-- =================================================
                                    #
                                ================================================== --}}
                                <td class="text-center">

                                    {{ $key + 1 }}

                                </td>


                                {{-- =================================================
                                    BOOKING
                                ================================================== --}}
                                <td>

                                    <strong class="text-primary">

                                        {{ $booking->booking_id }}

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        <i class="fas fa-calendar-plus"></i>

                                        {{ $booking->created_at
                                            ? $booking->created_at->format('d M Y')
                                            : 'N/A'
                                        }}

                                    </small>

                                    <br>

                                    <span class="badge bg-light text-dark mt-1">

                                        {{ $bookingTypeLabel }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    CUSTOMER
                                ================================================== --}}
                                <td>

                                    <strong>

                                        {{ $booking->customer_name }}

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        <i class="fas fa-phone"></i>

                                        {{ $booking->phone }}

                                    </small>


                                    @if($booking->email)

                                        <br>

                                        <small class="text-muted">

                                            <i class="fas fa-envelope"></i>

                                            {{ $booking->email }}

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                    STUDIO
                                ================================================== --}}
                                <td>

                                    <strong>

                                        {{ optional(
                                            optional($booking->studio)->category
                                        )->name ?? 'N/A' }}

                                    </strong>

                                </td>


                                {{-- =================================================
                                    BOOKING SCHEDULE
                                ================================================== --}}
                                <td>

                                    {{-- FROM DATE --}}

                                    @if($booking->booking_from_date)

                                        <strong>

                                            {{ \Carbon\Carbon::parse(
                                                $booking->booking_from_date
                                            )->format('d M Y') }}

                                        </strong>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif


                                    {{-- FROM TIME --}}

                                    @if($booking->booking_from_time)

                                        <br>

                                        <small class="text-muted">

                                            <i class="far fa-clock"></i>

                                            {{ \Carbon\Carbon::parse(
                                                $booking->booking_from_time
                                            )->format('h:i A') }}

                                        </small>

                                    @endif


                                    <div class="text-muted my-1">

                                        <i class="fas fa-arrow-down"></i>

                                    </div>


                                    {{-- TO DATE --}}

                                    @if($booking->booking_to_date)

                                        <strong>

                                            {{ \Carbon\Carbon::parse(
                                                $booking->booking_to_date
                                            )->format('d M Y') }}

                                        </strong>

                                    @else

                                        <span class="text-muted">

                                            Same Day

                                        </span>

                                    @endif


                                    {{-- TO TIME --}}

                                    @if($booking->booking_to_time)

                                        <br>

                                        <small class="text-muted">

                                            <i class="far fa-clock"></i>

                                            {{ \Carbon\Carbon::parse(
                                                $booking->booking_to_time
                                            )->format('h:i A') }}

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                    DURATION
                                ================================================== --}}
                                <td class="text-center">

                                    <span class="badge bg-info">

                                        {{ number_format($duration, 2) }}

                                        {{ $durationUnit }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    RATE
                                ================================================== --}}
                                <td class="text-end">

                                    <small class="text-muted d-block">

                                        {{ $rateLabel }}

                                    </small>

                                    <strong>

                                        ₹ {{ number_format($rate, 2) }}

                                    </strong>

                                </td>


                                {{-- =================================================
                                    TOTAL AMOUNT
                                ================================================== --}}
                                <td class="text-end">

                                    <strong class="text-primary">

                                        ₹ {{ number_format(
                                            $totalAmount,
                                            2
                                        ) }}

                                    </strong>

                                </td>


                                {{-- =================================================
                                    PAID
                                ================================================== --}}
                                <td class="text-end">

                                    <strong class="text-success">

                                        ₹ {{ number_format(
                                            $paidAmount,
                                            2
                                        ) }}

                                    </strong>

                                </td>


                                {{-- =================================================
                                    DUE
                                ================================================== --}}
                                <td class="text-end">

                                    @if($dueAmount > 0)

                                        <strong class="text-danger">

                                            ₹ {{ number_format(
                                                $dueAmount,
                                                2
                                            ) }}

                                        </strong>

                                    @else

                                        <strong class="text-success">

                                            ₹ 0.00

                                        </strong>

                                    @endif

                                </td>


                                {{-- =================================================
                                    PAYMENTS
                                ================================================== --}}
                                <td>

                                    {{-- Payment Count --}}

                                    <div class="mb-1">

                                        <span class="badge bg-secondary">

                                            {{ $payments->count() }}

                                            Payment{{ $payments->count() != 1 ? 's' : '' }}

                                        </span>

                                    </div>


                                    {{-- Successful Payment Invoice --}}

                                    @foreach($successfulPayments as $payment)

                                        @if(!empty($payment->payment_id))

                                            <div class="mb-1">

                                                <a
                                                    href="{{ route(
                                                        'studio.invoice.download',
                                                        $payment->id
                                                    ) }}"
                                                    target="_blank"
                                                    class="badge bg-primary text-decoration-none"
                                                    title="View Invoice">

                                                    <i class="mdi mdi-receipt-text-outline"></i>

                                                    {{ $payment->payment_id }}

                                                </a>

                                            </div>

                                        @endif

                                    @endforeach


                                    {{-- No Successful Payment --}}

                                    @if(
                                        $payments->count() > 0 &&
                                        $successfulPayments->count() == 0
                                    )

                                        <small class="text-muted">

                                            No successful payment

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}
                                <td class="text-center">

                                    @switch(
                                        strtolower(
                                            trim(
                                                $booking->booking_status ?? ''
                                            )
                                        )
                                    )


                                        {{-- FULLY PAID --}}

                                        @case('paid')

                                            <span class="badge bg-success">

                                                <i class="fas fa-check-circle"></i>

                                                Fully Paid

                                            </span>

                                            @break


                                        {{-- PARTIAL --}}

                                        @case('partial')

                                            <span class="badge bg-warning text-dark">

                                                <i class="fas fa-adjust"></i>

                                                Partial Paid

                                            </span>

                                            @break


                                        {{-- PENDING --}}

                                        @case('pending')

                                            <span class="badge bg-info">

                                                <i class="fas fa-clock"></i>

                                                Pending

                                            </span>

                                            @break


                                        {{-- FAILED --}}

                                        @case('failed')

                                            <span class="badge bg-danger">

                                                <i class="fas fa-times-circle"></i>

                                                Failed

                                            </span>

                                            @break


                                        {{-- CANCELLED --}}

                                        @case('cancelled')

                                            <span class="badge bg-secondary">

                                                <i class="fas fa-ban"></i>

                                                Cancelled

                                            </span>

                                            @break


                                        {{-- DEFAULT --}}

                                        @default

                                            <span class="badge bg-dark">

                                                Unpaid

                                            </span>

                                    @endswitch

                                </td>


                                {{-- =================================================
                                    ACTION
                                ================================================== --}}
                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'studio-booked.payment-history',
                                            $booking->id
                                        ) }}"
                                        class="btn btn-primary btn-sm">

                                        <i class="fas fa-wallet"></i>

                                        Payments

                                    </a>

                                </td>


                            </tr>


                        @empty


                            {{-- =================================================
                                EMPTY STATE
                            ================================================== --}}
                            <tr>

                                <td
                                    colspan="13"
                                    class="text-center py-5">

                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                                    <h5 class="text-muted">

                                        No Booking Found

                                    </h5>

                                    <p class="text-muted mb-0">

                                        No studio bookings are available.

                                    </p>

                                </td>

                            </tr>


                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
