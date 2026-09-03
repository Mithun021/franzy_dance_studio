@extends('backend.partial.master')

@section('title','Assign Instructor')

@section('backend-content')

<div class="container-fluid">


    {{-- Page Header --}}
    <div class="row mb-4">

        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1">
                        Assign Instructor
                    </h4>

                    <p class="text-muted mb-0">
                        Assign instructor/faculty to multiple students at once.
                    </p>
                </div>

            </div>

        </div>

    </div>


    {{-- Search Form --}}
    <div class="card border">

        <div class="card-header">

            <h5 class="mb-0">
                Search Students
            </h5>

        </div>

        <div class="card-body">

            <form id="searchStudentForm">

                @csrf

                <div class="row align-items-end">

                    {{-- Course --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Course <span class="text-danger">*</span>
                        </label>

                        <select
                            name="course_id"
                            id="course_id"
                            class="form-select select2"
                            required>

                            <option value="">
                                Select Course
                            </option>

                            @foreach($courses as $course)

                                <option value="{{ $course->id }}">
                                    {{ $course->course_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Batch --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Batch
                        </label>

                        <select
                            name="batch_id"
                            id="batch_id"
                            class="form-select select2">

                            <option value="">
                                All Batches
                            </option>

                            @foreach($batches as $batch)

                                <option value="{{ $batch->id }}">

                                    {{ $batch->batch_name }}

                                    @if($batch->course)
                                        - {{ $batch->course->course_name }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Level --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Level
                        </label>

                        <select
                            name="level_id"
                            id="level_id"
                            class="form-select select2">

                            <option value="">
                                All Levels
                            </option>

                            @foreach($levels as $level)

                                <option value="{{ $level->id }}">
                                    {{ $level->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Search Button --}}
                    <div class="col-12">

                        <button
                            type="submit"
                            id="searchStudentBtn"
                            class="btn btn-primary">

                            <i class="fas fa-search me-1"></i>

                            Search Students

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Student Result --}}
    <div
        class="card border mt-4"
        id="studentResultCard"
        style="display:none;">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Matched Students
                </h5>

                <span
                    class="badge bg-primary"
                    id="studentCount">
                    0
                </span>

            </div>

        </div>


        <div class="card-body">


            {{-- Student Table --}}
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>

                        <tr>

                            <th style="width:50px;" class="text-center">

                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="selectAllStudents">

                            </th>

                            <th style="width:70px;">
                                S.N.
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

                            <th>
                                Course
                            </th>

                            <th>
                                Level
                            </th>

                            <th>
                                Batch
                            </th>

                            <th>
                                Instructor
                            </th>

                        </tr>

                    </thead>

                    <tbody id="studentTableBody">

                    </tbody>

                </table>

            </div>


            {{-- No Student --}}
            <div
                id="noStudentMessage"
                class="text-center py-4"
                style="display:none;">

                <p class="text-muted mb-0">
                    No students found for the selected filters.
                </p>

            </div>


            {{-- Assign Instructor --}}
            <div
                id="assignSection"
                class="border-top pt-4 mt-4"
                style="display:none;">

                <div class="row align-items-end">

                    {{-- Instructor --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Instructor / Faculty
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="instructor_id"
                            class="form-select select2">

                            <option value="">
                                Select Instructor / Faculty
                            </option>

                            @foreach($instructors as $instructor)

                                <option value="{{ $instructor->id }}">
                                    {{ $instructor->name }}

                                    @if($instructor->phone)
                                        - {{ $instructor->phone }}
                                    @endif
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Action --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Action
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="assign_action"
                            class="form-select">

                            <option value="">
                                Select Action
                            </option>

                            <option value="apply">
                                Apply
                            </option>

                            <option value="revert">
                                Revert
                            </option>

                        </select>

                    </div>


                    {{-- Update Button --}}
                    <div class="col-md-4">

                        <button
                            type="button"
                            id="assignInstructorBtn"
                            class="btn btn-success">

                            <i class="fas fa-user-check me-1"></i>

                            Update / Confirm Instructor

                        </button>

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

    $('.select2').select2({
        width: '100%',
        allowClear: true
    });

    $('#instructor_id').prop('disabled', true);

    $('#assign_action').on('change', function () {
        let action = $(this).val();

        if (action === 'revert') {
            $('#instructor_id')
                .val('')
                .trigger('change')
                .prop('disabled', true);
        } else if (action === 'apply') {
            $('#instructor_id').prop('disabled', false);
        } else {
            $('#instructor_id')
                .val('')
                .trigger('change')
                .prop('disabled', true);
        }
    });

    $('#searchStudentForm').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let button = $('#searchStudentBtn');

        button.prop('disabled', true);
        button.html(
            '<i class="fas fa-spinner fa-spin me-1"></i> Searching...'
        );

        $('#studentResultCard').show();
        $('#studentTableBody').empty();
        $('#noStudentMessage').hide();
        $('#assignSection').hide();
        $('#selectAllStudents').prop('checked', false);
        $('#studentCount').text('0');

        $.ajax({
            url: "{{ route('assign-instuructor.search') }}",
            type: "POST",
            data: form.serialize(),
            success: function (response) {
                $('#studentTableBody').empty();
                $('#selectAllStudents').prop('checked', false);
                $('#noStudentMessage').hide();

                let students = response.students || [];

                $('#studentCount').text(students.length);

                if (students.length === 0) {
                    $('#noStudentMessage').show();
                    return;
                }

                $.each(students, function (index, studentCourse) {
                    let student = studentCourse.student;
                    let course = studentCourse.course;
                    let level = studentCourse.level;
                    let batch = studentCourse.batch;
                    let instructor = studentCourse.instructor;

                    let instructorHtml;

                    if (instructor) {
                        instructorHtml =
                            '<span class="badge bg-success">' +
                            escapeHtml(instructor.name) +
                            '</span>';
                    } else {
                        instructorHtml =
                            '<span class="badge bg-secondary">' +
                            'Not Assigned Yet' +
                            '</span>';
                    }

                    let row = `
                        <tr data-student-course-id="${studentCourse.id}">
                            <td class="text-center">
                                <input
                                    type="checkbox"
                                    class="form-check-input student-checkbox"
                                    value="${studentCourse.id}">
                            </td>
                            <td>${index + 1}</td>
                            <td>${escapeHtml(studentCourse.admission_no ?? '-')}</td>
                            <td>
                                <strong>
                                    ${escapeHtml(student?.name ?? '-')}
                                </strong>
                            </td>
                            <td>${escapeHtml(student?.phone ?? '-')}</td>
                            <td>${escapeHtml(course?.course_name ?? '-')}</td>
                            <td>${escapeHtml(level?.name ?? '-')}</td>
                            <td>${escapeHtml(batch?.batch_name ?? '-')}</td>
                            <td class="instructor-column">
                                ${instructorHtml}
                            </td>
                        </tr>
                    `;

                    $('#studentTableBody').append(row);
                });

                $('#assignSection').show();
            },
            error: function (xhr) {
                let message = 'Unable to search students.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    let errors = xhr.responseJSON.errors;

                    message = Object.values(errors)
                        .flat()
                        .join('\n');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Search Failed',
                    text: message
                });
            },
            complete: function () {
                button.prop('disabled', false);
                button.html(
                    '<i class="fas fa-search me-1"></i> Search Students'
                );
            }
        });
    });

    $('#selectAllStudents').on('change', function () {
        let checked = $(this).is(':checked');

        $('.student-checkbox').prop('checked', checked);
    });

    $(document).on('change', '.student-checkbox', function () {
        let total = $('.student-checkbox').length;
        let checked = $('.student-checkbox:checked').length;

        $('#selectAllStudents').prop(
            'checked',
            total > 0 && total === checked
        );

        $('#selectAllStudents').prop(
            'indeterminate',
            checked > 0 && checked < total
        );
    });

    $('#assignInstructorBtn').on('click', function () {
        let selectedStudents = [];

        $('.student-checkbox:checked').each(function () {
            selectedStudents.push($(this).val());
        });

        if (selectedStudents.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Student Selected',
                text: 'Please select at least one student.'
            });

            return;
        }

        let action = $('#assign_action').val();

        if (!action) {
            Swal.fire({
                icon: 'warning',
                title: 'Select Action',
                text: 'Please select Apply or Revert.'
            });

            return;
        }

        let instructorId = $('#instructor_id').val();
        let instructorName = $('#instructor_id option:selected')
            .text()
            .trim();

        if (action === 'apply' && !instructorId) {
            Swal.fire({
                icon: 'warning',
                title: 'Select Instructor',
                text: 'Please select an instructor/faculty.'
            });

            return;
        }

        let confirmationTitle;
        let confirmationHtml;
        let confirmButtonText;
        let confirmButtonColor;
        let alertIcon;

        if (action === 'apply') {
            confirmationTitle = 'Confirm Instructor Assignment';

            confirmationHtml =
                '<strong>' +
                selectedStudents.length +
                '</strong> student(s) will be assigned to ' +
                '<strong>' +
                escapeHtml(instructorName) +
                '</strong>.' +
                '<br><br>' +
                'Do you want to continue?';

            confirmButtonText = 'Yes, Apply';
            confirmButtonColor = '#198754';
            alertIcon = 'question';
        } else {
            confirmationTitle = 'Confirm Instructor Revert';

            confirmationHtml =
                '<strong>' +
                selectedStudents.length +
                '</strong> student(s) will have their instructor assignment removed.' +
                '<br><br>' +
                '<strong>This action cannot be undone automatically.</strong>' +
                '<br><br>' +
                'Are you sure you want to continue?';

            confirmButtonText = 'Yes, Revert';
            confirmButtonColor = '#dc3545';
            alertIcon = 'warning';
        }

        Swal.fire({
            icon: alertIcon,
            title: confirmationTitle,
            html: confirmationHtml,
            showCancelButton: true,
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Cancel',
            confirmButtonColor: confirmButtonColor,
            reverseButtons: true
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            updateInstructor(
                selectedStudents,
                action === 'apply' ? instructorId : null,
                action
            );
        });
    });

    function updateInstructor(studentCourseIds, instructorId, action) {
        let button = $('#assignInstructorBtn');

        button.prop('disabled', true);

        button.html(
            '<i class="fas fa-spinner fa-spin me-1"></i> Updating...'
        );

        $.ajax({
            url: "{{ route('assign-instuructor.assign') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                student_course_ids: studentCourseIds,
                instructor_id: instructorId,
                action: action
            },
            success: function (response) {
                if (!response.status) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: response.message || 'Unable to update instructor.'
                    });

                    return;
                }

                $.each(studentCourseIds, function (index, studentCourseId) {
                    let row = $(
                        'tr[data-student-course-id="' +
                        studentCourseId +
                        '"]'
                    );

                    let instructorColumn = row.find('.instructor-column');

                    if (action === 'apply') {
                        instructorColumn.html(
                            '<span class="badge bg-success">' +
                            escapeHtml(response.instructor.name) +
                            '</span>'
                        );
                    } else {
                        instructorColumn.html(
                            '<span class="badge bg-secondary">' +
                            'Not Assigned Yet' +
                            '</span>'
                        );
                    }
                });

                $('.student-checkbox').prop('checked', false);

                $('#selectAllStudents')
                    .prop('checked', false)
                    .prop('indeterminate', false);

                $('#assign_action')
                    .val('')
                    .trigger('change');

                $('#instructor_id')
                    .val('')
                    .trigger('change')
                    .prop('disabled', true);

                Swal.fire({
                    icon: 'success',
                    title: action === 'apply'
                        ? 'Instructor Assigned'
                        : 'Assignment Reverted',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                let message =
                    'Something went wrong while updating instructor.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    let errors = xhr.responseJSON.errors;

                    message = Object.values(errors)
                        .flat()
                        .join('\n');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: message
                });
            },
            complete: function () {
                button.prop('disabled', false);

                button.html(
                    '<i class="fas fa-user-check me-1"></i> ' +
                    'Update / Confirm Instructor'
                );
            }
        });
    }

    function escapeHtml(value) {
        if (value === null || value === undefined) {
            return '';
        }

        return $('<div>')
            .text(value)
            .html();
    }

});
</script>

@endpush
