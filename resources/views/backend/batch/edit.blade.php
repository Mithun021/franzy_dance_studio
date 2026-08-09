@extends('backend.partial.master')

@section('title', 'Edit Batch')

@section('backend-content')

<div class="row justify-content-center">

    <div class="col-lg-8">

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

                <form action="{{ route('batches.update',$batch->id) }}" method="POST">

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
                                        {{ old('course_id',$batch->course_id)==$course->id ? 'selected' : '' }}>

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
                                        {{ old('level_id',$batch->level_id)==$level->id ? 'selected' : '' }}>

                                        {{ $level->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- Batch Name --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Batch Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="batch_name"
                                class="form-control"
                                value="{{ old('batch_name',$batch->batch_name) }}"
                                required>

                        </div>

                        {{-- Class Days --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Class Days <span class="text-danger">*</span>
                            </label>

                            @php
                                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

                                $selectedDays = old('class_days', $batch->class_days);
                            @endphp

                            <div class="row">

                                @foreach($days as $day)

                                    <div class="col-md-3 mb-2">

                                        <div class="form-check">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="class_days[]"
                                                value="{{ $day }}"
                                                id="{{ $day }}"
                                                {{ in_array($day,$selectedDays) ? 'checked' : '' }}>

                                            <label class="form-check-label" for="{{ $day }}">

                                                {{ $day }}

                                            </label>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                        {{-- Start Time --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Start Time <span class="text-danger">*</span>
                            </label>

                            <input
                                type="time"
                                name="start_time"
                                class="form-control"
                                value="{{ old('start_time',$batch->start_time) }}"
                                required>

                        </div>

                        {{-- End Time --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                End Time <span class="text-danger">*</span>
                            </label>

                            <input
                                type="time"
                                name="end_time"
                                class="form-control"
                                value="{{ old('end_time',$batch->end_time) }}"
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
                                value="{{ old('capacity',$batch->capacity) }}"
                                min="1"
                                required>

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
