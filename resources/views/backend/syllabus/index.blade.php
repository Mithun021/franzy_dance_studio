@extends('backend.partial.master')

@section('title', 'Syllabus Management')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow-sm">

            <div class="card-header d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-0">

                        <i class="fas fa-book-open text-primary"></i>

                        Syllabus Management

                    </h4>

                    <small class="text-muted">
                        Manage Course & Level Syllabus
                    </small>

                </div>

                <a href="{{ route('syllabus.create') }}"
                   class="btn btn-primary">

                    <i class="fas fa-plus-circle"></i>

                    Add New Syllabus

                </a>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle" id="responsive-datatable">

                        <thead class="table-dark text-center">

                            <tr>

                                <th width="60">#</th>

                                <th>Course</th>

                                <th>Level</th>

                                <th>Total Chapters</th>

                                <th>Total Duration</th>

                                <th>Created</th>

                                <th width="220">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($syllabusCourses as $key => $item)

                                @php

                                    $totalDuration = $item->details
                                        ->pluck('duration')
                                        ->filter()
                                        ->implode(', ');

                                @endphp

                                <tr>

                                    <td class="text-center">

                                        {{ ++$key }}

                                    </td>

                                    <td>

                                        <strong>

                                            {{ optional($item->course)->course_name }}

                                        </strong>

                                    </td>

                                    <td>

                                        <span class="badge bg-info">

                                            {{ optional($item->level)->name }}

                                        </span>

                                    </td>

                                    <td class="text-center">

                                        <span class="badge bg-success">

                                            {{ $item->details->count() }}

                                        </span>

                                    </td>

                                    <td>

                                        @if($totalDuration)

                                            <small>

                                                {{ $totalDuration }}

                                            </small>

                                        @else

                                            <span class="text-muted">

                                                N/A

                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        {{ $item->created_at->format('d M Y') }}

                                    </td>

                                    <td class="text-center">

                                        <a href="{{ route('syllabus.show',$item->id) }}"
                                           class="btn btn-sm btn-info">

                                            <i class="mdi mdi-eye"></i>

                                        </a>

                                        <a href="{{ route('syllabus.edit',$item->id) }}"
                                           class="btn btn-sm btn-warning">

                                            <i class="mdi mdi-pencil"></i>

                                        </a>

                                        <form action="{{ route('syllabus.destroy',$item->id) }}"
                                              method="POST"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this syllabus?')">

                                                <i class="mdi mdi-delete"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7"
                                        class="text-center py-5">

                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                                        <h5>

                                            No Syllabus Found

                                        </h5>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
