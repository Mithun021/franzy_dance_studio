@extends('backend.partial.master')

@section('title', 'Edit Event')

@section('backend-content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Edit Event</h4>
            <p class="text-muted mb-0">
                Update event information
            </p>
        </div>

        <a href="{{ route('events.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form action="{{ route('events.update', $event->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="row">

            {{-- Main Information --}}
            <div class="col-lg-8">

                <div class="card shadow-sm mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">Event Information</h5>
                    </div>

                    <div class="card-body">

                        {{-- Event Name --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Event Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="event_name"
                                   class="form-control @error('event_name') is-invalid @enderror"
                                   value="{{ old('event_name', $event->event_name) }}"
                                   placeholder="Enter event name"
                                   required>

                            @error('event_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Description <span class="text-danger">*</span>
                            </label>

                            <textarea name="description"
                                      rows="6"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter event description"
                                      required>{{ old('description', $event->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="row">

                            {{-- Event Date --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Event Date <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="event_date"
                                       class="form-control @error('event_date') is-invalid @enderror"
                                       value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}"
                                       required>

                                @error('event_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Event Time --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Event Time
                                </label>

                                <input type="time"
                                       name="event_time"
                                       class="form-control @error('event_time') is-invalid @enderror"
                                       value="{{ old('event_time', $event->event_time ? substr($event->event_time, 0, 5) : '') }}">

                                @error('event_time')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- Venue --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Venue Details
                            </label>

                            <input type="text"
                                   name="venue_details"
                                   class="form-control"
                                   value="{{ old('venue_details', $event->venue_details) }}"
                                   placeholder="Enter venue name/details">

                        </div>

                    </div>

                </div>


                {{-- Location --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">Location Details</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- State --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    State
                                </label>

                                <input type="text"
                                       name="state"
                                       class="form-control"
                                       value="{{ old('state', $event->state) }}"
                                       placeholder="State">

                            </div>


                            {{-- City --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    City
                                </label>

                                <input type="text"
                                       name="city"
                                       class="form-control"
                                       value="{{ old('city', $event->city) }}"
                                       placeholder="City">

                            </div>


                            {{-- Pincode --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Pincode
                                </label>

                                <input type="text"
                                       name="pincode"
                                       class="form-control"
                                       value="{{ old('pincode', $event->pincode) }}"
                                       placeholder="Pincode"
                                       maxlength="10">

                            </div>

                        </div>


                        {{-- Address --}}
                        <div class="mb-0">

                            <label class="form-label">
                                Full Address
                            </label>

                            <textarea name="address"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Enter full address">{{ old('address', $event->address) }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Right Side --}}
            <div class="col-lg-4">

                {{-- Event Type --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">Event Type</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Event Type
                            </label>

                            <select name="is_free"
                                    id="is_free"
                                    class="form-select">

                                <option value="1"
                                    {{ old('is_free', $event->is_free ? '1' : '0') == '1' ? 'selected' : '' }}>
                                    Free Event
                                </option>

                                <option value="0"
                                    {{ old('is_free', $event->is_free ? '1' : '0') == '0' ? 'selected' : '' }}>
                                    Paid Event
                                </option>

                            </select>

                        </div>


                        <div class="mb-0"
                             id="amount-wrapper"
                             style="{{ old('is_free', $event->is_free ? '1' : '0') == '0' ? '' : 'display:none;' }}">

                            <label class="form-label">
                                Event Amount
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">₹</span>

                                <input type="number"
                                       name="event_amount"
                                       id="event_amount"
                                       class="form-control"
                                       value="{{ old('event_amount', $event->event_amount) }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00">

                            </div>

                        </div>

                        <div class="mt-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    id="status"
                                    class="form-select">

                                <option value="1"
                                    {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ old('status') == '0' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- Document --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">Event Document</h5>
                    </div>

                    <div class="card-body">

                        @if($event->document)

                            <div class="mb-3">

                                <label class="form-label">
                                    Current Document
                                </label>

                                <div>

                                    <a href="{{ asset('events/' . $event->document) }}"
                                       target="_blank"
                                       class="btn btn-outline-primary btn-sm">

                                        <i class="fas fa-file"></i>
                                        View Document

                                    </a>

                                </div>

                            </div>

                        @endif


                        <label class="form-label">
                            {{ $event->document ? 'Replace Document' : 'Upload Document' }}
                        </label>

                        <input type="file"
                               name="document"
                               class="form-control @error('document') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                        <small class="text-muted d-block mt-2">
                            PDF, DOC, DOCX, JPG, JPEG, PNG
                            <br>
                            Maximum size: 5 MB
                        </small>

                        @error('document')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Update --}}
                <div class="card shadow-sm">

                    <div class="card-body">

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-save"></i>
                            Update Event

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const isFree = document.getElementById('is_free');
    const amountWrapper = document.getElementById('amount-wrapper');
    const amountInput = document.getElementById('event_amount');

    function toggleAmount() {

        if (isFree.value === '0') {

            amountWrapper.style.display = 'block';
            amountInput.required = true;

        } else {

            amountWrapper.style.display = 'none';
            amountInput.required = false;
            amountInput.value = '';

        }
    }

    isFree.addEventListener('change', toggleAmount);

    toggleAmount();

});

</script>

@endsection
