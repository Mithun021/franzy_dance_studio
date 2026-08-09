@extends('backend.partial.master')

@section('title', 'Category Master')

@section('backend-content')

<div class="container-fluid">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        {{-- Add/Edit Form --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <h5 class="mb-0" id="form-title">Add Category</h5>
                </div>

                <div class="card-body">

                    <form id="categoryForm"
                        action="{{ route('category.store') }}"
                        method="POST">

                        @csrf

                        <input type="hidden" id="category_id">

                        <div class="mb-3">
                            <label class="form-label">
                                Category Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}">

                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Minimum Age
                            </label>

                            <input
                                type="number"
                                name="min_age"
                                id="min_age"
                                class="form-control @error('min_age') is-invalid @enderror"
                                value="{{ old('min_age') }}">

                            @error('min_age')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Maximum Age
                            </label>

                            <input
                                type="number"
                                name="max_age"
                                id="max_age"
                                class="form-control @error('max_age') is-invalid @enderror"
                                value="{{ old('max_age') }}">

                            @error('max_age')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="d-grid">

                            <button class="btn btn-primary" id="submitBtn">
                                Save Category
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Listing --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Category List
                    </h5>

                </div>

                <div class="card-body table-responsive">

                    <table class="table table-bordered table-striped">

                        <thead>

                        <tr>

                            <th width="60">#</th>

                            <th>Name</th>

                            <th>Min Age</th>

                            <th>Max Age</th>

                            <th width="140">Action</th>

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($categories as $key=>$category)

                            <tr>

                                <td>{{ $key+1 }}</td>

                                <td>{{ $category->name }}</td>

                                <td>{{ $category->min_age }}</td>

                                <td>{{ $category->max_age }}</td>

                                <td>

                                    <a href="{{ route('category.edit', $category->id) }}"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('category.destroy',$category->id) }}"
                                        method="POST"
                                        class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            onclick="return confirm('Are you sure?')"
                                            class="btn btn-danger btn-sm">

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center">

                                    No Categories Found

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
