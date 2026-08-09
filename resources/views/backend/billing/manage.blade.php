@extends('backend.partial.master')

@section('title','Manage Billing')

@section('backend-content')

<div class="container-fluid">

    {{-- ========================= --}}
    {{-- Alerts --}}
    {{-- ========================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                class="btn-close"
                data-bs-dismiss="alert"></button>

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


    <form
        action="{{ route('billing.update',$student_course->id) }}"
        method="POST"
        id="billingForm">

        @csrf


        <div class="card shadow">

            {{-- ========================= --}}
            {{-- Header --}}
            {{-- ========================= --}}

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-0">

                        Manage Student Billing

                    </h4>

                    <small>

                        Update / Add Payment

                    </small>

                </div>

                <a
                    href="{{ route('billing.index') }}"
                    class="btn btn-light">

                    <i class="fa fa-arrow-left me-1"></i>

                    Back

                </a>

            </div>


            <div class="card-body">


                {{-- ====================================== --}}
                {{-- Student Information --}}
                {{-- ====================================== --}}

                <h5 class="mb-3">

                    Student Information

                </h5>

                <div class="card border mb-4">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="fw-bold">

                                    Student Name

                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    readonly
                                    value="{{ $student_course->student->name }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="fw-bold">

                                    Mobile No.

                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    readonly
                                    value="{{ $student_course->student->phone }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="fw-bold">

                                    Admission No.

                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    readonly
                                    value="{{ $student_course->admission_no }}">

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ====================================== --}}
                {{-- Course Information --}}
                {{-- ====================================== --}}

                <h5 class="mb-3">

                    Course Information

                </h5>

                <div class="card border mb-4">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Admission Date

                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    readonly
                                    value="{{ \Carbon\Carbon::parse($student_course->admission_date)->format('d M Y') }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Course

                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    readonly
                                    value="{{ $student_course->course->course_name }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Level

                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    readonly
                                    value="{{ $student_course->level->level_name ?? '' }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Category

                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    readonly
                                    value="{{ $student_course->category->category_name ?? '' }}">

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="fw-bold">

                                    Batch

                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    readonly
                                    value="{{ $student_course->batch->batch_name ?? '' }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="fw-bold">

                                    Course Duration

                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    readonly
                                    value="{{ $student_course->course->course_duration }} {{ ucfirst($student_course->course->duration_type) }}">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="fw-bold">

                                    Monthly Fee

                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    readonly
                                    value="₹ {{ number_format($student_course->course_fee,2) }}">

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ====================================== --}}
                {{-- Fee Summary --}}
                {{-- ====================================== --}}

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
                                    readonly
                                    class="form-control bg-light"
                                    value="₹ {{ number_format($student_course->registration_fee,2) }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Admission Fee

                                </label>

                                <input
                                    type="text"
                                    readonly
                                    class="form-control bg-light"
                                    value="₹ {{ number_format($student_course->admission_fee,2) }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold">

                                    Grand Total

                                </label>

                                <input
                                    type="text"
                                    readonly
                                    class="form-control bg-light fw-bold"
                                    value="₹ {{ number_format($student_course->grand_total,2) }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold text-success">

                                    Total Paid

                                </label>

                                <input
                                    type="text"
                                    readonly
                                    class="form-control bg-light fw-bold"
                                    value="₹ {{ number_format($totalPaid,2) }}">

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-4">

                                <label class="fw-bold text-danger">

                                    Remaining Due

                                </label>

                                <input
                                    type="text"
                                    readonly
                                    class="form-control bg-light fw-bold"
                                    id="remaining_due"
                                    value="{{ $remainingAmount }}">

                            </div>

                            <div class="col-md-4">

                                <label class="fw-bold text-primary">

                                    Next Payable

                                </label>

                                <input
                                    type="text"
                                    readonly
                                    class="form-control bg-light fw-bold"
                                    id="next_payable"
                                    value="{{ $nextPayable }}">

                            </div>

                            <div class="col-md-4">

                                <label class="fw-bold">

                                    Payment Count

                                </label>

                                <input
                                    type="text"
                                    readonly
                                    class="form-control bg-light fw-bold"
                                    value="{{ $paymentCount }}">

                            </div>

                        </div>

                    </div>

                </div>

                <br>
                                {{-- ====================================== --}}
                {{-- Payment History --}}
                {{-- ====================================== --}}

                <h5 class="mb-3">

                    Payment History

                </h5>

                <div class="card border">

                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

                        <div>

                            <strong>

                                Existing Payments

                            </strong>

                        </div>

                        <span class="badge bg-success">

                            {{ $payments->count() }} Payments

                        </span>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th width="6%">#</th>

                                        <th width="10%">Date</th>

                                        <th width="10%">Mode</th>

                                        <th width="12%">Amount</th>

                                        <th width="12%">Reg. Fee</th>

                                        <th width="12%">Adm. Fee</th>

                                        <th width="12%">Monthly Fee</th>

                                        <th width="14%">Transaction</th>

                                        <th>Remarks</th>

                                        <th width="8%">Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($payments as $key => $payment)

                                    <tr>

                                        <td>

                                            {{ $key+1 }}

                                            <input
                                                type="hidden"
                                                name="payment_id[]"
                                                value="{{ $payment->id }}">

                                        </td>

                                        <td>
                                            {{ $payment->payment_date->format('Y-m-d') }}
                                            {{-- <input
                                                type="date"
                                                name="old_payment_date[]"
                                                class="form-control"
                                                value="{{ $payment->payment_date->format('Y-m-d') }}"> --}}

                                        </td>

                                        <td>
                                            {{ $payment->payment_mode ?? '' }}
                                            {{-- <select
                                                name="old_payment_mode[]"
                                                class="form-select old-payment-mode">

                                                <option
                                                    value="Cash"
                                                    {{ $payment->payment_mode=='Cash' ? 'selected' : '' }}>

                                                    Cash

                                                </option>

                                                <option
                                                    value="UPI"
                                                    {{ $payment->payment_mode=='UPI' ? 'selected' : '' }}>

                                                    UPI

                                                </option>

                                                <option
                                                    value="Card"
                                                    {{ $payment->payment_mode=='Card' ? 'selected' : '' }}>

                                                    Card

                                                </option>

                                                <option
                                                    value="Bank Transfer"
                                                    {{ $payment->payment_mode=='Bank Transfer' ? 'selected' : '' }}>

                                                    Bank Transfer

                                                </option>

                                                <option
                                                    value="Cheque"
                                                    {{ $payment->payment_mode=='Cheque' ? 'selected' : '' }}>

                                                    Cheque

                                                </option>

                                            </select> --}}

                                        </td>

                                        <td>

                                            {{ $payment->amount }}
                                            {{-- <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                name="old_amount[]"
                                                class="form-control old-payment-amount"
                                                value="{{ $payment->amount }}"> --}}

                                        </td>

                                        <td>
                                            {{ number_format($payment->registration_fee,2) }}
                                            {{-- <input
                                                type="text"
                                                class="form-control bg-light"
                                                readonly
                                                value="{{ number_format($payment->registration_fee,2) }}"> --}}

                                        </td>

                                        <td>

                                            {{ number_format($payment->admission_fee,2) }}
                                            {{-- <input
                                                type="text"
                                                class="form-control bg-light"
                                                readonly
                                                value="{{ number_format($payment->admission_fee,2) }}"> --}}

                                        </td>

                                        <td>

                                            {{ number_format($payment->course_fee,2) }}
                                            {{-- <input
                                                type="text"
                                                class="form-control bg-light"
                                                readonly
                                                value="{{ number_format($payment->course_fee,2) }}"> --}}

                                        </td>

                                        <td>

                                            {{ $payment->transaction_id }}
                                            {{-- <input
                                                type="text"
                                                name="old_transaction_id[]"
                                                class="form-control transaction-box"
                                                value="{{ $payment->transaction_id }}"> --}}

                                        </td>

                                        <td>

                                            {{ $payment->remarks }}
                                            {{-- <input
                                                type="text"
                                                name="old_remarks[]"
                                                class="form-control"
                                                value="{{ $payment->remarks }}"> --}}

                                        </td>

                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm delete-payment"
                                                data-id="{{ $payment->id }}">

                                                <i class="mdi mdi-delete"></i>

                                            </button>

                                        </td>

                                    </tr>

                                    @empty

                                    <tr>

                                        <td
                                            colspan="10"
                                            class="text-center text-danger py-4">

                                            No payment history found.

                                        </td>

                                    </tr>

                                    @endforelse

                                </tbody>

                                @if($payments->count())

                                <tfoot>

                                    <tr class="table-success">

                                        <th colspan="3"
                                            class="text-end">

                                            Total Paid

                                        </th>

                                        <th>

                                            {{ number_format($payments->sum('amount'),2) }}

                                        </th>

                                        <th>

                                            {{ number_format($payments->sum('registration_fee'),2) }}

                                        </th>

                                        <th>

                                            {{ number_format($payments->sum('admission_fee'),2) }}

                                        </th>

                                        <th colspan="4"></th>

                                    </tr>

                                </tfoot>

                                @endif

                            </table>

                        </div>

                    </div>

                </div>


                <br>


                {{-- ====================================== --}}
                {{-- New Payment Section --}}
                {{-- ====================================== --}}

                <h5 class="mb-3">

                    Add New Payment

                </h5>

                <div class="card border">

                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                        <h6 class="mb-0">

                            New Payment Entries

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
                                class="table table-bordered mb-0"
                                id="paymentTable">

                                <thead class="table-light">

                                    <tr>

                                        <th width="16%">

                                            Payment Date

                                        </th>

                                        <th width="16%">

                                            Payment Mode

                                        </th>

                                        <th width="14%">

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

                                    {{-- JS Row Append Here --}}

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <br>
                {{-- ====================================== --}}
                {{-- Billing Summary --}}
                {{-- ====================================== --}}

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
                                    readonly
                                    value="{{ number_format($student_course->grand_total,2) }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold text-success">

                                    Total Paid

                                </label>

                                <input
                                    type="text"
                                    id="summary_total_paid"
                                    class="form-control bg-light fw-bold"
                                    readonly
                                    value="{{ number_format($totalPaid,2) }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold text-danger">

                                    Remaining Due

                                </label>

                                <input
                                    type="text"
                                    id="summary_due"
                                    class="form-control bg-light fw-bold"
                                    readonly
                                    value="{{ number_format($remainingAmount,2) }}">

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="fw-bold text-primary">

                                    Current Payable

                                </label>

                                <input
                                    type="text"
                                    id="summary_payable"
                                    class="form-control bg-light fw-bold"
                                    readonly
                                    value="{{ number_format($nextPayable,2) }}">

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
                                    readonly
                                    value="0.00">

                            </div>

                            <div class="col-md-4">

                                <label class="fw-bold text-danger">

                                    Balance After Payment

                                </label>

                                <input
                                    type="text"
                                    id="balance_after_payment"
                                    class="form-control bg-light fw-bold"
                                    readonly
                                    value="{{ number_format($remainingAmount,2) }}">

                            </div>

                            <div class="col-md-4">

                                <label class="fw-bold">

                                    Payment Status

                                </label>

                                <input
                                    type="text"
                                    id="payment_status"
                                    class="form-control bg-light fw-bold"
                                    readonly
                                    value="No Payment">

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Hidden Fields --}}

                <input
                    type="hidden"
                    id="remaining_amount"
                    value="{{ $remainingAmount }}">

                <input
                    type="hidden"
                    id="next_payable_amount"
                    value="{{ $nextPayable }}">

                <input
                    type="hidden"
                    id="payment_count"
                    value="{{ $paymentCount }}">


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

                        Update Billing

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


