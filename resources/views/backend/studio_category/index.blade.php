@extends('backend.partial.master')

@section('title','Studio Category')

@section('backend-content')

<div class="row">

    {{-- =======================
        Add / Edit Form
    ======================== --}}
    <div class="col-lg-4">

        <div class="card">

            <div class="card-header">
                <h4 class="mb-0">
                    {{ isset($category) ? 'Edit Category' : 'Add Category' }}
                </h4>
            </div>

            <form
                action="{{ isset($category) ? route('studio-category.update',$category->id) : route('studio-category.store') }}"
                method="POST">

                @csrf

                @if(isset($category))
                    @method('PUT')
                @endif

                <div class="card-body">

                    {{-- Success --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Error --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Validation --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                        </div>
                    @endif

                    <div class="mb-3">

                        <label class="form-label">
                            Category Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Enter Category Name"
                            value="{{ old('name', isset($category) ? $category->name : '') }}">

                    </div>

                </div>

                <div class="card-footer text-end">

                    @if(isset($category))

                        <a href="{{ route('studio-category.index') }}"
                           class="btn btn-warning">

                            Cancel

                        </a>

                    @endif

                    <button class="btn btn-primary">

                        <i class="fa fa-save"></i>

                        {{ isset($category) ? 'Update' : 'Save' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- =======================
        Category List
    ======================== --}}

    <div class="col-lg-8">

        <div class="card">

            <div class="card-header">

                <h4 class="mb-0">

                    Category List

                </h4>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped"
                        id="responsive-datatable">

                        <thead>

                        <tr>

                            <th width="60">#</th>

                            <th>Name</th>

                            <th width="150">Created At</th>

                            <th width="120">Action</th>

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($categories as $key=>$item)

                            <tr>

                                <td>{{ $key+1 }}</td>

                                <td>{{ $item->name }}</td>

                                <td>

                                    {{ $item->created_at->format('d M Y') }}

                                </td>

                                <td>

                                    <a href="{{ route('studio-category.edit',$item->id) }}"
                                       class="btn btn-sm btn-primary">

                                        <i class="mdi mdi-pencil-outline fs-14"></i>

                                    </a>

                                    <form
                                        action="{{ route('studio-category.destroy',$item->id) }}"
                                        method="POST"
                                        class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this category?')">

                                            <i class="mdi mdi-delete fs-14"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center">

                                    No Category Found

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
