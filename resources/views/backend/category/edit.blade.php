@extends('backend.partial.master')

@section('title', 'Edit Category')

@section('backend-content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Category</h4>

                    <a href="{{ route('category.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('category.update', $category->id) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name', $category->name) }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Minimum Age <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="min_age"
                                   class="form-control"
                                   value="{{ old('min_age', $category->min_age) }}"
                                   min="1"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Maximum Age <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="max_age"
                                   class="form-control"
                                   value="{{ old('max_age', $category->max_age) }}"
                                   min="1"
                                   required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Category
                            </button>

                            <a href="{{ route('category.index') }}" class="btn btn-danger">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
