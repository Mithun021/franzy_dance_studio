@extends('backend.partial.master')

@section('title','Studio Management')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Studio List
                </h4>

                <a href="{{ route('studio.create') }}"
                   class="btn btn-primary">

                    <i class="fa fa-plus"></i>

                    Add Studio

                </a>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle"
                           id="responsive-datatable">

                        <thead class="table-light">

                        <tr>

                            <th width="60">#</th>

                            <th width="90">Image</th>

                            <th>Category</th>

                            <th width="140">Price(per day)</th>

                            <th>Description</th>

                            <th width="100">Status</th>

                            <th width="140">Created</th>

                            <th width="120">Action</th>

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($studios as $key => $studio)

                            <tr>

                                <td>
                                    {{ $key + 1 }}
                                </td>

                                <td>

                                    @if($studio->thumbnail)

                                        <img src="{{ asset('storage/'.$studio->thumbnail) }}"
                                             width="70"
                                             height="70"
                                             class="rounded border object-fit-cover">

                                    @else

                                        <img src="{{ asset('backend/images/no-image.png') }}"
                                             width="70"
                                             height="70"
                                             class="rounded border">

                                    @endif

                                </td>

                                <td>

                                    {{ $studio->category->name ?? '-' }}

                                </td>

                                <td>

                                    ₹ {{ number_format($studio->price,2) }}

                                </td>

                                <td>

                                    {{ \Illuminate\Support\Str::limit(strip_tags($studio->description),60) }}

                                </td>

                                <td>

                                    @if($studio->status=="Active")

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

                                    {{ $studio->created_at->format('d M Y') }}

                                </td>

                                <td>

                                    <a href="{{ route('studio.edit',$studio->id) }}"
                                       class="btn btn-sm btn-primary">

                                        <i class="mdi mdi-pencil-outline fs-14"></i>

                                    </a>

                                    <form action="{{ route('studio.destroy',$studio->id) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this studio?')">

                                            <i class="mdi mdi-delete fs-14"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center">

                                    No Studio Found.

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
