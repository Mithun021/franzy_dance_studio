@extends('backend.partial.master')

@section('title', 'Holiday Management')

@section('backend-content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">Holiday List</h5>

            <a href="{{ route('holidays.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Add Holidays
            </a>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped"  id="responsive-datatable">

                    <thead class="table-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th>Holiday Name</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Type</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($holidays as $key => $holiday)

                            <tr>

                                <td>{{ $key + 1 }}</td>

                                <td>{{ $holiday->holiday_name }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('d M Y') }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('l') }}
                                </td>

                                <td>

                                    @if($holiday->holiday_type == 'Festival')

                                        <span class="badge bg-success">
                                            Festival
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Weekly Off
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a href="{{ route('holidays.edit', $holiday->id) }}"
                                    class="btn btn-sm btn-info">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>

                                    <form action="{{ route('holidays.destroy', $holiday->id) }}"
                                        method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this holiday?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="mdi mdi-delete"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center">
                                    No holidays found.
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
