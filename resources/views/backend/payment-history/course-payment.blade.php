@extends('backend.partial.master')

@section('title', 'Course Payment Records')

@section('backend-content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
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

                <a
                    href="{{ route('billing.index') }}"
                    class="btn btn-secondary btn-sm"
                >
                    <i class="mdi mdi-arrow-left"></i>
                    Billing
                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTER
    ========================================================== --}}
    <div class="card mb-3">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="mdi mdi-filter-outline"></i>
                Filter Payments
            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('course.payment.index') }}"
            >

                <div class="row g-3">

                    {{-- STUDENT --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Student
                        </label>

                        <select
                            name="student_id"
                            class="form-select select2"
                        >

                            <option value="">
                                All Students
                            </option>

                            @foreach($students as $student)

                                <option
                                    value="{{ $student->id }}"
                                    {{ request('student_id') == $student->id ? 'selected' : '' }}
                                >

                                    {{ $student->name }}

                                    @if($student->user_id)
                                        - ID: {{ $student->user_id }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- COURSE --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Course
                        </label>

                        <select
                            name="course_id"
                            class="form-select"
                        >

                            <option value="">
                                All Courses
                            </option>

                            @foreach($courses as $course)

                                <option
                                    value="{{ $course->id }}"
                                    {{ request('course_id') == $course->id ? 'selected' : '' }}
                                >
                                    {{ $course->course_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BATCH --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Batch
                        </label>

                        <select
                            name="batch_id"
                            class="form-select"
                        >

                            <option value="">
                                All Batches
                            </option>

                            @foreach($batches as $batch)

                                <option
                                    value="{{ $batch->id }}"
                                    {{ request('batch_id') == $batch->id ? 'selected' : '' }}
                                >
                                    {{ $batch->batch_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Payment Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="success"
                                {{ request('status') == 'success' ? 'selected' : '' }}
                            >
                                Success
                            </option>

                            <option
                                value="pending"
                                {{ request('status') == 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="failed"
                                {{ request('status') == 'failed' ? 'selected' : '' }}
                            >
                                Failed
                            </option>

                            <option
                                value="cancelled"
                                {{ request('status') == 'cancelled' ? 'selected' : '' }}
                            >
                                Cancelled
                            </option>

                            <option
                                value="refunded"
                                {{ request('status') == 'refunded' ? 'selected' : '' }}
                            >
                                Refunded
                            </option>

                        </select>

                    </div>


                    {{-- FROM DATE --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            value="{{ request('from_date') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- TO DATE --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            value="{{ request('to_date') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- PAYMENT MODE --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Payment Mode
                        </label>

                        <select
                            name="payment_mode"
                            class="form-select"
                        >

                            <option value="">
                                All Payment Modes
                            </option>

                            @foreach($paymentModes as $mode)

                                <option
                                    value="{{ $mode }}"
                                    {{ request('payment_mode') == $mode ? 'selected' : '' }}
                                >
                                    {{ $mode }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- SEARCH --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Student / User ID / Course / Transaction"
                        >

                    </div>


                    {{-- BUTTONS --}}
                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="mdi mdi-filter"></i>
                            Filter
                        </button>


                        <a
                            href="{{ route('course.payment.index') }}"
                            class="btn btn-light border"
                            title="Reset"
                        >
                            <i class="mdi mdi-refresh"></i>
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}
    <div class="row g-3 mb-3">


        {{-- TOTAL PAYMENTS --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Payments
                            </small>

                            <h4 class="mb-0">
                                {{ $totalPayments }}
                            </h4>

                        </div>

                        <div class="text-primary fs-2">

                            <i class="mdi mdi-cash-multiple"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- SUCCESSFUL PAYMENTS --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Successful Payments
                            </small>

                            <h4 class="mb-0 text-success">
                                {{ $payments->where('status', 'success')->count() }}
                            </h4>

                        </div>

                        <div class="text-success fs-2">

                            <i class="mdi mdi-check-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- SUCCESSFUL AMOUNT --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Successful Amount
                            </small>

                            <h4 class="mb-0 text-primary">
                                ₹ {{ number_format($successfulAmount, 2) }}
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


    {{-- =========================================================
        PAYMENT TABLE
    ========================================================== --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="mdi mdi-history"></i>
                Payment History

            </h5>


            <span class="badge bg-primary">

                {{ $payments->count() }}
                Records

            </span>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle"
                    id="datatable-buttons"
                >

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>
                                Payment Date
                            </th>

                            <th>
                                Student
                            </th>

                            <th>
                                Course
                            </th>

                            <th>
                                Batch
                            </th>

                            <th>
                                Payment Mode
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Transaction / Reference
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Remarks
                            </th>

                            <th id="no-export">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($payments as $payment)

                            <tr>


                                {{-- =================================================
                                    #
                                ================================================== --}}
                                <td>

                                    {{ $loop->iteration }}

                                </td>


                                {{-- =================================================
                                    PAYMENT DATE
                                ================================================== --}}
                                <td>

                                    @if($payment->payment_date)

                                        <strong>

                                            {{ \Carbon\Carbon::parse(
                                                $payment->payment_date
                                            )->format('d M Y') }}

                                        </strong>


                                        @if($payment->created_at)

                                            <br>

                                            <small class="text-muted">

                                                {{ $payment->created_at->format('h:i A') }}

                                            </small>

                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- =================================================
                                    STUDENT
                                ================================================== --}}
                                <td>

                                    @if($payment->studentCourse?->student)

                                        <strong>

                                            {{ $payment->studentCourse->student->name }}

                                        </strong>

                                        <br>

                                        <small class="text-primary">

                                            User ID:
                                            {{ $payment->studentCourse->student->user_id ?? '-' }}

                                        </small>


                                        @if($payment->studentCourse->student->phone)

                                            <br>

                                            <small class="text-muted">

                                                {{ $payment->studentCourse->student->phone }}

                                            </small>

                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- =================================================
                                    COURSE
                                ================================================== --}}
                                <td>

                                    @if($payment->studentCourse?->course)

                                        <strong>

                                            {{ $payment->studentCourse->course->course_name }}

                                        </strong>


                                        @if($payment->studentCourse?->level)

                                            <br>

                                            <small class="text-muted">

                                                Level:
                                                {{ $payment->studentCourse->level->level_name ?? '-' }}

                                            </small>

                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- =================================================
                                    BATCH
                                ================================================== --}}
                                <td>

                                    {{ $payment->studentCourse?->batch?->batch_name ?? '-' }}


                                    @if($payment->studentCourse?->category)

                                        <br>

                                        <small class="text-muted">

                                            {{ $payment->studentCourse->category->category_name ?? '' }}

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                    PAYMENT MODE + INDIVIDUAL STATUS
                                ================================================== --}}
                                <td class="payment-mode-cell">

                                    @if(
                                        $payment->grouped_payment_records &&
                                        $payment->grouped_payment_records->count()
                                    )

                                        <div class="payment-parts">


                                            @foreach(
                                                $payment->grouped_payment_records
                                                as $record
                                            )

                                                @php

                                                    /*
                                                    |--------------------------------------------------------------------------
                                                    | IMPORTANT
                                                    |--------------------------------------------------------------------------
                                                    |
                                                    | actual_status comes directly from
                                                    | course_payment_records table.
                                                    |
                                                    */

                                                    $recordStatus = strtolower(
                                                        trim(
                                                            (string) $record->actual_status
                                                        )
                                                    );


                                                    $recordMode = strtolower(
                                                        trim(
                                                            (string) $record->payment_mode
                                                        )
                                                    );


                                                    $modeClass = match($recordMode) {

                                                        'cash'
                                                            => 'bg-success',

                                                        'upi'
                                                            => 'bg-primary',

                                                        'card'
                                                            => 'bg-info',

                                                        'bank transfer'
                                                            => 'bg-dark',

                                                        'cheque'
                                                            => 'bg-warning text-dark',

                                                        'online'
                                                            => 'bg-primary',

                                                        default
                                                            => 'bg-secondary',

                                                    };

                                                @endphp


                                                <div class="payment-part">


                                                    {{-- PAYMENT MODE + AMOUNT --}}
                                                    <div class="payment-part-info">

                                                        <span
                                                            class="badge {{ $modeClass }}"
                                                        >

                                                            {{ $record->payment_mode ?: 'Other' }}

                                                        </span>


                                                        <span class="payment-part-amount">

                                                            ₹
                                                            {{ number_format(
                                                                (float) $record->amount,
                                                                2
                                                            ) }}

                                                        </span>

                                                    </div>


                                                    {{-- =================================================
                                                        INDIVIDUAL PAYMENT STATUS
                                                    ================================================== --}}
                                                    <div class="payment-part-status">


                                                        {{-- SUCCESS --}}
                                                        @if($recordStatus === 'success')

                                                            <span class="badge bg-success">

                                                                <i class="mdi mdi-check-circle"></i>

                                                                Success

                                                            </span>


                                                        {{-- PENDING --}}
                                                        @elseif($recordStatus === 'pending')

                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-warning confirm-payment-btn"
                                                                onclick="confirmBillingPayment({{ $record->id }})"
                                                                title="Confirm {{ $record->payment_mode ?: 'Payment' }}"
                                                            >

                                                                <i class="mdi mdi-check-circle-outline"></i>

                                                                Confirm

                                                            </button>


                                                        {{-- FAILED --}}
                                                        @elseif($recordStatus === 'failed')

                                                            <span class="badge bg-danger">

                                                                <i class="mdi mdi-close-circle"></i>

                                                                Failed

                                                            </span>


                                                        {{-- CANCELLED --}}
                                                        @elseif($recordStatus === 'cancelled')

                                                            <span class="badge bg-secondary">

                                                                <i class="mdi mdi-cancel"></i>

                                                                Cancelled

                                                            </span>


                                                        {{-- REFUNDED --}}
                                                        @elseif($recordStatus === 'refunded')

                                                            <span class="badge bg-info">

                                                                <i class="mdi mdi-cash-refund"></i>

                                                                Refunded

                                                            </span>


                                                        {{-- UNKNOWN --}}
                                                        @else

                                                            <span class="badge bg-dark">

                                                                {{ ucfirst(
                                                                    $record->actual_status ?: 'Unknown'
                                                                ) }}

                                                            </span>

                                                        @endif


                                                    </div>

                                                </div>


                                            @endforeach

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    TOTAL AMOUNT
                                ================================================== --}}
                                <td
                                    class="text-end"
                                    data-order="{{ $payment->total_amount }}"
                                >

                                    <strong class="text-success fs-6">

                                        ₹
                                        {{ number_format(
                                            $payment->total_amount,
                                            2
                                        ) }}

                                    </strong>


                                    @if(
                                        $payment->grouped_payment_records &&
                                        $payment->grouped_payment_records->count() > 1
                                    )

                                        <br>

                                        <small class="text-muted">

                                            {{ $payment->grouped_payment_records->count() }}

                                            payment parts

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                    TRANSACTION / REFERENCE
                                ================================================== --}}
                                <td>

                                    @if(
                                        $payment->transaction_breakdown &&
                                        $payment->transaction_breakdown->count()
                                    )

                                        <div class="transaction-parts">


                                            @foreach(
                                                $payment->transaction_breakdown
                                                as $transaction
                                            )

                                                <div class="transaction-part">

                                                    <div>

                                                        <small class="text-muted">

                                                            {{ $transaction['payment_mode'] }}:

                                                        </small>


                                                        @if(
                                                            !empty(
                                                                $transaction['transaction_id']
                                                            )
                                                        )

                                                            <code>

                                                                {{ $transaction['transaction_id'] }}

                                                            </code>

                                                        @else

                                                            <span class="text-muted">
                                                                —
                                                            </span>

                                                        @endif

                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    OVERALL STATUS
                                ================================================== --}}
                                <td>


                                    @switch(
                                        strtolower(
                                            trim(
                                                (string) $payment->status
                                            )
                                        )
                                    )


                                        {{-- SUCCESS --}}
                                        @case('success')

                                            <span class="badge bg-success">

                                                <i class="mdi mdi-check-circle"></i>

                                                Success

                                            </span>

                                            @break


                                        {{-- PENDING --}}
                                        @case('pending')

                                            <span class="badge bg-warning text-dark">

                                                <i class="mdi mdi-clock-outline"></i>

                                                Pending

                                            </span>

                                            @break


                                        {{-- FAILED --}}
                                        @case('failed')

                                            <span class="badge bg-danger">

                                                <i class="mdi mdi-close-circle"></i>

                                                Failed

                                            </span>

                                            @break


                                        {{-- CANCELLED --}}
                                        @case('cancelled')

                                            <span class="badge bg-secondary">

                                                <i class="mdi mdi-cancel"></i>

                                                Cancelled

                                            </span>

                                            @break


                                        {{-- REFUNDED --}}
                                        @case('refunded')

                                            <span class="badge bg-info">

                                                <i class="mdi mdi-cash-refund"></i>

                                                Refunded

                                            </span>

                                            @break


                                        {{-- DEFAULT --}}
                                        @default

                                            <span class="badge bg-dark">

                                                {{ ucfirst(
                                                    $payment->status ?? 'Unknown'
                                                ) }}

                                            </span>

                                    @endswitch

                                </td>


                                {{-- =================================================
                                    REMARKS
                                ================================================== --}}
                                <td>

                                    @if($payment->combined_remarks)

                                        <span
                                            title="{{ $payment->combined_remarks }}"
                                        >

                                            {{
                                                \Illuminate\Support\Str::limit(
                                                    $payment->combined_remarks,
                                                    40
                                                )
                                            }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- =================================================
                                    ACTION
                                ================================================== --}}
                                <td class="text-center">

                                    <div class="d-flex gap-1 justify-content-center">


                                        {{-- INVOICE --}}
                                        @if($payment->status === 'success')

                                            <a
                                                href="{{ route(
                                                    'billing.invoice',
                                                    $payment->payment_id
                                                ) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-success"
                                                title="View Invoice"
                                            >

                                                <i class="mdi mdi-receipt-text-outline"></i>

                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i
                                            class="mdi mdi-cash-remove"
                                            style="font-size:40px;"
                                        ></i>

                                        <div class="mt-2">

                                            No payment records found.

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    {{-- =========================================================
                        FOOTER TOTAL
                    ========================================================== --}}
                    @if($payments->count())

                        <tfoot class="table-light">

                            <tr>

                                <th
                                    colspan="6"
                                    class="text-end"
                                >
                                    Total Successful Payment:
                                </th>


                                <th class="text-end text-success">

                                    ₹
                                    {{ number_format(
                                        $payments
                                            ->where('status', 'success')
                                            ->sum('total_amount'),
                                        2
                                    ) }}

                                </th>


                                <th colspan="4"></th>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>

</div>

@endsection


{{-- =========================================================
    STYLES
========================================================= --}}
@push('styles')

<style>

    .payment-mode-cell {
        min-width: 220px;
        vertical-align: middle;
    }


    .payment-parts {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }


    .payment-part {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 6px 7px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        background: #fff;
        min-height: 42px;
    }


    .payment-part-info {
        display: flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
    }


    .payment-part-amount {
        font-weight: 600;
        color: #333;
        white-space: nowrap;
    }


    .payment-part-status {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        white-space: nowrap;
    }


    .confirm-payment-btn {
        padding: 4px 8px;
        font-size: 11px;
        line-height: 1.2;
    }


    .payment-part-status .badge {
        font-size: 10px;
        padding: 5px 7px;
    }


    .transaction-parts {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }


    .transaction-part {
        padding: 3px 0;
    }


    .transaction-part code {
        font-size: 12px;
        word-break: break-all;
    }

</style>

@endpush


{{-- =========================================================
    SCRIPTS
========================================================= --}}
@push('scripts')

<script>

$(document).ready(function () {

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
                    targets: [10],
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


/*
|--------------------------------------------------------------------------
| CONFIRM BILLING PAYMENT
|--------------------------------------------------------------------------
*/

function confirmBillingPayment(paymentId)
{
    if (!confirm('Are you sure you want to confirm this payment?')) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Create normal POST form
    |--------------------------------------------------------------------------
    |
    | confirmPayment() controller returns:
    |
    | return back()->with('success', ...)
    |
    | So we should submit a normal POST request instead of expecting JSON.
    |
    */

    let form = document.createElement('form');

    form.method = 'POST';

    form.action =
        "{{ url('/backend/billing/payment') }}/"
        + paymentId
        + "/confirm";

    /*
    |--------------------------------------------------------------------------
    | CSRF TOKEN
    |--------------------------------------------------------------------------
    */

    let csrfInput = document.createElement('input');

    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = "{{ csrf_token() }}";

    form.appendChild(csrfInput);


    /*
    |--------------------------------------------------------------------------
    | Add form to body and submit
    |--------------------------------------------------------------------------
    */

    document.body.appendChild(form);

    form.submit();
}

</script>

@endpush
