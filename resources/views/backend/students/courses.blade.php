@extends('backend.partial.master')

@section('title', 'Student Courses')

@section('backend-content')

<div class="container-fluid">

    {{-- ============================================================
        STUDENT HEADER
    ============================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                {{-- Student Information --}}

                <div class="col-md-8">

                    <div class="d-flex align-items-center">

                        {{-- Profile Image --}}

                        @if($student->profile_image)

                            <img
                                src="{{ asset('storage/' . $student->profile_image) }}"
                                alt="{{ $student->name }}"
                                class="rounded-circle border shadow"
                                width="90"
                                height="90"
                                style="object-fit: cover;"
                            >

                        @else

                            <img
                                src="{{ asset('backend/images/user.png') }}"
                                alt="User"
                                class="rounded-circle border shadow"
                                width="90"
                                height="90"
                            >

                        @endif


                        {{-- Student Details --}}

                        <div class="ms-4">

                            <h3 class="mb-1">
                                {{ $student->name }}
                            </h3>


                            <div class="text-muted mb-1">

                                <i class="fa fa-envelope me-1"></i>

                                {{ $student->email ?: 'N/A' }}

                            </div>


                            <div class="text-muted mb-1">

                                <i class="fa fa-phone me-1"></i>

                                {{ $student->phone ?: 'N/A' }}

                            </div>


                            <div class="mt-2">

                                @if($student->is_active == 'yes')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Back Button --}}

                <div class="col-md-4 text-end">

                    <a
                        href="{{ route('students.view', $student->id) }}"
                        class="btn btn-secondary"
                    >

                        <i class="fa fa-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
        STATISTICS
    ============================================================= --}}

    <div class="row mb-4">

        {{-- Total Courses --}}

        <div class="col-md-3 mb-3">

            <div class="card border-0 shadow bg-primary text-white h-100">

                <div class="card-body text-center">

                    <h2 class="mb-1">
                        {{ $courses->count() }}
                    </h2>

                    <p class="mb-0">
                        Total Courses
                    </p>

                </div>

            </div>

        </div>


        {{-- Enrolled --}}

        <div class="col-md-3 mb-3">

            <div class="card border-0 shadow bg-success text-white h-100">

                <div class="card-body text-center">

                    <h2 class="mb-1">

                        {{ $courses->where('is_enroll', 1)->count() }}

                    </h2>

                    <p class="mb-0">
                        Enrolled
                    </p>

                </div>

            </div>

        </div>


        {{-- Ongoing --}}

        <div class="col-md-3 mb-3">

            <div class="card border-0 shadow bg-info text-white h-100">

                <div class="card-body text-center">

                    <h2 class="mb-1">

                        {{ $courses->where('status', 'ongoing')->count() }}

                    </h2>

                    <p class="mb-0">
                        Ongoing
                    </p>

                </div>

            </div>

        </div>


        {{-- Completed --}}

        <div class="col-md-3 mb-3">

            <div class="card border-0 shadow bg-warning text-white h-100">

                <div class="card-body text-center">

                    <h2 class="mb-1">

                        {{ $courses->where('status', 'completed')->count() }}

                    </h2>

                    <p class="mb-0">
                        Completed
                    </p>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
        COURSES
    ============================================================= --}}

    @if($courses->count())

        @foreach($courses as $studentCourse)

            <div class="card shadow mb-4">

                {{-- ====================================================
                    COURSE HEADER
                ===================================================== --}}

                <div class="card-header bg-dark text-white">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <h5 class="mb-0">

                            {{ optional($studentCourse->course)->course_name ?? 'Course Not Found' }}

                        </h5>


                        <div>

                            {{-- Enrollment Status --}}

                            @if($studentCourse->is_enroll)

                                <span class="badge bg-success">
                                    Enrolled
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Not Enroll
                                </span>

                            @endif


                            {{-- Course Status --}}

                            @if($studentCourse->status == 'ongoing')

                                <span class="badge bg-primary">
                                    Ongoing
                                </span>

                            @elseif($studentCourse->status == 'completed')

                                <span class="badge bg-success">
                                    Completed
                                </span>

                            @elseif($studentCourse->status == 'discontinued')

                                <span class="badge bg-danger">
                                    Discontinued
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- ====================================================
                    COURSE BODY
                ===================================================== --}}

                <div class="card-body">


                    {{-- =================================================
                        ADMISSION DETAILS
                    ================================================== --}}

                    <h5 class="mb-3">
                        Admission Details
                    </h5>


                    <div class="row">

                        {{-- Admission No --}}

                        <div class="col-md-3 mb-3">

                            <strong>
                                Admission No
                            </strong>

                            <p class="mb-0">

                                {{ $studentCourse->admission_no ?? 'N/A' }}

                            </p>

                        </div>


                        {{-- Admission Date --}}

                        <div class="col-md-3 mb-3">

                            <strong>
                                Admission Date
                            </strong>

                            <p class="mb-0">

                                @if($studentCourse->admission_date)

                                    {{ $studentCourse->admission_date->format('d M Y') }}

                                @else

                                    N/A

                                @endif

                            </p>

                        </div>


                        {{-- Registration Fee --}}

                        <div class="col-md-3 mb-3">

                            <strong>
                                Registration Fee
                            </strong>

                            <p class="mb-0 text-success fw-bold">

                                ₹ {{ number_format((float) $studentCourse->registration_fee, 2) }}

                            </p>

                        </div>


                        {{-- Admission Fee --}}

                        <div class="col-md-3 mb-3">

                            <strong>
                                Admission Fee
                            </strong>

                            <p class="mb-0 text-success fw-bold">

                                ₹ {{ number_format((float) $studentCourse->admission_fee, 2) }}

                            </p>

                        </div>

                    </div>


                    <hr>


                    {{-- =================================================
                        COURSE INFORMATION
                    ================================================== --}}

                    <h5 class="mb-3">
                        Course Information
                    </h5>


                    <div class="row">

                        {{-- Course --}}

                        <div class="col-md-3 mb-3">

                            <strong>
                                Course
                            </strong>

                            <p class="mb-0">

                                {{ optional($studentCourse->course)->course_name ?? 'N/A' }}

                            </p>


                            @if($studentCourse->course)

                                <small class="text-muted">

                                    {{ $studentCourse->course->duration ?? '' }}

                                    {{ $studentCourse->course->duration_type ?? '' }}

                                </small>

                            @endif

                        </div>


                        {{-- Level --}}

                        <div class="col-md-3 mb-3">

                            <strong>
                                Level
                            </strong>

                            <p class="mb-0">

                                {{ optional($studentCourse->level)->name ?? 'N/A' }}

                            </p>

                        </div>


                        {{-- Category --}}

                        <div class="col-md-3 mb-3">

                            <strong>
                                Category
                            </strong>

                            <p class="mb-0">

                                {{ optional($studentCourse->category)->name ?? 'N/A' }}

                            </p>

                        </div>


                        {{-- Monthly Fee --}}

                        <div class="col-md-3 mb-3">

                            <strong>
                                Monthly Fee
                            </strong>

                            <p class="mb-0 text-primary fw-bold">

                                ₹ {{ number_format((float) $studentCourse->monthly_fee, 2) }}

                            </p>

                        </div>

                    </div>


                    <hr>


                    {{-- =================================================
                        BATCH DETAILS
                    ================================================== --}}

                    <h5 class="mb-3">
                        Batch Details
                    </h5>


                    @if($studentCourse->batch)

                        <div class="row">

                            {{-- Batch Name --}}

                            <div class="col-md-3 mb-3">

                                <strong>
                                    Batch Name
                                </strong>

                                <p class="mb-0">

                                    {{ $studentCourse->batch->batch_name ?? 'N/A' }}

                                </p>

                            </div>


                            {{-- Timing --}}

                            <div class="col-md-3 mb-3">

                                <strong>
                                    Timing
                                </strong>

                                <p class="mb-0">

                                    @if(
                                        !empty($studentCourse->batch->start_time) &&
                                        !empty($studentCourse->batch->end_time)
                                    )

                                        {{ date('h:i A', strtotime($studentCourse->batch->start_time)) }}

                                        -

                                        {{ date('h:i A', strtotime($studentCourse->batch->end_time)) }}

                                    @else

                                        N/A

                                    @endif

                                </p>

                            </div>


                            {{-- Capacity --}}

                            <div class="col-md-3 mb-3">

                                <strong>
                                    Capacity
                                </strong>

                                <p class="mb-0">

                                    {{ $studentCourse->batch->capacity ?? 0 }}

                                </p>

                            </div>


                            {{-- Current Students --}}

                            <div class="col-md-3 mb-3">

                                <strong>
                                    Current Students
                                </strong>

                                <p class="mb-0">

                                    {{ $studentCourse->batch_enrolled_count }}

                                </p>

                            </div>

                        </div>


                        <div class="row">

                            {{-- Available Seats --}}

                            <div class="col-md-6 mb-3">

                                <strong>
                                    Available Seats
                                </strong>

                                <p class="mb-0">

                                    {{ $studentCourse->batch_available_seats }}

                                </p>

                            </div>


                            {{-- Class Days --}}

                            <div class="col-md-6 mb-3">

                                <strong>
                                    Class Days
                                </strong>

                                <p class="mb-0">

                                    @php
                                        $classDays = $studentCourse->batch->class_days ?? [];
                                    @endphp

                                    @if(is_array($classDays) && count($classDays))

                                        {{ implode(', ', $classDays) }}

                                    @else

                                        N/A

                                    @endif

                                </p>

                            </div>

                        </div>

                    @else

                        <div class="alert alert-warning">

                            <i class="fa fa-exclamation-triangle me-1"></i>

                            Batch not assigned yet.

                        </div>

                    @endif


                    <hr>


                    {{-- =================================================
                        INSTRUCTOR
                    ================================================== --}}

                    <h5 class="mb-3">
                        Instructor Details
                    </h5>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <strong>
                                Instructor Name
                            </strong>

                            <p class="mb-0">

                                {{ optional($studentCourse->instructor)->name ?? 'Not Assigned' }}

                            </p>

                        </div>


                        <div class="col-md-6 mb-3">

                            <strong>
                                Instructor Contact
                            </strong>

                            <p class="mb-0">

                                {{ optional($studentCourse->instructor)->phone ?? '-' }}

                            </p>

                        </div>

                    </div>


                    {{-- =================================================
                        COMPLETION DETAILS
                    ================================================== --}}

                    @if($studentCourse->completion_date)

                        <hr>

                        <div class="row">

                            <div class="col-md-6">

                                <strong>
                                    Completion Date
                                </strong>

                                <p class="text-success fw-bold mb-0">

                                    {{ $studentCourse->completion_date->format('d M Y') }}

                                </p>

                            </div>

                        </div>

                    @endif


                    {{-- =================================================
                        ACTIONS
                    ================================================== --}}

                    <hr>

                    <div class="d-flex flex-wrap gap-2 mt-3">

                        <a
                            href="{{ route('students.edit-course', $studentCourse->id) }}"
                            class="btn btn-warning"
                        >

                            <i class="fa fa-edit me-1"></i>

                            Edit Course

                        </a>

                    </div>

                </div>

            </div>

        @endforeach

    @else

        {{-- ============================================================
            NO COURSE
        ============================================================= --}}

        <div class="card shadow-sm">

            <div class="card-body text-center py-5">

                <i class="fa fa-book fa-4x text-secondary mb-3"></i>

                <h4>
                    No Course Found
                </h4>

                <p class="text-muted mb-0">

                    This student has not enrolled in any course yet.

                </p>

            </div>

        </div>

    @endif

</div>

@endsection
