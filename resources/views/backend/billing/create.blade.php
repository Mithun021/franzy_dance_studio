@extends('backend.partial.master')

@section('title','New Billing')

@section('backend-content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Alerts --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

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



    {{-- ========================================================= --}}
    {{-- Billing Form --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('billing.store') }}"
        method="POST"
        id="billingForm">

        @csrf


        <div class="card shadow">

            {{-- ================================================= --}}
            {{-- Header --}}
            {{-- ================================================= --}}

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-0">
                        Student Billing
                    </h4>

                    <small>
                        Collect Monthly / Admission Fees
                    </small>

                </div>


                <a
                    href="{{ route('billing.index') }}"
                    class="btn btn-light">

                    <i class="fa fa-arrow-left me-1"></i>

                    Back

                </a>

            </div>


            {{-- ================================================= --}}
            {{-- Body --}}
            {{-- ================================================= --}}

            <div class="card-body">


                {{-- ================================================= --}}
                {{-- Student & Course --}}
                {{-- ================================================= --}}

                <div class="row">

                    {{-- Student --}}

                    <div class="col-md-6 mb-3">

                        <label
                            for="student_id"
                            class="form-label">

                            Student

                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="student_id"
                            id="student_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Student
                            </option>


                            @foreach($students as $student)

                                <option
                                    value="{{ $student->id }}">

                                    {{ $student->name }}
                                    ({{ $student->phone }})

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Student Course --}}

                    <div class="col-md-6 mb-3">

                        <label
                            for="student_course_id"
                            class="form-label">

                            Course

                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="student_course_id"
                            id="student_course_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Student First
                            </option>

                        </select>

                    </div>

                </div>


                <hr>



                {{-- ================================================= --}}
                {{-- Course Information --}}
                {{-- ================================================= --}}

                <h5 class="mb-3">
                    Course Information
                </h5>


                <div class="card border">

                    <div class="card-body">

                        <div class="row">


                            {{-- Admission No --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="admission_no"
                                    class="fw-bold">

                                    Admission No

                                </label>


                                <input
                                    type="text"
                                    id="admission_no"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Admission Date --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="admission_date"
                                    class="fw-bold">

                                    Admission Date

                                </label>


                                <input
                                    type="text"
                                    id="admission_date"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Course Duration --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="course_duration"
                                    class="fw-bold">

                                    Course Duration

                                </label>


                                <input
                                    type="text"
                                    id="course_duration"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Batch --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="batch_name"
                                    class="fw-bold">

                                    Batch

                                </label>


                                <input
                                    type="text"
                                    id="batch_name"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                        </div>


                        <div class="row">


                            {{-- Level --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="level_name"
                                    class="fw-bold">

                                    Level

                                </label>


                                <input
                                    type="text"
                                    id="level_name"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Category --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="category_name"
                                    class="fw-bold">

                                    Category

                                </label>


                                <input
                                    type="text"
                                    id="category_name"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Course --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="course_name"
                                    class="fw-bold">

                                    Course

                                </label>


                                <input
                                    type="text"
                                    id="course_name"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                        </div>

                    </div>

                </div>


                <br>



                {{-- ================================================= --}}
                {{-- Fee Summary --}}
                {{-- ================================================= --}}

                <h5 class="mb-3">
                    Fee Summary
                </h5>


                <div class="card border">

                    <div class="card-body">

                        <div class="row">


                            {{-- Registration Fee --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="registration_fee"
                                    class="fw-bold">

                                    Registration Fee

                                </label>


                                <input
                                    type="text"
                                    id="registration_fee"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Admission Fee --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="admission_fee"
                                    class="fw-bold">

                                    Admission Fee

                                </label>


                                <input
                                    type="text"
                                    id="admission_fee"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Monthly Fee --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="course_fee"
                                    class="fw-bold">

                                    Monthly Fee

                                </label>


                                <input
                                    type="text"
                                    id="course_fee"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Grand Total --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="grand_total"
                                    class="fw-bold">

                                    Grand Total

                                </label>


                                <input
                                    type="text"
                                    id="grand_total"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>

                        </div>


                        {{-- Hidden Calculation Values --}}

                        <input
                            type="hidden"
                            id="payment_count"
                            value="0">


                        <input
                            type="hidden"
                            id="next_payable"
                            value="0">


                        <input
                            type="hidden"
                            id="remaining_amount"
                            value="0">

                    </div>

                </div>


                <hr>



                {{-- ========================================================= --}}
                {{-- Late Fine --}}
                {{-- ========================================================= --}}

                <div
                    id="lateFineSection"
                    class="mt-4 d-none">

                    <h5 class="mb-3">
                        Late Fine
                    </h5>


                    <div class="card border">


                        {{-- Late Fine Header --}}

                        <div class="card-header bg-warning">

                            <h6 class="mb-0">

                                <i class="fa fa-clock me-1"></i>

                                Late Fine Details

                            </h6>

                        </div>


                        {{-- Late Fine Body --}}

                        <div class="card-body">


                            <div class="row">


                                {{-- Payment Date --}}

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="late_payment_date"
                                        class="fw-bold">

                                        Payment Date

                                    </label>


                                    <input
                                        type="text"
                                        id="late_payment_date"
                                        class="form-control bg-light"
                                        readonly>

                                </div>


                                {{-- Due Date --}}

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="late_due_date"
                                        class="fw-bold">

                                        Due Date

                                    </label>


                                    <input
                                        type="text"
                                        id="late_due_date"
                                        class="form-control bg-light"
                                        readonly>

                                </div>


                                {{-- Previous Payment --}}

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="late_previous_payment"
                                        class="fw-bold">

                                        Previous Payment

                                    </label>


                                    <input
                                        type="text"
                                        id="late_previous_payment"
                                        class="form-control bg-light"
                                        readonly>

                                </div>


                                {{-- Late Fine --}}

                                <div class="col-md-3 mb-3">

                                    <label
                                        for="late_fine_amount"
                                        class="fw-bold">

                                        Late Fine

                                    </label>


                                    <input
                                        type="text"
                                        id="late_fine_amount"
                                        class="form-control bg-light fw-bold text-danger"
                                        value="0.00"
                                        readonly>

                                </div>

                            </div>



                            <div class="row">


                                {{-- Fine Type --}}

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="late_fine_type"
                                        class="fw-bold">

                                        Fine Type

                                    </label>


                                    <input
                                        type="text"
                                        id="late_fine_type"
                                        class="form-control bg-light"
                                        readonly>

                                </div>


                                {{-- Attendance Month --}}

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="late_attendance_month"
                                        class="fw-bold">

                                        Attendance Month

                                    </label>


                                    <input
                                        type="text"
                                        id="late_attendance_month"
                                        class="form-control bg-light"
                                        readonly>

                                </div>


                                {{-- Attendance Status --}}

                                <div class="col-md-4 mb-3">

                                    <label
                                        for="late_attendance_status"
                                        class="fw-bold">

                                        Attendance Status

                                    </label>


                                    <input
                                        type="text"
                                        id="late_attendance_status"
                                        class="form-control bg-light"
                                        readonly>

                                </div>

                            </div>



                            {{-- Fine Message --}}

                            <div
                                id="lateFineMessage"
                                class="alert mb-0 d-none">
                            </div>



                            {{-- Hidden Late Fine Values --}}

                            <input
                                type="hidden"
                                name="late_fine"
                                id="late_fine"
                                value="0.00">


                            <input
                                type="hidden"
                                name="late_fine_type"
                                id="late_fine_type_hidden"
                                value="">

                        </div>

                    </div>

                </div>


                <hr>



                {{-- ========================================================= --}}
                {{-- Payment Details --}}
                {{-- ========================================================= --}}

                <h5 class="mb-3">
                    Payment Details
                </h5>


                <div class="card border">


                    {{-- Payment Header --}}

                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                        <h6 class="mb-0">
                            Payment Entries
                        </h6>


                        <button
                            type="button"
                            class="btn btn-light btn-sm"
                            id="addPaymentRow">

                            <i class="fa fa-plus me-1"></i>

                            Add Payment

                        </button>

                    </div>



                    {{-- Payment Table --}}

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table
                                class="table table-bordered align-middle mb-0"
                                id="paymentTable">


                                <thead class="table-light">

                                    <tr>

                                        <th width="17%">
                                            Payment Date
                                        </th>

                                        <th width="17%">
                                            Payment Mode
                                        </th>

                                        <th width="15%">
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


                                    {{-- First Payment Row --}}

                                    <tr>


                                        {{-- Payment Date --}}

                                        <td>

                                            <input
                                                type="date"
                                                name="payment_date[]"
                                                class="form-control payment-date"
                                                value="{{ date('Y-m-d') }}"
                                                required>

                                        </td>


                                        {{-- Payment Mode --}}

                                        <td>

                                            <select
                                                name="payment_mode[]"
                                                class="form-select payment-mode"
                                                required>

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


                                        {{-- Amount --}}

                                        <td>

                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                name="amount[]"
                                                class="form-control payment-amount"
                                                placeholder="0.00"
                                                required>

                                        </td>


                                        {{-- Transaction --}}

                                        <td>

                                            <input
                                                type="text"
                                                name="transaction_id[]"
                                                class="form-control transaction-id"
                                                placeholder="Transaction / Ref No">

                                        </td>


                                        {{-- Remarks --}}

                                        <td>

                                            <input
                                                type="text"
                                                name="remarks[]"
                                                class="form-control"
                                                placeholder="Remarks">

                                        </td>


                                        {{-- Remove --}}

                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm removeRow"
                                                title="Remove">

                                                <i class="mdi mdi-delete"></i>

                                            </button>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


                <br>



                {{-- ========================================================= --}}
                {{-- Billing Summary --}}
                {{-- ========================================================= --}}

                <div class="card border">


                    <div class="card-header bg-success text-white">

                        <h5 class="mb-0">
                            Billing Summary
                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="row">


                            {{-- Grand Total --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="summary_grand_total"
                                    class="fw-bold">

                                    Grand Total

                                </label>


                                <input
                                    type="text"
                                    id="summary_grand_total"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>


                            {{-- Total Paid --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="summary_total_paid"
                                    class="fw-bold text-success">

                                    Total Paid

                                </label>


                                <input
                                    type="text"
                                    id="summary_total_paid"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>


                            {{-- Remaining Due --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="summary_due"
                                    class="fw-bold text-danger">

                                    Remaining Due

                                </label>


                                <input
                                    type="text"
                                    id="summary_due"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>


                            {{-- Current Payable --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="summary_payable"
                                    class="fw-bold text-primary">

                                    Current Payable

                                </label>


                                <input
                                    type="text"
                                    id="summary_payable"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>

                        </div>



                        <div class="row">


                            {{-- Current Entered Amount --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="current_payment"
                                    class="fw-bold">

                                    Current Entered Amount

                                </label>


                                <input
                                    type="text"
                                    id="current_payment"
                                    class="form-control bg-warning fw-bold"
                                    value="0.00"
                                    readonly>

                            </div>


                            {{-- Balance After Payment --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="balance_after_payment"
                                    class="fw-bold text-danger">

                                    Balance After Payment

                                </label>


                                <input
                                    type="text"
                                    id="balance_after_payment"
                                    class="form-control bg-light fw-bold"
                                    value="0.00"
                                    readonly>

                            </div>


                            {{-- Payment Status --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="payment_status"
                                    class="fw-bold">

                                    Payment Status

                                </label>


                                <input
                                    type="text"
                                    id="payment_status"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>

                        </div>


                    </div>

                </div>


                <hr>



                {{-- ========================================================= --}}
                {{-- Form Actions --}}
                {{-- ========================================================= --}}

                <div class="d-flex justify-content-between">

                    <a
                        href="{{ route('billing.index') }}"
                        class="btn btn-secondary">

                        <i class="fa fa-arrow-left me-1"></i>

                        Back

                    </a>


                    <button
                        type="submit"
                        class="btn btn-success"
                        id="saveBillingBtn">

                        <i class="fa fa-save me-1"></i>

                        Save Billing

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
    | GLOBAL
    |--------------------------------------------------------------------------
    */

    let lateFineRequest = null;


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    function getTodayDate() {

        let date = new Date();

        let year = date.getFullYear();
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let day = String(date.getDate()).padStart(2, '0');

        return year + '-' + month + '-' + day;
    }


    let today = getTodayDate();


    function number(value) {

        let result = parseFloat(value);

        return isNaN(result) ? 0 : result;
    }


    function money(value) {

        return number(value).toFixed(2);
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT CHANGE
    |--------------------------------------------------------------------------
    */

    $('#student_id').on('change', function () {

        let studentId = $(this).val();

        let courseDropdown = $('#student_course_id');

        resetCourseDetails();

        if (studentId === '') {

            courseDropdown.html(
                '<option value="">Select Student First</option>'
            );

            return;
        }


        courseDropdown.html(
            '<option value="">Loading Courses...</option>'
        );


        $.ajax({

            url: "{{ route('billing.student-courses') }}",

            type: "GET",

            data: {
                student_id: studentId
            },

            success: function (response) {

                courseDropdown.empty();

                courseDropdown.append(
                    '<option value="">Select Course</option>'
                );


                if (
                    response.status &&
                    response.courses &&
                    response.courses.length > 0
                ) {

                    $.each(
                        response.courses,
                        function (index, row) {

                            let courseName =
                                row.course?.course_name ??
                                row.course?.name ??
                                'Course';


                            let admissionNo =
                                row.admission_no ??
                                'N/A';


                            courseDropdown.append(`

                                <option value="${row.id}">

                                    ${courseName}
                                    (Admission No : ${admissionNo})

                                </option>

                            `);

                        }
                    );

                } else {

                    courseDropdown.append(
                        '<option value="">No Course Found</option>'
                    );

                }

            },

            error: function (xhr) {

                console.error(
                    "Student Course AJAX Error:",
                    xhr.responseText
                );

                courseDropdown.html(
                    '<option value="">Unable to Load Courses</option>'
                );

                alert(
                    "Unable to load student courses."
                );

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | COURSE CHANGE
    |--------------------------------------------------------------------------
    */

    $('#student_course_id').on('change', function () {

        let studentCourseId = $(this).val();

        resetCourseDetails();

        if (studentCourseId === '') {

            return;
        }


        $.ajax({

            url: "{{ route('billing.course-details') }}",

            type: "GET",

            data: {
                student_course_id: studentCourseId
            },

            success: function (response) {

                if (response.status) {

                    fillCourseDetails(response.data);

                    calculateLateFine();

                } else {

                    alert(
                        response.message ??
                        "Unable to fetch course details."
                    );

                }

            },

            error: function (xhr) {

                console.error(
                    "Course Details AJAX Error:",
                    xhr.responseText
                );

                alert(
                    "Unable to fetch course details."
                );

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | FILL COURSE DETAILS
    |--------------------------------------------------------------------------
    */

    function fillCourseDetails(data) {

        $("#admission_no").val(
            data.admission_no ?? ''
        );

        $("#admission_date").val(
            data.admission_date ?? ''
        );

        $("#course_duration").val(

            (data.course_duration ?? '') +
            " " +
            (data.duration_type ?? '')

        );

        $("#course_name").val(
            data.course_name ?? ''
        );

        $("#level_name").val(
            data.level ?? ''
        );

        $("#category_name").val(
            data.category ?? ''
        );

        $("#batch_name").val(
            data.batch ?? ''
        );


        /*
        |--------------------------------------------------------------------------
        | FEE
        |--------------------------------------------------------------------------
        */

        $("#registration_fee").val(
            money(data.registration_fee)
        );

        $("#admission_fee").val(
            money(data.admission_fee)
        );

        $("#course_fee").val(
            money(data.course_fee)
        );

        $("#grand_total").val(
            money(data.grand_total)
        );


        /*
        |--------------------------------------------------------------------------
        | PAYMENT SUMMARY
        |--------------------------------------------------------------------------
        */

        $("#summary_grand_total").val(
            money(data.grand_total)
        );

        $("#summary_total_paid").val(
            money(data.total_paid)
        );

        $("#summary_due").val(
            money(data.remaining_amount)
        );

        $("#summary_payable").val(
            money(data.next_payable)
        );


        $("#current_payment").val(
            "0.00"
        );

        $("#balance_after_payment").val(
            money(data.remaining_amount)
        );


        $("#payment_status").val(

            number(data.payment_count) === 0
                ? "First Payment"
                : "Regular Payment"

        );


        /*
        |--------------------------------------------------------------------------
        | HIDDEN
        |--------------------------------------------------------------------------
        */

        $("#payment_count").val(
            data.payment_count ?? 0
        );

        $("#next_payable").val(
            money(data.next_payable)
        );

        $("#remaining_amount").val(
            money(data.remaining_amount)
        );


        /*
        |--------------------------------------------------------------------------
        | RESET LATE FINE
        |--------------------------------------------------------------------------
        */

        resetLateFine(false);


        /*
        |--------------------------------------------------------------------------
        | PAYMENT
        |--------------------------------------------------------------------------
        */

        calculatePayment();

    }


    /*
    |--------------------------------------------------------------------------
    | RESET COURSE DETAILS
    |--------------------------------------------------------------------------
    */

    function resetCourseDetails() {

        $("#admission_no").val("");
        $("#admission_date").val("");
        $("#course_duration").val("");
        $("#course_name").val("");
        $("#level_name").val("");
        $("#category_name").val("");
        $("#batch_name").val("");

        $("#registration_fee").val("");
        $("#admission_fee").val("");
        $("#course_fee").val("");
        $("#grand_total").val("");

        $("#summary_grand_total").val("");
        $("#summary_total_paid").val("");
        $("#summary_due").val("");
        $("#summary_payable").val("");

        $("#current_payment").val("0.00");
        $("#balance_after_payment").val("0.00");
        $("#payment_status").val("");

        $("#payment_count").val("");
        $("#next_payable").val("");
        $("#remaining_amount").val("");

        resetLateFine();

        calculatePayment();

    }


    /*
    |--------------------------------------------------------------------------
    | ADD PAYMENT ROW
    |--------------------------------------------------------------------------
    */

    $("#addPaymentRow").on("click", function () {

        let row = `

            <tr>

                <td>

                    <input
                        type="date"
                        name="payment_date[]"
                        class="form-control"
                        value="${today}"
                    >

                </td>

                <td>

                    <select
                        name="payment_mode[]"
                        class="form-select payment-mode"
                    >

                        <option value="">Select</option>

                        <option value="Cash">Cash</option>

                        <option value="UPI">UPI</option>

                        <option value="Card">Card</option>

                        <option value="Bank Transfer">
                            Bank Transfer
                        </option>

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
                        placeholder="0.00"
                    >

                </td>

                <td>

                    <input
                        type="text"
                        name="transaction_id[]"
                        class="form-control transaction-id"
                        placeholder="Transaction No"
                    >

                </td>

                <td>

                    <input
                        type="text"
                        name="remarks[]"
                        class="form-control"
                        placeholder="Remarks"
                    >

                </td>

                <td class="text-center">

                    <button
                        type="button"
                        class="btn btn-danger btn-sm removeRow"
                        title="Remove"
                    >

                        <i class="mdi mdi-delete"></i>

                    </button>

                </td>

            </tr>

        `;


        $("#paymentTable tbody").append(row);


        let lastRow =
            $("#paymentTable tbody tr:last");


        lastRow
            .find(".payment-mode")
            .val("Cash")
            .trigger("change");


        calculatePayment();

    });


    /*
    |--------------------------------------------------------------------------
    | REMOVE PAYMENT ROW
    |--------------------------------------------------------------------------
    */

    $(document).on(
        "click",
        ".removeRow",
        function () {

            let totalRows =
                $("#paymentTable tbody tr").length;


            if (totalRows <= 1) {

                alert(
                    "At least one payment row is required."
                );

                return;

            }


            $(this)
                .closest("tr")
                .remove();


            calculatePayment();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PAYMENT MODE CHANGE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        "change",
        ".payment-mode",
        function () {

            let mode =
                $(this).val();


            let row =
                $(this).closest("tr");


            let transactionBox =
                row.find(".transaction-id");


            if (mode === "") {

                $(this).addClass("is-invalid");

            } else {

                $(this).removeClass("is-invalid");

            }


            if (mode === "Cash") {

                transactionBox
                    .val("")
                    .prop("readonly", true)
                    .attr(
                        "placeholder",
                        "Not Required"
                    );

            } else {

                transactionBox
                    .prop("readonly", false)
                    .attr(
                        "placeholder",
                        "Transaction / Ref No"
                    );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PAYMENT AMOUNT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        "input",
        "#paymentTable .payment-amount",
        function () {

            let value =
                parseFloat($(this).val());


            if (
                isNaN(value) ||
                value < 0
            ) {

                $(this).val("0");

            }


            calculatePayment();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FIRST ROW CASH
    |--------------------------------------------------------------------------
    */

    $("#paymentTable tbody tr:first")
        .find(".payment-mode")
        .val("Cash")
        .trigger("change");


    /*
    |--------------------------------------------------------------------------
    | CASH TRANSACTION CLEANUP
    |--------------------------------------------------------------------------
    */

    $(document).on(
        "blur",
        ".transaction-id",
        function () {

            let mode =
                $(this)
                    .closest("tr")
                    .find(".payment-mode")
                    .val();


            if (mode === "Cash") {

                $(this).val("");

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | LATE FINE CALCULATION
    |--------------------------------------------------------------------------
    */

    function calculateLateFine() {

        let studentId =
            $("#student_id").val();


        let studentCourseId =
            $("#student_course_id").val();


        if (
            !studentId ||
            !studentCourseId
        ) {

            resetLateFine();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Abort Previous
        |--------------------------------------------------------------------------
        */

        if (lateFineRequest) {

            lateFineRequest.abort();

            lateFineRequest = null;

        }


        $("#lateFineSection")
            .removeClass("d-none");


        $("#lateFineMessage")
            .removeClass(
                "alert-success alert-danger"
            )
            .addClass(
                "alert-warning"
            )
            .html(

                '<i class="fa fa-spinner fa-spin me-1"></i> ' +
                'Calculating late fine...'

            );


        lateFineRequest = $.ajax({

            url: "{{ route('billing.late-fine') }}",

            type: "GET",

            data: {

                student_id: studentId,

                student_course_id: studentCourseId

            },


            success: function (response) {

                console.group(
                    "========== LATE FINE =========="
                );

                console.log(
                    "Complete Response:",
                    response
                );

                console.log(
                    "Student ID:",
                    response.debug?.student_id
                );

                console.log(
                    "Student Course ID:",
                    response.debug?.student_course_id
                );

                console.log(
                    "Course ID:",
                    response.debug?.course_id
                );

                console.log(
                    "Batch ID:",
                    response.debug?.batch_id
                );

                console.log(
                    "Payment Date:",
                    response.payment_date
                );

                console.log(
                    "Previous Payment:",
                    response.previous_payment_date
                );

                console.log(
                    "Month Difference:",
                    response.month_difference
                );

                console.log(
                    "Due Date:",
                    response.due_date
                );

                console.log(
                    "Attendance Month:",
                    response.attendance_month
                );

                console.log(
                    "Attendance Status:",
                    response.attendance_status
                );

                console.log(
                    "Attendance Count:",
                    response.attendance_count
                );

                console.log(
                    "Present Count:",
                    response.present_count
                );

                console.log(
                    "Fine Type:",
                    response.fine_type
                );

                console.log(
                    "Fine Amount:",
                    response.late_fine
                );

                console.log(
                    "Apply Fine:",
                    response.apply
                );

                console.log(
                    "Message:",
                    response.message
                );

                console.log(
                    "Decision:",
                    response.debug?.decision
                );

                console.log(
                    "Reason:",
                    response.debug?.reason
                );

                console.groupEnd();


                if (!response.status) {

                    resetLateFine(false);


                    $("#lateFineSection")
                        .removeClass("d-none");


                    $("#lateFineMessage")
                        .removeClass(
                            "alert-warning alert-success"
                        )
                        .addClass(
                            "alert-danger"
                        )
                        .html(

                            '<i class="fa fa-exclamation-triangle me-1"></i> ' +

                            (
                                response.message ??
                                "Unable to calculate late fine."
                            )

                        );


                    calculatePayment();

                    return;

                }


                $("#late_payment_date").val(
                    response.payment_date ?? ""
                );


                $("#late_due_date").val(
                    response.due_date ?? "-"
                );


                $("#late_previous_payment").val(

                    response.previous_payment_date ??
                    "First Payment"

                );


                let fine =
                    number(
                        response.late_fine
                    );


                fine =
                    Math.max(
                        fine,
                        0
                    );


                $("#late_fine_amount").val(
                    money(fine)
                );


                $("#late_fine").val(
                    money(fine)
                );


                /*
                |--------------------------------------------------------------------------
                | FINE TYPE
                |--------------------------------------------------------------------------
                */

                let fineType =
                    response.fine_type ?? "";


                let fineTypeText =
                    "No Fine";


                switch (fineType) {

                    case "same_month_late_fee":

                        fineTypeText =
                            "Same Month Late Fee";

                        break;


                    case "next_month_late_fee":

                        fineTypeText =
                            "Next Month Late Fee";

                        break;


                    case "absent_charge_percentage":

                        fineTypeText =
                            "Absent Charge (" +
                            (
                                response.absent_percentage ??
                                0
                            ) +
                            "%)";

                        break;


                    default:

                        fineTypeText =
                            "No Fine";

                        break;

                }


                $("#late_fine_type").val(
                    fineTypeText
                );


                $("#late_fine_type_hidden").val(
                    fineType
                );


                /*
                |--------------------------------------------------------------------------
                | ATTENDANCE
                |--------------------------------------------------------------------------
                */

                $("#late_attendance_month").val(

                    response.attendance_month ??
                    "-"

                );


                $("#late_attendance_status").val(

                    response.attendance_status ??
                    "-"

                );


                /*
                |--------------------------------------------------------------------------
                | MESSAGE
                |--------------------------------------------------------------------------
                */

                let message =
                    response.message ??
                    "Late fine calculated.";


                if (fine > 0) {

                    $("#lateFineMessage")
                        .removeClass(
                            "alert-success alert-warning"
                        )
                        .addClass(
                            "alert-danger"
                        )
                        .html(

                            '<i class="fa fa-exclamation-triangle me-1"></i> ' +

                            message +

                            ' <strong>Fine: ₹' +

                            money(fine) +

                            '</strong>'

                        );

                } else {

                    $("#lateFineMessage")
                        .removeClass(
                            "alert-danger alert-warning"
                        )
                        .addClass(
                            "alert-success"
                        )
                        .html(

                            '<i class="fa fa-check-circle me-1"></i> ' +

                            message

                        );

                }


                calculatePayment();

            },


            error: function (
                xhr,
                status,
                error
            ) {

                if (status === "abort") {

                    return;

                }


                console.error(
                    "Late Fine AJAX Error:",
                    error
                );

                console.error(
                    "HTTP Status:",
                    xhr.status
                );

                console.error(
                    "Server Response:",
                    xhr.responseText
                );


                resetLateFine(false);


                $("#lateFineSection")
                    .removeClass("d-none");


                $("#lateFineMessage")
                    .removeClass(
                        "alert-warning alert-success"
                    )
                    .addClass(
                        "alert-danger"
                    )
                    .html(

                        '<i class="fa fa-exclamation-triangle me-1"></i> ' +

                        'Unable to calculate late fine.'

                    );


                calculatePayment();

            },


            complete: function () {

                lateFineRequest = null;

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | RESET LATE FINE
    |--------------------------------------------------------------------------
    */

    function resetLateFine(hideSection = true) {

        if (lateFineRequest) {

            lateFineRequest.abort();

            lateFineRequest = null;

        }


        if (hideSection) {

            $("#lateFineSection")
                .addClass("d-none");

        }


        $("#late_payment_date").val("");

        $("#late_due_date").val("");

        $("#late_previous_payment").val("");

        $("#late_fine_amount").val("0.00");

        $("#late_fine").val("0.00");

        $("#late_fine_type").val("");

        $("#late_fine_type_hidden").val("");

        $("#late_attendance_month").val("");

        $("#late_attendance_status").val("");

        $("#lateFineMessage")
            .removeClass(
                "alert-success alert-danger alert-warning"
            )
            .html("");

    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE PAYMENT
    |--------------------------------------------------------------------------
    */

    function calculatePayment() {

        /*
        |--------------------------------------------------------------------------
        | COURSE REMAINING
        |--------------------------------------------------------------------------
        */

        let remainingDue =
            number(
                $("#summary_due").val()
            );


        /*
        |--------------------------------------------------------------------------
        | LATE FINE
        |--------------------------------------------------------------------------
        */

        let lateFine =
            number(
                $("#late_fine").val()
            );


        lateFine =
            Math.max(
                lateFine,
                0
            );


        /*
        |--------------------------------------------------------------------------
        | TOTAL DUE
        |--------------------------------------------------------------------------
        */

        let totalDue =
            remainingDue +
            lateFine;


        /*
        |--------------------------------------------------------------------------
        | CURRENT PAYABLE
        |--------------------------------------------------------------------------
        */

        let currentPayable =
            number(
                $("#summary_payable").val()
            );


        let payableWithFine =
            currentPayable +
            lateFine;


        /*
        |--------------------------------------------------------------------------
        | ENTERED PAYMENT
        |--------------------------------------------------------------------------
        */

        let enteredAmount = 0;


        $("#paymentTable tbody .payment-amount")
            .each(function () {

                let amount =
                    number(
                        $(this).val()
                    );


                if (amount > 0) {

                    enteredAmount += amount;

                }

            });


        enteredAmount =
            Math.max(
                enteredAmount,
                0
            );


        /*
        |--------------------------------------------------------------------------
        | PREVENT OVER PAYMENT
        |--------------------------------------------------------------------------
        */

        if (
            enteredAmount >
            totalDue
        ) {

            let lastBox =
                $("#paymentTable tbody .payment-amount")
                    .last();


            let lastAmount =
                number(
                    lastBox.val()
                );


            let excess =
                enteredAmount -
                totalDue;


            let correctedAmount =
                lastAmount -
                excess;


            if (correctedAmount < 0) {

                correctedAmount = 0;

            }


            lastBox.val(
                money(correctedAmount)
            );


            return calculatePayment();

        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT PAYMENT
        |--------------------------------------------------------------------------
        */

        $("#current_payment").val(
            money(enteredAmount)
        );


        /*
        |--------------------------------------------------------------------------
        | BALANCE
        |--------------------------------------------------------------------------
        */

        let balance =
            totalDue -
            enteredAmount;


        if (balance < 0) {

            balance = 0;

        }


        $("#balance_after_payment").val(
            money(balance)
        );


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (enteredAmount <= 0) {

            if (
                number(
                    $("#payment_count").val()
                ) === 0
            ) {

                $("#payment_status").val(
                    "First Payment"
                );

            } else {

                $("#payment_status").val(
                    "No Payment"
                );

            }

        }

        else if (
            balance <= 0.009
        ) {

            $("#payment_status").val(
                "Completed"
            );

        }

        else if (
            enteredAmount >
            payableWithFine
        ) {

            $("#payment_status").val(
                "Advance Payment"
            );

        }

        else {

            $("#payment_status").val(
                "Partial Payment"
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DEBUG
        |--------------------------------------------------------------------------
        */

        console.log(
            "Payment Calculation:",
            {
                remainingDue: remainingDue,
                lateFine: lateFine,
                totalDue: totalDue,
                currentPayable: currentPayable,
                payableWithFine: payableWithFine,
                enteredAmount: enteredAmount,
                balance: balance
            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FEE CHANGE
    |--------------------------------------------------------------------------
    */

    $(
        "#registration_fee," +
        "#admission_fee," +
        "#course_fee"
    ).on(
        "keyup change",
        function () {

            calculatePayment();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUMMARY DUE CHANGE
    |--------------------------------------------------------------------------
    */

    $("#summary_due").on(
        "keyup change",
        function () {

            calculatePayment();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUMMARY PAYABLE CHANGE
    |--------------------------------------------------------------------------
    */

    $("#summary_payable").on(
        "keyup change",
        function () {

            calculatePayment();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT VALIDATION
    |--------------------------------------------------------------------------
    */

    $("#billingForm").on(
        "submit",
        function (e) {

            /*
            |--------------------------------------------------------------------------
            | STUDENT
            |--------------------------------------------------------------------------
            */

            let student =
                $("#student_id").val();


            if (student === "") {

                e.preventDefault();

                alert(
                    "Please select student."
                );

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | COURSE
            |--------------------------------------------------------------------------
            */

            let studentCourse =
                $("#student_course_id").val();


            if (studentCourse === "") {

                e.preventDefault();

                alert(
                    "Please select course."
                );

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | CALCULATE
            |--------------------------------------------------------------------------
            */

            calculatePayment();


            /*
            |--------------------------------------------------------------------------
            | TOTAL PAYABLE
            |--------------------------------------------------------------------------
            */

            let remainingDue =
                number(
                    $("#summary_due").val()
                );


            let lateFine =
                number(
                    $("#late_fine").val()
                );


            let totalPayable =
                remainingDue +
                lateFine;


            /*
            |--------------------------------------------------------------------------
            | CURRENT PAYMENT
            |--------------------------------------------------------------------------
            */

            let paid =
                number(
                    $("#current_payment").val()
                );


            if (paid <= 0) {

                e.preventDefault();

                alert(
                    "Please enter payment amount."
                );

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | OVER PAYMENT
            |--------------------------------------------------------------------------
            */

            if (
                paid >
                totalPayable + 0.009
            ) {

                e.preventDefault();

                alert(
                    "Payment amount cannot exceed Total Payable ₹" +
                    money(totalPayable) +
                    "."
                );

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | PAYMENT ROW VALIDATION
            |--------------------------------------------------------------------------
            */

            let valid = true;

            let errorMessage = "";


            $("#paymentTable tbody tr")
                .each(function (index) {

                    let row =
                        $(this);


                    let mode =
                        row
                            .find(".payment-mode")
                            .val();


                    let amount =
                        number(
                            row
                                .find(".payment-amount")
                                .val()
                        );


                    let transaction =
                        $.trim(
                            row
                                .find(".transaction-id")
                                .val() || ""
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | MODE
                    |--------------------------------------------------------------------------
                    */

                    if (mode === "") {

                        valid = false;

                        row
                            .find(".payment-mode")
                            .addClass("is-invalid");

                        errorMessage =
                            "Please select payment mode for row " +
                            (index + 1) +
                            ".";

                        return false;

                    } else {

                        row
                            .find(".payment-mode")
                            .removeClass("is-invalid");

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | AMOUNT
                    |--------------------------------------------------------------------------
                    */

                    if (amount <= 0) {

                        valid = false;

                        row
                            .find(".payment-amount")
                            .addClass("is-invalid");

                        errorMessage =
                            "Payment amount must be greater than 0 in row " +
                            (index + 1) +
                            ".";

                        return false;

                    } else {

                        row
                            .find(".payment-amount")
                            .removeClass("is-invalid");

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | TRANSACTION
                    |--------------------------------------------------------------------------
                    */

                    if (
                        mode !== "Cash" &&
                        transaction === ""
                    ) {

                        valid = false;

                        row
                            .find(".transaction-id")
                            .addClass("is-invalid");


                        errorMessage =
                            "Transaction / Reference No is required for " +
                            mode +
                            " payment in row " +
                            (index + 1) +
                            ".";

                        return false;

                    } else {

                        row
                            .find(".transaction-id")
                            .removeClass("is-invalid");

                    }

                });


            /*
            |--------------------------------------------------------------------------
            | INVALID
            |--------------------------------------------------------------------------
            */

            if (!valid) {

                e.preventDefault();

                alert(
                    errorMessage ||
                    "Please complete all payment details."
                );

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | FINAL TOTAL CHECK
            |--------------------------------------------------------------------------
            */

            let calculatedTotal = 0;


            $("#paymentTable tbody .payment-amount")
                .each(function () {

                    calculatedTotal +=
                        number(
                            $(this).val()
                        );

                });


            if (
                Math.abs(
                    calculatedTotal -
                    paid
                ) > 0.009
            ) {

                e.preventDefault();

                alert(
                    "Payment amount calculation mismatch. Please check payment rows."
                );

                return false;

            }


            return true;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | REMOVE INVALID AMOUNT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        "input",
        ".payment-amount",
        function () {

            if (
                number($(this).val()) > 0
            ) {

                $(this)
                    .removeClass(
                        "is-invalid"
                    );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | REMOVE INVALID TRANSACTION
    |--------------------------------------------------------------------------
    */

    $(document).on(
        "input",
        ".transaction-id",
        function () {

            if (
                $.trim(
                    $(this).val()
                ) !== ""
            ) {

                $(this)
                    .removeClass(
                        "is-invalid"
                    );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL PAYMENT MODE
    |--------------------------------------------------------------------------
    */

    $("#paymentTable tbody tr")
        .each(function () {

            let mode =
                $(this)
                    .find(".payment-mode")
                    .val();


            if (mode === "") {

                $(this)
                    .find(".payment-mode")
                    .val("Cash");

            }


            $(this)
                .find(".payment-mode")
                .trigger("change");

        });


    /*
    |--------------------------------------------------------------------------
    | INITIAL CALCULATION
    |--------------------------------------------------------------------------
    */

    calculatePayment();

});

</script>

@endpush
