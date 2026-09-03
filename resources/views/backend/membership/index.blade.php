@extends('backend.partial.master')

@section('title','Membership Plan')

@section('backend-content')

<div class="container-fluid">

    {{-- Success Message --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


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


    {{-- Add Membership Plan --}}

    <div class="card">

        <div class="card-header">

            <h5 class="card-title mb-0">
                Add Membership Plan
            </h5>

        </div>

        <div class="card-body">

            <form
                action="{{ route('membership.store') }}"
                method="POST">

                @csrf

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
                            value="{{ old('plan_name') }}"
                            placeholder="e.g. 3 Months"
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
                            value="{{ old('duration') }}"
                            min="1"
                            placeholder="3"
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

                            <option value="">
                                Select
                            </option>

                            <option
                                value="day"
                                {{ old('duration_type') == 'day' ? 'selected' : '' }}>
                                Day
                            </option>

                            <option
                                value="month"
                                {{ old('duration_type', 'month') == 'month' ? 'selected' : '' }}>
                                Month
                            </option>

                            <option
                                value="year"
                                {{ old('duration_type') == 'year' ? 'selected' : '' }}>
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

                            <option value="">
                                Select
                            </option>

                            <option
                                value="flat"
                                {{ old('discount_type') == 'flat' ? 'selected' : '' }}>
                                Flat Amount
                            </option>

                            <option
                                value="month"
                                {{ old('discount_type') == 'month' ? 'selected' : '' }}>
                                Free Month
                            </option>

                            <option
                                value="percentage"
                                {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
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
                            value="{{ old('discount_value', 0) }}"
                            min="0"
                            step="0.01"
                            required>

                        <small
                            class="text-muted"
                            id="discountHelp">
                            Discount amount
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
                                {{ old('is_active', 1) ? 'checked' : '' }}>

                        </div>

                    </div>

                </div>


                <div class="text-end">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>

                        Save Membership Plan

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- Membership Plan List --}}

    <div class="card mt-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="card-title mb-0">
                    Membership Plans
                </h5>

                <span class="badge bg-primary">
                    {{ $memberships->count() }} Plans
                </span>

            </div>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                S.N.
                            </th>

                            <th>
                                Plan Name
                            </th>

                            <th>
                                Duration
                            </th>

                            <th>
                                Discount
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="150">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($memberships as $index => $membership)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $membership->plan_name }}
                                    </strong>
                                </td>

                                <td>

                                    {{ $membership->duration }}

                                    {{ ucfirst($membership->duration_type) }}{{ $membership->duration > 1 ? 's' : '' }}

                                </td>

                                <td>

                                    @if($membership->discount_type === 'flat')

                                        <span class="badge bg-info">
                                            ₹{{ number_format($membership->discount_value, 2) }}
                                            Flat Discount
                                        </span>

                                    @elseif($membership->discount_type === 'month')

                                        <span class="badge bg-success">

                                            {{ number_format($membership->discount_value, 0) }}

                                            Month{{ $membership->discount_value > 1 ? 's' : '' }}

                                            Free

                                        </span>

                                    @elseif($membership->discount_type === 'percentage')

                                        <span class="badge bg-warning text-dark">

                                            {{ number_format($membership->discount_value, 2) }}%

                                            Discount

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($membership->is_active)

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route('membership.edit', $membership->id) }}"
                                        class="btn btn-sm btn-primary"
                                        title="Edit">

                                        <i class="mdi mdi-pencil"></i>

                                    </a>


                                    <form
                                        action="{{ route('membership.delete', $membership->id) }}"
                                        method="POST"
                                        class="d-inline deleteMembershipForm">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Delete">

                                            <i class="mdi mdi-delete"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-4">

                                    <div class="text-muted">

                                        <i class="fas fa-box-open fa-2x mb-2"></i>

                                        <div>
                                            No membership plan found.
                                        </div>

                                    </div>

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


    $('.deleteMembershipForm').on('submit', function (e) {

        e.preventDefault();

        let form = this;

        Swal.fire({
            icon: 'warning',
            title: 'Delete Membership Plan?',
            text: 'This membership plan will be permanently deleted.',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545'
        }).then(function (result) {

            if (result.isConfirmed) {
                form.submit();
            }

        });

    });

});
</script>

@endpush
