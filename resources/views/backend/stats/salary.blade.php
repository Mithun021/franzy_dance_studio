<div class="row mt-4">

    {{-- Today's Salary --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-primary shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Today's Salary
                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">
                    ₹ {{ number_format($salaryStats->today_salary ?? 0, 2) }}
                </div>

            </div>

        </div>

    </div>


    {{-- Monthly Salary --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-info shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    Monthly Salary
                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">
                    ₹ {{ number_format($salaryStats->monthly_salary ?? 0, 2) }}
                </div>

            </div>

        </div>

    </div>


    {{-- Yearly Salary --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-warning shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    Yearly Salary
                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">
                    ₹ {{ number_format($salaryStats->yearly_salary ?? 0, 2) }}
                </div>

            </div>

        </div>

    </div>


    {{-- Total Salary --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-danger shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                    Total Salary
                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">
                    ₹ {{ number_format($salaryStats->total_salary ?? 0, 2) }}
                </div>

            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-4">

        <div class="card shadow border-left-success">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Total Paid Salary
                </div>

                <div class="h3 font-weight-bold">
                    ₹ {{ number_format($salaryStats->total_paid ?? 0, 2) }}
                </div>

            </div>

        </div>

    </div>


    <div class="col-md-6 mb-4">

        <div class="card shadow border-left-danger">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                    Total Due Salary
                </div>

                <div class="h3 font-weight-bold">
                    ₹ {{ number_format($salaryStats->total_due ?? 0, 2) }}
                </div>

            </div>

        </div>

    </div>

</div>

<div class="card shadow mb-4">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">
            Salary Payment Method Wise
        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            @php
                $salaryPaymentMethods = [
                    'Cash',
                    'UPI',
                    'Bank Transfer',
                    'Cheque',
                    'Card'
                ];
            @endphp

            @foreach($salaryPaymentMethods as $method)

                @php
                    $paymentStat = $salaryPaymentStats
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

                                    <small class="text-muted">
                                        TODAY
                                    </small>

                                    <div class="font-weight-bold">
                                        ₹ {{ number_format($paymentStat->today_paid ?? 0, 2) }}
                                    </div>

                                </div>


                                <div class="col-6 mb-3">

                                    <small class="text-muted">
                                        MONTHLY
                                    </small>

                                    <div class="font-weight-bold">
                                        ₹ {{ number_format($paymentStat->monthly_paid ?? 0, 2) }}
                                    </div>

                                </div>


                                <div class="col-6">

                                    <small class="text-muted">
                                        YEARLY
                                    </small>

                                    <div class="font-weight-bold">
                                        ₹ {{ number_format($paymentStat->yearly_paid ?? 0, 2) }}
                                    </div>

                                </div>


                                <div class="col-6">

                                    <small class="text-muted">
                                        TOTAL
                                    </small>

                                    <div class="font-weight-bold text-success">
                                        ₹ {{ number_format($paymentStat->total_paid ?? 0, 2) }}
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

<div class="card shadow mb-4">

    <div class="card-header bg-dark text-white">

        <h5 class="mb-0">
            Employee Wise Salary
        </h5>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-light">

                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Salary Records</th>
                        <th>Total Salary</th>
                        <th>Total Paid</th>
                        <th>Total Due</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($employeeSalaryStats as $key => $employee)

                        <tr>

                            <td>
                                {{ $key + 1 }}
                            </td>

                            <td class="fw-bold">
                                {{ $employee->employee->name ?? 'Unknown' }}
                            </td>

                            <td>
                                {{ $employee->salary_records }}
                            </td>

                            <td>
                                ₹ {{ number_format($employee->total_salary, 2) }}
                            </td>

                            <td class="text-success fw-bold">
                                ₹ {{ number_format($employee->total_paid, 2) }}
                            </td>

                            <td class="text-danger fw-bold">
                                ₹ {{ number_format($employee->total_due, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center">
                                No salary records found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
