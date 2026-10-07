@extends('backend.partial.master')

@section('title', 'Edit Batch')

@section('backend-content')

<div class="row justify-content-center">

    <div class="col-lg-9">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Edit Batch</h4>

                <a href="{{ route('batches.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>

            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('batches.update', $batch->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- Course --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Course <span class="text-danger">*</span>
                            </label>

                            <select name="course_id" class="form-select" required>

                                <option value="">Select Course</option>

                                @foreach($courses as $course)

                                    <option value="{{ $course->id }}"
                                        {{ old('course_id', $batch->course_id) == $course->id ? 'selected' : '' }}>

                                        {{ $course->course_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- Level --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Level <span class="text-danger">*</span>
                            </label>

                            <select name="level_id" class="form-select" required>

                                <option value="">Select Level</option>

                                @foreach($levels as $level)

                                    <option value="{{ $level->id }}"
                                        {{ old('level_id', $batch->level_id) == $level->id ? 'selected' : '' }}>

                                        {{ $level->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- Batch Name --}}
                        <div class="col-md-8 mb-3">

                            <label class="form-label">
                                Batch Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="batch_name"
                                class="form-control"
                                value="{{ old('batch_name', $batch->batch_name) }}"
                                placeholder="Example : Morning Batch"
                                required>

                        </div>

                        {{-- Capacity --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Capacity <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="capacity"
                                class="form-control"
                                value="{{ old('capacity', $batch->capacity) }}"
                                min="1"
                                placeholder="30"
                                required>

                        </div>

                        {{-- Weekly Class Schedule --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label fw-bold">
                                Weekly Class Schedule
                            </label>

                            <div class="border rounded p-3 bg-light">

                                {{-- Header --}}
                                <div class="row fw-bold mb-2 d-none d-md-flex">

                                    <div class="col-md-4">
                                        Day
                                    </div>

                                    <div class="col-md-4">
                                        Start Time
                                    </div>

                                    <div class="col-md-4">
                                        End Time
                                    </div>

                                </div>

                                @php
                                    $days = [
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ];
                                @endphp

                                @foreach($days as $key => $label)

                                    <div class="row align-items-center border-bottom py-2">

                                        {{-- Day --}}
                                        <div class="col-md-4 mb-2 mb-md-0">

                                            <label class="form-label mb-0 fw-semibold">
                                                {{ $label }}
                                            </label>

                                        </div>

                                        {{-- Start Time --}}
                                        <div class="col-md-4 mb-2 mb-md-0">

                                            <label class="form-label d-md-none">
                                                Start Time
                                            </label>

                                            <input
                                                type="time"
                                                name="{{ $key }}_start_time"
                                                class="form-control"
                                                value="{{ old(
                                                    $key . '_start_time',
                                                    $batch->{$key . '_start_time'}
                                                ) }}">

                                        </div>

                                        {{-- End Time --}}
                                        <div class="col-md-4">

                                            <label class="form-label d-md-none">
                                                End Time
                                            </label>

                                            <input
                                                type="time"
                                                name="{{ $key }}_end_time"
                                                class="form-control"
                                                value="{{ old(
                                                    $key . '_end_time',
                                                    $batch->{$key . '_end_time'}
                                                ) }}">

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                            <small class="text-muted">
                                Leave the time fields blank for days when there is no class.
                            </small>

                        </div>

                    </div>

                    <div class="text-end">

                        <button type="submit" class="btn btn-primary">

                            <i class="fa fa-save"></i> Update Batch

                        </button>

                        <a href="{{ route('batches.index') }}" class="btn btn-danger">

                            Cancel

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
