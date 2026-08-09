@extends('backend.partial.master')

@section('title','Edit Certificate')

@section('backend-content')

<div class="container-fluid">

    {{-- Heading --}}
    <div class="row mb-3">

        <div class="col-md-12">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h4 class="mb-0">

                        <i data-feather="edit"></i>

                        Edit Certificate

                    </h4>

                </div>

            </div>

        </div>

    </div>

    {{-- Form --}}
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">

            Update Certificate

        </div>

        <div class="card-body">

            <form
                action="{{ route('certificate.update',$certificate->id) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Student --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Student Name

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $certificate->student->name }}"
                            readonly>

                    </div>

                    {{-- Course --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Course

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $certificate->course->course_name }}"
                            readonly>

                    </div>

                </div>

                <div class="row">

                    {{-- Existing Certificate --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Existing Certificate

                        </label>

                        <br>

                        @if($certificate->certificate_file)

                            <a
                                href="{{ asset('uploads/certificates/'.$certificate->certificate_file) }}"
                                target="_blank"
                                class="btn btn-success">

                                <i data-feather="eye"></i>

                                View Certificate

                            </a>

                        @else

                            <span class="badge bg-danger">

                                No Certificate Uploaded

                            </span>

                        @endif

                    </div>

                    {{-- Upload --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Upload New Certificate

                        </label>

                        <input
                            type="file"
                            name="certificate_file"
                            class="form-control @error('certificate_file') is-invalid @enderror"
                            accept=".pdf,.jpg,.jpeg,.png">

                        @error('certificate_file')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                        <small class="text-muted">

                            Allowed: PDF, JPG, JPEG, PNG (Max: 5 MB)

                        </small>

                    </div>

                </div>

                <div class="text-end">

                    <a
                        href="{{ route('certificate.index') }}"
                        class="btn btn-secondary">

                        <i data-feather="arrow-left"></i>

                        Back

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i data-feather="save"></i>

                        Update Certificate

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>
    feather.replace();
</script>

@endpush
