@extends('backend.partial.master')

@section('title','New Billing')

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

    <form action="{{ route('billing.store') }}"
          method="POST"
          id="billingForm">

        @csrf

        <div class="card shadow">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-0">

                        Student Billing

                    </h4>

                    <small>

                        Collect Monthly / Admission Fees

                    </small>

                </div>

                <a href="{{ route('billing.index') }}"
                    class="btn btn-light">

                    <i class="fa fa-arrow-left me-1"></i>

                    Back

                </a>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Student --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

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

                    {{-- Course --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

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

                <h5 class="mb-3">

                    Course Information

                </h5>

                <div class="card border">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Admission No

                                </label>

                                <input
                                    type="text"
                                    id="admission_no"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Admission Date

                                </label>

                                <input
                                    type="text"
                                    id="admission_date"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Course Duration

                                </label>

                                <input
                                    type="text"
                                    id="course_duration"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

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

                            <div class="col-md-4 mb-3">

                                <label class="fw-bold">

                                    Level

                                </label>

                                <input
                                    type="text"
                                    id="level_name"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="fw-bold">

                                    Category

                                </label>

                                <input
                                    type="text"
                                    id="category_name"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="fw-bold">

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

                <h5 class="mb-3">

                    Fee Summary

                </h5>

                <div class="card border">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Registration Fee

                                </label>

                                <input
                                    type="text"
                                    id="registration_fee"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Admission Fee

                                </label>

                                <input
                                    type="text"
                                    id="admission_fee"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Monthly Fee

                                </label>

                                <input
                                    type="text"
                                    id="course_fee"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Grand Total

                                </label>

                                <input
                                    type="text"
                                    id="grand_total"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>

                        </div>

                        {{-- Hidden Values --}}

                        <input
                            type="hidden"
                            id="payment_count">

                        <input
                            type="hidden"
                            id="next_payable">

                        <input
                            type="hidden"
                            id="remaining_amount">

                    </div>

                </div>

                <hr>
                                {{-- ========================================================= --}}
                {{-- Payment Details --}}
                {{-- ========================================================= --}}

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

                            <table
                                class="table table-bordered align-middle mb-0"
                                id="paymentTable">

                                <thead class="table-light">

                                    <tr>

                                        <th width="18%">

                                            Payment Date

                                        </th>

                                        <th width="18%">

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

                                    <tr>

                                        <td>

                                            <input
                                                type="date"
                                                name="payment_date[]"
                                                class="form-control"
                                                value="{{ date('Y-m-d') }}">

                                        </td>

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
                                                class="form-control transaction-id"
                                                placeholder="Transaction No">

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

                                                <i class="mdi mdi-delete"></i>

                                            </button>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- Billing Summary --}}
                {{-- ========================================================= --}}

                <br>

                <div class="card border">

                    <div class="card-header bg-success text-white">

                        <h5 class="mb-0">

                            Billing Summary

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Grand Total

                                </label>

                                <input
                                    type="text"
                                    id="summary_grand_total"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold text-success">

                                    Total Paid

                                </label>

                                <input
                                    type="text"
                                    id="summary_total_paid"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold text-danger">

                                    Remaining Due

                                </label>

                                <input
                                    type="text"
                                    id="summary_due"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold text-primary">

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

                            <div class="col-md-4">

                                <label class="fw-bold">

                                    Current Entered Amount

                                </label>

                                <input
                                    type="text"
                                    id="current_payment"
                                    class="form-control bg-warning fw-bold"
                                    readonly>

                            </div>

                            <div class="col-md-4">

                                <label class="fw-bold text-danger">

                                    Balance After Payment

                                </label>

                                <input
                                    type="text"
                                    id="balance_after_payment"
                                    class="form-control bg-light fw-bold"
                                    readonly>

                            </div>

                            <div class="col-md-4">

                                <label class="fw-bold">

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

                <div class="d-flex justify-content-between">

                    <a
                        href="{{ route('billing.index') }}"
                        class="btn btn-secondary">

                        <i class="fa fa-arrow-left"></i>

                        Back

                    </a>

                    <button
                        type="submit"
                        class="btn btn-success">

                        <i class="fa fa-save"></i>

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

        let today = new Date().toISOString().split('T')[0];

        /*
        |--------------------------------------------------------------------------
        | Student Change
        |--------------------------------------------------------------------------
        */

        $('#student_id').change(function () {

            let studentId = $(this).val();

            let courseDropdown = $('#student_course_id');

            courseDropdown.html(
                '<option value="">Loading Courses...</option>'
            );

            resetCourseDetails();

            if(studentId == ''){

                courseDropdown.html(
                    '<option value="">Select Student First</option>'
                );

                return;
            }

            $.ajax({

                url : "{{ route('billing.student-courses') }}",

                type : "GET",

                data : {

                    student_id : studentId

                },

                success:function(response){

                    courseDropdown.empty();

                    courseDropdown.append(
                        '<option value="">Select Course</option>'
                    );

                    if(response.status){

                        $.each(response.courses,function(index,row){

                            courseDropdown.append(

                                `<option value="${row.id}">

                                    ${row.course.course_name}

                                    (Admission No : ${row.admission_no})

                                </option>`

                            );

                        });

                    }

                },

                error:function(){

                    alert("Unable to load student courses.");

                }

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Course Change
        |--------------------------------------------------------------------------
        */

        $('#student_course_id').change(function(){

            let studentCourseId = $(this).val();

            resetCourseDetails();

            if(studentCourseId==""){

                return;

            }

            $.ajax({

                url : "{{ route('billing.course-details') }}",

                type : "GET",

                data : {

                    student_course_id : studentCourseId

                },

                success:function(response){

                    if(response.status){

                        fillCourseDetails(response.data);

                    }

                },

                error:function(){

                    alert("Unable to fetch course details.");

                }

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Fill Course Details
        |--------------------------------------------------------------------------
        */

        function fillCourseDetails(data){

            $("#admission_no").val(data.admission_no);

            $("#admission_date").val(data.admission_date);

            $("#course_duration").val(

                data.course_duration + " " + data.duration_type

            );

            $("#course_name").val(data.course_name);

            $("#level_name").val(data.level);

            $("#category_name").val(data.category);

            $("#batch_name").val(data.batch);

            /*
            |--------------------------------------------------------------------------
            | Fee
            |--------------------------------------------------------------------------
            */

            $("#registration_fee").val(data.registration_fee);

            $("#admission_fee").val(data.admission_fee);

            $("#course_fee").val(data.course_fee);

            $("#grand_total").val(data.grand_total);

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            $("#summary_grand_total").val(data.grand_total);

            $("#summary_total_paid").val(data.total_paid);

            $("#summary_due").val(data.remaining_amount);

            $("#summary_payable").val(data.next_payable);

            $("#current_payment").val("0.00");

            $("#balance_after_payment").val(data.remaining_amount);

            $("#payment_status").val(
                data.payment_count==0
                ? "First Payment"
                : "Regular Payment"
            );

            /*
            |--------------------------------------------------------------------------
            | Hidden Fields
            |--------------------------------------------------------------------------
            */

            $("#payment_count").val(data.payment_count);

            $("#next_payable").val(data.next_payable);

            $("#remaining_amount").val(data.remaining_amount);

            calculatePayment();

        }


        /*
        |--------------------------------------------------------------------------
        | Reset
        |--------------------------------------------------------------------------
        */

        function resetCourseDetails(){

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

            $("#current_payment").val("");

            $("#balance_after_payment").val("");

            $("#payment_status").val("");

            $("#payment_count").val("");

            $("#next_payable").val("");

            $("#remaining_amount").val("");

            calculatePayment();

        }


        /*
    |--------------------------------------------------------------------------
    | Add Payment Row
    |--------------------------------------------------------------------------
    */

    $("#addPaymentRow").click(function () {

        let row = `

        <tr>

            <td>

                <input
                    type="date"
                    name="payment_date[]"
                    class="form-control"
                    value="${today}">

            </td>

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
                    placeholder="Transaction No">

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

                    <i class="mdi mdi-delete"></i>

                </button>

            </td>

        </tr>

        `;

        $("#paymentTable tbody").append(row);

        $("#paymentTable tbody tr:last .payment-mode")
            .val("Cash")
            .trigger("change");

        calculatePayment();

    });


    /*
    |--------------------------------------------------------------------------
    | Remove Payment Row
    |--------------------------------------------------------------------------
    */

    $(document).on("click", ".removeRow", function () {

        if ($("#paymentTable tbody tr").length == 1) {

            alert("At least one payment row is required.");

            return;

        }

        $(this).closest("tr").remove();

        calculatePayment();

    });


    /*
    |--------------------------------------------------------------------------
    | Cash / Online Mode
    |--------------------------------------------------------------------------
    */

    $(document).on("change", ".payment-mode", function () {

        let mode = $(this).val();

        let transactionBox = $(this)
            .closest("tr")
            .find(".transaction-id");

        if (mode == "Cash") {

            transactionBox.val("");

            transactionBox.prop("readonly", true);

            transactionBox.attr(
                "placeholder",
                "Not Required"
            );

        } else {

            transactionBox.prop("readonly", false);

            transactionBox.attr(
                "placeholder",
                "Transaction / Ref No"
            );

        }

    });



   $(document).on("input", "#paymentTable .payment-amount", function () {

        let amount = parseFloat($(this).val());

        if(isNaN(amount) || amount < 0){

            $(this).val(0);

        }

        calculatePayment();

    });


    /*
    |--------------------------------------------------------------------------
    | Auto Select Cash For First Row
    |--------------------------------------------------------------------------
    */

    $("#paymentTable tbody tr:first .payment-mode")

        .val("Cash")

        .trigger("change");


    /*
    |--------------------------------------------------------------------------
    | Empty Transaction if Cash
    |--------------------------------------------------------------------------
    */

    $(document).on(

        "blur",

        ".transaction-id",

        function () {

            let mode = $(this)

                .closest("tr")

                .find(".payment-mode")

                .val();

            if (mode == "Cash") {

                $(this).val("");

            }

        }

    );


    /*
    |--------------------------------------------------------------------------
    | Validate Payment Mode
    |--------------------------------------------------------------------------
    */

    $(document).on(

        "change",

        ".payment-mode",

        function () {

            let mode = $(this).val();

            if (mode == "") {

                $(this).addClass("is-invalid");

            } else {

                $(this).removeClass("is-invalid");

            }

        }

    );


        /*
        |--------------------------------------------------------------------------
        | Calculate Payment
        |--------------------------------------------------------------------------
        */

        function calculatePayment() {

            let remainingDue = parseFloat($("#summary_due").val()) || 0;
            let currentPayable = parseFloat($("#summary_payable").val()) || 0;

            let enteredAmount = 0;

            $("#paymentTable tbody .payment-amount").each(function () {

                let amt = parseFloat($(this).val());

                if (!isNaN(amt)) {
                    enteredAmount += amt;
                }

            });

            // Over Payment

            if (enteredAmount > remainingDue) {

                alert("Payment cannot exceed Remaining Due.");

                let lastBox = $("#paymentTable tbody .payment-amount").last();

                let last = parseFloat(lastBox.val()) || 0;

                let excess = enteredAmount - remainingDue;

                lastBox.val((last - excess).toFixed(2));

                return calculatePayment();

            }

            $("#current_payment").val(enteredAmount.toFixed(2));

            let balance = remainingDue - enteredAmount;

            if(balance < 0){
                balance = 0;
            }

            $("#balance_after_payment").val(balance.toFixed(2));

            if(enteredAmount == 0){

                $("#payment_status").val("No Payment");

            }
            else if(balance == 0){

                $("#payment_status").val("Completed");

            }
            else if(enteredAmount > currentPayable){

                $("#payment_status").val("Advance Payment");

            }
            else{

                $("#payment_status").val("Partial Payment");

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Fee Change
        |--------------------------------------------------------------------------
        */

        $("#registration_fee,#admission_fee,#course_fee").on(

            "keyup change",

            function(){

                calculatePayment();

            }

        );


        /*
        |--------------------------------------------------------------------------
        | Form Validation
        |--------------------------------------------------------------------------
        */

        $("form").submit(function(e){

            let student=$("#student_id").val();

            let studentCourse=$("#student_course_id").val();

            if(student==""){

                alert("Please select student.");

                e.preventDefault();

                return;

            }

            if(studentCourse==""){

                alert("Please select course.");

                e.preventDefault();

                return;

            }

            let payable=parseFloat($("#summary_due").val())||0;

            calculatePayment();

            let paid=parseFloat($("#current_payment").val())||0;

            if(paid<=0){

                alert("Please enter payment amount.");

                e.preventDefault();

                return;

            }

            if(paid>payable){

                alert("Payment amount cannot exceed Remaining Due.");

                e.preventDefault();

                return;

            }

            let valid=true;

            $("#paymentTable tbody tr").each(function(){

                let mode=$(this).find(".payment-mode").val();

                let amount=parseFloat(
                    $(this).find(".payment-amount").val()
                )||0;

                let txn=$(this)
                    .find(".transaction-id")
                    .val();

                if(mode==""){

                    valid=false;

                }

                if(amount<=0){

                    valid=false;

                }

                if(mode!="Cash" && txn==""){

                    alert(
                        "Transaction No is required for online payment."
                    );

                    valid=false;

                }

            });

            if(!valid){

                alert("Please complete all payment details.");

                e.preventDefault();

                return;

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Auto Calculation
        |--------------------------------------------------------------------------
        */

        calculatePayment();


        /*
        |--------------------------------------------------------------------------
        | If Payable Changes
        |--------------------------------------------------------------------------
        */

        $("#summary_due").on("change",function(){

            calculatePayment();

        });

    });

    </script>
@endpush
