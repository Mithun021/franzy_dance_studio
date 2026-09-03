@extends('backend.partial.master')

@section('title', 'Payment Details')

@section('backend-content')

{{-- ================================================================
    ALERTS
================================================================ --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="mdi mdi-check-circle-outline"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show">

        <i class="mdi mdi-alert-circle-outline"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif



{{-- ================================================================
    PAGE HEADER
================================================================ --}}

<div class="card mb-3">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">
            Payment Details
        </h4>


        <a
            href="{{ route('billing.index') }}"
            class="btn btn-secondary"
        >

            <i class="mdi mdi-arrow-left"></i>

            Back to Billing

        </a>

    </div>

</div>



{{-- ================================================================
    STUDENT / COURSE INFORMATION
================================================================ --}}

<div class="card mb-3">

    <div class="card-header">

        <h5 class="mb-0">
            Student & Course Information
        </h5>

    </div>


    <div class="card-body">

        <div class="row g-3">

            {{-- Student --}}
            <div class="col-md-3">

                <small class="text-muted">
                    Student
                </small>

                <h6 class="mb-0">

                    {{ $studentCourse->student->name ?? 'N/A' }}

                </h6>

            </div>


            {{-- Admission --}}
            <div class="col-md-3">

                <small class="text-muted">
                    Admission No
                </small>

                <h6 class="mb-0">

                    {{ $studentCourse->student->user_id ?? 'N/A' }}

                </h6>

            </div>


            {{-- Course --}}
            <div class="col-md-3">

                <small class="text-muted">
                    Course
                </small>

                <h6 class="mb-0">

                    {{ $studentCourse->course->course_name ?? 'N/A' }}

                </h6>

            </div>


            {{-- Batch --}}
            <div class="col-md-3">

                <small class="text-muted">
                    Batch
                </small>

                <h6 class="mb-0">

                    {{ $studentCourse->batch->batch_name ?? 'N/A' }}

                </h6>

            </div>


            {{-- Monthly Fee --}}
            <div class="col-md-3">

                <small class="text-muted">
                    Monthly Fee
                </small>

                <h6 class="mb-0">

                    ₹{{ number_format(
                        (float) $studentCourse->monthly_fee,
                        2
                    ) }}

                </h6>

            </div>


            {{-- Paid Months --}}
            <div class="col-md-3">

                <small class="text-muted">
                    Paid Months
                </small>

                <h6 class="mb-0">

                    @if($paidMonthCount > 0)

                        {{ $paidMonthLabel }}

                        <span class="badge bg-success ms-1">

                            {{ $paidMonthCount }}

                            {{ $paidMonthCount == 1 ? 'Month' : 'Months' }}

                        </span>

                    @else

                        <span class="text-muted">
                            No payment yet
                        </span>

                    @endif

                </h6>

            </div>


            {{-- Registration --}}
            <div class="col-md-3">

                <small class="text-muted">
                    Registration Fee
                </small>

                <h6 class="mb-0">

                    ₹{{ number_format(
                        $registrationFee,
                        2
                    ) }}

                </h6>

            </div>


            {{-- Admission Fee --}}
            <div class="col-md-3">

                <small class="text-muted">
                    Admission Fee
                </small>

                <h6 class="mb-0">

                    ₹{{ number_format(
                        $admissionFee,
                        2
                    ) }}

                </h6>

            </div>

        </div>

    </div>

</div>



{{-- ================================================================
    SUMMARY CARDS
================================================================ --}}

<div class="row g-3 mb-3">

    {{-- Total Billing --}}
    <div class="col-md-3">

        <div class="card border">

            <div class="card-body">

                <small class="text-muted">
                    Total Billing
                </small>

                <h4 class="mb-0 text-primary">

                    ₹{{ number_format(
                        $totalBilling,
                        2
                    ) }}

                </h4>

            </div>

        </div>

    </div>


    {{-- Course Fee --}}
    <div class="col-md-3">

        <div class="card border">

            <div class="card-body">

                <small class="text-muted">
                    Course Fee
                </small>

                <h4 class="mb-0">

                    ₹{{ number_format(
                        $totalCourseFee,
                        2
                    ) }}

                </h4>

            </div>

        </div>

    </div>


    {{-- Total Paid --}}
    <div class="col-md-3">

        <div class="card border">

            <div class="card-body">

                <small class="text-muted">
                    Total Paid
                </small>

                <h4 class="mb-0 text-success">

                    ₹{{ number_format(
                        $totalPaid,
                        2
                    ) }}

                </h4>

            </div>

        </div>

    </div>


    {{-- Due --}}
    <div class="col-md-3">

        <div class="card border">

            <div class="card-body">

                <small class="text-muted">
                    Total Due
                </small>

                <h4 class="mb-0 text-danger">

                    ₹{{ number_format(
                        $totalDue,
                        2
                    ) }}

                </h4>

            </div>

        </div>

    </div>

</div>



{{-- ================================================================
    MONTHLY BILLING
================================================================ --}}

<div class="card mb-3">

    <div class="card-header">

        <h5 class="mb-0">
            Monthly Billing Details
        </h5>

    </div>


    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>#</th>

                        <th>Fee Month</th>

                        <th>Monthly Fee</th>

                        <th>Payable</th>

                        <th>Paid</th>

                        <th>Due</th>

                        <th>Due Date</th>

                        <th>Payment %</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($monthRecords as $monthRecord)

                        @php

                            $payable = (float) $monthRecord->payable_amount;

                            $paid = (float) $monthRecord->paid_amount;

                            $due = max(
                                0,
                                $payable - $paid
                            );

                        @endphp

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>

                                <strong>

                                    {{ \Carbon\Carbon::parse(
                                        $monthRecord->fee_month
                                    )->format('F Y') }}

                                </strong>

                                @if($monthRecord->payment_rule)

                                    <br>

                                    <small class="text-muted">

                                        {{ $monthRecord->payment_rule }}

                                    </small>

                                @endif

                            </td>


                            <td>

                                ₹{{ number_format(
                                    (float) $monthRecord->monthly_fee,
                                    2
                                ) }}

                            </td>


                            <td>

                                ₹{{ number_format(
                                    $payable,
                                    2
                                ) }}

                            </td>


                            <td class="text-success">

                                <strong>

                                    ₹{{ number_format(
                                        $paid,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            <td class="text-danger">

                                <strong>

                                    ₹{{ number_format(
                                        $due,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            <td>

                                {{ $monthRecord->due_date
                                    ? \Carbon\Carbon::parse(
                                        $monthRecord->due_date
                                    )->format('d M Y')
                                    : '-'
                                }}

                            </td>


                            <td>

                                <div
                                    class="progress"
                                    style="height: 8px;"
                                >

                                    <div
                                        class="progress-bar"
                                        role="progressbar"
                                        style="width: {{ min(100, (float) $monthRecord->payment_percentage) }}%;"
                                    ></div>

                                </div>

                                <small>

                                    {{ number_format(
                                        (float) $monthRecord->payment_percentage,
                                        2
                                    ) }}%

                                </small>

                            </td>


                            <td>

                                @if($monthRecord->status === 'paid')

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @elseif($monthRecord->status === 'partial')

                                    <span class="badge bg-warning">
                                        Partial
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Unpaid
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center text-muted"
                            >

                                No monthly billing records found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>



{{-- ================================================================
    PAYMENT HISTORY
================================================================ --}}

<div class="card mb-3">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Payment History
        </h5>


        <span class="badge bg-success">

            {{ $payments->count() }}

            {{ $payments->count() == 1 ? 'Payment' : 'Payments' }}

        </span>

    </div>


    <div class="card-body">

        <div class="table-responsive">

            <table
                class="table table-bordered table-hover align-middle"
            >

                <thead class="table-dark">

                    <tr>

                        <th>#</th>

                        <th>Payment Date</th>

                        <th>Payment Mode</th>

                        <th>Amount</th>

                        <th>Transaction / Reference</th>

                        <th>Status</th>

                        <th>Remarks</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($payments as $payment)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            {{-- Payment Date --}}
                            <td>

                                {{ $payment->payment_date
                                    ? \Carbon\Carbon::parse(
                                        $payment->payment_date
                                    )->format('d M Y')
                                    : '-'
                                }}

                            </td>


                            {{-- Mode --}}
                            <td>

                                @if($payment->payment_mode === 'Cash')

                                    <span class="badge bg-success">
                                        Cash
                                    </span>

                                @elseif($payment->payment_mode === 'UPI')

                                    <span class="badge bg-primary">
                                        UPI
                                    </span>

                                @elseif($payment->payment_mode === 'Card')

                                    <span class="badge bg-info">
                                        Card
                                    </span>

                                @elseif($payment->payment_mode === 'Bank Transfer')

                                    <span class="badge bg-dark">
                                        Bank Transfer
                                    </span>

                                @elseif($payment->payment_mode === 'Cheque')

                                    <span class="badge bg-warning">
                                        Cheque
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $payment->payment_mode }}
                                    </span>

                                @endif

                            </td>


                            {{-- Amount --}}
                            <td>

                                <strong class="text-success">

                                    ₹{{ number_format(
                                        (float) $payment->amount,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            {{-- Transaction --}}
                            <td>

                                @if($payment->transaction_id)

                                    <code>

                                        {{ $payment->transaction_id }}

                                    </code>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($payment->status === 'success')

                                    <span class="badge bg-success">

                                        Success

                                    </span>

                                @elseif($payment->status === 'pending')

                                    <span class="badge bg-warning">

                                        Pending

                                    </span>

                                @elseif($payment->status === 'failed')

                                    <span class="badge bg-danger">

                                        Failed

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        {{ ucfirst(
                                            $payment->status
                                        ) }}

                                    </span>

                                @endif

                            </td>


                            {{-- Remarks --}}
                            <td>

                                {{ $payment->remarks ?: '-' }}

                            </td>


                            {{-- Action --}}
                            <td>

                                 @if($payment->status !== 'success')

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-warning"
                                        onclick="confirmBillingPayment({{ $payment->id }})"
                                        title="Confirm Payment"
                                    >

                                        <i class="mdi mdi-check-circle-outline"></i>

                                        Confirm

                                    </button>

                                @endif

                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="deleteBillingPayment(
                                        {{ $payment->id }}
                                    )"
                                    title="Delete Payment"
                                >

                                    <i class="mdi mdi-delete"></i>

                                    Delete

                                </button>

                                {{-- Invoice --}}
                                <a
                                    href="{{ route('billing.invoice', $payment->id) }}"
                                    class="btn btn-sm btn-success"
                                    title="View Invoice"
                                >
                                    <i class="mdi mdi-receipt-text-outline"></i>
                                    Invoice
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center text-muted py-4"
                            >

                                No payment history found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>


                @if($payments->isNotEmpty())

                    <tfoot>

                        <tr class="table-light">

                            <th colspan="3" class="text-end">

                                Total Paid:

                            </th>

                            <th class="text-success">

                                ₹{{ number_format(
                                    $totalPaid,
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



{{-- ================================================================
    FINE / PENALTY HISTORY
================================================================ --}}

@if($lateFines->isNotEmpty())

    <div class="card mb-3">

        <div class="card-header">

            <h5 class="mb-0">
                Late Fine / Penalty History
            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Date</th>

                            <th>Due Date</th>

                            <th>Amount</th>

                            <th>Paid</th>

                            <th>Status</th>

                            <th>Remarks</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($lateFines as $fine)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    {{ $fine->fine_date
                                        ? \Carbon\Carbon::parse(
                                            $fine->fine_date
                                        )->format('d M Y')
                                        : '-'
                                    }}

                                </td>


                                <td>

                                    {{ $fine->due_date
                                        ? \Carbon\Carbon::parse(
                                            $fine->due_date
                                        )->format('d M Y')
                                        : '-'
                                    }}

                                </td>


                                <td>

                                    ₹{{ number_format(
                                        (float) $fine->fine_amount,
                                        2
                                    ) }}

                                </td>


                                <td class="text-success">

                                    ₹{{ number_format(
                                        (float) $fine->paid_amount,
                                        2
                                    ) }}

                                </td>


                                <td>

                                    @if($fine->status === 'paid')

                                        <span class="badge bg-success">
                                            Paid
                                        </span>

                                    @elseif($fine->status === 'partial')

                                        <span class="badge bg-warning">
                                            Partial
                                        </span>

                                    @elseif($fine->status === 'waived')

                                        <span class="badge bg-info">
                                            Waived
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Unpaid
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ $fine->remarks ?: '-' }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endif



{{-- ================================================================
    DELETE PAYMENT FORM
================================================================ --}}

<form
    id="deletePaymentForm"
    method="POST"
    style="display:none;"
>

    @csrf

    @method('DELETE')

</form>



{{-- ================================================================
    DELETE SCRIPT
================================================================ --}}

<script>

    function deleteBillingPayment(paymentId)
    {
        if (!paymentId) {
            return;
        }


        const confirmed = confirm(
            'Are you sure you want to delete this payment?\n\n' +
            'The monthly billing amounts will be recalculated after deletion.'
        );


        if (!confirmed) {
            return;
        }


        const form =
            document.getElementById(
                'deletePaymentForm'
            );


        form.action =
            "{{ url('backend/billing/payment-delete') }}/"
            + paymentId;


        form.submit();
    }

</script>

@endsection

@push('scripts')

<script>

function confirmBillingPayment(paymentId)
{
    if (!paymentId) {
        return;
    }


    const confirmed = confirm(
        'Are you sure you want to confirm this payment?\n\n' +
        'The payment status will be changed to SUCCESS.'
    );


    if (!confirmed) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Create POST Form
    |--------------------------------------------------------------------------
    */

    const form = document.createElement('form');

    form.method = 'POST';

    form.action =
        "{{ url('/backend/billing/payment') }}/" +
        paymentId +
        "/confirm";


    /*
    |--------------------------------------------------------------------------
    | CSRF Token
    |--------------------------------------------------------------------------
    */

    const csrf = document.createElement('input');

    csrf.type = 'hidden';

    csrf.name = '_token';

    csrf.value =
        "{{ csrf_token() }}";

    form.appendChild(csrf);


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    document.body.appendChild(form);

    form.submit();
}

</script>

@endpush
