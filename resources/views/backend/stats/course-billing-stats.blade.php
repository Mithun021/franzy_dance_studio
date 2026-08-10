{{-- ========================================================= --}}
{{-- COURSE BILLING STATISTICS --}}
{{-- ========================================================= --}}

<div class="row mt-4">


    {{-- Today's Collection --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-primary shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                    Today's Collection

                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">

                    ₹ {{ number_format(
                        $courseBillingStats->today_collection ?? 0,
                        2
                    ) }}

                </div>

            </div>

        </div>

    </div>


    {{-- Monthly Collection --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-success shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                    Monthly Collection

                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">

                    ₹ {{ number_format(
                        $courseBillingStats->monthly_collection ?? 0,
                        2
                    ) }}

                </div>

            </div>

        </div>

    </div>


    {{-- Yearly Collection --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-info shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">

                    Yearly Collection

                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">

                    ₹ {{ number_format(
                        $courseBillingStats->yearly_collection ?? 0,
                        2
                    ) }}

                </div>

            </div>

        </div>

    </div>


    {{-- Total Collection --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-dark shadow h-100 py-2">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">

                    Total Collection

                </div>

                <div class="h3 mb-0 font-weight-bold text-gray-800">

                    ₹ {{ number_format(
                        $courseBillingStats->total_collection ?? 0,
                        2
                    ) }}

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row">


    {{-- Total Students --}}
    <div class="col-xl-4 col-md-6 mb-4">

        <div class="card shadow border-left-primary">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                    Total Admissions

                </div>

                <div class="h3 font-weight-bold">

                    {{ $courseStudentStats->total_students ?? 0 }}

                </div>

            </div>

        </div>

    </div>


    {{-- Enrolled --}}
    <div class="col-xl-4 col-md-6 mb-4">

        <div class="card shadow border-left-success">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                    Enrolled Students

                </div>

                <div class="h3 font-weight-bold">

                    {{ $courseStudentStats->enrolled_students ?? 0 }}

                </div>

            </div>

        </div>

    </div>


    {{-- Pending Enrollment --}}
    <div class="col-xl-4 col-md-6 mb-4">

        <div class="card shadow border-left-warning">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">

                    Enrollment Pending

                </div>

                <div class="h3 font-weight-bold">

                    {{ $courseStudentStats->pending_enrollment ?? 0 }}

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-xl-6 col-md-6 mb-4">

        <div class="card shadow border-left-danger">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">

                    Total Course Billing Due

                </div>

                <div class="h3 font-weight-bold text-danger">

                    ₹ {{ number_format(
                        $totalCourseDue,
                        2
                    ) }}

                </div>

            </div>

        </div>

    </div>


    <div class="col-xl-6 col-md-6 mb-4">

        <div class="card shadow border-left-success">

            <div class="card-body">

                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                    Successful Collection

                </div>

                <div class="h3 font-weight-bold text-success">

                    ₹ {{ number_format(
                        $courseBillingStats->total_collection ?? 0,
                        2
                    ) }}

                </div>

            </div>

        </div>

    </div>

</div>

<div class="card shadow mb-4">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            Course Payment Method Wise Collection

        </h5>

    </div>


    <div class="card-body">

        <div class="row">

            @php

                $coursePaymentMethods = [
                    'Cash',
                    'UPI',
                    'Card',
                    'Bank Transfer',
                    'Cheque'
                ];

            @endphp


            @foreach($coursePaymentMethods as $method)

                @php

                    $paymentStat = $coursePaymentMethodStats
                        ->firstWhere(
                            'payment_mode',
                            $method
                        );

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

                                        ₹ {{ number_format(
                                            $paymentStat->today_collection ?? 0,
                                            2
                                        ) }}

                                    </div>

                                </div>


                                <div class="col-6 mb-3">

                                    <small class="text-muted">
                                        MONTHLY
                                    </small>

                                    <div class="font-weight-bold">

                                        ₹ {{ number_format(
                                            $paymentStat->monthly_collection ?? 0,
                                            2
                                        ) }}

                                    </div>

                                </div>


                                <div class="col-6">

                                    <small class="text-muted">
                                        YEARLY
                                    </small>

                                    <div class="font-weight-bold">

                                        ₹ {{ number_format(
                                            $paymentStat->yearly_collection ?? 0,
                                            2
                                        ) }}

                                    </div>

                                </div>


                                <div class="col-6">

                                    <small class="text-muted">
                                        TOTAL
                                    </small>

                                    <div class="font-weight-bold text-success">

                                        ₹ {{ number_format(
                                            $paymentStat->total_collection ?? 0,
                                            2
                                        ) }}

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

            Payment Type Wise Collection

        </h5>

    </div>


    <div class="card-body">

        <div class="row">

            @php

                $paymentTypes = [
                    'full' => 'Full Payment',
                    'half' => 'Half Payment',
                    'next_month' => 'Next Month'
                ];

            @endphp


            @foreach($paymentTypes as $type => $label)

                @php

                    $typeStat = $coursePaymentTypeStats
                        ->firstWhere(
                            'payment_type',
                            $type
                        );

                @endphp


                <div class="col-md-4 mb-3">

                    <div class="card border shadow-sm">

                        <div class="card-body text-center">

                            <h5 class="font-weight-bold">

                                {{ $label }}

                            </h5>


                            <div class="h3 text-primary">

                                ₹ {{ number_format(
                                    $typeStat->total_collection ?? 0,
                                    2
                                ) }}

                            </div>


                            <small class="text-muted">

                                {{ $typeStat->payment_count ?? 0 }}

                                Payments

                            </small>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>
