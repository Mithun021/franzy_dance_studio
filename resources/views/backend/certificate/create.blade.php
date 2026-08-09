@extends('backend.partial.master')

@section('title','Upload Certificate')

@section('backend-content')

<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="row mb-3">

        <div class="col-md-12">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h4 class="mb-0">

                        <i data-feather="award"></i>

                        Student Certificate Upload

                    </h4>

                </div>

            </div>

        </div>

    </div>

    {{-- Search Card --}}
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">

            Select Course

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <label class="form-label">

                        Course <span class="text-danger">*</span>

                    </label>

                    <select
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

                <div class="col-md-2 d-flex align-items-end">

                    <button
                        type="button"
                        id="searchStudent"
                        class="btn btn-primary w-100">

                        <i data-feather="search"></i>

                        Search

                    </button>

                </div>

            </div>

        </div>

    </div>

    {{-- Student List --}}
    <div
        id="studentList"
        class="mt-4">

    </div>

</div>

@endsection


@push('scripts')

<script>

$(document).on('click','#searchStudent',function(){

    let course_id = $('#course_id').val();

    if(course_id=='')
    {
        alert('Please select course.');
        return;
    }

    $.ajax({

        url:"{{ route('certificate.fetch-students') }}",

        type:"POST",

        data:{
            _token:"{{ csrf_token() }}",
            course_id:course_id
        },

        beforeSend:function(){

            $('#studentList').html(

                '<div class="card shadow-sm">'+
                    '<div class="card-body text-center">'+
                        'Loading Students...'+
                    '</div>'+
                '</div>'

            );

        },

        success:function(response){

            $('#studentList').html(response);

            feather.replace();

        },

        error:function(){

            alert('Something went wrong.');

        }

    });

});

</script>

@endpush
