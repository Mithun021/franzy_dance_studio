@extends('backend.partial.master')

@section('title', 'Course Payment Records')

@section('backend-content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="card mb-3">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>
                <h4 class="mb-0">
                    <i class="mdi mdi-cash-multiple me-1"></i>
                    Course Payment Records
                </h4>

                <small class="text-muted">
                    Complete payment history of all students
                </small>
            </div>

            <div>

                <a href="{{ route('billing.index') }}"
                   class="btn btn-secondary btn-sm">

                    <i class="mdi mdi-arrow-left"></i>
                    Billing
                </a>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTER --}}
    {{-- ========================================================= --}}

    <div class="card mb-3">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="mdi mdi-filter-outline"></i>
                Filter Payments
            </h5>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('course.payment.index') }}">

                <div class="row g-3">

                    {{-- Student --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Student
                        </label>

                        <select name="student_id"
                                class="form-select">

                            <option value="">
                                All Students
                            </option>

                            @foreach($students as $student)

                                <option value="{{ $student->id }}"
                                    {{ request('student_id') == $student->id ? 'selected' : '' }}>

                                    {{ $student->name }}

                                    @if(!empty($student->admission_no))
                                        - {{ $student->admission_no }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Course --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Course
                        </label>

                        <select name="course_id"
                                class="form-select">

                            <option value="">
                                All Courses
                            </option>

                            @foreach($courses as $course)

                                <option value="{{ $course->id }}"
                                    {{ request('course_id') == $course->id ? 'selected' : '' }}>

                                    {{ $course->course_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- From Date --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input type="date"
                               name="from_date"
                               value="{{ request('from_date') }}"
                               class="form-control">

                    </div>


                    {{-- To Date --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input type="date"
                               name="to_date"
                               value="{{ request('to_date') }}"
                               class="form-control">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Payment Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="success"
                                {{ request('status') == 'success' ? 'selected' : '' }}>
                                Success
                            </option>

                            <option value="pending"
                                {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="failed"
                                {{ request('status') == 'failed' ? 'selected' : '' }}>
                                Failed
                            </option>

                            <option value="cancelled"
                                {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                            <option value="refunded"
                                {{ request('status') == 'refunded' ? 'selected' : '' }}>
                                Refunded
                            </option>

                        </select>

                    </div>


                    {{-- Payment Mode --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Payment Mode
                        </label>

                        <select name="payment_mode"
                                class="form-select">

                            <option value="">
                                All Payment Modes
                            </option>

                            @php
                                $paymentModes = $payments
                                    ->pluck('payment_mode')
                                    ->filter()
                                    ->unique()
                                    ->sort();
                            @endphp

                            @foreach($paymentModes as $mode)

                                <option value="{{ $mode }}"
                                    {{ request('payment_mode') == $mode ? 'selected' : '' }}>

                                    {{ $mode }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Search --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Order / Transaction ID
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Order ID / Transaction ID">

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-6 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="mdi mdi-filter"></i>
                            Apply Filter

                        </button>


                        <a href="{{ route('course.payment.index') }}"
                           class="btn btn-light border">

                            <i class="mdi mdi-refresh"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    @php

        $successPayments = $payments->where('status', 'success');

        $lateFineAmount = $successPayments->sum('late_fine');

        $successAmount = $successPayments->sum('amount') - $lateFineAmount;



        $totalAmount = $successAmount + $lateFineAmount;

    @endphp


    <div class="row g-3 mb-3">

        {{-- Total Records --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Payments
                            </small>

                            <h4 class="mb-0">
                                {{ $payments->count() }}
                            </h4>

                        </div>

                        <div class="text-primary fs-2">
                            <i class="mdi mdi-cash-multiple"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Successful Amount --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Successful Amount
                            </small>

                            <h4 class="mb-0 text-success">
                                ₹ {{ number_format($successAmount, 2) }}
                            </h4>

                        </div>

                        <div class="text-success fs-2">
                            <i class="mdi mdi-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Late Fine --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Late Fine Collected
                            </small>

                            <h4 class="mb-0 text-danger">
                                ₹ {{ number_format($lateFineAmount, 2) }}
                            </h4>

                        </div>

                        <div class="text-danger fs-2">
                            <i class="mdi mdi-alert-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Collection --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Collection
                            </small>

                            <h4 class="mb-0 text-primary">
                                ₹ {{ number_format($totalAmount, 2) }}
                            </h4>

                        </div>

                        <div class="text-primary fs-2">
                            <i class="mdi mdi-wallet"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PAYMENT TABLE --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="mdi mdi-history"></i>

                Payment History

            </h5>

            <span class="badge bg-primary">

                {{ $payments->count() }} Records

            </span>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle"
                    id="responsive-datatable">

                    <thead class="table-dark">

                    <tr>

                        <th>#</th>

                        <th>Payment Date</th>

                        <th>Order ID</th>

                        <th>Student</th>

                        <th>Course</th>

                        <th>Payment Mode</th>

                        <th>Payment Type</th>

                        <th>Amount</th>

                        <th>Late Fine</th>

                        <th>Total Amount</th>

                        <th>Transaction ID</th>

                        <th>Status</th>

                        <th>Payment Proof</th>

                        <th>Remarks</th>

                        <th id="no-export">
                            Action
                        </th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse($payments as $key => $payment)

                        <tr>

                            {{-- # --}}
                            <td>
                                {{ $key + 1 }}
                            </td>


                            {{-- Payment Date --}}
                            <td>

                                @if($payment->payment_date)

                                    {{ $payment->payment_date->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Order ID --}}
                            <td>

                                @if($payment->order_id)

                                    <span class="badge bg-light text-dark border">

                                        {{ $payment->order_id }}

                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Student --}}
                            <td>

                                @if($payment->student)

                                    <strong>
                                        {{ $payment->student->name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        {{ $payment->student->email ?? '' }}

                                    </small>

                                    @if(!empty($payment->student->phone))

                                        <br>

                                        <small>

                                            {{ $payment->student->phone }}

                                        </small>

                                    @endif

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Course --}}
                            <td>

                                @if($payment->studentCourse)

                                    <strong>

                                        {{ optional($payment->studentCourse->course)->course_name ?? '-' }}

                                    </strong>

                                    <br>

                                    <small class="text-primary">

                                        Admission:
                                        {{ $payment->studentCourse->admission_no ?? '-' }}

                                    </small>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Payment Mode --}}
                            <td>

                                @if($payment->payment_mode)

                                    <span class="badge bg-info text-dark">

                                        {{ $payment->payment_mode }}

                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Payment Type --}}
                            <td>

                                {{ ucfirst($payment->payment_type ?? '-') }}

                            </td>


                            {{-- Amount --}}
                            <td class="text-end">

                                <strong>

                                    ₹ {{ number_format(
                                        (float)$payment->amount - (float)$payment->late_fine,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            {{-- Late Fine --}}
                            <td class="text-end">

                                @if((float)$payment->late_fine > 0)

                                    <span class="text-danger fw-bold">

                                        ₹ {{ number_format((float)$payment->late_fine, 2) }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Total Amount --}}
                            <td class="text-end">

                                <strong class="text-primary">

                                    {{-- ₹
                                    {{ number_format(
                                        (float)$payment->amount +
                                        (float)$payment->late_fine,
                                        2
                                    ) }} --}}

                                    ₹ {{ number_format((float) $payment->amount, 2) }}

                                </strong>

                            </td>


                            {{-- Transaction ID --}}
                            <td>

                                @if($payment->transaction_id)

                                    <small>

                                        {{ $payment->transaction_id }}

                                    </small>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @switch($payment->status)

                                    @case('success')

                                        <span class="badge bg-success">

                                            <i class="mdi mdi-check-circle"></i>
                                            Success

                                        </span>

                                        @break


                                    @case('pending')

                                        <span class="badge bg-warning text-dark">

                                            <i class="mdi mdi-clock-outline"></i>
                                            Pending

                                        </span>

                                        @break


                                    @case('failed')

                                        <span class="badge bg-danger">

                                            <i class="mdi mdi-close-circle"></i>
                                            Failed

                                        </span>

                                        @break


                                    @case('cancelled')

                                        <span class="badge bg-secondary">

                                            <i class="mdi mdi-cancel"></i>
                                            Cancelled

                                        </span>

                                        @break


                                    @case('refunded')

                                        <span class="badge bg-info">

                                            <i class="mdi mdi-cash-refund"></i>
                                            Refunded

                                        </span>

                                        @break


                                    @default

                                        <span class="badge bg-dark">

                                            {{ ucfirst($payment->status ?? 'Unknown') }}

                                        </span>

                                @endswitch

                            </td>


                            {{-- Payment Proof --}}
                            <td class="text-center">

                                @if(
                                    $payment->payment_mode !== 'Online Payment'
                                    && !empty($payment->payment_proof)
                                )

                                    <a
                                        href="{{ asset('storage/' . $payment->payment_proof) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-secondary"
                                        title="View Payment Proof">

                                        <i class="mdi mdi-file-image"></i>

                                    </a>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Remarks --}}
                            <td>

                                @if($payment->remarks)

                                    <span title="{{ $payment->remarks }}">

                                        {{ \Illuminate\Support\Str::limit(
                                            $payment->remarks,
                                            40
                                        ) }}

                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Action --}}
                            <td class="text-center">

                                @if($payment->status === 'success')

                                    <a
                                        href="{{ route('billing.invoice',$payment->id) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-primary"
                                        title="View Invoice">

                                        <i class="mdi mdi-file-document"></i>

                                    </a>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="15"
                                class="text-center py-4">

                                <div class="text-muted">

                                    <i class="mdi mdi-cash-remove fs-2"></i>

                                    <div class="mt-2">
                                        No payment records found.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>


                    {{-- ================================================= --}}
                    {{-- FOOTER TOTAL --}}
                    {{-- ================================================= --}}

                    @if($payments->count())

                        <tfoot class="table-light">

                        <tr>

                            <th colspan="7" class="text-end">

                                Total:

                            </th>

                            {{-- <th class="text-end">

                                ₹
                                {{ number_format(
                                    $payments->where('status','success')->sum('amount'),
                                    2
                                ) }}

                            </th> --}}

                            <th class="text-end">
                                ₹ {{ number_format(
                                    $payments->where('status', 'success')->sum('amount')
                                    - $payments->where('status', 'success')->sum('late_fine'),
                                    2
                                ) }}
                            </th>

                            <th class="text-end text-danger">

                                ₹
                                {{ number_format(
                                    $payments->where('status','success')->sum('late_fine'),
                                    2
                                ) }}

                            </th>

                            <th class="text-end text-primary">

                                ₹
                                {{ number_format(
                                    $payments->where('status','success')->sum('amount'),
                                    // +
                                    // $payments->where('status','success')->sum('late_fine'),
                                    2
                                ) }}

                            </th>

                            <th colspan="5"></th>

                        </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>

</div>

@endsection

{{--
@push('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */

    if ($.fn.DataTable) {

        $('#datatable-buttons').DataTable({

            pageLength: 25,

            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],

            order: [
                [1, 'desc']
            ],

            columnDefs: [

                {
                    targets: [14],
                    orderable: false,
                    searchable: false
                }

            ],

            dom:
                '<"row mb-2"' +
                    '<"col-md-6"B>' +
                    '<"col-md-6"f>' +
                '>' +
                '<"row"' +
                    '<"col-md-6"l>' +
                    '<"col-md-6"i>' +
                '>' +
                'rt' +
                '<"row mt-2"' +
                    '<"col-md-6"i>' +
                    '<"col-md-6"p>' +
                '>',

            buttons: [

                {
                    extend: 'copy',
                    exportOptions: {
                        columns: ':not(#no-export)'
                    }
                },

                {
                    extend: 'excel',
                    title: 'Course Payment Records',
                    exportOptions: {
                        columns: ':not(#no-export)'
                    }
                },

                {
                    extend: 'csv',
                    title: 'Course Payment Records',
                    exportOptions: {
                        columns: ':not(#no-export)'
                    }
                },

                {
                    extend: 'print',
                    title: 'Course Payment Records',
                    exportOptions: {
                        columns: ':not(#no-export)'
                    }
                }

            ]

        });

    }

});

</script>

@endpush --}}
