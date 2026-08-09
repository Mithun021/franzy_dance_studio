@extends('backend.partial.master')

@section('title','Student Attendance')

@section('backend-content')

<div class="container-fluid">

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

    {{-- Page Heading --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <h4 class="mb-0">
                        <i data-feather="calendar"></i>
                        Add Attendance
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance Form --}}
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            Take Attendance
        </div>

        <div class="card-body">

            <form id="attendanceForm">

                @csrf

                <div class="row">

                    {{-- Attendance Date --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Attendance Date
                        </label>

                        <input
                            type="date"
                            name="attendance_date"
                            id="attendance_date"
                            class="form-control"
                            value="{{ date('Y-m-d') }}"
                            required>

                    </div>

                    {{-- Course --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Course
                        </label>

                        <select
                            name="course_id"
                            id="course_id"
                            class="form-control">

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

                        <label class="form-label">
                            Batch
                        </label>

                        <select
                            name="batch_id"
                            id="batch_id"
                            class="form-control">

                            <option value="">
                                Select Batch
                            </option>

                        </select>

                    </div>

                </div>

                <div class="text-end">

                    <button
                        type="button"
                        id="searchStudent"
                        class="btn btn-primary">

                        <i data-feather="search"></i>

                        Load Students

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- Student Table --}}
    <div
        id="studentAttendanceTable"
        class="mt-4">

    </div>

</div>

@endsection

@push('scripts')

<script>
$(document).ready(function () {

    $('#course_id').change(function () {

        let course_id = $(this).val();

        $('#batch_id').html('<option value="">Loading...</option>');

        if(course_id == '')
        {
            $('#batch_id').html('<option value="">Select Batch</option>');
            return;
        }

        $.ajax({

            url: "{{ route('attendance.fetch-batches') }}",

            type: "POST",

            data: {
                _token: "{{ csrf_token() }}",
                course_id: course_id
            },

            success:function(response){

                let option = '<option value="">Select Batch</option>';

                $.each(response,function(index,row){

                    option += '<option value="'+row.id+'">'+row.batch_name+'</option>';

                });

                $('#batch_id').html(option);

            }

        });

    });

});
</script>

<script>

$(document).on('click','#searchStudent',function(){

    let course_id = $('#course_id').val();
    let batch_id  = $('#batch_id').val();
    let attendance_date = $('#attendance_date').val();

    if(course_id == '' || batch_id == '' || attendance_date == '')
    {
        alert('Please select Attendance Date, Course and Batch.');
        return;
    }

    $.ajax({

        url:"{{ route('attendance.fetch-students') }}",

        type:"POST",

        data:{
            _token:"{{ csrf_token() }}",
            course_id:course_id,
            batch_id:batch_id,
            attendance_date:attendance_date
        },

        beforeSend:function(){

            $('#studentAttendanceTable').html(
                '<div class="text-center p-3">Loading...</div>'
            );

        },

        success:function(response){

            $('#studentAttendanceTable').html(response);

            feather.replace();

        }

    });

});
</script>

@endpush
