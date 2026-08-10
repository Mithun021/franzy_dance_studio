{{-- ========================================================= --}}
{{-- PAYMENT COLLECTION CHARTS --}}
{{-- ========================================================= --}}

<div class="row">

    {{-- Current Year --}}
    <div class="col-lg-12 mb-4">

        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    Current Year Payment Collection
                </h5>

                <small class="text-muted">
                    {{ now()->year }} monthly successful payments
                </small>

            </div>

            <div class="card-body">

                <div style="height:350px;">
                    <canvas id="monthlyPaymentChart"></canvas>
                </div>

            </div>

        </div>

    </div>


    {{-- Year Wise --}}
    <div class="col-lg-6 mb-4">

        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    Year Wise Payment Collection
                </h5>

            </div>

            <div class="card-body">

                <div style="height:350px;">
                    <canvas id="yearlyPaymentChart"></canvas>
                </div>

            </div>

        </div>

    </div>


    {{-- Course Wise --}}
    <div class="col-lg-6 mb-4">

        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    Course Wise Payment Collection
                </h5>

            </div>

            <div class="card-body">

                <div style="height:350px;">
                    <canvas id="coursePaymentChart"></canvas>
                </div>

            </div>

        </div>

    </div>

</div>

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | 1. Current Year Monthly Payment
    |--------------------------------------------------------------------------
    */

    const monthlyData = @json($monthlyPaymentData);

    new Chart(
        document.getElementById('monthlyPaymentChart'),
        {
            type: 'line',

            data: {

                labels: monthlyData.map(item => item.month),

                datasets: [

                    {
                        label: 'Payment Collected',

                        data: monthlyData.map(item => item.total),

                        tension: 0.4,

                        fill: true
                    }

                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    tooltip: {

                        callbacks: {

                            label: function(context) {

                                return '₹ ' +
                                    Number(context.raw)
                                    .toLocaleString('en-IN', {
                                        minimumFractionDigits: 2
                                    });

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function(value) {

                                return '₹ ' +
                                    Number(value)
                                    .toLocaleString('en-IN');

                            }

                        }

                    }

                }

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | 2. Year Wise Payment
    |--------------------------------------------------------------------------
    */

    const yearlyData = @json($yearlyPaymentData);

    new Chart(
        document.getElementById('yearlyPaymentChart'),
        {
            type: 'bar',

            data: {

                labels: yearlyData.map(item => item.year),

                datasets: [

                    {
                        label: 'Payment Collected',

                        data: yearlyData.map(item => item.total)

                    }

                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    tooltip: {

                        callbacks: {

                            label: function(context) {

                                return '₹ ' +
                                    Number(context.raw)
                                    .toLocaleString('en-IN', {
                                        minimumFractionDigits: 2
                                    });

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function(value) {

                                return '₹ ' +
                                    Number(value)
                                    .toLocaleString('en-IN');

                            }

                        }

                    }

                }

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | 3. Course Wise Payment
    |--------------------------------------------------------------------------
    */

    const courseData = @json($coursePaymentData);

    new Chart(
        document.getElementById('coursePaymentChart'),
        {
            type: 'bar',

            data: {

                labels: courseData.map(item => item.course),

                datasets: [

                    {
                        label: 'Payment Collected',

                        data: courseData.map(item => item.total)

                    }

                ]

            },

            options: {

                indexAxis: 'y',

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    tooltip: {

                        callbacks: {

                            label: function(context) {

                                return '₹ ' +
                                    Number(context.raw)
                                    .toLocaleString('en-IN', {
                                        minimumFractionDigits: 2
                                    });

                            }

                        }

                    }

                },

                scales: {

                    x: {

                        beginAtZero: true,

                        ticks: {

                            callback: function(value) {

                                return '₹ ' +
                                    Number(value)
                                    .toLocaleString('en-IN');

                            }

                        }

                    }

                }

            }

        }

    );

});

</script>

@endpush
