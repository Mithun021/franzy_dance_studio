@extends('backend.partial.master')

@section('title','Add Salary')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Add Salary</h4>

                <a href="{{ route('salary-management.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>

            </div>

            <form action="{{ route('salary-management.store') }}" method="POST">

                @csrf

                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">

                        {{-- Employee --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Employee / Staff <span class="text-danger">*</span>
                            </label>

                            <select
                                name="user_id"
                                id="user_id"
                                class="form-control">

                                <option value="">Select Employee</option>

                                @foreach($employees as $employee)

                                    <option
                                        value="{{ $employee->id }}"
                                        data-monthly-salary="{{ $employee->salary }}"
                                        {{ old('user_id') == $employee->id ? 'selected' : '' }}>

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
                                Salary Month <span class="text-danger">*</span>
                            </label>

                            <input
                                type="month"
                                name="salary_month"
                                class="form-control"
                                value="{{ old('salary_month', date('Y-m')) }}">

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
                                value="{{ old('salary_amount') }}">

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
                                value="{{ old('paid_amount',0) }}">

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
                                class="form-control"
                                readonly>

                        </div>

                        {{-- Payment Method --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Payment Method
                            </label>

                            <select
                                name="payment_method"
                                class="form-control">

                                <option value="">Select</option>

                                <option value="Cash" {{ old('payment_method')=='Cash' ? 'selected':'' }}>
                                    Cash
                                </option>

                                <option value="UPI" {{ old('payment_method')=='UPI' ? 'selected':'' }}>
                                    UPI
                                </option>

                                <option value="Bank Transfer" {{ old('payment_method')=='Bank Transfer' ? 'selected':'' }}>
                                    Bank Transfer
                                </option>

                                <option value="Cheque" {{ old('payment_method')=='Cheque' ? 'selected':'' }}>
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
                                name="description"
                                rows="4"
                                class="form-control">{{ old('description') }}</textarea>

                        </div>

                    </div>

                </div>

                <div class="card-footer text-end">

                    <button class="btn btn-primary">

                        <i class="fa fa-save"></i>

                        Save Salary

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

$(function(){

    function calculateDue(){

        let salary = parseFloat($("#salary_amount").val()) || 0;

        let paid = parseFloat($("#paid_amount").val()) || 0;

        let due = salary - paid;

        if(due < 0){

            due = 0;

        }

        $("#due_amount").val(due.toFixed(2));

    }

    $("#user_id").change(function(){

        let salary = $(this).find(':selected').data('monthly-salary') || 0;

        $("#salary_amount").val(salary);

        calculateDue();

    });

    $("#salary_amount,#paid_amount").on("keyup change",function(){

        calculateDue();

    });

    calculateDue();

});

</script>

@endpush
