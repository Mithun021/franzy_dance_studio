@extends('backend.partial.master')

@section('title', 'Studio Payment History')

@section('backend-content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="row mb-3">

        <div class="col-md-6">

            <h4 class="mb-1">

                <i class="fas fa-credit-card text-primary"></i>

                Studio Payment History

            </h4>

            <small class="text-muted">

                Complete studio booking payment records

            </small>

        </div>


        <div class="col-md-6 text-end">

            <a href="{{ route('studio-booked.index') }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>

                Back to Bookings

            </a>

        </div>

    </div>


    {{-- =========================================================
        FILTER CARD
    ========================================================== --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">

                <i class="fas fa-filter text-primary"></i>

                Payment Filters

            </h6>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('studio-payment.history') }}">

                <div class="row g-3">


                    {{-- Payment ID --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Payment ID

                        </label>

                        <input type="text"
                               name="payment_id"
                               class="form-control"
                               placeholder="Enter Payment ID"
                               value="{{ request('payment_id') }}">

                    </div>


                    {{-- Booking ID --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Booking ID

                        </label>

                        <input type="text"
                               name="booking_id"
                               class="form-control"
                               placeholder="Enter Booking ID"
                               value="{{ request('booking_id') }}">

                    </div>


                    {{-- Customer --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Customer Name

                        </label>

                        <input type="text"
                               name="customer_name"
                               class="form-control"
                               placeholder="Customer Name"
                               value="{{ request('customer_name') }}">

                    </div>


                    {{-- Studio --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Studio

                        </label>

                        <select name="studio_name"
                                class="form-select">

                            <option value="">

                                All Studios

                            </option>


                            @foreach($studioCategories as $studio)

                                <option value="{{ $studio->name }}"
                                    {{ request('studio_name') == $studio->name ? 'selected' : '' }}>

                                    {{ $studio->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- From Date --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            From Date

                        </label>

                        <input type="date"
                               name="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">

                    </div>


                    {{-- To Date --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            To Date

                        </label>

                        <input type="date"
                               name="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Payment Status

                        </label>

                        <select name="payment_status"
                                class="form-select">

                            <option value="">

                                All Status

                            </option>


                            @foreach($paymentStatuses as $status)

                                <option value="{{ $status }}"
                                    {{ request('payment_status') == $status ? 'selected' : '' }}>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-search"></i>

                            Filter

                        </button>


                        <a href="{{ route('studio-payment.history') }}"
                           class="btn btn-light border">

                            <i class="fas fa-sync-alt"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- =========================================================
        SUMMARY
    ========================================================== --}}
    <div class="row mb-4">


        {{-- Total Records --}}
        <div class="col-md-3">

            <div class="card border-primary shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Total Records

                            </small>

                            <h4 class="mb-0 text-primary">

                                {{ $payments->total() }}

                            </h4>

                        </div>


                        <div class="text-primary fs-3">

                            <i class="fas fa-list"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- Successful --}}
        <div class="col-md-3">

            <div class="card border-success shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Successful

                            </small>

                            <h4 class="mb-0 text-success">

                                ₹ {{ number_format(
                                    $totalSuccessful,
                                    2
                                ) }}

                            </h4>

                        </div>


                        <div class="text-success fs-3">

                            <i class="fas fa-check-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- Pending --}}
        <div class="col-md-3">

            <div class="card border-info shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Pending

                            </small>

                            <h4 class="mb-0 text-info">

                                ₹ {{ number_format(
                                    $totalPending,
                                    2
                                ) }}

                            </h4>

                        </div>


                        <div class="text-info fs-3">

                            <i class="fas fa-clock"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- Failed / Refunded --}}
        <div class="col-md-3">

            <div class="card border-danger shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Failed / Refunded

                            </small>

                            <h4 class="mb-0 text-danger">

                                ₹ {{ number_format(
                                    $totalFailedRefunded,
                                    2
                                ) }}

                            </h4>

                        </div>


                        <div class="text-danger fs-3">

                            <i class="fas fa-exclamation-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        PAYMENT TABLE
    ========================================================== --}}
    <div class="card shadow-sm">


        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <div>

                <h6 class="mb-0">

                    <i class="fas fa-history text-primary"></i>

                    Payment Records

                </h6>


                <small class="text-muted">

                    Showing {{ $payments->count() }}

                    of

                    {{ $payments->total() }}

                    records

                </small>

            </div>

        </div>



        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th width="50">
                                #
                            </th>

                            <th>
                                Payment ID
                            </th>

                            <th>
                                Booking ID
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Studio
                            </th>

                            <th>
                                Booking Dates
                            </th>

                            <th>
                                Payment Date
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Method
                            </th>

                            <th>
                                Transaction ID
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Received By
                            </th>

                        </tr>

                    </thead>



                    <tbody>

                        @forelse($payments as $payment)

                            @php

                                $status = strtolower(
                                    $payment->payment_status ?? ''
                                );

                            @endphp


                            <tr>


                                {{-- # --}}
                                <td>

                                    {{ $payments->firstItem() + $loop->index }}

                                </td>



                                {{-- Payment ID --}}
                                <td>

                                    @if($payment->payment_id)

                                        <a href="{{ route(
                                            'studio.invoice.download',
                                            $payment->id
                                        ) }}"
                                           target="_blank"
                                           class="text-primary fw-semibold text-decoration-none">

                                            <i class="mdi mdi-receipt-text-outline"></i>

                                            {{ $payment->payment_id }}

                                        </a>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- Booking ID --}}
                                <td>

                                    @if($payment->booking)

                                        <strong>

                                            {{ $payment->booking->booking_id }}

                                        </strong>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- Customer --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $payment->booking->customer_name ?? '-' }}

                                    </div>


                                    @if(!empty($payment->booking?->phone))

                                        <small class="text-muted">

                                            <i class="fas fa-phone"></i>

                                            {{ $payment->booking->phone }}

                                        </small>

                                    @endif

                                </td>



                                {{-- Studio --}}
                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ $payment->booking?->studio?->category?->name ?? '-' }}

                                    </span>

                                </td>



                                {{-- Booking Dates --}}
                                <td>

                                    @if($payment->booking)

                                        <div class="fw-semibold">

                                            {{ \Carbon\Carbon::parse(
                                                $payment->booking->booking_from_date
                                            )->format('d M Y') }}

                                        </div>


                                        <small class="text-muted">

                                            to

                                            {{ \Carbon\Carbon::parse(
                                                $payment->booking->booking_to_date
                                            )->format('d M Y') }}

                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- Payment Date --}}
                                <td>

                                    @if($payment->payment_date)

                                        <div class="fw-semibold">

                                            {{ $payment->payment_date->format('d M Y') }}

                                        </div>


                                        <small class="text-muted">

                                            {{ $payment->payment_date->format('h:i A') }}

                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- Amount --}}
                                <td class="text-end">

                                    <strong class="
                                        {{ $status === 'success'
                                            ? 'text-success'
                                            : 'text-dark'
                                        }}
                                    ">

                                        ₹ {{ number_format(
                                            $payment->amount ?? 0,
                                            2
                                        ) }}

                                    </strong>

                                </td>



                                {{-- Payment Type --}}
                                <td>

                                    @if($payment->payment_type)

                                        <span class="badge bg-secondary">

                                            {{ $payment->payment_type }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- Payment Method --}}
                                <td>

                                    {{ $payment->payment_method ?? '-' }}

                                </td>



                                {{-- Transaction ID --}}
                                <td>

                                    @if($payment->transaction_id)

                                        <small class="text-break">

                                            {{ $payment->transaction_id }}

                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>



                                {{-- Status --}}
                                <td>

                                    @switch($status)

                                        @case('success')

                                            <span class="badge bg-success">

                                                <i class="fas fa-check-circle"></i>

                                                Success

                                            </span>

                                        @break


                                        @case('pending')

                                            <span class="badge bg-info">

                                                <i class="fas fa-clock"></i>

                                                Pending

                                            </span>

                                        @break


                                        @case('failed')

                                            <span class="badge bg-danger">

                                                <i class="fas fa-times-circle"></i>

                                                Failed

                                            </span>

                                        @break


                                        @case('refunded')

                                            <span class="badge bg-warning text-dark">

                                                <i class="fas fa-undo"></i>

                                                Refunded

                                            </span>

                                        @break


                                        @case('cancelled')

                                            <span class="badge bg-secondary">

                                                <i class="fas fa-ban"></i>

                                                Cancelled

                                            </span>

                                        @break


                                        @default

                                            <span class="badge bg-secondary">

                                                {{ $payment->payment_status ?? '-' }}

                                            </span>

                                    @endswitch

                                </td>



                                {{-- Creator --}}
                                <td>

                                    {{ $payment->creator->name ?? '-' }}

                                </td>


                            </tr>


                        @empty

                            <tr>

                                <td colspan="13"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fas fa-wallet fa-2x mb-3"></i>

                                        <h6>

                                            No Payment Records Found

                                        </h6>

                                        <small>

                                            Try changing your filters.

                                        </small>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>



        {{-- =====================================================
            PAGINATION
        ====================================================== --}}
        @if($payments->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="text-muted small">

                        Showing

                        <strong>

                            {{ $payments->firstItem() }}

                        </strong>

                        to

                        <strong>

                            {{ $payments->lastItem() }}

                        </strong>

                        of

                        <strong>

                            {{ $payments->total() }}

                        </strong>

                        records

                    </div>


                    <div>

                        {{ $payments->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection
