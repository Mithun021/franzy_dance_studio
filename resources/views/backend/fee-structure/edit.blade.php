@extends('backend.partial.master')

@section('title', 'Edit Fee Structure')

@section('backend-content')

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Edit Fee Structure</h4>

                <a href="{{ route('fee-structures.index') }}" class="btn btn-secondary">
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

                <form action="{{ route('fee-structures.update',$feeStructure->id) }}" method="POST">

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
                                        {{ old('course_id',$feeStructure->course_id)==$course->id ? 'selected' : '' }}>

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
                                        {{ old('level_id',$feeStructure->level_id)==$level->id ? 'selected' : '' }}>

                                        {{ $level->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- Category --}}
                        <div class="col-md-4 mb-3 d-none">

                            <label class="form-label">
                                Category <span class="text-danger">*</span>
                            </label>

                            <select name="category_id" class="form-select">

                                <option value="">Select Category</option>

                                @foreach($categories as $category)

                                    <option value="{{ $category->id }}"
                                        {{ old('category_id',$feeStructure->category_id)==$category->id ? 'selected' : '' }}>

                                        {{ $category->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- Registration Fee --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Registration Fee
                            </label>

                            <input
                                type="number"
                                name="registration_fee"
                                step="0.01"
                                min="0"
                                class="form-control"
                                value="{{ old('registration_fee',$feeStructure->registration_fee) }}"
                                required>

                        </div>

                        {{-- Admission Fee --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Admission Fee
                            </label>

                            <input
                                type="number"
                                name="admission_fee"
                                step="0.01"
                                min="0"
                                class="form-control"
                                value="{{ old('admission_fee',$feeStructure->admission_fee) }}"
                                required>

                        </div>

                        {{-- Monthly Fee --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Monthly Fee <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="monthly_fee"
                                step="0.01"
                                min="0"
                                class="form-control"
                                value="{{ old('monthly_fee',$feeStructure->monthly_fee) }}"
                                required>

                        </div>

                    </div>

                    <div class="text-end">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Fee Structure
                        </button>

                        <a href="{{ route('fee-structures.index') }}" class="btn btn-danger">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
