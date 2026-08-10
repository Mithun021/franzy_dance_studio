@extends('backend.partial.master')

@section('title','Add Course')

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

    <form action="{{ route('students.store-course',$student->id) }}"
          method="POST">

        @csrf

        <div class="card shadow">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-0">

                        Add Course

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
                            value="{{ old('admission_date',date('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Admission Status

                            <span class="text-danger">*</span>

                        </label>

                        <select name="is_enroll" class="form-control" required>
                            <option value="">Select Status</option>
                            <option value="0" {{ old('is_enroll') == '0' ? 'selected' : '' }}>
                                Not Enroll
                            </option>
                            <option value="1" {{ old('is_enroll') == '1' ? 'selected' : '' }}>
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
                                            {{ old('course_id')==$course->id ? 'checked':'' }}>

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

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Level

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="level_id"
                            id="level_id"
                            class="form-select"
                            required>

                            <option value="">

                                Select Level

                            </option>

                            @foreach($levels as $level)

                                <option
                                    value="{{ $level->id }}"
                                    {{ old('level_id')==$level->id?'selected':'' }}>

                                    {{ $level->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Category --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Category

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="category_id"
                            id="category_id"
                            class="form-select"
                            required>

                            <option value="">

                                Select Category

                            </option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    {{ old('category_id')==$category->id?'selected':'' }}>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Faculty --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Faculty
                        </label>

                        <select
                            name="instructor_id"
                            id="instructor_id"
                            class="form-select"
                            >

                            <option value="">

                                Select Faculty

                            </option>

                            @foreach($faculty as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ old('instructor_id')==$item->id?'selected':'' }}>

                                    {{ $item->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Hidden Batch ID --}}

                    <input
                        type="hidden"
                        name="batch_id"
                        id="selected_batch_id"
                        value="{{ old('batch_id') }}">

                </div>

                <hr>

                <h5 class="mb-3">

                    Available Batches

                </h5>

                <div id="batch_container">

                    <div class="alert alert-secondary mb-0">

                        Select Course & Level First

                    </div>

                </div>

                <hr>

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
                            name="registration_fee"
                            id="registration_fee"
                            class="form-control"
                            value="{{ old('registration_fee','0.00') }}"
                            readonly>

                    </div>

                    {{-- Admission Fee --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Admission Fee

                        </label>

                        <input
                            type="text"
                            name="admission_fee"
                            id="admission_fee"
                            class="form-control"
                            value="{{ old('admission_fee','0.00') }}"
                            readonly>

                    </div>

                    {{-- Monthly Fee --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Monthly Fee

                        </label>

                        <input
                            type="text"
                            name="monthly_fee"
                            id="monthly_fee"
                            class="form-control"
                            value="{{ old('monthly_fee','0.00') }}"
                            readonly>

                    </div>

                </div>

                <hr>

                <h5 class="mb-3">

                    Fee Summary

                </h5>

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <tbody>

                            <tr>

                                <th width="35%">

                                    Registration Fee

                                </th>

                                <td id="summary_registration">

                                    0.00

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Admission Fee

                                </th>

                                <td id="summary_admission">

                                    0.00

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Monthly Fee

                                </th>

                                <td>

                                    <span id="summary_monthly">

                                        0.00

                                    </span>

                                    ×

                                    <span id="summary_duration">

                                        {{ old('course_duration',$studentCourse->course_duration ?? 0) }}

                                    </span>

                                    {{ old('duration_type',$studentCourse->duration_type ?? '') }}

                                    =

                                    <strong id="summary_total_monthly">

                                        0.00

                                    </strong>

                                </td>

                            </tr>

                            <tr class="table-success">

                                <th>

                                    Grand Total

                                </th>

                                <th id="summary_total">

                                    0.00

                                </th>

                            </tr>

                        </tbody>

                    </table>

                </div>

                <input type="hidden" name="course_duration" id="course_duration"
                value="{{ old('course_duration',$studentCourse->course_duration ?? '') }}">

                <input type="hidden" name="duration_type" id="duration_type"
                value="{{ old('duration_type',$studentCourse->duration_type ?? '') }}">

                <input type="hidden" name="total_monthly_fee" id="total_monthly_fee">

                <input type="hidden" name="grand_total" id="grand_total">

                <hr>

                    <h5 class="mb-3">

                        Payment Details

                    </h5>

                    <div class="card border">

                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                            <h6 class="mb-0">

                                Payment Entries

                            </h6>

                            <button
                                type="button"
                                class="btn btn-light btn-sm"
                                id="addPaymentRow">

                                <i class="fa fa-plus"></i>

                                Add Payment

                            </button>

                        </div>

                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-bordered align-middle mb-0" id="paymentTable">

                                    <thead class="table-light">

                                        <tr>

                                            <th width="18%">

                                                Payment Mode

                                            </th>

                                            <th width="18%">

                                                Amount

                                            </th>

                                            <th width="20%">

                                                Transaction No.

                                            </th>

                                            <th>

                                                Remarks

                                            </th>

                                            <th width="8%">

                                                Action

                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>

                                            <td>

                                                <select
                                                    name="payment_mode[]"
                                                    class="form-select payment-mode">

                                                    <option value="">

                                                        Select

                                                    </option>

                                                    <option value="Cash">

                                                        Cash

                                                    </option>

                                                    <option value="UPI">

                                                        UPI

                                                    </option>

                                                    <option value="Card">

                                                        Card

                                                    </option>

                                                    <option value="Bank Transfer">

                                                        Bank Transfer

                                                    </option>

                                                    <option value="Cheque">

                                                        Cheque

                                                    </option>

                                                </select>

                                            </td>

                                            <td>

                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    name="amount[]"
                                                    class="form-control payment-amount"
                                                    placeholder="0.00">



                                            </td>

                                            <td>

                                                <input
                                                    type="text"
                                                    name="transaction_id[]"
                                                    class="form-control"
                                                    placeholder="Txn / Ref No">

                                            </td>

                                            <td>

                                                <input
                                                    type="text"
                                                    name="remarks[]"
                                                    class="form-control"
                                                    placeholder="Remarks">

                                            </td>

                                            <td class="text-center">

                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm removeRow">

                                                    <i class="mdi mdi-delete fs-14"></i>

                                                </button>

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                    <br>

                    <div class="row">

                        <div class="col-md-4">

                            <label class="fw-bold">

                                Total Payable

                            </label>

                            <input
                                type="text"
                                id="totalPayable"
                                class="form-control bg-light fw-bold"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label class="fw-bold text-success">

                                Total Paid

                            </label>

                            <input
                                type="text"
                                id="totalPaid"
                                class="form-control bg-light fw-bold"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label class="fw-bold text-danger">

                                Due Amount

                            </label>

                            <input
                                type="text"
                                id="dueAmount"
                                class="form-control bg-light fw-bold"
                                readonly>

                        </div>

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

                        Save Course

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection

@push('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Fetch Batches
    |--------------------------------------------------------------------------
    */

    function fetchBatches() {

        let courseId = $('input[name="course_id"]:checked').val();
        let levelId  = $('#level_id').val();

        let container  = $('#batch_container');
        let hiddenInput = $('#selected_batch_id');

        hiddenInput.val('');

        if (!courseId || !levelId) {

            container.html(`
                <div class="alert alert-secondary mb-0">
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

            success: function (response) {

                container.empty();

                if (response.status && response.batches.length > 0) {

                    $.each(response.batches, function (index, batch) {

                        let badge = '';
                        let disabled = '';

                        if (batch.is_full) {

                            badge = '<span class="badge bg-danger">Full</span>';

                            disabled = 'disabled';

                        } else {

                            badge = '<span class="badge bg-success">Available</span>';

                        }

                        container.append(`

                            <div class="card mb-3 batch-card border">

                                <div class="card-body">

                                    <div class="form-check">

                                        <input
                                            class="form-check-input batch-radio"
                                            type="radio"
                                            name="batch_radio"
                                            value="${batch.id}"
                                            // ${disabled}
                                            >

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

                } else {

                    container.html(`

                        <div class="alert alert-warning mb-0">

                            No Batch Available.

                        </div>

                    `);

                }

            },

            error: function () {

                container.html(`

                    <div class="alert alert-danger mb-0">

                        Failed to load batches.

                    </div>

                `);

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

        let levelId = $('#level_id').val();

        // let categoryId = $('#category_id').val();

        let regFee = $('#registration_fee');

        let admFee = $('#admission_fee');

        let monthFee = $('#monthly_fee');

        regFee.val('');

        admFee.val('');

        monthFee.val('');

        // Summary Reset
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

        if (!courseId || !levelId) {

            return;

        }

        regFee.val('Loading...');

        admFee.val('Loading...');

        monthFee.val('Loading...');

        $.ajax({

            url: "{{ route('fetch.fee.structure') }}",

            type: "GET",

            data: {

                course_id: courseId,

                level_id: levelId,

                // category_id: categoryId

            },

            success: function(response) {

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

                    let totalAmount =
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

                    $("#grand_total").val(totalAmount.toFixed(2));

                    /*
                    |--------------------------------------------------------------------------
                    | Fee Summary
                    |--------------------------------------------------------------------------
                    */

                    $("#summary_registration").text(registrationFee.toFixed(2));

                    $("#summary_admission").text(admissionFee.toFixed(2));

                    $("#summary_monthly").text(monthlyFee.toFixed(2));

                    $("#summary_duration").text(duration + " " + durationType);

                    $("#summary_total_monthly").text(totalMonthlyFee.toFixed(2));

                    $("#summary_total").text(totalAmount.toFixed(2));

                    /*
                    |--------------------------------------------------------------------------
                    | Payment Summary (if available)
                    |--------------------------------------------------------------------------
                    */

                    $("#totalPayable").val(totalAmount.toFixed(2));

                    let totalPaid = 0;

                    $(".payment-amount").each(function () {

                        totalPaid += parseFloat($(this).val()) || 0;

                    });

                    let due = totalAmount - totalPaid;

                    if (due < 0) {

                        due = 0;

                    }

                    $("#totalPaid").val(totalPaid.toFixed(2));

                    $("#dueAmount").val(due.toFixed(2));

                } else {

                    regFee.val('');

                    admFee.val('');

                    monthFee.val('');

                    console.log(response.message);

                }

            },

            error: function(xhr) {

                regFee.val('');

                admFee.val('');

                monthFee.val('');

                console.log(xhr.responseText);

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    $(document).on('change', 'input[name="course_id"]', function () {

        fetchBatches();

        fetchFeeStructure();

    });

    $('#level_id').on('change', function () {

        fetchBatches();

        fetchFeeStructure();

    });

    // $('#category_id').on('change', function () {

    //     fetchFeeStructure();

    // });

    $(document).on('change', '.batch-radio', function () {

        $('#selected_batch_id').val($(this).val());

        $('.batch-card').removeClass('border-primary shadow');

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

@push('scripts')

<script>

$(document).ready(function(){

    /*
    |--------------------------------------------------------------------------
    | Calculate Total
    |--------------------------------------------------------------------------
    */

    function calculateTotal(){

        let registration = parseFloat($("#registration_fee").val()) || 0;

        let admission = parseFloat($("#admission_fee").val()) || 0;

        let monthly = parseFloat($("#monthly_fee").val()) || 0;

        let totalPayable = registration + admission + monthly;

        let totalPaid = 0;

        $(".payment-amount").each(function(){

            totalPaid += parseFloat($(this).val()) || 0;

        });

        let due = totalPayable - totalPaid;

        if(due < 0){

            due = 0;

        }

        $("#totalPayable").val(totalPayable.toFixed(2));

        $("#totalPaid").val(totalPaid.toFixed(2));

        $("#dueAmount").val(due.toFixed(2));

    }

    /*
    |--------------------------------------------------------------------------
    | Add Payment Row
    |--------------------------------------------------------------------------
    */

    $("#addPaymentRow").click(function(){

        let row = `

        <tr>

            <td>

                <select
                    name="payment_mode[]"
                    class="form-select payment-mode">

                    <option value="">Select</option>

                    <option value="Cash">Cash</option>

                    <option value="UPI">UPI</option>

                    <option value="Card">Card</option>

                    <option value="Bank Transfer">Bank Transfer</option>

                    <option value="Cheque">Cheque</option>

                </select>

            </td>

            <td>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="amount[]"
                    class="form-control payment-amount"
                    placeholder="0.00">

            </td>

            <td>

                <input
                    type="text"
                    name="transaction_id[]"
                    class="form-control transaction-id"
                    placeholder="Txn / Ref No">

            </td>

            <td>

                <input
                    type="text"
                    name="remarks[]"
                    class="form-control"
                    placeholder="Remarks">

            </td>

            <td class="text-center">

                <button
                    type="button"
                    class="btn btn-danger btn-sm removeRow">

                    <i class="mdi mdi-delete fs-14"></i>

                </button>

            </td>

        </tr>

        `;

        $("#paymentTable tbody").append(row);

    });

    /*
    |--------------------------------------------------------------------------
    | Remove Row
    |--------------------------------------------------------------------------
    */

    $(document).on("click",".removeRow",function(){

        if($("#paymentTable tbody tr").length==1){

            alert("At least one payment row is required.");

            return;

        }

        $(this).closest("tr").remove();

        calculateTotal();

    });

    /*
    |--------------------------------------------------------------------------
    | Amount Change
    |--------------------------------------------------------------------------
    */

    $(document).on("keyup change",".payment-amount",function(){

        calculateTotal();

    });

    /*
    |--------------------------------------------------------------------------
    | Payment Mode Change
    |--------------------------------------------------------------------------
    */

    $(document).on("change",".payment-mode",function(){

        let mode=$(this).val();

        let txn=$(this).closest("tr").find(".transaction-id");

        if(mode=="Cash"){

            txn.val("");

            txn.prop("readonly",true);

            txn.attr("placeholder","Not Required");

        }else{

            txn.prop("readonly",false);

            txn.attr("placeholder","Transaction / Ref No");

        }

    });

    /*
    |--------------------------------------------------------------------------
    | Fee Change
    |--------------------------------------------------------------------------
    */

    $("#registration_fee,#admission_fee,#monthly_fee").on("keyup change",function(){

        calculateTotal();

    });

    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    calculateTotal();

});

</script>

@endpush
