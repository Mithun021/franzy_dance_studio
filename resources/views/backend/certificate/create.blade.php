@extends('backend.partial.master')

@section('title','Upload Certificate')

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

                                Upload Certificate

                            </h4>

                            <small class="text-muted">

                                Upload certificate for a student

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


    {{-- =====================================================
        CERTIFICATE FORM
    ====================================================== --}}

    <div class="row">

        <div class="col-lg-8 col-xl-7">

            <div class="card shadow-sm">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i data-feather="upload"></i>

                        Certificate Information

                    </h5>

                </div>


                <form
                    action="{{ route('certificate.store') }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf


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
                                        {{ old('user_id') == $student->id ? 'selected' : '' }}>

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
                                        {{ old('course_id') == $course->id ? 'selected' : '' }}>

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
                                        {{ old('level_id') == $level->id ? 'selected' : '' }}>

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
                            CERTIFICATE FILE
                        ================================================== --}}

                        <div class="mb-3">

                            <label
                                for="certificate_file"
                                class="form-label fw-semibold">

                                Certificate File

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="file"
                                name="certificate_file"
                                id="certificate_file"
                                class="form-control @error('certificate_file') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required>


                            @error('certificate_file')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror


                            <small class="text-muted">

                                Accepted formats:
                                PDF, JPG, JPEG, PNG

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

                            <i class="fa fa-upload"></i>

                            Upload Certificate

                        </button>

                    </div>

                </form>

            </div>

        </div>


        {{-- =====================================================
            INFORMATION CARD
        ====================================================== --}}

        <div class="col-lg-4 col-xl-5">

            <div class="card shadow-sm">

                <div class="card-header">

                    <h5 class="mb-0">

                        <i data-feather="info"></i>

                        Certificate Details

                    </h5>

                </div>


                <div class="card-body">

                    <div class="d-flex mb-3">

                        <div class="me-3 text-primary">

                            <i data-feather="user"></i>

                        </div>

                        <div>

                            <strong>
                                Student
                            </strong>

                            <div class="text-muted small">

                                Select the student for whom
                                the certificate is being uploaded.

                            </div>

                        </div>

                    </div>


                    <div class="d-flex mb-3">

                        <div class="me-3 text-primary">

                            <i data-feather="book-open"></i>

                        </div>

                        <div>

                            <strong>
                                Course
                            </strong>

                            <div class="text-muted small">

                                Select the course associated
                                with the certificate.

                            </div>

                        </div>

                    </div>


                    <div class="d-flex mb-3">

                        <div class="me-3 text-primary">

                            <i data-feather="layers"></i>

                        </div>

                        <div>

                            <strong>
                                Level
                            </strong>

                            <div class="text-muted small">

                                Select the completed level
                                of the course.

                            </div>

                        </div>

                    </div>


                    <div class="d-flex">

                        <div class="me-3 text-primary">

                            <i data-feather="file"></i>

                        </div>

                        <div>

                            <strong>
                                Certificate File
                            </strong>

                            <div class="text-muted small">

                                Upload the student's certificate
                                in PDF or image format.

                            </div>

                        </div>

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

    /*
    |--------------------------------------------------------------------------
    | Feather Icons
    |--------------------------------------------------------------------------
    */

    if (typeof feather !== 'undefined') {

        feather.replace();

    }

});

</script>

@endpush