@push('scripts')

<script>

$(document).ready(function(){

    let today = new Date().toISOString().split('T')[0];

    /*==========================================================
    ADD PAYMENT ROW
    ==========================================================*/

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
                    name="amount[]"
                    class="form-control payment-amount"
                    step="0.01"
                    min="0"
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


    /*==========================================================
    REMOVE ROW
    ==========================================================*/

    $(document).on("click",".removeRow",function(){

        $(this).closest("tr").remove();

        calculatePayment();

    });


    /*==========================================================
    PAYMENT MODE
    ==========================================================*/

    $(document).on("change",".payment-mode",function(){

        let mode = $(this).val();

        let transactionBox = $(this)
            .closest("tr")
            .find(".transaction-id");

        if(mode=="Cash"){

            transactionBox
                .val("")
                .prop("readonly",true)
                .attr("placeholder","Not Required");

        }else{

            transactionBox
                .prop("readonly",false)
                .attr("placeholder","Transaction / Ref No");

        }

    });


    /*==========================================================
    AMOUNT INPUT
    ==========================================================*/

    $(document).on("input",".payment-amount",function(){

        let amount = parseFloat($(this).val());

        if(isNaN(amount) || amount<0){

            $(this).val(0);

        }

        calculatePayment();

    });


    /*==========================================================
    CALCULATE PAYMENT
    ==========================================================*/

    function calculatePayment(){

        let remainingDue =
            parseFloat($("#remaining_amount").val()) || 0;

        let currentPayable =
            parseFloat($("#next_payable_amount").val()) || 0;

        let enteredAmount = 0;

        $(".payment-amount").each(function(){

            let amt = parseFloat($(this).val());

            if(!isNaN(amt)){

                enteredAmount += amt;

            }

        });

        if(enteredAmount > remainingDue){

            alert("Payment cannot exceed remaining due.");

            let lastBox = $(".payment-amount").last();

            let lastValue = parseFloat(lastBox.val()) || 0;

            let excess = enteredAmount - remainingDue;

            lastBox.val((lastValue-excess).toFixed(2));

            return calculatePayment();

        }

        $("#current_payment").val(
            enteredAmount.toFixed(2)
        );

        let balance = remainingDue-enteredAmount;

        if(balance<0){

            balance=0;

        }

        $("#balance_after_payment").val(
            balance.toFixed(2)
        );

        if(enteredAmount==0){

            $("#payment_status").val("No Payment");

        }
        else if(balance==0){

            $("#payment_status").val("Completed");

        }
        else if(enteredAmount>currentPayable){

            $("#payment_status").val("Advance Payment");

        }
        else{

            $("#payment_status").val("Partial Payment");

        }

    }



    /*==========================================================
    OLD PAYMENT MODE
    ==========================================================*/

    $(document).on("change",".old-payment-mode",function(){

        let mode = $(this).val();

        let transactionBox = $(this)
            .closest("tr")
            .find(".transaction-box");

        if(mode=="Cash"){

            transactionBox
                .val("")
                .prop("readonly",true)
                .attr("placeholder","Not Required");

        }else{

            transactionBox
                .prop("readonly",false)
                .attr("placeholder","Transaction No");

        }

    });


    /*==========================================================
    DELETE PAYMENT
    ==========================================================*/

    $(document).on("click",".delete-payment",function(){

        if(!confirm("Delete this payment ?")){

            return;

        }

        let button = $(this);

        let paymentId = button.data("id");

        let url = "{{ route('billing.delete-payment', ':id') }}";
        url = url.replace(':id', paymentId);

        $.ajax({

            url: url,

            type : "POST",

            data : {

                _token : "{{ csrf_token() }}",

                _method : "DELETE"

            },

            success:function(response){

                if(response.status){

                    button.closest("tr").fadeOut(300,function(){

                        $(this).remove();

                        location.reload();

                    });

                }else{

                    alert(response.message);

                }

            },

            error:function(){

                alert("Unable to delete payment.");

            }

        });

    });


    /*==========================================================
    FORM VALIDATION
    ==========================================================*/

    $("#billingForm").submit(function(e){

        calculatePayment();

        let paid =
            parseFloat($("#current_payment").val()) || 0;

        let due =
            parseFloat($("#remaining_amount").val()) || 0;

        if(paid<=0){

            alert("Please enter payment amount.");

            e.preventDefault();

            return;

        }

        if(paid>due){

            alert("Payment exceeds remaining due.");

            e.preventDefault();

            return;

        }

        let valid = true;

        $("#paymentTable tbody tr").each(function(){

            let mode =
                $(this).find(".payment-mode").val();

            let amount =
                parseFloat(
                    $(this)
                    .find(".payment-amount")
                    .val()
                ) || 0;

            let txn =
                $(this)
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
                    "Transaction Number is required."
                );

                valid=false;

            }

        });

        if(!valid){

            alert("Please complete payment details.");

            e.preventDefault();

            return;

        }

    });


    /*==========================================================
    INITIALIZE OLD PAYMENT MODES
    ==========================================================*/

    $(".old-payment-mode").each(function(){

        $(this).trigger("change");

    });


    /*==========================================================
    AUTO CASH FOR FIRST NEW ROW
    ==========================================================*/

    $("#paymentTable tbody tr:first .payment-mode")
        .val("Cash")
        .trigger("change");


    /*==========================================================
    INITIAL CALCULATION
    ==========================================================*/

    calculatePayment();

    });

</script>

@endpush
