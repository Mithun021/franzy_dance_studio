@extends('backend.partial.master')

@section('title', 'Enrolled Courses')

@section('backend-content')

<div class="container-fluid">
{{-- ============================================================
    PAGE HEADER
============================================================= --}}

<div class="card shadow-sm mb-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>

                <h4 class="mb-1">
                    <i class="fa fa-book me-2"></i>
                    Enrolled Courses
                </h4>

                <p class="text-muted mb-0">
                    All enrolled students and their courses
                </p>

            </div>

            <div>

                <span class="badge bg-primary fs-6">

                    Total Enrollments:
                    {{ $courses->count() }}

                </span>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
    STATISTICS
============================================================= --}}

<div class="row mb-4">

    {{-- Total Enrollments --}}

    <div class="col-md-3 mb-3">

        <div class="card border-0 shadow bg-primary text-white h-100">

            <div class="card-body text-center">

                <h2 class="mb-1">
                    {{ $courses->count() }}
                </h2>

                <p class="mb-0">
                    Total Enrollments
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

        <div class="card border-0 shadow bg-success text-white h-100">

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


    {{-- Discontinued --}}

    <div class="col-md-3 mb-3">

        <div class="card border-0 shadow bg-danger text-white h-100">

            <div class="card-body text-center">

                <h2 class="mb-1">
                    {{ $courses->where('status', 'discontinued')->count() }}
                </h2>

                <p class="mb-0">
                    Discontinued
                </p>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
    ENROLLED COURSES TABLE
============================================================= --}}

<div class="card shadow-sm">

    <div class="card-header bg-dark text-white">

        <h5 class="mb-0">

            <i class="fa fa-list me-2"></i>

            All Enrolled Courses

        </h5>

    </div>


    <div class="card-body">

        @if($courses->count())

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Student
                            </th>

                            <th>
                                Admission No
                            </th>

                            <th>
                                Course
                            </th>

                            <th>
                                Level
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Batch
                            </th>

                            <th>
                                Monthly Fee
                            </th>

                            <th>
                                Admission Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="100">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($courses as $key => $studentCourse)

                            <tr>

                                {{-- # --}}

                                <td>

                                    {{ $key + 1 }}

                                </td>


                                {{-- =================================================
                                    STUDENT
                                ================================================== --}}

                                <td>

                                    <div class="d-flex align-items-center">

                                        @if(
                                            $studentCourse->student &&
                                            $studentCourse->student->profile_image
                                        )

                                            <img
                                                src="{{ asset('storage/' . $studentCourse->student->profile_image) }}"
                                                alt="{{ $studentCourse->student->name }}"
                                                class="rounded-circle border me-2"
                                                width="45"
                                                height="45"
                                                style="object-fit: cover;"
                                            >

                                        @else

                                            <img
                                                src="{{ asset('backend/images/user.png') }}"
                                                alt="User"
                                                class="rounded-circle border me-2"
                                                width="45"
                                                height="45"
                                            >

                                        @endif


                                        <div>

                                            <strong>

                                                {{ optional($studentCourse->student)->name ?? 'Student Not Found' }}

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                {{ optional($studentCourse->student)->phone ?? '-' }}

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- =================================================
                                    ADMISSION NO
                                ================================================== --}}

                                <td>

                                    {{ $studentCourse->admission_no ?? 'N/A' }}

                                </td>


                                {{-- =================================================
                                    COURSE
                                ================================================== --}}

                                <td>

                                    <strong>

                                        {{ optional($studentCourse->course)->course_name ?? 'Course Not Found' }}

                                    </strong>

                                    @if($studentCourse->course)

                                        <br>

                                        <small class="text-muted">

                                            {{ $studentCourse->course->duration ?? '' }}

                                            {{ $studentCourse->course->duration_type ?? '' }}

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                    LEVEL
                                ================================================== --}}

                                <td>

                                    {{ optional($studentCourse->level)->name ?? 'N/A' }}

                                </td>


                                {{-- =================================================
                                    CATEGORY
                                ================================================== --}}

                                <td>

                                    {{ optional($studentCourse->category)->name ?? 'N/A' }}

                                </td>


                                {{-- =================================================
                                    BATCH
                                ================================================== --}}

                                <td>

                                    @if($studentCourse->batch)

                                        <strong>

                                            {{ $studentCourse->batch->batch_name ?? 'N/A' }}

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            @if(
                                                !empty($studentCourse->batch->start_time) &&
                                                !empty($studentCourse->batch->end_time)
                                            )

                                                {{ date('h:i A', strtotime($studentCourse->batch->start_time)) }}

                                                -

                                                {{ date('h:i A', strtotime($studentCourse->batch->end_time)) }}

                                            @endif

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Not Assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    MONTHLY FEE
                                ================================================== --}}

                                <td>

                                    <span class="text-primary fw-bold">

                                        ₹ {{ number_format((float) $studentCourse->monthly_fee, 2) }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    ADMISSION DATE
                                ================================================== --}}

                                <td>

                                    @if($studentCourse->admission_date)

                                        {{ $studentCourse->admission_date->format('d M Y') }}

                                    @else

                                        N/A

                                    @endif

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

                                <td>

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

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ ucfirst($studentCourse->status ?? 'Unknown') }}

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    ACTION
                                ================================================== --}}

                                <td>

                                    <div class="d-flex gap-1">

                                        {{-- Edit --}}

                                        <a
                                            href="{{ route('students.edit-course', $studentCourse->id) }}"
                                            class="btn btn-sm btn-warning"
                                            title="Edit Course"
                                        >

                                            <i class="mdi mdi-pencil-outline fs-14"></i>

                                        </a>


                                        {{-- Delete --}}

                                        <form
                                            action="{{ route('students.delete-course', $studentCourse->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm(
                                                'Are you sure you want to delete this enrollment? All related payment, monthly fee and late fine records will also be deleted.'
                                            );"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete Course"
                                            >

                                                <i class="mdi mdi-delete-outline fs-14"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="text-center py-5">

                <i class="fa fa-book fa-4x text-secondary mb-3"></i>

                <h4>
                    No Enrolled Courses Found
                </h4>

                <p class="text-muted mb-0">

                    No student has enrolled in any course yet.

                </p>

            </div>

        @endif

    </div>

</div>
</div>

@endsection
