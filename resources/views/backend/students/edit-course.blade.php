@extends('backend.partial.master')

@section('title','Edit Course')

@section('backend-content')

<div class="container-fluid">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('students.update-course',$studentCourse->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="card shadow">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-0">

                        Edit Course

                    </h4>

                    <small>

                        Student :
                        <strong>{{ $student->name }}</strong>

                    </small>

                </div>

                <a href="{{ route('students.courses',$student->id) }}"
                   class="btn btn-light">

                    Back

                </a>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Admission Date --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Admission Date

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="admission_date"
                            class="form-control"
                            value="{{ old('admission_date',\Carbon\Carbon::parse($studentCourse->admission_date)->format('Y-m-d')) }}"
                            required>

                    </div>

                    {{-- Admission Status --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Admission Status

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="is_enroll"
                            class="form-select"
                            required>

                            <option value="">Select Status</option>

                            <option
                                value="0"
                                {{ old('is_enroll',$studentCourse->is_enroll)==0?'selected':'' }}>

                                Not Enroll

                            </option>

                            <option
                                value="1"
                                {{ old('is_enroll',$studentCourse->is_enroll)==1?'selected':'' }}>

                                Enroll

                            </option>

                        </select>

                    </div>

                </div>

                <hr>

                <h5 class="mb-3">

                    Select Course

                </h5>

                <div class="row">

                    @foreach($courses as $course)

                        <div class="col-lg-4 col-md-6 mb-3">

                            <label class="card border cursor-pointer">

                                <div class="card-body">

                                    <div class="form-check">

                                        <input
                                            class="form-check-input course-radio"
                                            type="radio"
                                            name="course_id"
                                            value="{{ $course->id }}"
                                            {{ old('course_id',$studentCourse->course_id)==$course->id?'checked':'' }}>

                                        <label class="form-check-label fw-bold">

                                            {{ $course->course_name }}

                                        </label>

                                        <p class="m-0 course-duration"
                                            data-duration="{{ $course->duration }}"
                                            data-type="{{ $course->duration_type }}">

                                                {{ $course->duration }}
                                                {{ $course->duration_type }}

                                        </p>

                                    </div>

                                </div>

                            </label>

                        </div>

                    @endforeach

                </div>

                <hr>

                <div class="row">

                    {{-- Level --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Level

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="level_id"
                            id="level_id"
                            class="form-select"
                            required>

                            <option value="">Select Level</option>

                            @foreach($levels as $level)

                                <option
                                    value="{{ $level->id }}"
                                    {{ old('level_id',$studentCourse->level_id)==$level->id?'selected':'' }}>

                                    {{ $level->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Category --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Category

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="category_id"
                            id="category_id"
                            class="form-select"
                            required>

                            <option value="">Select Category</option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    {{ old('category_id',$studentCourse->category_id)==$category->id?'selected':'' }}>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Faculty --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Faculty

                        </label>

                        <select
                            name="instructor_id"
                            class="form-select">

                            <option value="">

                                Select Faculty

                            </option>

                            @foreach($faculty as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ old('instructor_id',$studentCourse->instructor_id)==$item->id?'selected':'' }}>

                                    {{ $item->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Course Status --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Course Status

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required>

                            <option
                                value="ongoing"
                                {{ old('status',$studentCourse->status)=='ongoing'?'selected':'' }}>

                                Ongoing

                            </option>

                            <option
                                value="completed"
                                {{ old('status',$studentCourse->status)=='completed'?'selected':'' }}>

                                Completed

                            </option>

                            <option
                                value="discontinued"
                                {{ old('status',$studentCourse->status)=='discontinued'?'selected':'' }}>

                                Discontinued

                            </option>

                        </select>

                    </div>

                    {{-- Hidden Batch --}}
                    <input
                        type="hidden"
                        id="selected_batch_id"
                        name="batch_id"
                        value="{{ old('batch_id',$studentCourse->batch_id) }}">

                </div>

                <hr>

                                {{-- =========================
                    Available Batches
                ========================== --}}

                <h5 class="mb-3">

                    Available Batches

                </h5>

                <div id="batch_container">

                    <div class="alert alert-secondary mb-0">

                        Loading batches...

                    </div>

                </div>

                <hr>

                {{-- =========================
                    Fee Details
                ========================== --}}

                <h5 class="mb-3">

                    Fee Details

                </h5>

                <div class="row">

                    {{-- Registration Fee --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Registration Fee

                        </label>

                        <input
                            type="text"
                            id="registration_fee"
                            name="registration_fee"
                            class="form-control"
                            readonly
                            value="{{ old('registration_fee',$studentCourse->registration_fee) }}">

                    </div>

                    {{-- Admission Fee --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Admission Fee

                        </label>

                        <input
                            type="text"
                            id="admission_fee"
                            name="admission_fee"
                            class="form-control"
                            readonly
                            value="{{ old('admission_fee',$studentCourse->admission_fee) }}">

                    </div>

                    {{-- Monthly Fee --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Monthly Fee

                        </label>

                        <input
                            type="text"
                            id="monthly_fee"
                            name="monthly_fee"
                            class="form-control"
                            readonly
                            value="{{ old('monthly_fee',$studentCourse->course_fee) }}">

                    </div>

                </div>

                <h5 class="mb-3">Fee Summary</h5>

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <tbody>

                            <tr>
                                <th width="35%">Registration Fee</th>
                                <td id="summary_registration">0.00</td>
                            </tr>

                            <tr>
                                <th>Admission Fee</th>
                                <td id="summary_admission">0.00</td>
                            </tr>

                            <tr>
                                <th>Monthly Fee</th>
                                <td>

                                    <span id="summary_monthly">0.00</span>

                                    ×

                                    <span id="summary_duration">
                                        {{ old('course_duration',$studentCourse->course_duration) }}
                                    </span>

                                    {{ old('duration_type',$studentCourse->duration_type) }}

                                    =

                                    <strong id="summary_total_monthly">0.00</strong>

                                </td>
                            </tr>

                            <tr class="table-success">

                                <th>Grand Total</th>

                                <th id="summary_total">0.00</th>

                            </tr>

                        </tbody>

                    </table>

                    <input
                        type="hidden"
                        name="course_duration"
                        id="course_duration"
                        value="{{ old('course_duration',$studentCourse->course_duration) }}">

                    <input
                        type="hidden"
                        name="duration_type"
                        id="duration_type"
                        value="{{ old('duration_type',$studentCourse->duration_type) }}">

                    <input
                        type="hidden"
                        name="total_monthly_fee"
                        id="total_monthly_fee"
                        value="{{ old('total_monthly_fee',$studentCourse->total_monthly_fee) }}">

                    <input
                        type="hidden"
                        name="grand_total"
                        id="grand_total"
                        value="{{ old('grand_total',$studentCourse->grand_total) }}">

                </div>

                <hr>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('students.courses',$student->id) }}"
                       class="btn btn-secondary">

                        Back

                    </a>

                    <button
                        type="submit"
                        class="btn btn-success">

                        <i class="fa fa-save me-1"></i>

                        Update Course

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection

@push('scripts')

<script>

$(function () {

    /*
    |--------------------------------------------------------------------------
    | Fetch Batch
    |--------------------------------------------------------------------------
    */

    function fetchBatches() {

        let courseId = $('input[name="course_id"]:checked').val();

        let levelId = $('#level_id').val();

        let selectedBatch = $('#selected_batch_id').val();

        let container = $('#batch_container');

        if (!courseId || !levelId) {

            container.html(`
                <div class="alert alert-secondary">
                    Select Course & Level First.
                </div>
            `);

            return;
        }

        container.html(`
            <div class="text-center p-4">

                <div class="spinner-border text-primary"></div>

                <p class="mt-2 mb-0">

                    Loading Batches...

                </p>

            </div>
        `);

        $.ajax({

            url: "{{ route('fetch.batches') }}",

            type: "GET",

            data: {

                course_id: courseId,

                level_id: levelId

            },

            success: function(response){

                container.empty();

                if(response.status && response.batches.length){

                    $.each(response.batches,function(index,batch){

                        let badge='';

                        let disabled='';

                        let checked='';

                        let activeClass='';

                        if(batch.is_full){

                            badge='<span class="badge bg-danger">Full</span>';

                            disabled='disabled';

                        }else{

                            badge='<span class="badge bg-success">Available</span>';

                        }

                        if(selectedBatch==batch.id){

                            checked='checked';

                            activeClass='border-primary shadow';

                        }

                        container.append(`

<div class="card mb-3 batch-card border ${activeClass}">

    <div class="card-body">

        <div class="form-check">

            <input
                class="form-check-input batch-radio"
                type="radio"
                name="batch_radio"
                value="${batch.id}"
                ${checked}
                ${disabled}>

            <label class="form-check-label w-100">

                <div class="d-flex justify-content-between">

                    <h5 class="mb-0">

                        ${batch.batch_name}

                    </h5>

                    ${badge}

                </div>

                <hr>

                <div class="row text-center">

                    <div class="col-md-3">

                        <strong>Time</strong>

                        <br>

                        ${batch.start_time} - ${batch.end_time}

                    </div>

                    <div class="col-md-3">

                        <strong>Days</strong>

                        <br>

                        ${batch.days_text}

                    </div>

                    <div class="col-md-3">

                        <strong>Capacity</strong>

                        <br>

                        ${batch.enrolled_students}/${batch.capacity}

                    </div>

                    <div class="col-md-3">

                        <strong>Status</strong>

                        <br>

                        ${badge}

                    </div>

                </div>

            </label>

        </div>

    </div>

</div>

`);

                    });

                }else{

                    container.html(`

<div class="alert alert-warning">

    No Batch Available.

</div>

`);

                }

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Fetch Fee Structure
    |--------------------------------------------------------------------------
    */

    function fetchFeeStructure() {

        let course = $('input[name="course_id"]:checked');

        let courseId = course.val();

        let levelId = $("#level_id").val();

        let categoryId = $("#category_id").val();

        let regFee = $("#registration_fee");

        let admFee = $("#admission_fee");

        let monthFee = $("#monthly_fee");

        /*
        |--------------------------------------------------------------------------
        | Reset
        |--------------------------------------------------------------------------
        */

        regFee.val("");

        admFee.val("");

        monthFee.val("");

        $("#summary_registration").text("0.00");

        $("#summary_admission").text("0.00");

        $("#summary_monthly").text("0.00");

        $("#summary_duration").text("0");

        $("#summary_total_monthly").text("0.00");

        $("#summary_total").text("0.00");

        $("#course_duration").val("");

        $("#duration_type").val("");

        $("#total_monthly_fee").val("");

        $("#grand_total").val("");

        if (!courseId || !levelId || !categoryId) {

            return;

        }

        regFee.val("Loading...");

        admFee.val("Loading...");

        monthFee.val("Loading...");

        $.ajax({

            url: "{{ route('fetch.fee.structure') }}",

            type: "GET",

            data: {

                course_id: courseId,

                level_id: levelId,

                category_id: categoryId

            },

            success: function (response) {

                if (response.status) {

                    let registrationFee = parseFloat(response.data.registration_fee) || 0;

                    let admissionFee = parseFloat(response.data.admission_fee) || 0;

                    let monthlyFee = parseFloat(response.data.monthly_fee) || 0;

                    let duration = parseInt(
                        course.closest(".card")
                            .find(".course-duration")
                            .data("duration")
                    ) || 0;

                    let durationType =
                        course.closest(".card")
                            .find(".course-duration")
                            .data("type");

                    let totalMonthlyFee = monthlyFee * duration;

                    let grandTotal =
                        registrationFee +
                        admissionFee +
                        totalMonthlyFee;

                    /*
                    |--------------------------------------------------------------------------
                    | Fee Inputs
                    |--------------------------------------------------------------------------
                    */

                    regFee.val(registrationFee.toFixed(2));

                    admFee.val(admissionFee.toFixed(2));

                    monthFee.val(monthlyFee.toFixed(2));

                    /*
                    |--------------------------------------------------------------------------
                    | Hidden Inputs
                    |--------------------------------------------------------------------------
                    */

                    $("#course_duration").val(duration);

                    $("#duration_type").val(durationType);

                    $("#total_monthly_fee").val(totalMonthlyFee.toFixed(2));

                    $("#grand_total").val(grandTotal.toFixed(2));

                    /*
                    |--------------------------------------------------------------------------
                    | Summary
                    |--------------------------------------------------------------------------
                    */

                    $("#summary_registration").text(registrationFee.toFixed(2));

                    $("#summary_admission").text(admissionFee.toFixed(2));

                    $("#summary_monthly").text(monthlyFee.toFixed(2));

                    $("#summary_duration").text(duration + " " + durationType);

                    $("#summary_total_monthly").text(totalMonthlyFee.toFixed(2));

                    $("#summary_total").text(grandTotal.toFixed(2));

                } else {

                    regFee.val("");

                    admFee.val("");

                    monthFee.val("");

                    console.log(response.message);

                }

            },

            error: function (xhr) {

                regFee.val("");

                admFee.val("");

                monthFee.val("");

                console.log(xhr.responseText);

            }

        });

    }
        /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    $(document).on('change','input[name="course_id"]',function(){

        fetchBatches();

        fetchFeeStructure();

    });

    $('#level_id').on('change',function(){

        fetchBatches();

        fetchFeeStructure();

    });

    $('#category_id').on('change',function(){

        fetchFeeStructure();

    });

    /*
    |--------------------------------------------------------------------------
    | Batch Selection
    |--------------------------------------------------------------------------
    */

    $(document).on('change','.batch-radio',function(){

        $('#selected_batch_id').val($(this).val());

        $('.batch-card')
            .removeClass('border-primary shadow');

        $(this)
            .closest('.batch-card')
            .addClass('border-primary shadow');

    });

    /*
    |--------------------------------------------------------------------------
    | Page Load
    |--------------------------------------------------------------------------
    */

    fetchBatches();

    fetchFeeStructure();

});

</script>

@endpush
