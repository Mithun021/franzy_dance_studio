@extends('backend.partial.master')

@section('title', 'Batch Master')

@section('backend-content')

<div class="row">

    <div class="col-md-12">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

                <table class="table table-bordered table-striped" id="responsive-datatable">

                    <thead>

                    <tr>

                        <th width="60">SL</th>

                        <th>Course</th>

                        <th>Level</th>

                        <th>Batch Name</th>

                        <th>Class Days</th>

                        <th>Timing</th>

                        <th>Capacity</th>

                        <th width="160">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($batches as $key => $batch)

                        <tr>

                            <td>{{ $key+1 }}</td>

                            <td>{{ $batch->course->course_name }}</td>

                            <td>{{ $batch->level->name }}</td>

                            <td>{{ $batch->batch_name }}</td>

                            <td>

                                @foreach($batch->class_days as $day)

                                    <span class="badge bg-primary">

                                        {{ $day }}

                                    </span>

                                @endforeach

                            </td>

                            <td>

                                {{ date('h:i A', strtotime($batch->start_time)) }}

                                -

                                {{ date('h:i A', strtotime($batch->end_time)) }}

                            </td>

                            <td>

                                {{ $batch->capacity }}

                            </td>

                            <td>

                                <a href="{{ route('batches.edit',$batch->id) }}"
                                   class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <form action="{{ route('batches.destroy',$batch->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this batch?')">

                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center text-danger">

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
