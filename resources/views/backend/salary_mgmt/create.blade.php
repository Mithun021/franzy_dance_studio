@extends('backend.partial.master')

@section('title','Add Salary')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow-sm">

            {{-- =====================================================
                HEADER
            ====================================================== --}}

            <div class="card-header d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1">
                        Add Salary
                    </h4>

                    <small class="text-muted">
                        Calculate faculty salary from successful course payments
                    </small>

                </div>

                <a
                    href="{{ route('salary-management.index') }}"
                    class="btn btn-secondary">

                    <i class="fa fa-arrow-left"></i>
                    Back

                </a>

            </div>


            <form
                action="{{ route('salary-management.store') }}"
                method="POST"
                id="salaryForm">

                @csrf


                <div class="card-body">

                    {{-- =====================================================
                        ALERTS
                    ====================================================== --}}

                    @if($errors->any())

                        <div class="alert alert-danger alert-dismissible fade show">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

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


                    {{-- =====================================================
                        EMPLOYEE + MONTH
                    ====================================================== --}}

                    <div class="row">

                        {{-- Employee --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Employee / Staff

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="user_id"
                                id="user_id"
                                class="form-control select2">

                                <option value="">
                                    Select Employee / Staff
                                </option>

                                @foreach($employees as $employee)

                                    <option
                                        value="{{ $employee->id }}"
                                        data-percentage="{{ $employee->salary_percentage }}"
                                        {{ old('user_id') == $employee->id ? 'selected' : '' }}>

                                        {{ $employee->user_id }}
                                        -
                                        {{ $employee->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Salary Month --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Salary Month

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="month"
                                name="salary_month"
                                id="salary_month"
                                class="form-control"
                                value="{{ old('salary_month',date('Y-m')) }}">

                        </div>

                    </div>


                    {{-- =====================================================
                        LOADING
                    ====================================================== --}}

                    <div
                        id="loadingBox"
                        class="alert alert-info d-none">

                        <i class="fa fa-spinner fa-spin"></i>

                        Calculating salary details...

                    </div>


                    {{-- =====================================================
                        SALARY SUMMARY
                    ====================================================== --}}

                    <div
                        id="salarySummary"
                        class="d-none">


                        <div class="row">


                            {{-- Assigned Students --}}

                            <div class="col-xl-3 col-md-6 mb-4">

                                <div class="card border-0 bg-primary text-white shadow-sm">

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between">

                                            <div>

                                                <small>
                                                    Assigned Students
                                                </small>

                                                <h3
                                                    class="mb-0 mt-2"
                                                    id="total_students">

                                                    0

                                                </h3>

                                            </div>

                                            <i class="fa fa-users fa-2x opacity-50"></i>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- Payment Before Calculation --}}

                            <div class="col-xl-3 col-md-6 mb-4">

                                <div class="card border-0 bg-info text-white shadow-sm">

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between">

                                            <div>

                                                <small>
                                                    Payment Before Calculation
                                                </small>

                                                <h3 class="mb-0 mt-2">

                                                    ₹
                                                    <span id="payment_received">
                                                        0.00
                                                    </span>

                                                </h3>

                                            </div>

                                            <i class="fa fa-money fa-2x opacity-50"></i>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- Total Earning --}}

                            <div class="col-xl-3 col-md-6 mb-4">

                                <div class="card border-0 bg-success text-white shadow-sm">

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between">

                                            <div>

                                                <small>
                                                    Total Earning Amount
                                                </small>

                                                <h3 class="mb-0 mt-2">

                                                    ₹
                                                    <span id="total_earning">
                                                        0.00
                                                    </span>

                                                </h3>

                                            </div>

                                            <i class="fa fa-line-chart fa-2x opacity-50"></i>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- Divided Percentage --}}

                            <div class="col-xl-3 col-md-6 mb-4">

                                <div class="card border-0 bg-warning text-dark shadow-sm">

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between">

                                            <div>

                                                <small>
                                                    Divided Percentage
                                                </small>

                                                <h3 class="mb-0 mt-2">

                                                    <span id="salary_percentage">
                                                        0
                                                    </span>%

                                                </h3>

                                            </div>

                                            <i class="fa fa-percent fa-2x opacity-50"></i>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =====================================================
                            SALARY SNAPSHOT
                        ====================================================== --}}

                        <div class="card border-primary mb-4">

                            <div class="card-header bg-light">

                                <h5 class="mb-0">

                                    <i class="fa fa-database text-primary"></i>

                                    Salary Calculation Snapshot

                                </h5>

                                <small class="text-muted">

                                    These values represent the calculation basis
                                    for this salary record.

                                </small>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="border rounded p-3">

                                            <small class="text-muted">
                                                Assigned Students
                                            </small>

                                            <h4 class="mb-0">

                                                <span id="snapshot_assigned_students">
                                                    0
                                                </span>

                                            </h4>

                                        </div>

                                    </div>


                                    <div class="col-md-4">

                                        <div class="border rounded p-3">

                                            <small class="text-muted">
                                                Payment Before Calculation
                                            </small>

                                            <h4 class="mb-0">

                                                ₹
                                                <span id="snapshot_payment">
                                                    0.00
                                                </span>

                                            </h4>

                                        </div>

                                    </div>


                                    <div class="col-md-4">

                                        <div class="border rounded p-3">

                                            <small class="text-muted">
                                                Divided Percentage
                                            </small>

                                            <h4 class="mb-0">

                                                <span id="snapshot_percentage">
                                                    0
                                                </span>%

                                            </h4>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =====================================================
                            HIDDEN SNAPSHOT VALUES
                        ====================================================== --}}

                        <input
                            type="hidden"
                            name="assigned_student"
                            id="assigned_student">

                        <input
                            type="hidden"
                            name="payment_before_calculation"
                            id="payment_before_calculation">

                        <input
                            type="hidden"
                            name="divided_percentage"
                            id="divided_percentage">


                        {{-- =====================================================
                            PAID / DUE
                        ====================================================== --}}

                        <div class="row">


                            {{-- Existing Paid --}}

                            <div class="col-md-6">

                                <div class="card border-success">

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between">

                                            <div>

                                                <h6 class="text-muted">
                                                    Existing Paid Amount
                                                </h6>

                                                <h3 class="text-success mb-0">

                                                    ₹
                                                    <span id="existing_paid">
                                                        0.00
                                                    </span>

                                                </h3>

                                            </div>

                                            <i class="fa fa-check-circle fa-2x text-success"></i>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- Existing Due --}}

                            <div class="col-md-6">

                                <div class="card border-danger">

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between">

                                            <div>

                                                <h6 class="text-muted">
                                                    Existing Due Amount
                                                </h6>

                                                <h3 class="text-danger mb-0">

                                                    ₹
                                                    <span id="existing_due">
                                                        0.00
                                                    </span>

                                                </h3>

                                            </div>

                                            <i class="fa fa-exclamation-circle fa-2x text-danger"></i>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Hidden Salary Amount --}}

                        <input
                            type="hidden"
                            name="salary_amount"
                            id="salary_amount">


                        {{-- =====================================================
                            PAYMENT LEDGER
                        ====================================================== --}}

                        <div class="card mt-4">

                            <div class="card-header">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <h5 class="mb-0">

                                            <i class="fa fa-list"></i>

                                            Payment Ledger

                                        </h5>

                                        <small class="text-muted">

                                            Successful payments received from
                                            assigned students

                                        </small>

                                    </div>

                                    <span
                                        class="badge bg-primary"
                                        id="payment_count">

                                        0 Payments

                                    </span>

                                </div>

                            </div>


                            <div class="card-body p-0">

                                <div class="table-responsive">

                                    <table class="table table-hover table-bordered mb-0">

                                        <thead class="table-light">

                                            <tr>

                                                <th>
                                                    #
                                                </th>

                                                <th>
                                                    Student
                                                </th>

                                                <th>
                                                    Course
                                                </th>

                                                <th class="text-center">
                                                    Assigned Students
                                                </th>

                                                <th class="text-end">
                                                    Payment Before Calculation
                                                </th>

                                                <th class="text-center">
                                                    Divided %
                                                </th>

                                                <th class="text-end">
                                                    Faculty Earning
                                                </th>

                                                <th>
                                                    Payment Date
                                                </th>

                                                <th>
                                                    Mode
                                                </th>

                                                <th>
                                                    Transaction ID
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody id="paymentTableBody">

                                            <tr>

                                                <td
                                                    colspan="10"
                                                    class="text-center text-muted py-5">

                                                    Select employee and salary month

                                                </td>

                                            </tr>

                                        </tbody>


                                        <tfoot>

                                            <tr class="table-light fw-bold">

                                                <td colspan="4" class="text-end">

                                                    Total

                                                </td>

                                                <td
                                                    class="text-end"
                                                    id="tableTotalReceived">

                                                    ₹ 0.00

                                                </td>

                                                <td></td>

                                                <td
                                                    class="text-end text-success"
                                                    id="tableTotalEarning">

                                                    ₹ 0.00

                                                </td>

                                                <td colspan="3"></td>

                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>

                            </div>

                        </div>


                        {{-- =====================================================
                            SALARY PAYMENT
                        ====================================================== --}}

                        <div class="card mt-4">

                            <div class="card-header">

                                <h5 class="mb-0">

                                    <i class="fa fa-credit-card"></i>

                                    Salary Payment

                                </h5>

                            </div>


                            <div class="card-body">

                                <div class="row">


                                    {{-- Total Earning --}}

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">

                                            Total Earning

                                        </label>

                                        <input
                                            type="text"
                                            id="display_salary_amount"
                                            class="form-control fw-bold"
                                            readonly>

                                    </div>


                                    {{-- Paid Amount --}}

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">

                                            Paid Amount

                                        </label>

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            name="paid_amount"
                                            id="paid_amount"
                                            class="form-control"
                                            value="{{ old('paid_amount',0) }}">

                                    </div>


                                    {{-- Due Amount --}}

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">

                                            Due Amount

                                        </label>

                                        <input
                                            type="text"
                                            id="due_amount"
                                            class="form-control fw-bold text-danger"
                                            readonly>

                                    </div>


                                    {{-- Payment Method --}}

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">

                                            Payment Method

                                        </label>

                                        <select
                                            name="payment_method"
                                            class="form-control">

                                            <option value="">
                                                Select Payment Method
                                            </option>

                                            <option value="Cash">
                                                Cash
                                            </option>

                                            <option value="UPI">
                                                UPI
                                            </option>

                                            <option value="Bank Transfer">
                                                Bank Transfer
                                            </option>

                                            <option value="Cheque">
                                                Cheque
                                            </option>

                                        </select>

                                    </div>


                                    {{-- Description --}}

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">

                                            Description

                                        </label>

                                        <textarea
                                            name="description"
                                            rows="2"
                                            class="form-control"
                                            placeholder="Optional remarks...">{{ old('description') }}</textarea>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                    FOOTER
                ====================================================== --}}

                <div class="card-footer d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('salary-management.index') }}"
                        class="btn btn-secondary">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        id="saveSalaryBtn"
                        class="btn btn-primary"
                        disabled>

                        <i class="fa fa-save"></i>

                        Save Salary

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | Helper: Number
    |--------------------------------------------------------------------------
    */

    function number(value)
    {
        let result = parseFloat(value);

        return isNaN(result) ? 0 : result;
    }


    /*
    |--------------------------------------------------------------------------
    | Helper: Money
    |--------------------------------------------------------------------------
    */

    function money(value)
    {
        value = number(value);

        return value.toLocaleString(
            'en-IN',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Helper: Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value)
    {
        if (
            value === null ||
            value === undefined
        ) {
            return '';
        }

        return $('<div>')
            .text(value)
            .html();
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Due
    |--------------------------------------------------------------------------
    */

    function calculateDue()
    {

        let total = number(
            $('#salary_amount').val()
        );

        let paid = number(
            $('#paid_amount').val()
        );


        if (paid < 0) {

            paid = 0;

            $('#paid_amount').val('0');

        }


        if (paid > total) {

            paid = total;

            $('#paid_amount').val(
                total.toFixed(2)
            );

        }


        let due = total - paid;


        if (due < 0) {

            due = 0;

        }


        $('#due_amount').val(
            '₹ ' + money(due)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Fetch Salary Details
    |--------------------------------------------------------------------------
    */

    function fetchSalaryDetails()
    {

        let userId = $('#user_id').val();

        let salaryMonth = $('#salary_month').val();


        /*
        |--------------------------------------------------------------------------
        | Required
        |--------------------------------------------------------------------------
        */

        if (
            !userId ||
            !salaryMonth
        ) {

            $('#salarySummary')
                .addClass('d-none');

            $('#saveSalaryBtn')
                .prop('disabled', true);

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */

        $('#loadingBox')
            .removeClass('d-none');

        $('#salarySummary')
            .removeClass('d-none');

        $('#saveSalaryBtn')
            .prop('disabled', true);


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: "{{ route('salary-management.fetch-details') }}",

            type: "GET",

            data: {

                user_id: userId,

                salary_month: salaryMonth

            },


            success: function(response)
            {

                if (!response.success) {

                    alert(
                        'Unable to calculate salary.'
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Snapshot
                |--------------------------------------------------------------------------
                */

                let assignedStudents =
                    number(
                        response.assigned_student
                    );


                let paymentBeforeCalculation =
                    number(
                        response.payment_before_calculation
                    );


                let dividedPercentage =
                    number(
                        response.divided_percentage
                    );


                /*
                |--------------------------------------------------------------------------
                | Summary Cards
                |--------------------------------------------------------------------------
                */

                $('#total_students').text(
                    assignedStudents
                );


                $('#payment_received').text(
                    money(
                        paymentBeforeCalculation
                    )
                );


                $('#total_earning').text(
                    money(
                        response.total_earning_amount
                    )
                );


                $('#salary_percentage').text(
                    dividedPercentage
                );


                /*
                |--------------------------------------------------------------------------
                | Snapshot Cards
                |--------------------------------------------------------------------------
                */

                $('#snapshot_assigned_students')
                    .text(
                        assignedStudents
                    );


                $('#snapshot_payment')
                    .text(
                        money(
                            paymentBeforeCalculation
                        )
                    );


                $('#snapshot_percentage')
                    .text(
                        dividedPercentage
                    );


                /*
                |--------------------------------------------------------------------------
                | Hidden Snapshot Values
                |--------------------------------------------------------------------------
                */

                $('#assigned_student')
                    .val(
                        assignedStudents
                    );


                $('#payment_before_calculation')
                    .val(
                        paymentBeforeCalculation.toFixed(2)
                    );


                $('#divided_percentage')
                    .val(
                        dividedPercentage.toFixed(2)
                    );


                /*
                |--------------------------------------------------------------------------
                | Salary Amount
                |--------------------------------------------------------------------------
                */

                let totalEarning =
                    number(
                        response.total_earning_amount
                    );


                $('#salary_amount')
                    .val(
                        totalEarning.toFixed(2)
                    );


                $('#display_salary_amount')
                    .val(
                        '₹ ' + money(totalEarning)
                    );


                /*
                |--------------------------------------------------------------------------
                | Existing Salary
                |--------------------------------------------------------------------------
                */

                $('#existing_paid').text(
                    money(
                        response.paid_amount
                    )
                );


                $('#existing_due').text(
                    money(
                        response.due_amount
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | New Payment
                |--------------------------------------------------------------------------
                |
                | Existing paid amount ko new payment field me
                | automatically add nahi karenge.
                |
                */

                $('#paid_amount')
                    .val(0);


                calculateDue();


                /*
                |--------------------------------------------------------------------------
                | Payment Count
                |--------------------------------------------------------------------------
                */

                let count =
                    response.payment_count || 0;


                $('#payment_count').text(

                    count +

                    (
                        count === 1
                            ? ' Payment'
                            : ' Payments'
                    )

                );


                /*
                |--------------------------------------------------------------------------
                | Payment Table
                |--------------------------------------------------------------------------
                */

                let tbody =
                    $('#paymentTableBody');


                tbody.empty();


                if (
                    !response.payment_details ||
                    response.payment_details.length === 0
                ) {

                    tbody.html(`

                        <tr>

                            <td
                                colspan="10"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i
                                        class="fa fa-credit-card fa-2x mb-2">
                                    </i>

                                    <br>

                                    No successful payment received
                                    for this month.

                                </div>

                            </td>

                        </tr>

                    `);

                }
                else {

                    $.each(

                        response.payment_details,

                        function(index, payment)
                        {

                            tbody.append(`

                                <tr>

                                    <td>
                                        ${index + 1}
                                    </td>


                                    <td>

                                        <strong>

                                            ${escapeHtml(
                                                payment.student
                                            )}

                                        </strong>

                                    </td>


                                    <td>

                                        ${escapeHtml(
                                            payment.course
                                        )}

                                    </td>


                                    <td class="text-center">

                                        <span
                                            class="badge bg-primary">

                                            ${number(
                                                payment.assigned_student
                                            )}

                                        </span>

                                    </td>


                                    <td class="text-end fw-semibold">

                                        ₹ ${money(
                                            payment.payment_before_calculation
                                        )}

                                    </td>


                                    <td class="text-center">

                                        <span
                                            class="badge bg-warning text-dark">

                                            ${number(
                                                payment.divided_percentage
                                            )}%

                                        </span>

                                    </td>


                                    <td class="text-end text-success fw-bold">

                                        ₹ ${money(
                                            payment.earning
                                        )}

                                    </td>


                                    <td>

                                        ${escapeHtml(
                                            payment.payment_date
                                        )}

                                    </td>


                                    <td>

                                        <span
                                            class="badge bg-light text-dark">

                                            ${escapeHtml(
                                                payment.payment_mode
                                            )}

                                        </span>

                                    </td>


                                    <td>

                                        ${escapeHtml(
                                            payment.transaction_id
                                        )}

                                    </td>

                                </tr>

                            `);

                        }

                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Table Totals
                |--------------------------------------------------------------------------
                */

                $('#tableTotalReceived').text(

                    '₹ ' +

                    money(
                        response.total_payment_received
                    )

                );


                $('#tableTotalEarning').text(

                    '₹ ' +

                    money(
                        response.total_earning_amount
                    )

                );


                /*
                |--------------------------------------------------------------------------
                | Enable Save
                |--------------------------------------------------------------------------
                */

                $('#saveSalaryBtn')
                    .prop('disabled', false);

            },


            /*
            |--------------------------------------------------------------------------
            | AJAX Error
            |--------------------------------------------------------------------------
            */

            error: function(xhr)
            {

                console.error(xhr);


                let message =
                    'Unable to fetch salary details.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                alert(message);


                $('#salarySummary')
                    .addClass('d-none');


                $('#saveSalaryBtn')
                    .prop('disabled', true);

            },


            /*
            |--------------------------------------------------------------------------
            | Complete
            |--------------------------------------------------------------------------
            */

            complete: function()
            {

                $('#loadingBox')
                    .addClass('d-none');

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Employee Change
    |--------------------------------------------------------------------------
    */

    $('#user_id').on(
        'change',
        function()
        {

            fetchSalaryDetails();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Salary Month Change
    |--------------------------------------------------------------------------
    */

    $('#salary_month').on(
        'change',
        function()
        {

            fetchSalaryDetails();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Paid Amount
    |--------------------------------------------------------------------------
    */

    $('#paid_amount').on(
        'input change keyup',
        function()
        {

            calculateDue();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    if (
        $('#user_id').val() &&
        $('#salary_month').val()
    ) {

        fetchSalaryDetails();

    }

});

</script>

@endpush
