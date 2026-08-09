@extends('backend.partial.master')

@section('title','Student Courses')

@section('backend-content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="d-flex align-items-center">

                        @if($student->profile_image)

                            <img src="{{ asset('storage/'.$student->profile_image) }}"
                                class="rounded-circle border shadow"
                                width="90"
                                height="90"
                                style="object-fit:cover;">

                        @else

                            <img src="{{ asset('backend/images/user.png') }}"
                                class="rounded-circle border shadow"
                                width="90"
                                height="90">

                        @endif

                        <div class="ms-4">

                            <h3 class="mb-1">

                                {{ $student->name }}

                            </h3>

                            <div class="text-muted">

                                <i class="fa fa-envelope"></i>

                                {{ $student->email ?: 'N/A' }}

                            </div>

                            <div class="text-muted">

                                <i class="fa fa-phone"></i>

                                {{ $student->phone }}

                            </div>

                            <div class="mt-2">

                                @if($student->is_active=='yes')

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

                <div class="col-md-4 text-end">

                    <a href="{{ route('students.view',$student->id) }}"
                        class="btn btn-secondary">

                        <i class="fa fa-arrow-left"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>

    </div>



    {{-- Statistics --}}

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow bg-primary text-white">

                <div class="card-body text-center">

                    <h2>

                        {{ $courses->count() }}

                    </h2>

                    <p class="mb-0">

                        Total Courses

                    </p>

                </div>

            </div>

        </div>



        <div class="col-md-3">

            <div class="card border-0 shadow bg-success text-white">

                <div class="card-body text-center">

                    <h2>

                        {{ $courses->where('is_enroll',1)->count() }}

                    </h2>

                    <p class="mb-0">

                        Enrolled

                    </p>

                </div>

            </div>

        </div>



        {{-- <div class="col-md-3">

            <div class="card border-0 shadow bg-warning">

                <div class="card-body text-center">

                    <h2>

                        {{ $courses->where('is_enroll',0)->count() }}

                    </h2>

                    <p class="mb-0">

                        Payment Pending

                    </p>

                </div>

            </div>

        </div> --}}



        <div class="col-md-3">

            <div class="card border-0 shadow bg-info text-white">

                <div class="card-body text-center">

                    <h2>

                        {{ $courses->where('status','ongoing')->count() }}

                    </h2>

                    <p class="mb-0">

                        Ongoing

                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow bg-warning text-white">

                <div class="card-body text-center">

                    <h2>

                        {{ $courses->where('status','completed')->count() }}

                    </h2>

                    <p class="mb-0">

                        Completed

                    </p>

                </div>

            </div>

        </div>

    </div>



        @if($courses->count())

        @foreach($courses as $course)

        <div class="card shadow mb-4">

            <div class="card-header bg-dark text-white">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">

                        {{ $course->course->course_name }}

                    </h5>

                    <div>

                        @if($course->is_enroll)

                            <span class="badge bg-success">

                                Enrolled

                            </span>

                        @else

                            <span class="badge bg-warning">

                                Payment Pending

                            </span>

                        @endif

                        @if($course->status=="ongoing")

                            <span class="badge bg-primary">

                                Ongoing

                            </span>

                        @elseif($course->status=="completed")

                            <span class="badge bg-success">

                                Completed

                            </span>

                        @else

                            <span class="badge bg-danger">

                                Discontinued

                            </span>

                        @endif

                    </div>

                </div>

            </div>

            <div class="card-body">
                {{-- ============================
            Admission Details
        ============================= --}}

        <div class="row">

            <div class="col-md-3 mb-3">

                <strong>Admission No</strong>

                <p class="mb-0">

                    {{ $course->admission_no }}

                </p>

            </div>

            <div class="col-md-3 mb-3">

                <strong>Admission Date</strong>

                <p class="mb-0">

                    {{ $course->admission_date->format('d M Y') }}

                </p>

            </div>

            <div class="col-md-3 mb-3">

                <strong>Registration Fee</strong>

                <p class="mb-0 text-success fw-bold">

                    ₹ {{ number_format($course->registration_fee,2) }}

                </p>

            </div>

            <div class="col-md-3 mb-3">

                <strong>Admission Fee</strong>

                <p class="mb-0 text-success fw-bold">

                    ₹ {{ number_format($course->admission_fee,2) }}

                </p>

            </div>

        </div>

        <hr>

        {{-- ============================
            Course Details
        ============================= --}}

        <h5 class="mb-3">

            Course Information

        </h5>

        <div class="row">

            <div class="col-md-3 mb-3">

                <strong>Course</strong>

                <p class="mb-0">

                    {{ optional($course->course)->course_name }}

                </p>
                <small>{{ optional($course->course)->duration }}  {{ optional($course->course)->duration_type }}</small>

            </div>

            <div class="col-md-3 mb-3">

                <strong>Level</strong>

                <p>

                    {{ optional($course->level)->name }}

                </p>

            </div>

            <div class="col-md-3 mb-3">

                <strong>Category</strong>

                <p>

                    {{ optional($course->category)->name }}

                </p>

            </div>

            <div class="col-md-3 mb-3">

                <strong>Monthly Fee</strong>

                <p class="text-primary fw-bold">

                    ₹ {{ number_format($course->course_fee,2) }}

                </p>

            </div>

        </div>

        <hr>

        {{-- ============================
            Batch Details
        ============================= --}}

        <h5 class="mb-3">

            Batch Details

        </h5>

        @if($course->batch)

        <div class="row">

            <div class="col-md-3 mb-3">

                <strong>Batch Name</strong>

                <p>

                    {{ $course->batch->batch_name }}

                </p>

            </div>

            <div class="col-md-3 mb-3">

                <strong>Timing</strong>

                <p>

                    {{ date('h:i A',strtotime($course->batch->start_time)) }}

                    -

                    {{ date('h:i A',strtotime($course->batch->end_time)) }}

                </p>

            </div>

            <div class="col-md-3 mb-3">

                <strong>Capacity</strong>

                <p>

                    {{ $course->batch->capacity }}

                </p>

            </div>

            <div class="col-md-3 mb-3">

                <strong>Current Students</strong>

                <p>

                    {{ $course->batch->studentCourses()
                        ->where('is_enroll',1)
                        ->where('status','ongoing')
                        ->count() }}

                </p>

            </div>

        </div>

        <div class="row">

            <div class="col-md-6 mb-3">

                <strong>Available Seats</strong>

                @php

                    $enrolled = $course->batch->studentCourses()
                                    ->where('is_enroll',1)
                                    ->where('status','ongoing')
                                    ->count();

                    $available = $course->batch->capacity - $enrolled;

                @endphp

                <p>

                    {{ $available }}

                </p>

            </div>

            <div class="col-md-6 mb-3">

                <strong>Class Days</strong>

                <p>

                    {{ implode(', ', $course->batch->class_days ?? []) }}

                </p>

            </div>

        </div>

        @else

        <div class="alert alert-warning">

            Batch not assigned yet.

        </div>

        @endif

        <hr>

        {{-- ============================
            Instructor
        ============================= --}}

        <h5 class="mb-3">

            Instructor Details

        </h5>

        <div class="row">

            <div class="col-md-6">

                <strong>Instructor Name</strong>

                <p>

                    {{ optional($course->instructor)->name ?? 'Not Assigned' }}

                </p>

            </div>

            <div class="col-md-6">

                <strong>Instructor Contact</strong>

                <p>

                    {{ optional($course->instructor)->phone ?? '-' }}

                </p>

            </div>

        </div>

        <hr>
        {{-- ============================================
    Completion Details
============================================= --}}

@if($course->completion_date)

<div class="row">

    <div class="col-md-6">

        <strong>Completion Date</strong>

        <p class="text-success fw-bold">

            {{ \Carbon\Carbon::parse($course->completion_date)->format('d M Y') }}

        </p>

    </div>

</div>

<hr>

@endif


{{-- ============================================
    Payment Notice
============================================= --}}

{{-- @if(!$course->is_enroll)

<div class="alert alert-warning border-start border-5 border-warning">

    <h6 class="mb-2">

        <i class="fa fa-exclamation-circle"></i>

        Payment Pending

    </h6>

    <p class="mb-0">

        Student has filled the admission form but payment has not been completed.
        Until payment is completed, the student is <strong>NOT ENROLLED</strong> in this course.

    </p>

</div>

@endif --}}


{{-- ============================================
    Course Actions
============================================= --}}

<div class="d-flex flex-wrap gap-2 mt-4">

    {{-- <a href="#"
       class="btn btn-primary">

        <i class="fa fa-eye"></i>

        View Details

    </a> --}}

    <a href="{{ route('students.edit-course',$course->id) }}"
       class="btn btn-warning">

        <i class="fa fa-edit"></i>

        Edit Course

    </a>

    {{-- @if(!$course->is_enroll)

    <a href="#"
       class="btn btn-success">

        <i class="fa fa-credit-card"></i>

        Complete Payment

    </a>

    @endif --}}

</div>

</div>

</div>

@endforeach

@else

<div class="card">

    <div class="card-body text-center py-5">

        <i class="fa fa-book fa-4x text-secondary mb-3"></i>

        <h4>

            No Course Found

        </h4>

        <p class="text-muted">

            This student has not enrolled in any course yet.

        </p>

    </div>

</div>

@endif

</div>

@endsection
