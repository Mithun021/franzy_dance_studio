@extends('backend.partial.master')

@section('title', 'Edit Membership Plan')

@section('backend-content')

<div class="container-fluid">

    {{-- Validation Errors --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Edit Membership Plan
            </h5>

        </div>


        <div class="card-body">

            <form
                action="{{ route('membership.update', $membership->id) }}"
                method="POST">

                @csrf

                @method('PUT')


                <div class="row">

                    {{-- Plan Name --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-semibold">

                            Plan Name

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="plan_name"
                            class="form-control"
                            value="{{ old('plan_name', $membership->plan_name) }}"
                            required>

                    </div>


                    {{-- Duration --}}

                    <div class="col-md-2 mb-3">

                        <label class="form-label fw-semibold">

                            Duration

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            name="duration"
                            class="form-control"
                            value="{{ old('duration', $membership->duration) }}"
                            min="1"
                            required>

                    </div>


                    {{-- Duration Type --}}

                    <div class="col-md-2 mb-3">

                        <label class="form-label fw-semibold">

                            Duration Type

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="duration_type"
                            class="form-select"
                            required>

                            <option value="day"
                                {{ old('duration_type', $membership->duration_type) == 'day' ? 'selected' : '' }}>
                                Day
                            </option>

                            <option value="month"
                                {{ old('duration_type', $membership->duration_type) == 'month' ? 'selected' : '' }}>
                                Month
                            </option>

                            <option value="year"
                                {{ old('duration_type', $membership->duration_type) == 'year' ? 'selected' : '' }}>
                                Year
                            </option>

                        </select>

                    </div>


                    {{-- Discount Type --}}

                    <div class="col-md-2 mb-3">

                        <label class="form-label fw-semibold">

                            Discount Type

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="discount_type"
                            id="discount_type"
                            class="form-select"
                            required>

                            <option value="flat"
                                {{ old('discount_type', $membership->discount_type) == 'flat' ? 'selected' : '' }}>
                                Flat Amount
                            </option>

                            <option value="month"
                                {{ old('discount_type', $membership->discount_type) == 'month' ? 'selected' : '' }}>
                                Free Month
                            </option>

                            <option value="percentage"
                                {{ old('discount_type', $membership->discount_type) == 'percentage' ? 'selected' : '' }}>
                                Percentage
                            </option>

                        </select>

                    </div>


                    {{-- Discount Value --}}

                    <div class="col-md-2 mb-3">

                        <label class="form-label fw-semibold">

                            Discount Value

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            name="discount_value"
                            id="discount_value"
                            class="form-control"
                            value="{{ old('discount_value', $membership->discount_value) }}"
                            min="0"
                            step="0.01"
                            required>

                        <small
                            class="text-muted"
                            id="discountHelp">
                        </small>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-1 mb-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input
                                type="hidden"
                                name="is_active"
                                value="0">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $membership->is_active) ? 'checked' : '' }}>

                        </div>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('membership.index') }}"
                        class="btn btn-secondary">

                        <i class="fas fa-arrow-left me-1"></i>

                        Back

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>

                        Update Membership Plan

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

    function updateDiscountHelp() {

        let type = $('#discount_type').val();

        if (type === 'flat') {

            $('#discountHelp').text(
                'Enter flat discount amount in ₹'
            );

        } else if (type === 'month') {

            $('#discountHelp').text(
                'Enter number of free months'
            );

        } else if (type === 'percentage') {

            $('#discountHelp').text(
                'Enter discount percentage (0-100)'
            );

        } else {

            $('#discountHelp').text(
                'Discount amount'
            );

        }

    }

    updateDiscountHelp();

    $('#discount_type').on('change', function () {
        updateDiscountHelp();
    });

});
</script>

@endpush
