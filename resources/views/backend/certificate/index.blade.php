@extends('backend.partial.master')

@section('title','Certificate List')

@section('backend-content')

<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="row mb-3">

        <div class="col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h4 class="mb-0">

                        <i data-feather="award"></i>

                        Certificate List

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-6 text-end">

            <a href="{{ route('certificate.create') }}"
               class="btn btn-primary mt-2">

                <i data-feather="plus"></i>

                Upload Certificate

            </a>

        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif

    {{-- Certificate Table --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">

                Uploaded Certificates

            </h5>

        </div>

        <div class="card-body p-2">

            <table class="table table-bordered table-hover mb-0" id="responsive-datatable">

                <thead class="table-light">

                    <tr>

                        <th width="60">SL</th>

                        <th>Student</th>

                        <th>Phone</th>

                        <th>Course</th>

                        <th>Certificate</th>

                        <th width="180">Action</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($certificates as $key => $certificate)

                    <tr>

                        <td>

                            {{ ++$key }}

                        </td>

                        <td>

                            {{ $certificate->student->name ?? '-' }}

                        </td>

                        <td>

                            {{ $certificate->student->phone ?? '-' }}

                        </td>

                        <td>

                            {{ $certificate->course->course_name ?? '-' }}

                        </td>

                        <td>

                            @if($certificate->certificate_file)

                                <a href="{{ asset('uploads/certificates/'.$certificate->certificate_file) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-success">

                                    <i data-feather="eye"></i>

                                    View

                                </a>

                            @else

                                <span class="badge bg-danger">

                                    Not Uploaded

                                </span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('certificate.edit',$certificate->id) }}"
                               class="btn btn-sm btn-warning">

                                <i data-feather="edit"></i>

                            </a>

                            <form
                                action="{{ route('certificate.destroy',$certificate->id) }}"
                                method="POST"
                                style="display:inline-block;"
                                onsubmit="return confirm('Delete this certificate?')">

                                @csrf

                                @method('DELETE')

                                <button
                                    class="btn btn-sm btn-danger">

                                    <i data-feather="trash-2"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center text-danger py-4">

                            No Certificate Found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
