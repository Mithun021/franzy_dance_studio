{{-- ========================================================= --}}
{{-- Expense Statistics --}}
{{-- ========================================================= --}}

<div class="row mt-4">

    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-danger shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">

                    Today's Expense

                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">

                    ₹ {{ number_format($expenseStats->today_expense ?? 0, 2) }}

                </div>

            </div>

        </div>

    </div>


    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-warning shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">

                    Monthly Expense

                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">

                    ₹ {{ number_format($expenseStats->monthly_expense ?? 0, 2) }}

                </div>

            </div>

        </div>

    </div>


    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-info shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">

                    Yearly Expense

                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">

                    ₹ {{ number_format($expenseStats->yearly_expense ?? 0, 2) }}

                </div>

            </div>

        </div>

    </div>


    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-dark shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">

                    Total Expense

                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">

                    ₹ {{ number_format($expenseStats->total_expense ?? 0, 2) }}

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- Payment Method Wise Expense --}}
{{-- ========================================================= --}}

<div class="card shadow mb-4">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            Expense Payment Method Wise

        </h5>

    </div>


    <div class="card-body">

        <div class="row">

            @php

                $paymentMethods = [
                    'Cash',
                    'UPI',
                    'Bank Transfer',
                    'Cheque',
                    'Card'
                ];

            @endphp


            @foreach($paymentMethods as $method)

                @php

                    $paymentStat = $expensePaymentStats
                        ->firstWhere('payment_method', $method);

                @endphp


                <div class="col-xl-4 col-md-6 mb-4">

                    <div class="card border shadow-sm h-100">

                        <div class="card-body">

                            <h5 class="font-weight-bold text-primary mb-3">

                                {{ $method }}

                            </h5>


                            <div class="row">

                                <div class="col-6 mb-3">

                                    <small class="text-muted text-uppercase">

                                        Today

                                    </small>

                                    <div class="font-weight-bold">

                                        ₹ {{ number_format($paymentStat->today_expense ?? 0, 2) }}

                                    </div>

                                </div>


                                <div class="col-6 mb-3">

                                    <small class="text-muted text-uppercase">

                                        Monthly

                                    </small>

                                    <div class="font-weight-bold">

                                        ₹ {{ number_format($paymentStat->monthly_expense ?? 0, 2) }}

                                    </div>

                                </div>


                                <div class="col-6">

                                    <small class="text-muted text-uppercase">

                                        Yearly

                                    </small>

                                    <div class="font-weight-bold">

                                        ₹ {{ number_format($paymentStat->yearly_expense ?? 0, 2) }}

                                    </div>

                                </div>


                                <div class="col-6">

                                    <small class="text-muted text-uppercase">

                                        Total

                                    </small>

                                    <div class="font-weight-bold text-danger">

                                        ₹ {{ number_format($paymentStat->total_expense ?? 0, 2) }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>
