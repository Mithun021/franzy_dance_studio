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

            {{-- =========================================================
                 HEADER
            ========================================================== --}}
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-0">
                        Add Course
                    </h4>

                    <small>
                        Student :
                        <strong>{{ $student->name }} - {{ $student->user_id }}</strong>
                    </small>

                </div>

                <a href="{{ route('students.courses',$student->id) }}"
                   class="btn btn-light">

                    Back

                </a>

            </div>


            <div class="card-body">


                {{-- =========================================================
                     ADMISSION DETAILS
                ========================================================== --}}

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
                            id="admission_date"
                            class="form-control"
                            value="{{ old('admission_date',date('Y-m-d')) }}"
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
                            class="form-control"
                            required>

                            <option value="">
                                Select Status
                            </option>

                            <option
                                value="0"
                                {{ old('is_enroll') == '0' ? 'selected' : '' }}>

                                Not Enroll

                            </option>

                            <option
                                value="1"
                                {{ old('is_enroll') == '1' ? 'selected' : '' }}>

                                Enroll

                            </option>

                        </select>

                    </div>

                </div>


                <hr>


                {{-- =========================================================
                     COURSE
                ========================================================== --}}

                <h5 class="mb-3">
                    Select Course
                </h5>


                <div class="row">

                    @foreach($courses as $course)

                        <div class="col-lg-4 col-md-6 mb-3">

                            <label class="card border cursor-pointer h-100">

                                <div class="card-body">

                                    <div class="form-check">

                                        <input
                                            class="form-check-input course-radio"
                                            type="radio"
                                            name="course_id"
                                            value="{{ $course->id }}"
                                            {{ old('course_id') == $course->id ? 'checked' : '' }}>

                                        <label class="form-check-label fw-bold">

                                            {{ $course->course_name }}

                                        </label>

                                    </div>

                                </div>

                            </label>

                        </div>

                    @endforeach

                </div>


                <hr>


                {{-- =========================================================
                     LEVEL / CATEGORY / FACULTY
                ========================================================== --}}

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
                                    {{ old('level_id') == $level->id ? 'selected' : '' }}>

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
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>

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
                            class="form-select">

                            <option value="">
                                Select Faculty
                            </option>

                            @foreach($faculty as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ old('instructor_id') == $item->id ? 'selected' : '' }}>

                                    {{ $item->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Hidden Batch --}}
                    <input
                        type="hidden"
                        name="batch_id"
                        id="selected_batch_id"
                        value="{{ old('batch_id') }}">

                </div>


                <hr>


                {{-- =========================================================
                     AVAILABLE BATCHES
                ========================================================== --}}

                <h5 class="mb-3">
                    Available Batches
                </h5>


                <div id="batch_container">

                    <div class="alert alert-secondary mb-0">

                        Select Course & Level First

                    </div>

                </div>


                <hr>


                {{-- =========================================================
                     FEE DETAILS
                ========================================================== --}}

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
                            value="0.00"
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
                            value="0.00"
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
                            value="0.00"
                            readonly>

                    </div>

                </div>


                {{-- =========================================================
                     BILLING CALCULATION
                ========================================================== --}}

                <div class="card border-primary mb-4">

                    <div class="card-header bg-primary text-white">

                        <h6 class="mb-0">
                            First Billing Calculation
                        </h6>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- Admission Date --}}
                            <div class="col-md-3 mb-3">

                                <label class="form-label fw-bold">
                                    Admission Date
                                </label>

                                <input
                                    type="text"
                                    id="billing_admission_date"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Billing Month --}}
                            <div class="col-md-3 mb-3">

                                <label class="form-label fw-bold">
                                    Billing Month
                                </label>

                                <input
                                    type="text"
                                    id="billing_month"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Payment Rule --}}
                            <div class="col-md-3 mb-3">

                                <label class="form-label fw-bold">
                                    Payment Rule
                                </label>

                                <input
                                    type="text"
                                    id="payment_rule"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Payment Percentage --}}
                            <div class="col-md-3 mb-3">

                                <label class="form-label fw-bold">
                                    Payment Percentage
                                </label>

                                <input
                                    type="text"
                                    id="payment_percentage_display"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                        </div>


                        <div class="row">

                            {{-- Monthly Fee --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Standard Monthly Fee
                                </label>

                                <input
                                    type="text"
                                    id="billing_monthly_fee"
                                    class="form-control bg-light"
                                    readonly>

                            </div>


                            {{-- Calculated First Month Fee --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label fw-bold">
                                    First Month Payable
                                </label>

                                <input
                                    type="text"
                                    name="first_month_fee"
                                    id="first_month_fee"
                                    class="form-control bg-light fw-bold text-primary"
                                    value="0.00"
                                    readonly>

                            </div>


                            {{-- Billing Date --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Billing Date
                                </label>

                                <input
                                    type="date"
                                    name="billing_date"
                                    id="billing_date"
                                    class="form-control bg-light"
                                    readonly>

                            </div>

                        </div>


                        {{-- Explanation --}}
                        <div id="billing_message"
                             class="alert alert-info mb-0">

                            Select admission date to calculate billing.

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                     HIDDEN BILLING VALUES
                ========================================================== --}}

                <input
                    type="hidden"
                    name="billing_month"
                    id="billing_month_hidden">

                <input
                    type="hidden"
                    name="payment_percentage"
                    id="payment_percentage">

                <input
                    type="hidden"
                    name="payment_rule"
                    id="payment_rule_hidden">


                <hr>


                {{-- =========================================================
                     FEE SUMMARY
                ========================================================== --}}

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

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Billing Month
                                </th>

                                <td id="summary_billing_month">
                                    -
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Payment Rule
                                </th>

                                <td>

                                    <span id="summary_payment_rule">
                                        -
                                    </span>

                                    &nbsp;

                                    <span
                                        class="badge bg-info"
                                        id="summary_payment_percentage">

                                        0%

                                    </span>

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    First Month Payable
                                </th>

                                <td>

                                    <strong id="summary_first_month">
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


                <input
                    type="hidden"
                    name="total_monthly_fee"
                    id="total_monthly_fee"
                    value="0.00">

                <input
                    type="hidden"
                    name="grand_total"
                    id="grand_total"
                    value="0.00">


                <hr>


                {{-- =========================================================
                     PAYMENT DETAILS
                ========================================================== --}}

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

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


                <br>


                {{-- =========================================================
                     PAYMENT SUMMARY
                ========================================================== --}}

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


                {{-- =========================================================
                     BUTTONS
                ========================================================== --}}

                <div class="d-flex justify-content-between">

                    <a
                        href="{{ route('students.courses',$student->id) }}"
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


    /* ============================================================
       HELPER
    ============================================================ */

    function money(value)
    {
        let number = parseFloat(value);

        if (isNaN(number)) {
            number = 0;
        }

        return number.toFixed(2);
    }


    /* ============================================================
       FETCH BATCHES
    ============================================================ */

    function fetchBatches()
    {

        let courseId =
            $('input[name="course_id"]:checked').val();

        let levelId =
            $('#level_id').val();

        let container =
            $('#batch_container');

        let hiddenInput =
            $('#selected_batch_id');

        hiddenInput.val('');


        if (!courseId || !levelId)
        {

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


            success: function (response)
            {

                container.empty();


                if (
                    response.status &&
                    response.batches &&
                    response.batches.length > 0
                )
                {

                    $.each(
                        response.batches,
                        function(index, batch)
                        {

                            let badge = '';

                            let disabled = '';


                            if (batch.is_full)
                            {

                                badge =
                                    '<span class="badge bg-danger">Full</span>';

                                disabled =
                                    'disabled';

                            }
                            else
                            {

                                badge =
                                    '<span class="badge bg-success">Available</span>';

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

                                                        <strong>
                                                            Time
                                                        </strong>

                                                        <br>

                                                        ${batch.start_time}
                                                        -
                                                        ${batch.end_time}

                                                    </div>


                                                    <div class="col-md-3">

                                                        <strong>
                                                            Days
                                                        </strong>

                                                        <br>

                                                        ${batch.days_text}

                                                    </div>


                                                    <div class="col-md-3">

                                                        <strong>
                                                            Capacity
                                                        </strong>

                                                        <br>

                                                        ${batch.enrolled_students}
                                                        /
                                                        ${batch.capacity}

                                                    </div>


                                                    <div class="col-md-3">

                                                        <strong>
                                                            Status
                                                        </strong>

                                                        <br>

                                                        ${badge}

                                                    </div>

                                                </div>

                                            </label>

                                        </div>

                                    </div>

                                </div>

                            `);

                        }
                    );

                }
                else
                {

                    container.html(`

                        <div class="alert alert-warning mb-0">

                            No Batch Available.

                        </div>

                    `);

                }

            },


            error: function ()
            {

                container.html(`

                    <div class="alert alert-danger mb-0">

                        Failed to load batches.

                    </div>

                `);

            }

        });

    }



    /* ============================================================
       FETCH FEE STRUCTURE
    ============================================================ */

    function fetchFeeStructure()
    {

        let courseId =
            $('input[name="course_id"]:checked').val();

        let levelId =
            $('#level_id').val();


        $('#registration_fee').val('');

        $('#admission_fee').val('');

        $('#monthly_fee').val('');


        if (!courseId || !levelId)
        {
            resetBilling();

            return;
        }


        $('#registration_fee')
            .val('Loading...');

        $('#admission_fee')
            .val('Loading...');

        $('#monthly_fee')
            .val('Loading...');


        $.ajax({

            url: "{{ route('fetch.fee.structure') }}",

            type: "GET",

            data: {

                course_id: courseId,

                level_id: levelId

            },


            success: function(response)
            {

                if (response.status)
                {

                    let registrationFee =
                        parseFloat(
                            response.data.registration_fee
                        ) || 0;


                    let admissionFee =
                        parseFloat(
                            response.data.admission_fee
                        ) || 0;


                    let monthlyFee =
                        parseFloat(
                            response.data.monthly_fee
                        ) || 0;


                    $('#registration_fee')
                        .val(money(registrationFee));


                    $('#admission_fee')
                        .val(money(admissionFee));


                    $('#monthly_fee')
                        .val(money(monthlyFee));


                    /*
                    |------------------------------------------------------
                    | Recalculate Billing
                    |------------------------------------------------------
                    */

                    calculateBilling();

                }
                else
                {

                    $('#registration_fee')
                        .val('0.00');

                    $('#admission_fee')
                        .val('0.00');

                    $('#monthly_fee')
                        .val('0.00');


                    resetBilling();


                    console.log(
                        response.message
                    );

                }

            },


            error: function(xhr)
            {

                $('#registration_fee')
                    .val('0.00');

                $('#admission_fee')
                    .val('0.00');

                $('#monthly_fee')
                    .val('0.00');


                resetBilling();


                console.log(
                    xhr.responseText
                );

            }

        });

    }



    /* ============================================================
       BILLING CALCULATION

       1 - 15  = 100% SAME MONTH

       16 - 25 = 50% SAME MONTH

       26 - END = 100% NEXT MONTH
    ============================================================ */

    function calculateBilling()
    {

        let admissionDate =
            $('#admission_date').val();


        let monthlyFee =
            parseFloat(
                $('#monthly_fee').val()
            ) || 0;


        let registrationFee =
            parseFloat(
                $('#registration_fee').val()
            ) || 0;


        let admissionFee =
            parseFloat(
                $('#admission_fee').val()
            ) || 0;


        if (!admissionDate)
        {

            resetBilling();

            return;

        }


        let dateParts =
            admissionDate.split('-');


        let year =
            parseInt(dateParts[0]);


        let month =
            parseInt(dateParts[1]) - 1;


        let day =
            parseInt(dateParts[2]);


        let admission =
            new Date(
                year,
                month,
                day
            );


        let billingDate =
            new Date(
                year,
                month,
                day
            );


        let paymentPercentage =
            100;


        let paymentRule =
            '';


        let billingMonthText =
            '';


        /*
        |--------------------------------------------------------------
        | 1 - 15
        |--------------------------------------------------------------
        */

        if (
            day >= 1 &&
            day <= 15
        )
        {

            paymentPercentage =
                100;


            paymentRule =
                'Full Month Payment';


            billingDate =
                new Date(
                    year,
                    month,
                    1
                );

        }


        /*
        |--------------------------------------------------------------
        | 16 - 25
        |--------------------------------------------------------------
        */

        else if (
            day >= 16 &&
            day <= 25
        )
        {

            paymentPercentage =
                50;


            paymentRule =
                'Half Month Payment';


            billingDate =
                new Date(
                    year,
                    month,
                    1
                );

        }


        /*
        |--------------------------------------------------------------
        | 26 - Month End
        |--------------------------------------------------------------
        */

        else
        {

            paymentPercentage =
                100;


            paymentRule =
                'Next Month Full Payment';


            billingDate =
                new Date(
                    year,
                    month + 1,
                    1
                );

        }


        /*
        |--------------------------------------------------------------
        | Calculate First Month Fee
        |--------------------------------------------------------------
        */

        let firstMonthFee =
            monthlyFee *
            (
                paymentPercentage / 100
            );


        /*
        |--------------------------------------------------------------
        | Billing Month
        |--------------------------------------------------------------
        */

        billingMonthText =
            billingDate.toLocaleString(
                'en-US',
                {
                    month: 'long',
                    year: 'numeric'
                }
            );


        /*
        |--------------------------------------------------------------
        | Billing Date YYYY-MM-DD
        |--------------------------------------------------------------
        */

        let billingDateString =
            billingDate.getFullYear() +
            '-' +
            String(
                billingDate.getMonth() + 1
            ).padStart(2, '0') +
            '-' +
            String(
                billingDate.getDate()
            ).padStart(2, '0');


        /*
        |--------------------------------------------------------------
        | Display
        |--------------------------------------------------------------
        */

        $('#billing_admission_date')
            .val(
                formatDate(admission)
            );


        $('#billing_month')
            .val(
                billingMonthText
            );


        $('#payment_rule')
            .val(
                paymentRule
            );


        $('#payment_percentage_display')
            .val(
                paymentPercentage + '%'
            );


        $('#billing_monthly_fee')
            .val(
                money(monthlyFee)
            );


        $('#first_month_fee')
            .val(
                money(firstMonthFee)
            );


        $('#billing_date')
            .val(
                billingDateString
            );


        /*
        |--------------------------------------------------------------
        | Hidden Values
        |--------------------------------------------------------------
        */

        $('#billing_month_hidden')
            .val(
                billingDate.getFullYear() +
                '-' +
                String(
                    billingDate.getMonth() + 1
                ).padStart(2, '0')
            );


        $('#payment_percentage')
            .val(
                paymentPercentage
            );


        $('#payment_rule_hidden')
            .val(
                paymentRule
            );


        /*
        |--------------------------------------------------------------
        | Message
        |--------------------------------------------------------------
        */

        let message = '';


        if (
            day >= 1 &&
            day <= 15
        )
        {

            message = `
                Admission is between
                <strong>1st - 15th</strong>.
                Student will be charged
                <strong>100%</strong>
                of the monthly fee for
                <strong>${billingMonthText}</strong>.
            `;

        }
        else if (
            day >= 16 &&
            day <= 25
        )
        {

            message = `
                Admission is between
                <strong>16th - 25th</strong>.
                Student will be charged
                <strong>50%</strong>
                of the monthly fee for
                <strong>${billingMonthText}</strong>.
            `;

        }
        else
        {

            message = `
                Admission is between
                <strong>26th - month end</strong>.
                Current month is skipped and
                <strong>100%</strong>
                monthly fee will be charged for
                <strong>${billingMonthText}</strong>.
            `;

        }


        $('#billing_message')
            .html(message);


        /*
        |--------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------
        */

        $('#summary_registration')
            .text(
                money(registrationFee)
            );


        $('#summary_admission')
            .text(
                money(admissionFee)
            );


        $('#summary_monthly')
            .text(
                money(monthlyFee)
            );


        $('#summary_billing_month')
            .text(
                billingMonthText
            );


        $('#summary_payment_rule')
            .text(
                paymentRule
            );


        $('#summary_payment_percentage')
            .text(
                paymentPercentage + '%'
            );


        $('#summary_first_month')
            .text(
                money(firstMonthFee)
            );


        /*
        |--------------------------------------------------------------
        | Grand Total
        |--------------------------------------------------------------
        */

        let grandTotal =
            registrationFee +
            admissionFee +
            firstMonthFee;


        $('#summary_total')
            .text(
                money(grandTotal)
            );


        $('#total_monthly_fee')
            .val(
                money(firstMonthFee)
            );


        $('#grand_total')
            .val(
                money(grandTotal)
            );


        /*
        |--------------------------------------------------------------
        | Recalculate Payment
        |--------------------------------------------------------------
        */

        calculatePaymentTotal();

    }



    /* ============================================================
       RESET BILLING
    ============================================================ */

    function resetBilling()
    {

        $('#billing_admission_date')
            .val('');


        $('#billing_month')
            .val('');


        $('#payment_rule')
            .val('');


        $('#payment_percentage_display')
            .val('');


        $('#billing_monthly_fee')
            .val('0.00');


        $('#first_month_fee')
            .val('0.00');


        $('#billing_date')
            .val('');


        $('#billing_month_hidden')
            .val('');


        $('#payment_percentage')
            .val('');


        $('#payment_rule_hidden')
            .val('');


        $('#billing_message')
            .html(
                'Select admission date to calculate billing.'
            );


        $('#summary_registration')
            .text('0.00');


        $('#summary_admission')
            .text('0.00');


        $('#summary_monthly')
            .text('0.00');


        $('#summary_billing_month')
            .text('-');


        $('#summary_payment_rule')
            .text('-');


        $('#summary_payment_percentage')
            .text('0%');


        $('#summary_first_month')
            .text('0.00');


        $('#summary_total')
            .text('0.00');


        $('#total_monthly_fee')
            .val('0.00');


        $('#grand_total')
            .val('0.00');


        calculatePaymentTotal();

    }



    /* ============================================================
       DATE FORMAT
    ============================================================ */

    function formatDate(date)
    {

        return String(
            date.getDate()
        ).padStart(2, '0')
        +
        '-'
        +
        String(
            date.getMonth() + 1
        ).padStart(2, '0')
        +
        '-'
        +
        date.getFullYear();

    }



    /* ============================================================
       GET TOTAL PAID
    ============================================================ */

    function getTotalPaid()
    {

        let totalPaid = 0;


        $('.payment-amount').each(function()
        {

            let amount =
                parseFloat(
                    $(this).val()
                ) || 0;


            totalPaid += amount;

        });


        return totalPaid;

    }



    /* ============================================================
       PAYMENT TOTAL

       IMPORTANT:

       Total Paid can NEVER be greater than Total Payable.
    ============================================================ */

    function calculatePaymentTotal()
    {

        let totalPayable =
            parseFloat(
                $('#grand_total').val()
            ) || 0;


        let totalPaid =
            getTotalPaid();


        /*
        |--------------------------------------------------------------
        | Safety
        |--------------------------------------------------------------
        */

        if (totalPaid < 0)
        {
            totalPaid = 0;
        }


        /*
        |--------------------------------------------------------------
        | Due Amount
        |--------------------------------------------------------------
        */

        let due =
            totalPayable -
            totalPaid;


        if (due < 0)
        {
            due = 0;
        }


        $('#totalPayable')
            .val(
                money(totalPayable)
            );


        $('#totalPaid')
            .val(
                money(totalPaid)
            );


        $('#dueAmount')
            .val(
                money(due)
            );

    }



    /* ============================================================
       VALIDATE PAYMENT AMOUNT

       Example:

       Total Payable = 3000

       Payment 1 = 1000
       Payment 2 = 2000

       Total = 3000       VALID

       Payment 1 = 2000
       Payment 2 = 1500

       Total = 3500       INVALID
    ============================================================ */

    $(document).on(
        'input',
        '.payment-amount',
        function()
        {

            let input =
                $(this);


            let currentAmount =
                parseFloat(
                    input.val()
                ) || 0;


            if (currentAmount < 0)
            {

                input.val('0');

                currentAmount = 0;

            }


            let totalPayable =
                parseFloat(
                    $('#grand_total').val()
                ) || 0;


            /*
            |----------------------------------------------------------
            | Calculate all OTHER payment rows
            |----------------------------------------------------------
            */

            let otherPayments = 0;


            $('.payment-amount').each(
                function()
                {

                    if (
                        this !== input[0]
                    )
                    {

                        otherPayments +=
                            parseFloat(
                                $(this).val()
                            ) || 0;

                    }

                }
            );


            /*
            |----------------------------------------------------------
            | Maximum amount allowed in this row
            |----------------------------------------------------------
            */

            let remaining =
                totalPayable -
                otherPayments;


            if (remaining < 0)
            {
                remaining = 0;
            }


            /*
            |----------------------------------------------------------
            | Current payment exceeds remaining amount
            |----------------------------------------------------------
            */

            if (
                currentAmount >
                remaining
            )
            {

                input.val(
                    money(remaining)
                );


                /*
                |------------------------------------------------------
                | Warning
                |------------------------------------------------------
                */

                let message =
                    'Maximum payment allowed is ₹' +
                    money(remaining) +
                    '. Total Paid cannot exceed Total Payable.';


                /*
                |------------------------------------------------------
                | Use Bootstrap alert
                |------------------------------------------------------
                */

                showPaymentWarning(message);

            }


            calculatePaymentTotal();

        }
    );



    /* ============================================================
       PAYMENT WARNING
    ============================================================ */

    function showPaymentWarning(message)
    {

        /*
        |--------------------------------------------------------------
        | Remove Existing Warning
        |--------------------------------------------------------------
        */

        $('#paymentLimitWarning')
            .remove();


        /*
        |--------------------------------------------------------------
        | Add Warning
        |--------------------------------------------------------------
        */

        $('#paymentTable')
            .closest('.card')
            .before(`

                <div
                    id="paymentLimitWarning"
                    class="alert alert-warning alert-dismissible fade show">

                    <strong>
                        Payment Limit:
                    </strong>

                    ${message}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            `);


        /*
        |--------------------------------------------------------------
        | Auto Remove After 4 Seconds
        |--------------------------------------------------------------
        */

        setTimeout(
            function()
            {

                $('#paymentLimitWarning')
                    .fadeOut(
                        300,
                        function()
                        {
                            $(this).remove();
                        }
                    );

            },
            4000
        );

    }



    /* ============================================================
       ADD PAYMENT ROW
    ============================================================ */

    $('#addPaymentRow').on(
        'click',
        function()
        {

            let totalPayable =
                parseFloat(
                    $('#grand_total').val()
                ) || 0;


            let totalPaid =
                getTotalPaid();


            /*
            |----------------------------------------------------------
            | No More Payment Required
            |----------------------------------------------------------
            */

            if (
                totalPaid >= totalPayable &&
                totalPayable > 0
            )
            {

                showPaymentWarning(
                    'Total Payable is already fully paid. No additional payment can be added.'
                );

                return;

            }


            let row = `

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


            $('#paymentTable tbody')
                .append(row);

        }
    );



    /* ============================================================
       REMOVE PAYMENT ROW
    ============================================================ */

    $(document).on(
        'click',
        '.removeRow',
        function()
        {

            if (
                $('#paymentTable tbody tr').length === 1
            )
            {

                alert(
                    'At least one payment row is required.'
                );

                return;

            }


            $(this)
                .closest('tr')
                .remove();


            calculatePaymentTotal();

        }
    );



    /* ============================================================
       PAYMENT AMOUNT CHANGE
    ============================================================ */

    $(document).on(
        'keyup change',
        '.payment-amount',
        function()
        {

            /*
            |----------------------------------------------------------
            | Trigger same validation as input
            |----------------------------------------------------------
            */

            $(this).trigger('input');

        }
    );



    /* ============================================================
       PAYMENT MODE
    ============================================================ */

    $(document).on(
        'change',
        '.payment-mode',
        function()
        {

            let mode =
                $(this).val();


            let txn =
                $(this)
                    .closest('tr')
                    .find('.transaction-id');


            if (
                mode === 'Cash'
            )
            {

                txn.val('');

                txn.prop(
                    'readonly',
                    true
                );

                txn.attr(
                    'placeholder',
                    'Not Required'
                );

            }
            else
            {

                txn.prop(
                    'readonly',
                    false
                );

                txn.attr(
                    'placeholder',
                    'Transaction / Ref No'
                );

            }

        }
    );



    /* ============================================================
       ADMISSION DATE CHANGE
    ============================================================ */

    $('#admission_date').on(
        'change',
        function()
        {

            calculateBilling();

        }
    );



    /* ============================================================
       BATCH SELECTION
    ============================================================ */

    $(document).on(
        'change',
        '.batch-radio',
        function()
        {

            $('#selected_batch_id')
                .val(
                    $(this).val()
                );


            $('.batch-card')
                .removeClass(
                    'border-primary shadow'
                );


            $(this)
                .closest('.batch-card')
                .addClass(
                    'border-primary shadow'
                );

        }
    );



    /* ============================================================
       COURSE CHANGE
    ============================================================ */

    $(document).on(
        'change',
        'input[name="course_id"]',
        function()
        {

            fetchBatches();

            fetchFeeStructure();

        }
    );



    /* ============================================================
       LEVEL CHANGE
    ============================================================ */

    $('#level_id').on(
        'change',
        function()
        {

            fetchBatches();

            fetchFeeStructure();

        }
    );



    /* ============================================================
       FORM SUBMIT VALIDATION

       Final safety check.

       Even if someone manipulates the input manually,
       form will NOT submit if Total Paid > Total Payable.
    ============================================================ */

    $('form').on(
        'submit',
        function(e)
        {

            let totalPayable =
                parseFloat(
                    $('#grand_total').val()
                ) || 0;


            let totalPaid =
                getTotalPaid();


            /*
            |----------------------------------------------------------
            | Floating point safety
            |----------------------------------------------------------
            */

            totalPayable =
                Math.round(
                    totalPayable * 100
                ) / 100;


            totalPaid =
                Math.round(
                    totalPaid * 100
                ) / 100;


            /*
            |----------------------------------------------------------
            | Prevent Overpayment
            |----------------------------------------------------------
            */

            if (
                totalPaid >
                totalPayable
            )
            {

                e.preventDefault();


                showPaymentWarning(
                    'Total Paid (₹' +
                    money(totalPaid) +
                    ') cannot be greater than Total Payable (₹' +
                    money(totalPayable) +
                    ').'
                );


                /*
                |------------------------------------------------------
                | Scroll to payment section
                |------------------------------------------------------
                */

                $('html, body')
                    .animate(
                        {
                            scrollTop:
                                $('#paymentTable')
                                .offset()
                                .top - 100
                        },
                        500
                    );


                return false;

            }

        }
    );



    /* ============================================================
       PAGE LOAD
    ============================================================ */

    fetchBatches();

    fetchFeeStructure();

    calculateBilling();

    calculatePaymentTotal();


});

</script>

@endpush
