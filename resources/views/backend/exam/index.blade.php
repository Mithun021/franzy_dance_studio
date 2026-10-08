@extends('backend.partial.master')

@section('title', 'Exams')

@section('backend-content')

<div class="row">

    {{-- Add Exam --}}
    <div class="col-md-4">

        <div class="card">

            <div class="card-header">
                <h4 class="card-title mb-0">
                    Add Exam
                </h4>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('exam.store') }}" method="POST">

                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            Exam Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="exam_name"
                            class="form-control"
                            value="{{ old('exam_name') }}"
                            placeholder="Enter exam name"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Status
                        </label>

                        <select name="status" class="form-select">

                            <option value="1"
                                {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old('status') === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Add Exam
                    </button>

                </form>

            </div>

        </div>

    </div>


    {{-- Exam List --}}
    <div class="col-md-8">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="card-title mb-0">
                    Exam List
                </h4>

                <span class="badge bg-primary">
                    Total: {{ $exams->count() }}
                </span>

            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped"
                        id="responsive-datatable"
                    >

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Exam Name</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($exams as $key => $exam)

                                <tr>

                                    <td>
                                        {{ $key + 1 }}
                                    </td>

                                    <td>
                                        {{ $exam->exam_name }}
                                    </td>

                                    <td>

                                        @if($exam->status)
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

                                        <a
                                            href="{{ route('exam.edit', $exam->id) }}"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('exam.destroy', $exam->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this exam?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center">
                                        No exams found.
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
