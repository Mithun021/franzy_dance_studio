@extends('backend.partial.master')

@section('title', 'Edit Expense')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Expense</h4>

                <a href="{{ route('expense.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>

            <form action="{{ route('expense.update', $expense->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="card-body">

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">

                        {{-- Expense Date --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Expense Date <span class="text-danger">*</span>
                            </label>

                            <input
                                type="date"
                                name="expense_date"
                                class="form-control @error('expense_date') is-invalid @enderror"
                                value="{{ old('expense_date', optional($expense->expense_date)->format('Y-m-d')) }}"
                            >

                            @error('expense_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Expense Title --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Expense Title <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Enter Expense Title"
                                value="{{ old('title', $expense->title) }}"
                            >

                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Amount --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Amount <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="amount"
                                step="0.01"
                                min="1"
                                class="form-control @error('amount') is-invalid @enderror"
                                placeholder="Enter Amount"
                                value="{{ old('amount', $expense->amount) }}"
                            >

                            @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Payment Method --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Payment Method
                            </label>

                            <select
                                name="payment_method"
                                class="form-control @error('payment_method') is-invalid @enderror"
                            >

                                <option value="">Select Payment Method</option>

                                <option value="Cash"
                                    {{ old('payment_method', $expense->payment_method) == 'Cash' ? 'selected' : '' }}>
                                    Cash
                                </option>

                                <option value="UPI"
                                    {{ old('payment_method', $expense->payment_method) == 'UPI' ? 'selected' : '' }}>
                                    UPI
                                </option>

                                <option value="Bank Transfer"
                                    {{ old('payment_method', $expense->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>
                                    Bank Transfer
                                </option>

                                <option value="Cheque"
                                    {{ old('payment_method', $expense->payment_method) == 'Cheque' ? 'selected' : '' }}>
                                    Cheque
                                </option>

                                <option value="Card"
                                    {{ old('payment_method', $expense->payment_method) == 'Card' ? 'selected' : '' }}>
                                    Card
                                </option>

                            </select>

                            @error('payment_method')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Description --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter Description"
                            >{{ old('description', $expense->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

                <div class="card-footer text-end">

                    <a href="{{ route('expense.index') }}" class="btn btn-warning">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Update Expense
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
