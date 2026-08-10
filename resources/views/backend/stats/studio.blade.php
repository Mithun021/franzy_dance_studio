{{-- ===================================================== --}}
{{-- Studio Booking Stats --}}
{{-- ===================================================== --}}

<div class="row">

    @foreach($studioStats as $studio)

        <div class="col-xl-4 col-md-6 mb-4">

            <div class="card shadow h-100">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        {{ $studio['name'] }}

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Today --}}
                        <div class="col-6 mb-4">

                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                                Today's Booking

                            </div>

                            <div class="h4 mb-0 font-weight-bold">

                                {{ $studio['today'] }}

                            </div>

                        </div>


                        {{-- Monthly --}}
                        <div class="col-6 mb-4">

                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                                Monthly Booking

                            </div>

                            <div class="h4 mb-0 font-weight-bold">

                                {{ $studio['monthly'] }}

                            </div>

                        </div>


                        {{-- Yearly --}}
                        <div class="col-6">

                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">

                                Yearly Booking

                            </div>

                            <div class="h4 mb-0 font-weight-bold">

                                {{ $studio['yearly'] }}

                            </div>

                        </div>


                        {{-- Total --}}
                        <div class="col-6">

                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">

                                Total Booking

                            </div>

                            <div class="h4 mb-0 font-weight-bold">

                                {{ $studio['total'] }}

                            </div>

                        </div>

                    </div>


                    <hr>


                    {{-- Pending --}}
                    <div class="d-flex justify-content-between align-items-center">

                        <span class="text-warning font-weight-bold">

                            Pending / Other

                        </span>

                        <span class="badge bg-warning text-dark">

                            {{ $studio['pending'] }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

</div>
