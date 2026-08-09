@extends('backend.partial.master')

@section('title', 'Edit Salary')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Edit Salary</h4>

                <a href="{{ route('salary-management.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>

            </div>

            <form action="{{ route('salary-management.update',$salary->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">

                        {{-- Employee --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Employee / Staff
                                <span class="text-danger">*</span>
                            </label>

                            <select name="user_id"
                                    id="user_id"
                                    class="form-control">

                                <option value="">Select Employee</option>

                                @foreach($employees as $employee)

                                    <option
                                        value="{{ $employee->id }}"
                                        data-monthly-salary="{{ $employee->monthly_salary }}"
                                        {{ old('user_id',$salary->user_id)==$employee->id ? 'selected' : '' }}>

                                        {{ $employee->user_id }}
                                        -
                                        {{ $employee->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- Salary Month --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Salary Month
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="month"
                                name="salary_month"
                                class="form-control"
                                value="{{ old('salary_month', \Carbon\Carbon::parse($salary->salary_month)->format('Y-m')) }}">

                        </div>

                        {{-- Salary Amount --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Salary Amount
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                id="salary_amount"
                                name="salary_amount"
                                class="form-control"
                                value="{{ old('salary_amount',$salary->salary_amount) }}">

                        </div>

                        {{-- Paid Amount --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Paid Amount
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                id="paid_amount"
                                name="paid_amount"
                                class="form-control"
                                value="{{ old('paid_amount',$salary->paid_amount) }}">

                        </div>

                        {{-- Due Amount --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Due Amount
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                id="due_amount"
                                class="form-control bg-light"
                                value="{{ old('due_amount',$salary->due_amount) }}"
                                readonly>

                        </div>

                        {{-- Payment Method --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Payment Method
                            </label>

                            <select name="payment_method"
                                    class="form-control">

                                <option value="">Select</option>

                                <option value="Cash"
                                    {{ old('payment_method',$salary->payment_method)=='Cash'?'selected':'' }}>
                                    Cash
                                </option>

                                <option value="UPI"
                                    {{ old('payment_method',$salary->payment_method)=='UPI'?'selected':'' }}>
                                    UPI
                                </option>

                                <option value="Bank Transfer"
                                    {{ old('payment_method',$salary->payment_method)=='Bank Transfer'?'selected':'' }}>
                                    Bank Transfer
                                </option>

                                <option value="Cheque"
                                    {{ old('payment_method',$salary->payment_method)=='Cheque'?'selected':'' }}>
                                    Cheque
                                </option>

                            </select>

                        </div>

                        {{-- Description --}}

                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                rows="4"
                                name="description"
                                class="form-control">{{ old('description',$salary->description) }}</textarea>

                        </div>

                    </div>

                </div>

                <div class="card-footer text-end">

                    <a href="{{ route('salary-management.index') }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                    <button class="btn btn-primary">

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

    function calculateDue() {

        let salary = parseFloat($("#salary_amount").val()) || 0;

        let paid = parseFloat($("#paid_amount").val()) || 0;

        if (paid > salary) {

            paid = salary;

            $("#paid_amount").val(salary.toFixed(2));

        }

        let due = salary - paid;

        $("#due_amount").val(due.toFixed(2));

    }

    /*
    |--------------------------------------------------------------------------
    | Employee Changed
    |--------------------------------------------------------------------------
    */

    $("#user_id").on("change", function () {

        let monthlySalary = $(this).find(":selected").data("monthly-salary");

        if(monthlySalary){

            $("#salary_amount").val(monthlySalary);

        }

        calculateDue();

    });

    /*
    |--------------------------------------------------------------------------
    | Recalculate
    |--------------------------------------------------------------------------
    */

    $("#salary_amount,#paid_amount").on("keyup change", function(){

        calculateDue();

    });

    /*
    |--------------------------------------------------------------------------
    | Initial Calculation
    |--------------------------------------------------------------------------
    */

    calculateDue();

});

</script>

@endpush
