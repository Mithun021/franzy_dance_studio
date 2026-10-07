@extends('backend.partial.master')

@section('title', 'Events')

@section('backend-content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Events</h4>
            <p class="text-muted mb-0">Manage all events</p>
        </div>

        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Event
        </a>
    </div>

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

    {{-- Events Table --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped" id="responsive-datatable">

                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th width="110">Date</th>
                            <th>Event Name</th>
                            <th width="110">Time</th>
                            <th>Venue</th>
                            <th width="100">Type</th>
                            <th width="100">Amount</th>
                            <th width="100">Document</th>
                            <th width="100">Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($events as $event)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                {{-- Date --}}
                                <td>
                                    {{ $event->event_date?->format('d M Y') }}
                                </td>

                                {{-- Event Name --}}
                                <td>
                                    <strong>
                                        {{ $event->event_name }}
                                    </strong>

                                    @if($event->city || $event->state)
                                        <br>
                                        <small class="text-muted">
                                            {{ $event->city }}
                                            @if($event->city && $event->state)
                                                ,
                                            @endif
                                            {{ $event->state }}
                                        </small>
                                    @endif
                                </td>

                                {{-- Time --}}
                                <td>
                                    @if($event->event_time)
                                        {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Venue --}}
                                <td>
                                    {{ $event->venue_details ?: '-' }}
                                </td>

                                {{-- Type --}}
                                <td>
                                    @if($event->is_free)
                                        <span class="badge bg-success">
                                            Free
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Paid
                                        </span>
                                    @endif
                                </td>

                                {{-- Amount --}}
                                <td>
                                    @if(!$event->is_free)
                                        ₹{{ number_format($event->event_amount ?? 0, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Document --}}
                                <td>

                                    @if($event->document)

                                        <a href="{{ asset('events/' . $event->document) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-file"></i>
                                        </a>

                                    @else
                                        <span class="text-muted">-</span>
                                    @endif

                                </td>

                                <td>
                                    @if($event->status)
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex gap-1">

                                        <a href="{{ route('events.edit', $event->id) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <form action="{{ route('events.destroy', $event->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this event?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete">
                                                <i class="mdi mdi-delete"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                        <br>
                                        No events found.
                                    </div>
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
