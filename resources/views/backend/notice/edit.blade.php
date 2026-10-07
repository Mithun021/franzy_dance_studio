@extends('backend.partial.master')

@section('title', 'Edit Notice')

@section('backend-content')

<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="card-title mb-0">
                    Edit Notice
                </h4>

                <a href="{{ route('notice.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

            </div>

            <div class="card-body">

                @if($errors->any())

                    <div class="alert alert-danger">

                        <ul class="mb-0">

                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif

                <form action="{{ route('notice.update', $notice->id) }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- Notice Date --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Notice Date <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                   name="notice_date"
                                   class="form-control"
                                   value="{{ old('notice_date', $notice->notice_date?->format('Y-m-d')) }}"
                                   required>

                            @error('notice_date')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>

                            <select name="status"
                                    class="form-select"
                                    required>

                                <option value="1"
                                    {{ old('status', $notice->status) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ old('status', $notice->status) == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                            @error('status')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        {{-- Title --}}
                        <div class="col-12 mb-3">

                            <label class="form-label">
                                Notice Title <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="title"
                                   class="form-control"
                                   placeholder="Enter notice title"
                                   value="{{ old('title', $notice->title) }}"
                                   required>

                            @error('title')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        {{-- Description --}}
                        <div class="col-12 mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description"
                                      class="form-control"
                                      rows="6"
                                      placeholder="Enter notice description">{{ old('description', $notice->description) }}</textarea>

                            @error('description')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        {{-- Existing File --}}
                        @if($notice->files)

                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Current Attachment
                                </label>

                                <div>

                                    <a href="{{ asset('notices/' . $notice->files) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-info">
                                        View Current File
                                    </a>

                                </div>

                            </div>

                        @endif

                        {{-- New File --}}
                        <div class="col-md-8 mb-3">

                            <label class="form-label">
                                Replace Attachment
                            </label>

                            <input type="file"
                                   name="files"
                                   class="form-control"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                            <small class="text-muted">
                                Leave empty to keep the current file.
                                Allowed: PDF, DOC, DOCX, JPG, JPEG, PNG.
                                Maximum size: 5 MB.
                            </small>

                            @error('files')
                                <small class="text-danger d-block">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                    </div>

                    <div class="mt-3">

                        <button type="submit"
                                class="btn btn-primary">
                            Update Notice
                        </button>

                        <a href="{{ route('notice.index') }}"
                           class="btn btn-secondary">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
