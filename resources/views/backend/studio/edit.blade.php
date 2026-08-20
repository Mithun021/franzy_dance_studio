@extends('backend.partial.master')

@section('title','Edit Studio')

@section('backend-content')

<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">Edit Studio</h4>

                <a href="{{ route('studio.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>

            </div>

            <form action="{{ route('studio.update',$studio->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if($errors->any())

                        <div class="alert alert-danger alert-dismissible fade show">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"></button>

                        </div>

                    @endif

                    {{-- Session Error --}}
                    @if(session('error'))

                        <div class="alert alert-danger alert-dismissible fade show">

                            {{ session('error') }}

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"></button>

                        </div>

                    @endif

                    <div class="row">

                        {{-- Category --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Studio Category
                                <span class="text-danger">*</span>

                            </label>

                            <select name="studio_category_id"
                                    class="form-control">

                                <option value="">Select Category</option>

                                @foreach($categories as $category)

                                    <option
                                        value="{{ $category->id }}"
                                        {{ old('studio_category_id',$studio->studio_category_id)==$category->id ? 'selected' : '' }}>

                                        {{ $category->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- Price --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Price(per hours)
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="price_per_hour"
                                class="form-control"
                                placeholder="Enter Price"
                                value="{{ old('price_per_hour',$studio->price_per_hour) }}">

                        </div>

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Price(per day)
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="number"
                                name="price_per_day"
                                class="form-control"
                                min="0"
                                step="0.01"
                                value="{{ old('price_per_day',$studio->price_per_day) }}">

                        </div>

                        {{-- Thumbnail --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Thumbnail

                            </label>

                            <input
                                type="file"
                                name="thumbnail"
                                id="thumbnail"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp">

                            <small class="text-muted">

                                Leave empty to keep existing image.

                            </small>

                        </div>

                        {{-- Preview --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Current Thumbnail

                            </label>

                            <div>

                                @if($studio->thumbnail)

                                    <img
                                        id="previewImage"
                                        src="{{ asset('storage/'.$studio->thumbnail) }}"
                                        class="img-thumbnail"
                                        style="width:150px;height:150px;object-fit:cover;">

                                @else

                                    <img
                                        id="previewImage"
                                        src="{{ asset('backend/images/no-image.png') }}"
                                        class="img-thumbnail"
                                        style="width:150px;height:150px;object-fit:cover;">

                                @endif

                            </div>

                        </div>

                        {{-- Description --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">

                                Description

                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                class="form-control"
                                placeholder="Write Description...">{{ old('description',$studio->description) }}</textarea>

                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Status
                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="status"
                                class="form-control">

                                <option value="Active"
                                    {{ old('status',$studio->status)=='Active' ? 'selected' : '' }}>

                                    Active

                                </option>

                                <option value="Inactive"
                                    {{ old('status',$studio->status)=='Inactive' ? 'selected' : '' }}>

                                    Inactive

                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="card-footer text-end">

                    <a href="{{ route('studio.index') }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                    <button class="btn btn-primary">

                        <i class="fa fa-save"></i>

                        Update Studio

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.getElementById('thumbnail').addEventListener('change', function(e){

    const file = e.target.files[0];

    if(file){

        const reader = new FileReader();

        reader.onload = function(event){

            document.getElementById('previewImage').src = event.target.result;

        };

        reader.readAsDataURL(file);

    }

});

</script>

@endpush
