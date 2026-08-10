@extends('backend.partial.master')

@section('title','Billing List')

@section('backend-content')

<div class="card-header d-flex justify-content-between align-items-center">

    <h4 class="mb-0">
        Billing List
    </h4>

    <a href="{{ route('billing.create') }}"
       class="btn btn-primary">

        <i class="mdi mdi-plus"></i>

        New Billing

    </a>

</div>

<div class="card-body">

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle" id="datatable-buttons">

            <thead class="table-dark">

            <tr>

                <th>#</th>

                <th>Student</th>

                <th>Course</th>

                {{-- <th>Duration</th> --}}

                <th>Batch</th>

                <th>Course Amount</th>

                <th>Monthly Fee</th>

                <th>Total Received</th>

                <th>Due Amount</th>

                <th>Payment History</th>

                <th>Status</th>

                <th width="120" id="no-export">Action</th>

            </tr>

            </thead>

            <tbody>

            @forelse($billings as $key=>$row)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | All Payments
                    |--------------------------------------------------------------------------
                    */

                    $payments = \App\Models\StudentPayment::where(
                        'student_course_id',
                        $row->id
                    )
                    ->orderBy('payment_date')
                    ->get();


                    /*
                    |--------------------------------------------------------------------------
                    | Total Received
                    |--------------------------------------------------------------------------
                    | Only SUCCESS payments will be counted
                    |--------------------------------------------------------------------------
                    */

                    $received = \App\Models\StudentPayment::where(
                        'student_course_id',
                        $row->id
                    )
                    ->where('status', 'success')
                    ->sum('amount');


                    /*
                    |--------------------------------------------------------------------------
                    | Due Amount
                    |--------------------------------------------------------------------------
                    */

                    $due = $row->grand_total - $received;

                @endphp

                <tr>

                    <td>

                        {{ $key+1 }}

                    </td>

                    <td>

                        <strong>

                            {{ $row->student->name }}

                        </strong>

                        <br>

                        <small>

                            {{ $row->admission_no }}

                        </small>

                    </td>

                    <td>

                        <p class="m-0">
                            {{ $row->course->course_name }}
                        </p>

                        <small class="text-primary">

                            {{ $row->course_duration }}

                            {{ $row->duration_type }}

                        </small>

                    </td>

                    <td>

                        {{ $row->batch->batch_name ?? '-' }}

                    </td>

                    <td>

                        ₹ {{ number_format($row->grand_total,2) }}

                    </td>

                    <td>

                        ₹ {{ number_format($row->course_fee,2) }}

                    </td>

                    {{-- ================================================= --}}
                    {{-- Total Received - SUCCESS ONLY --}}
                    {{-- ================================================= --}}

                    <td class="text-success fw-bold">

                        ₹ {{ number_format($received,2) }}

                    </td>

                    {{-- ================================================= --}}
                    {{-- Due Amount --}}
                    {{-- ================================================= --}}

                    <td>

                        @if($due <= 0)

                            <span class="badge bg-success">

                                Complete

                            </span>

                        @else

                            <span class="badge bg-danger">

                                ₹ {{ number_format($due,2) }}

                            </span>

                        @endif

                    </td>

                    {{-- ================================================= --}}
                    {{-- Payment History --}}
                    {{-- ================================================= --}}

                    <td>

                        @if($payments->count())

                            <table class="table table-sm table-bordered mb-0">

                                <thead>

                                <tr>

                                    <th>Date</th>

                                    <th>Mode</th>

                                    <th>Amount</th>

                                    <th>Status</th>

                                    <th>Inv</th>

                                </tr>

                                </thead>

                                <tbody>

                                @foreach($payments as $payment)

                                    <tr>

                                        {{-- Date --}}

                                        <td>

                                            {{ $payment->payment_date->format('d M Y') }}

                                        </td>


                                        {{-- Payment Mode --}}

                                        <td>

                                            {{ $payment->payment_mode }}

                                        </td>


                                        {{-- Amount --}}

                                        <td>

                                            ₹ {{ number_format($payment->amount,2) }}

                                        </td>


                                        {{-- Status --}}

                                        <td>

                                            @if($payment->status === 'success')

                                                <span class="badge bg-success">

                                                    Success

                                                </span>

                                            @elseif($payment->status === 'pending')

                                                <span class="badge bg-warning text-dark">

                                                    Pending

                                                </span>

                                            @elseif($payment->status === 'failed')

                                                <span class="badge bg-danger">

                                                    Failed

                                                </span>

                                            @elseif($payment->status === 'cancelled')

                                                <span class="badge bg-secondary">

                                                    Cancelled

                                                </span>

                                            @elseif($payment->status === 'refunded')

                                                <span class="badge bg-info">

                                                    Refunded

                                                </span>

                                            @else

                                                <span class="badge bg-dark">

                                                    {{ ucfirst($payment->status) }}

                                                </span>

                                            @endif

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

                                                <span class="text-muted">-</span>

                                            @endif

                                        </td>


                                        {{-- Invoice --}}
                                        {{-- Invoice ONLY for SUCCESS payment --}}

                                        <td class="text-center">

                                            @if($payment->status === 'success')

                                                <a
                                                    href="{{ route('billing.invoice',$payment->id) }}"
                                                    class="btn btn-sm btn-primary"
                                                    target="_blank"
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

                                @endforeach

                                </tbody>

                            </table>

                        @else

                            -

                        @endif

                    </td>

                    {{-- ================================================= --}}
                    {{-- Overall Billing Status --}}
                    {{-- ================================================= --}}

                    <td>

                        @if($due <= 0)

                            <span class="badge bg-success">

                                Completed

                            </span>

                        @else

                            <span class="badge bg-warning">

                                Pending

                            </span>

                        @endif

                    </td>

                    {{-- ================================================= --}}
                    {{-- Action --}}
                    {{-- ================================================= --}}

                    <td>

                        @if($due > 0)

                            <a
                                href="{{ route('billing.manage',$row->id) }}"
                                class="btn btn-primary btn-sm">

                                <i class="fa fa-credit-card"></i>

                                Manage Payment

                            </a>

                        @else

                            <button
                                class="btn btn-success btn-sm"
                                disabled>

                                Paid

                            </button>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="12"
                        class="text-center">

                        No Billing Found

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
