@extends('backend.partial.master')

@section('title','Edit Salary')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow-sm">

            {{-- =====================================================
                HEADER
            ====================================================== --}}

            <div class="card-header d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1">
                        Edit Salary
                    </h4>

                    <small class="text-muted">
                        Update salary payment details
                    </small>

                </div>

                <a
                    href="{{ route('salary-management.index') }}"
                    class="btn btn-secondary">

                    <i class="fa fa-arrow-left"></i>

                    Back

                </a>

            </div>


            {{-- =====================================================
                FORM
            ====================================================== --}}

            <form
                action="{{ route('salary-management.update', $salary->id) }}"
                method="POST"
                id="salaryForm">

                @csrf

                @method('PUT')


                <div class="card-body">


                    {{-- =====================================================
                        ALERTS
                    ====================================================== --}}

                    @if($errors->any())

                        <div class="alert alert-danger alert-dismissible fade show">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert">
                            </button>

                        </div>

                    @endif


                    @if(session('error'))

                        <div class="alert alert-danger alert-dismissible fade show">

                            {{ session('error') }}

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert">
                            </button>

                        </div>

                    @endif


                    {{-- =====================================================
                        SALARY INFORMATION
                    ====================================================== --}}

                    <div class="row">


                        {{-- Salary ID --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Salary ID
                            </label>

                            <input
                                type="text"
                                class="form-control fw-bold"
                                value="{{ $salary->salary_id }}"
                                readonly>

                        </div>


                        {{-- Employee --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Employee / Staff
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ optional($salary->user)->user_id }} - {{ optional($salary->user)->name }}"
                                readonly>

                        </div>


                        {{-- Salary Month --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Salary Month
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $salary->salary_month ? \Carbon\Carbon::parse($salary->salary_month)->format('F Y') : '-' }}"
                                readonly>

                        </div>

                    </div>


                    {{-- =====================================================
                        SALARY SUMMARY
                    ====================================================== --}}

                    <div class="row">


                        {{-- Assigned Students --}}

                        <div class="col-xl-3 col-md-6 mb-4">

                            <div class="card border-0 bg-primary text-white shadow-sm">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <small>
                                                Assigned Students
                                            </small>

                                            <h3 class="mb-0 mt-2">

                                                {{ number_format((int) $salary->assigned_student) }}

                                            </h3>

                                        </div>

                                        <i class="fa fa-users fa-2x opacity-50"></i>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Payment Before Calculation --}}

                        <div class="col-xl-3 col-md-6 mb-4">

                            <div class="card border-0 bg-info text-white shadow-sm">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <small>
                                                Payment Before Calculation
                                            </small>

                                            <h3 class="mb-0 mt-2">

                                                ₹
                                                {{ number_format((float) $salary->payment_before_calculate, 2) }}

                                            </h3>

                                        </div>

                                        <i class="fa fa-money fa-2x opacity-50"></i>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Total Earning --}}

                        <div class="col-xl-3 col-md-6 mb-4">

                            <div class="card border-0 bg-success text-white shadow-sm">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <small>
                                                Total Earning Amount
                                            </small>

                                            <h3 class="mb-0 mt-2">

                                                ₹
                                                {{ number_format((float) $salary->salary_amount, 2) }}

                                            </h3>

                                        </div>

                                        <i class="fa fa-line-chart fa-2x opacity-50"></i>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Divided Percentage --}}

                        <div class="col-xl-3 col-md-6 mb-4">

                            <div class="card border-0 bg-warning text-dark shadow-sm">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <small>
                                                Divided Percentage
                                            </small>

                                            <h3 class="mb-0 mt-2">

                                                {{ number_format((float) $salary->divided_percentage, 2) }}%

                                            </h3>

                                        </div>

                                        <i class="fa fa-percent fa-2x opacity-50"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                        SALARY CALCULATION SNAPSHOT
                    ====================================================== --}}

                    <div class="card border-primary mb-4">

                        <div class="card-header bg-light">

                            <h5 class="mb-0">

                                <i class="fa fa-database text-primary"></i>

                                Salary Calculation Snapshot

                            </h5>

                            <small class="text-muted">

                                These values were captured when this salary
                                was originally calculated.

                            </small>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                {{-- Assigned Students --}}

                                <div class="col-md-4">

                                    <div class="border rounded p-3">

                                        <small class="text-muted">
                                            Assigned Students
                                        </small>

                                        <h4 class="mb-0">

                                            {{ number_format((int) $salary->assigned_student) }}

                                        </h4>

                                    </div>

                                </div>


                                {{-- Payment Before Calculation --}}

                                <div class="col-md-4">

                                    <div class="border rounded p-3">

                                        <small class="text-muted">
                                            Payment Before Calculation
                                        </small>

                                        <h4 class="mb-0">

                                            ₹
                                            {{ number_format((float) $salary->payment_before_calculate, 2) }}

                                        </h4>

                                    </div>

                                </div>


                                {{-- Divided Percentage --}}

                                <div class="col-md-4">

                                    <div class="border rounded p-3">

                                        <small class="text-muted">
                                            Divided Percentage
                                        </small>

                                        <h4 class="mb-0">

                                            {{ number_format((float) $salary->divided_percentage, 2) }}%

                                        </h4>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                        SALARY PAYMENT
                    ====================================================== --}}

                    <div class="card mt-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                <i class="fa fa-credit-card"></i>

                                Salary Payment

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                {{-- Total Earning --}}

                                <div class="col-md-4 mb-3">

                                    <label class="form-label fw-semibold">

                                        Total Earning

                                    </label>

                                    <input
                                        type="text"
                                        id="display_salary_amount"
                                        class="form-control fw-bold"
                                        value="₹ {{ number_format((float) $salary->salary_amount, 2) }}"
                                        readonly>

                                </div>


                                {{-- Paid Amount --}}

                                <div class="col-md-4 mb-3">

                                    <label class="form-label fw-semibold">

                                        Paid Amount

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="{{ number_format((float) $salary->salary_amount, 2, '.', '') }}"
                                        name="paid_amount"
                                        id="paid_amount"
                                        class="form-control"
                                        value="{{ old('paid_amount', $salary->paid_amount ?? 0) }}"
                                        required>

                                </div>


                                {{-- Due Amount --}}

                                <div class="col-md-4 mb-3">

                                    <label class="form-label fw-semibold">

                                        Due Amount

                                    </label>

                                    <input
                                        type="text"
                                        id="due_amount"
                                        class="form-control fw-bold text-danger"
                                        value="₹ {{ number_format((float) $salary->due_amount, 2) }}"
                                        readonly>

                                </div>


                                {{-- Payment Method --}}

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">

                                        Payment Method

                                    </label>

                                    <select
                                        name="payment_method"
                                        class="form-control">

                                        <option value="">
                                            Select Payment Method
                                        </option>

                                        <option
                                            value="Cash"
                                            {{ old('payment_method', $salary->payment_method) == 'Cash' ? 'selected' : '' }}>

                                            Cash

                                        </option>

                                        <option
                                            value="UPI"
                                            {{ old('payment_method', $salary->payment_method) == 'UPI' ? 'selected' : '' }}>

                                            UPI

                                        </option>

                                        <option
                                            value="Bank Transfer"
                                            {{ old('payment_method', $salary->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>

                                            Bank Transfer

                                        </option>

                                        <option
                                            value="Cheque"
                                            {{ old('payment_method', $salary->payment_method) == 'Cheque' ? 'selected' : '' }}>

                                            Cheque

                                        </option>

                                    </select>

                                </div>


                                {{-- Description --}}

                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">

                                        Description

                                    </label>

                                    <textarea
                                        name="description"
                                        rows="2"
                                        class="form-control"
                                        placeholder="Optional remarks...">{{ old('description', $salary->description) }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                        IMPORTANT INFORMATION
                    ====================================================== --}}

                    <div class="alert alert-info mt-4 mb-0">

                        <i class="fa fa-info-circle"></i>

                        <strong>Salary Ledger Protection:</strong>

                        Assigned students, payment before calculation,
                        divided percentage and total earning are historical
                        snapshot values. Editing this salary only changes
                        the payment details.

                    </div>

                </div>


                {{-- =====================================================
                    FOOTER
                ====================================================== --}}

                <div class="card-footer d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('salary-management.index') }}"
                        class="btn btn-secondary">

                        <i class="fa fa-times"></i>

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fa fa-save"></i>

                        Update Salary

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Number Helper
    |--------------------------------------------------------------------------
    */

    function number(value)
    {
        let result = parseFloat(value);

        return isNaN(result) ? 0 : result;
    }


    /*
    |--------------------------------------------------------------------------
    | Money Helper
    |--------------------------------------------------------------------------
    */

    function money(value)
    {
        return number(value).toLocaleString(
            'en-IN',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Salary Amount
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Salary amount comes from database snapshot.
    |
    */

    const salaryAmount =
        number(
            "{{ (float) $salary->salary_amount }}"
        );


    /*
    |--------------------------------------------------------------------------
    | Calculate Due
    |--------------------------------------------------------------------------
    */

    function calculateDue()
    {

        let paid =
            number(
                $('#paid_amount').val()
            );


        /*
        |----------------------------------------------------------------------
        | Negative Protection
        |----------------------------------------------------------------------
        */

        if (paid < 0) {

            paid = 0;

            $('#paid_amount').val('0');

        }


        /*
        |----------------------------------------------------------------------
        | Over Payment Protection
        |----------------------------------------------------------------------
        */

        if (paid > salaryAmount) {

            paid = salaryAmount;

            $('#paid_amount').val(
                salaryAmount.toFixed(2)
            );

        }


        /*
        |----------------------------------------------------------------------
        | Due
        |----------------------------------------------------------------------
        */

        let due =
            salaryAmount - paid;


        if (due < 0) {

            due = 0;

        }


        $('#due_amount').val(
            '₹ ' + money(due)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Paid Amount Change
    |--------------------------------------------------------------------------
    */

    $('#paid_amount').on(
        'input change keyup',
        function () {

            calculateDue();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Calculation
    |--------------------------------------------------------------------------
    */

    calculateDue();


    /*
    |--------------------------------------------------------------------------
    | Form Submit Protection
    |--------------------------------------------------------------------------
    */

    $('#salaryForm').on(
        'submit',
        function (e) {

            let paid =
                number(
                    $('#paid_amount').val()
                );


            if (paid < 0) {

                e.preventDefault();

                alert(
                    'Paid amount cannot be negative.'
                );

                return false;

            }


            if (paid > salaryAmount) {

                e.preventDefault();

                alert(
                    'Paid amount cannot be greater than total earning amount.'
                );

                return false;

            }

        }
    );

});

</script>

@endpush
