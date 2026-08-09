@extends('backend.partial.master')

@section('title', 'Studio Bookings')

@section('backend-content')

<div class="container-fluid">

    <!-- Header -->
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

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Booking List
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle" id="responsive-datatable">

                    <thead class="table-dark text-center">

                        <tr>

                            <th>#</th>

                            <th>Booking</th>

                            <th>Customer</th>

                            <th>Studio</th>

                            <th>Booking Dates</th>

                            <th>Days</th>

                            <th>Per Day</th>

                            <th>Total Amount</th>

                            <th>Paid</th>

                            <th>Due</th>

                            <th>Payments</th>

                            <th>Status</th>

                            <th width="120">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($bookings as $key => $booking)

                            <tr>

                                <td class="text-center">
                                    {{ ++$key }}
                                </td>

                                <td>

                                    <strong>{{ $booking->booking_id }}</strong>

                                    <br>

                                    <small class="text-muted">

                                        {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}

                                    </small>

                                </td>

                                <td>

                                    <strong>

                                        {{ $booking->customer_name }}

                                    </strong>

                                    <br>

                                    <small>

                                        <i class="fas fa-phone"></i>

                                        {{ $booking->phone }}

                                    </small>

                                    @if($booking->email)

                                        <br>

                                        <small>

                                            <i class="fas fa-envelope"></i>

                                            {{ $booking->email }}

                                        </small>

                                    @endif

                                </td>

                                <td>

                                    {{ optional(optional($booking->studio)->category)->name ?? 'N/A' }}

                                </td>

                                <td>

                                    <strong>

                                        {{ \Carbon\Carbon::parse($booking->booking_from_date)->format('d M Y') }}

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        @if($booking->booking_to_date)

                                            {{ \Carbon\Carbon::parse($booking->booking_to_date)->format('d M Y') }}

                                        @else

                                            Same Day

                                        @endif

                                    </small>

                                </td>

                                <td class="text-center">

                                    <span class="badge bg-info">

                                        {{ $booking->total_days }}

                                        Day{{ $booking->total_days > 1 ? 's' : '' }}

                                    </span>

                                </td>

                                <td class="text-end">

                                    ₹ {{ number_format($booking->studio_amount,2) }}

                                </td>

                                <td class="text-end">

                                    <strong class="text-primary">

                                        ₹ {{ number_format($booking->booking_amount,2) }}

                                    </strong>

                                </td>

                                <td class="text-end">

                                    <span class="text-success fw-bold">

                                        ₹ {{ number_format($booking->total_paid,2) }}

                                    </span>

                                </td>

                                <td class="text-end">

                                    <span class="text-danger fw-bold">

                                        ₹ {{ number_format($booking->due_amount,2) }}

                                    </span>

                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $booking->payments->count() }}
                                    </span>

                                    @foreach($booking->payments as $payment)
                                        @if(!empty($payment->payment_id))
                                            <a href="{{ route('studio.invoice.download', $payment->id) }}"
                                            class="badge bg-primary text-decoration-none ms-1"
                                            target="_blank"
                                            title="View Invoice">

                                                <i class="mdi mdi-receipt-text-outline"></i>
                                                {{ $payment->payment_id }}

                                            </a>
                                        @endif
                                    @endforeach
                                </td>

                                <td class="text-center">

                                    @switch($booking->booking_status)

                                        @case('Paid')

                                            <span class="badge bg-success">

                                                Fully Paid

                                            </span>

                                            @break

                                        @case('Partial')

                                            <span class="badge bg-warning text-dark">

                                                Partial Paid

                                            </span>

                                            @break

                                        @case('Pending')

                                            <span class="badge bg-info">

                                                Pending

                                            </span>

                                            @break

                                        @case('Failed')

                                            <span class="badge bg-danger">

                                                Failed

                                            </span>

                                            @break

                                        @case('Cancelled')

                                            <span class="badge bg-secondary">

                                                Cancelled

                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-dark">

                                                Unpaid

                                            </span>

                                    @endswitch

                                </td>

                                <td class="text-center">

                                    <a href="{{ route('studio-booked.payment-history',$booking->id) }}"
                                       class="btn btn-primary btn-sm">

                                        <i class="fas fa-wallet"></i>

                                        Payments

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="13" class="text-center py-5">

                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                                    <h5>No Booking Found</h5>

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
