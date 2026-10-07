@extends('backend.partial.master')

@section('title', 'Notice Master')

@section('backend-content')

<div class="row">

    <div class="col-12">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    Notice List
                </h4>

                <a href="{{ route('notice.create') }}" class="btn btn-primary">
                    Add Notice
                </a>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped" id="responsive-datatable">

                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th width="130">Notice Date</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th width="120">File</th>
                                <th width="100">Status</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($notices as $notice)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $notice->notice_date?->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $notice->title }}
                                        </strong>
                                    </td>

                                    <td>
                                        @if($notice->description)
                                            {{ \Illuminate\Support\Str::limit(strip_tags($notice->description), 100) }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>

                                    <td>

                                        @if($notice->files)

                                            <a href="{{ asset('notices/' . $notice->files) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-info">
                                                View File
                                            </a>

                                        @else

                                            <span class="text-muted">
                                                No File
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        @if($notice->status)

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <div class="d-flex gap-1">

                                            {{-- Edit --}}
                                            <a href="{{ route('notice.edit', $notice->id) }}"
                                               class="btn btn-sm btn-warning">
                                                Edit
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('notice.destroy', $notice->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this notice?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <span class="text-muted">
                                            No notices found.
                                        </span>
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
