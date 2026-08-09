@extends('backend.partial.master')

@section('title','Edit Attendance')

@section('backend-content')

<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="row mb-3">

        <div class="col-md-12">

            <div class="card shadow-sm">

                <div class="card-body py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h4 class="mb-0">

                            <i data-feather="edit"></i>

                            Edit Attendance

                        </h4>

                        <a href="{{ route('attendance.index') }}"
                           class="btn btn-secondary">

                            <i data-feather="arrow-left"></i>

                            Back

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Attendance Form --}}

    <form action="{{ route('attendance.update') }}"
          method="POST">

        @csrf
        @method('PUT')

        <input type="hidden"
               name="attendance_date"
               value="{{ $attendance_date }}">

        <input type="hidden"
               name="course_id"
               value="{{ $course_id }}">

        <input type="hidden"
               name="batch_id"
               value="{{ $batch_id }}">

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">

                Attendance Information

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Attendance Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Attendance Date

                        </label>

                        <input type="date"
                               class="form-control"
                               value="{{ $attendance_date }}"
                               readonly>

                    </div>

                    {{-- Course --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Course

                        </label>

                        <select class="form-control"
                                disabled>

                            @foreach($courses as $course)

                                <option value="{{ $course->id }}"
                                    {{ $course->id == $course_id ? 'selected' : '' }}>

                                    {{ $course->course_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Batch --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Batch

                        </label>

                        <select class="form-control"
                                disabled>

                            @foreach($batches as $batch)

                                <option value="{{ $batch->id }}"
                                    {{ $batch->id == $batch_id ? 'selected' : '' }}>

                                    {{ $batch->batch_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>

        {{-- Student List --}}

        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">

                    Student Attendance

                </h5>

            </div>

            <div class="card-body p-0">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">

                                SL

                            </th>

                            <th>

                                Admission No

                            </th>

                            <th>

                                Student Name

                            </th>

                            <th>

                                Phone

                            </th>

                            <th width="260">

                                Attendance

                            </th>

                            <th>

                                Remarks

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($students as $key => $student)

                        @php

                            $record = $attendance[$student->user_id] ?? null;

                            $status = $record->status ?? 'Present';

                        @endphp

                        <tr>

                            <td>

                                {{ $key + 1 }}

                            </td>

                            <td>

                                {{ $student->admission_no }}

                            </td>

                            <td>

                                {{ $student->student->name }}

                            </td>

                            <td>

                                {{ $student->student->phone }}

                            </td>

                            <td>

                                <label class="me-3">

                                    <input type="radio"
                                           name="attendance[{{ $student->user_id }}]"
                                           value="Present"
                                           {{ $status == 'Present' ? 'checked' : '' }}>

                                    Present

                                </label>

                                <label class="me-3">

                                    <input type="radio"
                                           name="attendance[{{ $student->user_id }}]"
                                           value="Absent"
                                           {{ $status == 'Absent' ? 'checked' : '' }}>

                                    Absent

                                </label>

                                <label>

                                    <input type="radio"
                                           name="attendance[{{ $student->user_id }}]"
                                           value="Leave"
                                           {{ $status == 'Leave' ? 'checked' : '' }}>

                                    Leave

                                </label>

                            </td>

                            <td>

                                <input type="text"
                                       class="form-control"
                                       name="remarks[{{ $student->user_id }}]"
                                       value="{{ $record->remarks ?? '' }}"
                                       placeholder="Remarks">

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center text-danger">

                                No Student Found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>
                        @if($students->count())

                <div class="card-footer">

                    <div class="d-flex justify-content-end">

                        <a href="{{ route('attendance.index') }}"
                           class="btn btn-secondary me-2">

                            <i data-feather="x-circle"></i>

                            Cancel

                        </a>

                        <button type="submit"
                                class="btn btn-success">

                            <i data-feather="save"></i>

                            Update Attendance

                        </button>

                    </div>

                </div>

            @endif

        </div>

    </form>

</div>

@endsection

@push('scripts')

<script>

$(document).ready(function(){

    feather.replace();

});

</script>

@endpush
