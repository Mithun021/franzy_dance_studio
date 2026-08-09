@extends('backend.partial.master')

@section('title','Salary Invoice')

@section('backend-content')

<style>

.invoice-box{

    max-width:900px;

    margin:auto;

    background:#fff;

    border:1px solid #ddd;

    padding:30px;

}

.invoice-table{

    width:100%;

    border-collapse:collapse;

}

.invoice-table td,
.invoice-table th{

    border:1px solid #dee2e6;

    padding:10px;

}

.invoice-table th{

    background:#f8f9fa;

}

@media print{

    body *{

        visibility:hidden;

    }

    #invoice-area,
    #invoice-area *{

        visibility:visible;

    }

    #invoice-area{

        position:absolute;

        left:0;

        top:0;

        width:100%;

    }

    .no-print{

        display:none !important;

    }

}

</style>

<div class="row">

    <div class="col-lg-12">

        <div class="mb-3 no-print">

            <a href="{{ route('salary-management.index') }}"
               class="btn btn-secondary">

                <i class="fa fa-arrow-left"></i>

                Back

            </a>

            <button
                onclick="window.print()"
                class="btn btn-primary">

                <i class="fa fa-print"></i>

                Print

            </button>

        </div>

        <div id="invoice-area">

            <div class="invoice-box">

                <table width="100%">

                    <tr>

                        <td>

                            <h2 class="mb-0">

                                {{-- {{ config('app.name') }} --}}
                                FRANZY DANCE STUDIO

                            </h2>

                            <small>Salary Payment Invoice</small>

                        </td>

                        <td align="right">

                            <h4>

                                Invoice

                            </h4>

                            <strong>

                                {{ $salary->salary_id }}

                            </strong>

                        </td>

                    </tr>

                </table>

                <hr>

                <table class="invoice-table">

                    <tr>

                        <th width="30%">Employee ID</th>

                        <td>

                            {{ $salary->employee->user_id }}

                        </td>

                    </tr>

                    <tr>

                        <th>Employee Name</th>

                        <td>

                            {{ $salary->employee->name }}

                        </td>

                    </tr>

                    <tr>

                        <th>Email</th>

                        <td>

                            {{ $salary->employee->email }}

                        </td>

                    </tr>

                    <tr>

                        <th>Phone</th>

                        <td>

                            {{ $salary->employee->phone }}

                        </td>

                    </tr>

                    <tr>

                        <th>Salary Month</th>

                        <td>

                            {{ \Carbon\Carbon::parse($salary->salary_month)->format('F Y') }}

                        </td>

                    </tr>

                </table>

                <br>

                <table class="invoice-table">

                    <thead>

                        <tr>

                            <th>Description</th>

                            <th width="160">

                                Amount

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>

                                Monthly Salary

                            </td>

                            <td>

                                ₹ {{ number_format($salary->salary_amount,2) }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Paid Amount

                            </td>

                            <td>

                                ₹ {{ number_format($salary->paid_amount,2) }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Due Amount

                            </td>

                            <td>

                                ₹ {{ number_format($salary->due_amount,2) }}

                            </td>

                        </tr>

                    </tbody>

                    <tfoot>

                        <tr>

                            <th>

                                Payment Method

                            </th>

                            <th>

                                {{ $salary->payment_method }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

                <br>

                <table class="invoice-table">

                    <tr>

                        <th width="30%">

                            Remarks

                        </th>

                        <td>

                            {{ $salary->description ?: '-' }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Created By

                        </th>

                        <td>

                            {{ $salary->creator->name }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Created At

                        </th>

                        <td>

                            {{ $salary->created_at->format('d M Y h:i A') }}

                        </td>

                    </tr>

                </table>

                <br><br><br>

                <table width="100%">

                    <tr>

                        <td align="left">

                            ______________________

                            <br>

                            Employee Signature

                        </td>

                        <td align="right">

                            ______________________

                            <br>

                            Authorized Signature

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
