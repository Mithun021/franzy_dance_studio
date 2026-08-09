<div class="row">

    {{-- Daily Registration --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-primary shadow h-100 py-2">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col">

                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                            Today's Registration

                        </div>

                        <div class="h3 mb-0 font-weight-bold">

                            {{ $todayUsers }}

                        </div>

                    </div>

                    <div class="col-auto">

                        <i data-feather="user-plus" style="width:40px;height:40px;"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Monthly Registration --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-success shadow h-100 py-2">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col">

                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                            Monthly Registration

                        </div>

                        <div class="h3 mb-0 font-weight-bold">

                            {{ $monthlyUsers }}

                        </div>

                    </div>

                    <div class="col-auto">

                        <i data-feather="users" style="width:40px;height:40px;"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Yearly Registration --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-warning shadow h-100 py-2">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col">

                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">

                            Yearly Registration

                        </div>

                        <div class="h3 mb-0 font-weight-bold">

                            {{ $yearlyUsers }}

                        </div>

                    </div>

                    <div class="col-auto">

                        <i data-feather="calendar" style="width:40px;height:40px;"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Total Student --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-danger shadow h-100 py-2">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col">

                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">

                            Total Students

                        </div>

                        <div class="h3 mb-0 font-weight-bold">

                            {{ $totalUsers }}

                        </div>

                    </div>

                    <div class="col-auto">

                        <i data-feather="user-check" style="width:40px;height:40px;"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row">

    {{-- Today's Enrollment --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-info shadow h-100 py-2">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col">

                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">

                            Today's Enrollment

                        </div>

                        <div class="h3 mb-0 font-weight-bold">

                            {{ $todayEnroll }}

                        </div>

                    </div>

                    <div class="col-auto">

                        <i data-feather="user-plus"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Monthly Enrollment --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-success shadow h-100 py-2">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col">

                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                            Monthly Enrollment

                        </div>

                        <div class="h3 mb-0 font-weight-bold">

                            {{ $monthlyEnroll }}

                        </div>

                    </div>

                    <div class="col-auto">

                        <i data-feather="users"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Yearly Enrollment --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-warning shadow h-100 py-2">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col">

                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">

                            Yearly Enrollment

                        </div>

                        <div class="h3 mb-0 font-weight-bold">

                            {{ $yearlyEnroll }}

                        </div>

                    </div>

                    <div class="col-auto">

                        <i data-feather="calendar"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Total Enrollment --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-primary shadow h-100 py-2">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col">

                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                            Total Enrollment

                        </div>

                        <div class="h3 mb-0 font-weight-bold">

                            {{ $totalEnroll }}

                        </div>

                    </div>

                    <div class="col-auto">

                        <i data-feather="book-open"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <h6 class="text-success">Today's Ongoing</h6>
                <h2>{{ $todayOngoing }}</h2>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <h6 class="text-success">Monthly Ongoing</h6>
                <h2>{{ $monthlyOngoing }}</h2>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <h6 class="text-success">Yearly Ongoing</h6>
                <h2>{{ $yearlyOngoing }}</h2>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <h6 class="text-success">Total Ongoing</h6>
                <h2>{{ $totalOngoing }}</h2>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <h6 class="text-primary">Today's Completed</h6>
                <h2>{{ $todayCompleted }}</h2>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <h6 class="text-primary">Monthly Completed</h6>
                <h2>{{ $monthlyCompleted }}</h2>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <h6 class="text-primary">Yearly Completed</h6>
                <h2>{{ $yearlyCompleted }}</h2>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <h6 class="text-primary">Total Completed</h6>
                <h2>{{ $totalCompleted }}</h2>
            </div>
        </div>
    </div>

</div>

<div class="row">

    {{-- Daily Payment --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-success shadow h-100 py-2">

            <div class="card-body">

                <h6 class="text-success">
                    Daily Payment
                </h6>

                <h2>
                    ₹ {{ number_format($dailyPayment,2) }}
                </h2>

            </div>

        </div>

    </div>

    {{-- Monthly Payment --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-primary shadow h-100 py-2">

            <div class="card-body">

                <h6 class="text-primary">
                    Monthly Payment
                </h6>

                <h2>
                    ₹ {{ number_format($monthlyPayment,2) }}
                </h2>

            </div>

        </div>

    </div>

    {{-- Yearly Payment --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-warning shadow h-100 py-2">

            <div class="card-body">

                <h6 class="text-warning">
                    Yearly Payment
                </h6>

                <h2>
                    ₹ {{ number_format($yearlyPayment,2) }}
                </h2>

            </div>

        </div>

    </div>

    {{-- Total Payment --}}
    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-danger shadow h-100 py-2">

            <div class="card-body">

                <h6 class="text-danger">
                    Total Payment
                </h6>

                <h2>
                    ₹ {{ number_format($totalPayment,2) }}
                </h2>

            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-lg-6">

        <div class="card shadow mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Monthly Payment ({{ date('Y') }})
                </h5>

            </div>

            <div class="card-body">

                <canvas id="monthlyPaymentChart"></canvas>

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="card shadow mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Yearly Payment
                </h5>

            </div>

            <div class="card-body">

                <canvas id="yearlyPaymentChart"></canvas>

            </div>

        </div>

    </div>

</div>

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    new Chart(document.getElementById('monthlyPaymentChart'), {

    type: 'bar',

    data: {

        labels: [
            'Jan','Feb','Mar','Apr','May','Jun',
            'Jul','Aug','Sep','Oct','Nov','Dec'
        ],

        datasets: [{

            label: 'Payment',

            data: @json($monthlyData),

            backgroundColor: '#4e73df'

        }]

    },

    options: {

        responsive: true,

        plugins: {

            legend: {

                display: false

            }

        }

    }

});

new Chart(document.getElementById('yearlyPaymentChart'), {

    type: 'bar',

    data: {

        labels: @json($yearLabels),

        datasets: [{

            label: 'Payment',

            data: @json($yearData),

            backgroundColor: '#1cc88a'

        }]

    },

    options: {

        responsive: true,

        plugins: {

            legend: {

                display: false

            }

        }

    }

});
</script>
@endpush
