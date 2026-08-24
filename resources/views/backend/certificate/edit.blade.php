@extends('backend.partial.master')

@section('title','Edit Certificate')

@section('backend-content')

<div class="container-fluid">

    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}

    <div class="row mb-3">

        <div class="col-md-12">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h4 class="mb-1">

                                <i data-feather="award"></i>

                                Edit Certificate

                            </h4>

                            <small class="text-muted">

                                Update student certificate details

                            </small>

                        </div>


                        <a
                            href="{{ route('certificate.index') }}"
                            class="btn btn-secondary">

                            <i class="fa fa-arrow-left"></i>

                            Back

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        ALERTS
    ====================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =====================================================
        FORM
    ====================================================== --}}

    <div class="row">

        <div class="col-lg-8 col-xl-7">

            <div class="card shadow-sm">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i data-feather="edit"></i>

                        Certificate Information

                    </h5>

                </div>


                <form
                    action="{{ route('certificate.update', $certificate->id) }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    @method('PUT')


                    <div class="card-body">


                        {{-- =================================================
                            STUDENT
                        ================================================== --}}

                        <div class="mb-4">

                            <label
                                for="user_id"
                                class="form-label fw-semibold">

                                Student

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                name="user_id"
                                id="user_id"
                                class="form-control @error('user_id') is-invalid @enderror"
                                required>

                                <option value="">

                                    Select Student

                                </option>


                                @foreach($students as $student)

                                    <option
                                        value="{{ $student->id }}"
                                        {{ old('user_id', $certificate->user_id) == $student->id ? 'selected' : '' }}>

                                        {{ $student->user_id }}
                                        -
                                        {{ $student->name }}

                                    </option>

                                @endforeach

                            </select>


                            @error('user_id')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                            COURSE
                        ================================================== --}}

                        <div class="mb-4">

                            <label
                                for="course_id"
                                class="form-label fw-semibold">

                                Course

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                name="course_id"
                                id="course_id"
                                class="form-control @error('course_id') is-invalid @enderror"
                                required>

                                <option value="">

                                    Select Course

                                </option>


                                @foreach($courses as $course)

                                    <option
                                        value="{{ $course->id }}"
                                        {{ old('course_id', $certificate->course_id) == $course->id ? 'selected' : '' }}>

                                        {{ $course->course_name }}

                                    </option>

                                @endforeach

                            </select>


                            @error('course_id')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                            LEVEL
                        ================================================== --}}

                        <div class="mb-4">

                            <label
                                for="level_id"
                                class="form-label fw-semibold">

                                Level

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                name="level_id"
                                id="level_id"
                                class="form-control @error('level_id') is-invalid @enderror"
                                required>

                                <option value="">

                                    Select Level

                                </option>


                                @foreach($levels as $level)

                                    <option
                                        value="{{ $level->id }}"
                                        {{ old('level_id', $certificate->level_id) == $level->id ? 'selected' : '' }}>

                                        {{ $level->name }}

                                    </option>

                                @endforeach

                            </select>


                            @error('level_id')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- =================================================
                            CURRENT CERTIFICATE
                        ================================================== --}}

                        @if($certificate->certificate_file)

                            <div class="mb-4">

                                <label class="form-label fw-semibold">

                                    Current Certificate

                                </label>


                                <div class="border rounded p-3 bg-light">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>

                                            <i
                                                data-feather="file"
                                                class="text-primary">
                                            </i>

                                            <span class="ms-2">

                                                {{ $certificate->certificate_file }}

                                            </span>

                                        </div>


                                        <a
                                            href="{{ asset('uploads/certificates/' . $certificate->certificate_file) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-primary">

                                            <i class="fa fa-eye"></i>

                                            View

                                        </a>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- =================================================
                            NEW CERTIFICATE FILE
                        ================================================== --}}

                        <div class="mb-3">

                            <label
                                for="certificate_file"
                                class="form-label fw-semibold">

                                Replace Certificate File

                            </label>


                            <input
                                type="file"
                                name="certificate_file"
                                id="certificate_file"
                                class="form-control @error('certificate_file') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png">


                            @error('certificate_file')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror


                            <small class="text-muted">

                                Leave this empty if you do not want to
                                replace the current certificate.

                                Accepted formats:
                                PDF, JPG, JPEG, PNG.
                                Maximum size: 5 MB.

                            </small>

                        </div>

                    </div>


                    {{-- =====================================================
                        FOOTER
                    ====================================================== --}}

                    <div class="card-footer d-flex justify-content-end gap-2">

                        <a
                            href="{{ route('certificate.index') }}"
                            class="btn btn-secondary">

                            <i class="fa fa-times"></i>

                            Cancel

                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="fa fa-save"></i>

                            Update Certificate

                        </button>

                    </div>

                </form>

            </div>

        </div>


        {{-- =====================================================
            INFORMATION
        ====================================================== --}}

        <div class="col-lg-4 col-xl-5">

            <div class="card shadow-sm">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i data-feather="info"></i>

                        Certificate Information

                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-4">

                        <small class="text-muted">
                            Student
                        </small>

                        <h6 class="mb-0">

                            {{ $certificate->student->name ?? '-' }}

                        </h6>

                    </div>


                    <div class="mb-4">

                        <small class="text-muted">
                            Course
                        </small>

                        <h6 class="mb-0">

                            {{ $certificate->course->course_name ?? '-' }}

                        </h6>

                    </div>


                    <div class="mb-4">

                        <small class="text-muted">
                            Level
                        </small>

                        <h6 class="mb-0">

                            {{ $certificate->level->name ?? '-' }}

                        </h6>

                    </div>


                    <div>

                        <small class="text-muted">
                            Certificate
                        </small>

                        <h6 class="mb-0">

                            {{ $certificate->certificate_file ?? '-' }}

                        </h6>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

$(document).ready(function () {

    if (typeof feather !== 'undefined') {

        feather.replace();

    }

});

</script>

@endpush
