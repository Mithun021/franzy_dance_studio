<div class="row">

    @forelse($courseLevelStats as $stat)

        <div class="col-xl-6 col-md-6 mb-4">

            <div class="card shadow h-100">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        {{ $stat->course->course_name ?? 'Course' }}

                        @if($stat->level)
                            - {{ $stat->level->name }}
                        @endif

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row text-center">

                        {{-- Today's Enroll --}}
                        <div class="col-6 col-md-3 mb-3">

                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                                Today's Enroll

                            </div>

                            <div class="h3 mb-0 font-weight-bold">

                                {{ $stat->today_enroll }}

                            </div>

                        </div>


                        {{-- Monthly Enroll --}}
                        <div class="col-6 col-md-3 mb-3">

                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                                Monthly Enroll

                            </div>

                            <div class="h3 mb-0 font-weight-bold">

                                {{ $stat->monthly_enroll }}

                            </div>

                        </div>


                        {{-- Yearly Enroll --}}
                        <div class="col-6 col-md-3 mb-3">

                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">

                                Yearly Enroll

                            </div>

                            <div class="h3 mb-0 font-weight-bold">

                                {{ $stat->yearly_enroll }}

                            </div>

                        </div>


                        {{-- Total Enroll --}}
                        <div class="col-6 col-md-3 mb-3">

                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">

                                Total Enroll

                            </div>

                            <div class="h3 mb-0 font-weight-bold">

                                {{ $stat->total_enroll }}

                            </div>

                        </div>

                    </div>


                    <hr>


                    {{-- Pending --}}
                    <div class="d-flex justify-content-between align-items-center">

                        <span class="font-weight-bold text-warning">

                            Enroll Pending

                        </span>

                        <span class="badge bg-warning text-dark fs-6">

                            {{ $stat->enroll_pending }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-info">

                No course enrollment data found.

            </div>

        </div>

    @endforelse

</div>

@include('backend.stats.studio')
