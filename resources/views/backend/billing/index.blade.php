@extends('backend.partial.master')

@section('title','Billing List')

@section('backend-content')

<style>
    tr th, tr td{
        font-size: 12px;
    }
    table table tr th, table table tr td{
        font-size: 10px;
    }
</style>

<div class="container-fluid">

<div class="card">

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

                        $received = \App\Models\StudentPayment::where(
                            'student_course_id',
                            $row->id
                        )->sum('amount');

                        $due = $row->grand_total - $received;

                        $payments = \App\Models\StudentPayment::where(
                            'student_course_id',
                            $row->id
                        )->orderBy('payment_date')
                        ->get();

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

                            <p class="m-0">{{ $row->course->course_name }}</p>

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

                        <td class="text-success fw-bold">

                            ₹ {{ number_format($received,2) }}

                        </td>

                        <td>

                            @if($due<=0)

                                <span class="badge bg-success">

                                    Complete

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    ₹ {{ number_format($due,2) }}

                                </span>

                            @endif

                        </td>

                        <td>

                            @if($payments->count())

                                <table class="table table-sm table-bordered mb-0">

                                    <thead>

                                    <tr>

                                        <th>Date</th>

                                        <th>Mode</th>

                                        <th>Amount</th>
                                        <th>Inv</th>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    @foreach($payments as $payment)

                                        <tr>

                                            <td>

                                                {{ $payment->payment_date->format('d M Y') }}

                                            </td>

                                            <td>

                                                {{ $payment->payment_mode }}

                                            </td>

                                            <td>

                                                ₹ {{ number_format($payment->amount,2) }}

                                            </td>
                                            <td class="text-center">

                                                <a href="{{ route('billing.invoice',$payment->id) }}"
                                                class="btn btn-sm btn-primary"
                                                target="_blank"
                                                title="View Invoice">

                                                    <i class="mdi mdi-file-document"></i>

                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                    </tbody>

                                </table>

                            @else

                                -

                            @endif

                        </td>

                        <td>

                            @if($due<=0)

                                <span class="badge bg-success">

                                    Completed

                                </span>

                            @else

                                <span class="badge bg-warning">

                                    Pending

                                </span>

                            @endif

                        </td>

                        <td>

                            @if($due>0)

                                <a href="{{ route('billing.manage',$row->id) }}"
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

                        <td colspan="12"
                            class="text-center">

                            No Billing Found

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
