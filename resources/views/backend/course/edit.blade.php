@extends('backend.partial.master')

@section('title', 'Edit Course')

@section('backend-content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">Edit Course</h4>

                    <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>

                </div>

                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('courses.update', $course->id) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">
                                Course Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="course_name"
                                class="form-control"
                                value="{{ old('course_name', $course->course_name) }}"
                                placeholder="Enter Course Name"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Duration <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="duration"
                                class="form-control"
                                value="{{ old('duration', $course->duration) }}"
                                min="1"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Duration Type <span class="text-danger">*</span>
                            </label>

                            <select
                                name="duration_type"
                                class="form-select"
                                required>

                                <option value="">Select Duration Type</option>

                                {{-- <option value="Days"
                                    {{ old('duration_type', $course->duration_type) == 'Days' ? 'selected' : '' }}>
                                    Days
                                </option> --}}

                                <option value="Months"
                                    {{ old('duration_type', $course->duration_type) == 'Months' ? 'selected' : '' }}>
                                    Months
                                </option>

                                {{-- <option value="Years"
                                    {{ old('duration_type', $course->duration_type) == 'Years' ? 'selected' : '' }}>
                                    Years
                                </option> --}}

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Total Classes <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="total_classes"
                                class="form-control"
                                value="{{ old('total_classes', $course->total_classes) }}"
                                min="1"
                                required>
                        </div>

                        <div class="text-end">

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Course
                            </button>

                            <a href="{{ route('courses.index') }}" class="btn btn-danger">
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
