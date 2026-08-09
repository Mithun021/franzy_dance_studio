@extends('backend.partial.master')

@section('title', 'Edit Level')

@section('backend-content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">Edit Level</h4>

                    <a href="{{ route('level.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>

                </div>

                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('level.update', $level->id) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">

                            <label class="form-label">
                                Level Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $level->name) }}"
                                placeholder="Enter Level Name"
                                required>

                        </div>

                        <div class="text-end">

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Level
                            </button>

                            <a href="{{ route('level.index') }}" class="btn btn-danger">
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
