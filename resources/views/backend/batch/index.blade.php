@extends('backend.partial.master')

@section('title', 'Batch Master')

@section('backend-content')

<div class="row">

    <div class="col-md-12">

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Batch List
                </h4>

                <a href="{{ route('batches.create') }}" class="btn btn-primary">

                    <i class="fa fa-plus"></i> Add Batch

                </a>

            </div>

            <div class="card-body table-responsive">

                <table
                    class="table table-bordered table-striped"
                    id="responsive-datatable">

                    <thead>

                    <tr>

                        <th width="60">SL</th>

                        <th>Course</th>

                        <th>Level</th>

                        <th>Batch Name</th>

                        <th>Class Schedule</th>

                        <th>Capacity</th>

                        <th width="160">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($batches as $key => $batch)

                        <tr>

                            {{-- SL --}}
                            <td>
                                {{ $key + 1 }}
                            </td>

                            {{-- Course --}}
                            <td>
                                {{ $batch->course->course_name }}
                            </td>

                            {{-- Level --}}
                            <td>
                                {{ $batch->level->name }}
                            </td>

                            {{-- Batch Name --}}
                            <td>
                                {{ $batch->batch_name }}
                            </td>

                            {{-- Class Schedule --}}
                            <td>

                                @php

                                    $scheduleDays = [
                                        'monday' => 'Mon',
                                        'tuesday' => 'Tue',
                                        'wednesday' => 'Wed',
                                        'thursday' => 'Thu',
                                        'friday' => 'Fri',
                                        'saturday' => 'Sat',
                                        'sunday' => 'Sun',
                                    ];

                                @endphp

                                @foreach($scheduleDays as $dayKey => $dayName)

                                    @php

                                        $start = $batch->{$dayKey . '_start_time'};
                                        $end = $batch->{$dayKey . '_end_time'};

                                    @endphp

                                    @if($start && $end)

                                        <div class="mb-1">

                                            <span class="badge bg-primary">
                                                {{ $dayName }}
                                            </span>

                                            <span class="ms-1">

                                                {{ date('h:i A', strtotime($start)) }}

                                                -

                                                {{ date('h:i A', strtotime($end)) }}

                                            </span>

                                        </div>

                                    @endif

                                @endforeach

                                {{-- No schedule --}}
                                @php

                                    $hasSchedule = false;

                                    foreach ($scheduleDays as $dayKey => $dayName) {

                                        if (
                                            $batch->{$dayKey . '_start_time'} &&
                                            $batch->{$dayKey . '_end_time'}
                                        ) {
                                            $hasSchedule = true;
                                            break;
                                        }

                                    }

                                @endphp

                                @if(!$hasSchedule)

                                    <span class="text-muted">
                                        No Schedule
                                    </span>

                                @endif

                            </td>

                            {{-- Capacity --}}
                            <td>

                                {{ $batch->capacity }}

                            </td>

                            {{-- Action --}}
                            <td>

                                <a
                                    href="{{ route('batches.edit', $batch->id) }}"
                                    class="btn btn-warning btn-sm">

                                    <i class="fa fa-edit"></i>
                                    Edit

                                </a>

                                <form
                                    action="{{ route('batches.destroy', $batch->id) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this batch?')">

                                        <i class="fa fa-trash"></i>
                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center text-danger">

                                No Batch Found

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
