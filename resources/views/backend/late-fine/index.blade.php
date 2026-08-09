@extends('backend.partial.master')

@section('title', 'Late Fine Management')

@section('backend-content')

<div class="col-md-6">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">

        <div class="card-header">

            <h4 class="mb-0">
                Late Fine Management
            </h4>

        </div>

        <div class="card-body">

            <form action="{{ route('late-fines.store') }}" method="POST">

                @csrf

                {{-- Due Date --}}
                <div class="mb-3">

                    <label class="form-label">
                        Fee Due Date
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">

                        <input
                            type="number"
                            name="due_date"
                            class="form-control"
                            min="1"
                            max="31"
                            placeholder="Enter Due Date"
                            value="{{ old('due_date', $lateFine->due_date ?? 5) }}"
                            required>

                        <span class="input-group-text">
                            of Every Month
                        </span>

                    </div>

                    <small class="text-muted">
                        Monthly fee must be paid by this date.
                    </small>

                </div>


                {{-- Same Month Late Fee --}}
                <div class="mb-3">

                    <label class="form-label">
                        Same Month Late Fee
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            ₹
                        </span>

                        <input
                            type="number"
                            name="same_month_late_fee"
                            class="form-control"
                            min="0"
                            step="0.01"
                            placeholder="Enter Same Month Late Fee"
                            value="{{ old('same_month_late_fee', $lateFine->same_month_late_fee ?? '') }}"
                            required>

                    </div>

                    <small class="text-muted">
                        Late fee charged when the monthly fee is paid after the due date within the same month.
                    </small>

                </div>


                {{-- Next Month Late Fee --}}
                <div class="mb-3">

                    <label class="form-label">
                        Next Month Late Fee
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            ₹
                        </span>

                        <input
                            type="number"
                            name="next_month_late_fee"
                            class="form-control"
                            min="0"
                            step="0.01"
                            placeholder="Enter Next Month Late Fee"
                            value="{{ old('next_month_late_fee', $lateFine->next_month_late_fee ?? '') }}"
                            required>

                    </div>

                    <small class="text-muted">
                        Late fee charged when the previous month's fee is paid in the following month.
                    </small>

                </div>


                {{-- Full Month Absent Charge --}}
                <div class="mb-3">

                    <label class="form-label">
                        Full Month Absent Charge
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">

                        <input
                            type="number"
                            name="absent_charge_percentage"
                            class="form-control"
                            min="0"
                            max="100"
                            step="0.01"
                            placeholder="Enter Percentage"
                            value="{{ old('absent_charge_percentage', $lateFine->absent_charge_percentage ?? 50) }}"
                            required>

                        <span class="input-group-text">
                            %
                        </span>

                    </div>

                    <small class="text-muted">
                        Percentage of monthly fee charged if the student remains absent for the entire month.
                    </small>

                </div>


                <div class="text-end">

                    <button type="submit" class="btn btn-primary">

                        <i class="fa fa-save"></i>

                        {{ $lateFine ? 'Update Late Fine' : 'Save Late Fine' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
