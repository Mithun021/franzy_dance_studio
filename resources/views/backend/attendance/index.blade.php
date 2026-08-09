@extends('backend.partial.master')

@section('title','Attendance List')

@section('backend-content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="card shadow-sm mb-3">

        <div class="card-body">

            {{-- Success Message --}}
            @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <i data-feather="check-circle"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

            @endif

            {{-- Error Message --}}
            @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <i data-feather="alert-circle"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())

            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <i data-feather="alert-triangle"></i>

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

            @endif

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    <i data-feather="calendar"></i>
                    Attendance List
                </h4>

                <a href="{{ route('attendance.create') }}" class="btn btn-primary">

                    <i data-feather="plus"></i>

                    Take Attendance

                </a>

            </div>

        </div>

    </div>

    {{-- Filter --}}
    <div class="card shadow-sm mb-3">

        <div class="card-header bg-light">

            <strong>Filter Attendance</strong>

        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('attendance.index') }}">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            From Date

                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            To Date

                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Course

                        </label>

                        <select
                            name="course_id"
                            class="form-control">

                            <option value="">

                                All Courses

                            </option>

                            @foreach($courses as $course)

                                <option
                                    value="{{ $course->id }}"
                                    {{ request('course_id')==$course->id ? 'selected' : '' }}>

                                    {{ $course->course_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3 mb-3 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2">

                            <i data-feather="search"></i>

                            Search

                        </button>

                        <a
                            href="{{ route('attendance.index') }}"
                            class="btn btn-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- Attendance Table --}}
    <div class="card shadow-sm">

        <div class="card-body p-2">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0" id="datatable-buttons">

                    <thead class="table-dark">

                        <tr>

                            <th width="60">SL</th>

                            <th>Date</th>

                            <th>Student</th>

                            <th>Course</th>

                            <th>Batch</th>

                            <th>Status</th>

                            <th>Remarks</th>

                            <th>Created By</th>

                            <th width="130">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($attendance as $key => $row)

                            <tr>

                                <td>

                                    {{ ++$key }}

                                </td>

                                <td>

                                    {{ $row->attendance_date->format('d-m-Y') }}

                                </td>

                                <td>

                                    {{ $row->student->name ?? '-' }}

                                </td>

                                <td>

                                    {{ $row->course->course_name ?? '-' }}

                                </td>

                                <td>

                                    {{ $row->batch->batch_name ?? '-' }}

                                </td>

                                <td>

                                    @if($row->status=='Present')

                                        <span class="badge bg-success">

                                            Present

                                        </span>

                                    @elseif($row->status=='Absent')

                                        <span class="badge bg-danger">

                                            Absent

                                        </span>

                                    @elseif($row->status=='Holiday')

                                        <span class="badge bg-warning text-dark">

                                            Holiday

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ $row->status }}

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $row->remarks }}

                                </td>

                                <td>

                                    @if($row->creator)

                                        <span class="badge bg-info">

                                            {{ $row->creator->name }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route('attendance.edit',[$row->attendance_date->format('Y-m-d'),$row->course_id,$row->batch_id]) }}"
                                        class="btn btn-sm btn-primary">

                                        <i data-feather="edit"></i>

                                    </a>

                                    <form
                                        action="{{ route('attendance.destroy',$row->id) }}"
                                        method="POST"
                                        class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this attendance?')">

                                            <i data-feather="trash-2"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center text-danger">

                                    No Attendance Found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
