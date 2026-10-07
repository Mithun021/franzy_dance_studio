@extends('backend.partial.master')

@section('title', 'View Notice')

@section('backend-content')

<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="card-title mb-0">
                    Notice Details
                </h4>

                <div>

                    <a href="{{ route('notice.edit', $notice->id) }}"
                       class="btn btn-warning">
                        Edit
                    </a>

                    <a href="{{ route('notice.index') }}"
                       class="btn btn-secondary">
                        Back
                    </a>

                </div>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Notice Date --}}
                    <div class="col-md-6 mb-4">

                        <label class="fw-bold">
                            Notice Date
                        </label>

                        <div class="mt-1">
                            {{ $notice->notice_date?->format('d-m-Y') }}
                        </div>

                    </div>

                    {{-- Status --}}
                    <div class="col-md-6 mb-4">

                        <label class="fw-bold">
                            Status
                        </label>

                        <div class="mt-1">

                            @if($notice->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>

                    {{-- Title --}}
                    <div class="col-12 mb-4">

                        <label class="fw-bold">
                            Notice Title
                        </label>

                        <div class="mt-1">
                            {{ $notice->title }}
                        </div>

                    </div>

                    {{-- Description --}}
                    <div class="col-12 mb-4">

                        <label class="fw-bold">
                            Description
                        </label>

                        <div class="mt-2">

                            @if($notice->description)

                                {!! nl2br(e($notice->description)) !!}

                            @else

                                <span class="text-muted">
                                    No description available.
                                </span>

                            @endif

                        </div>

                    </div>

                    {{-- File --}}
                    <div class="col-12 mb-4">

                        <label class="fw-bold">
                            Attachment
                        </label>

                        <div class="mt-2">

                            @if($notice->files)

                                <a href="{{ asset('notices/' . $notice->files) }}"
                                   target="_blank"
                                   class="btn btn-info">

                                    View / Download File

                                </a>

                            @else

                                <span class="text-muted">
                                    No attachment available.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
