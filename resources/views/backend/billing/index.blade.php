@extends('backend.partial.master')

@section('title', 'Billing List')

@section('backend-content')

<div class="card">

    {{-- ============================================================
        HEADER
    ============================================================= --}}
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


    {{-- ============================================================
        BODY
    ============================================================= --}}
    <div class="card-body">

        <div class="table-responsive">

            <table
                class="table table-bordered table-hover align-middle"
                id="datatable-buttons"
            >

                <thead class="table-dark">

                    <tr>

                        <th>#</th>

                        <th>Student</th>

                        <th>Course</th>

                        <th>Batch</th>

                        <th>Course Amount</th>

                        <th>Payment All Months</th>

                        <th>Payment History</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($studentCourses as $studentCourse)

                        <tr>

                            {{-- ====================================================
                                #
                            ===================================================== --}}
                            <td>
                                {{ $loop->iteration }}
                            </td>


                            {{-- ====================================================
                                STUDENT
                            ===================================================== --}}
                            <td>

                                <strong>
                                    {{ $studentCourse->student->name ?? 'N/A' }}
                                </strong>

                                <br>

                                <small class="text-muted">

                                    Admission No:
                                    {{ $studentCourse->student->admission_no ?? 'N/A' }}

                                </small>

                            </td>


                            {{-- ====================================================
                                COURSE
                            ===================================================== --}}
                            <td>

                                {{ $studentCourse->course->course_name ?? 'N/A' }}

                            </td>


                            {{-- ====================================================
                                BATCH
                            ===================================================== --}}
                            <td>

                                {{ $studentCourse->batch->batch_name ?? 'N/A' }}

                            </td>


                            {{-- ====================================================
                                COURSE AMOUNT
                            ===================================================== --}}
                            <td>

                                <strong class="text-primary">

                                    ₹{{ number_format(
                                        (float) $studentCourse->monthly_fee,
                                        2
                                    ) }}

                                </strong>

                                <br>

                                <small class="text-muted">
                                    Monthly Fee
                                </small>

                            </td>


                            {{-- ====================================================
                                PAYMENT ALL MONTHS
                            ===================================================== --}}
                            <td>

                                @if($studentCourse->paid_month_count > 0)

                                    <strong>

                                        {{ $studentCourse->paid_month_label }}

                                    </strong>

                                    <br>

                                    <span class="badge bg-success">

                                        {{ $studentCourse->paid_month_count }}

                                        {{ $studentCourse->paid_month_count == 1 ? 'Month' : 'Months' }}

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        No Payment Yet

                                    </span>

                                @endif

                            </td>


                            {{-- ====================================================
                                PAYMENT HISTORY
                            ===================================================== --}}
                            <td>

                                <strong class="text-success">

                                    ₹{{ number_format(
                                        (float) ($studentCourse->total_paid_amount ?? 0),
                                        2
                                    ) }}

                                </strong>

                                <br>

                                <small class="text-muted">

                                    {{ $studentCourse->payment_count ?? 0 }}

                                    {{ ($studentCourse->payment_count ?? 0) == 1
                                        ? 'Payment'
                                        : 'Payments'
                                    }}

                                </small>

                            </td>


                            {{-- ====================================================
                                ACTION
                            ===================================================== --}}

                            <td>

                                <div class="d-flex gap-2 flex-wrap">


                                    {{-- ====================================================
                                        VIEW PAYMENT HISTORY
                                    ===================================================== --}}

                                    <a
                                        href="{{ route(
                                            'billing.payments',
                                            $studentCourse->id
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                        title="View Payment History"
                                    >

                                        <i class="mdi mdi-eye"></i>

                                        View

                                    </a>



                                    {{-- ====================================================
                                        OVERALL INVOICE
                                    ===================================================== --}}

                                    @php

                                        $latestPayment =
                                            $studentCourse
                                                ->paymentRecords
                                                ->first();

                                    @endphp


                                    @if($latestPayment)

                                        <a
                                            href="{{ route(
                                                'billing.overall-invoice',
                                                $latestPayment->id
                                            ) }}"
                                            class="btn btn-sm btn-success"
                                            target="_blank"
                                            title="Overall Payment Invoice"
                                        >

                                            <i class="mdi mdi-file-document-outline"></i>

                                            Invoice

                                        </a>

                                    @endif



                                    {{-- ====================================================
                                        DELETE PAYMENT
                                    ===================================================== --}}

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="deletePayment(
                                            {{ $studentCourse->id }}
                                        )"
                                        title="Delete Payment"
                                    >

                                        <i class="mdi mdi-delete"></i>

                                        Delete

                                    </button>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center text-muted py-4"
                            >

                                <i class="mdi mdi-information-outline"></i>

                                No billing records found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

function deletePayment(studentCourseId)
{
    if (!studentCourseId) {
        return;
    }


    if (
        !confirm(
            'WARNING!\n\n' +
            'This will permanently delete ALL billing/payment history for this student.\n\n' +
            'This includes:\n' +
            '• Payment Records\n' +
            '• Monthly Billing Records\n' +
            '• Late Fine / Penalty Records\n\n' +
            'The student and course enrollment will NOT be deleted.\n\n' +
            'Are you sure you want to continue?'
        )
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Create DELETE Form
    |--------------------------------------------------------------------------
    */

    const form = document.createElement('form');

    form.method = 'POST';

    form.action =
        "{{ url('/backend/billing/destroy') }}/" +
        studentCourseId;


    /*
    |--------------------------------------------------------------------------
    | CSRF
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
    | DELETE Method
    |--------------------------------------------------------------------------
    */

    const method = document.createElement('input');

    method.type = 'hidden';

    method.name = '_method';

    method.value = 'DELETE';

    form.appendChild(method);


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
