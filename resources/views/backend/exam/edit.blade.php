@extends('backend.partial.master')

@section('title', 'Edit Exam')

@section('backend-content')

<div class="row">

    <div class="col-md-6">

        <div class="card">

            <div class="card-header">
                <h4 class="card-title mb-0">
                    Edit Exam
                </h4>
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

                <form
                    action="{{ route('exam.update', $exam->id) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')

                    <div class="mb-3">

                        <label class="form-label">
                            Exam Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="exam_name"
                            class="form-control"
                            value="{{ old('exam_name', $exam->exam_name) }}"
                            placeholder="Enter exam name"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status" class="form-select">

                            <option
                                value="1"
                                {{ old('status', $exam->status) == 1 ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ old('status', $exam->status) == 0 ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Update Exam
                    </button>

                    <a
                        href="{{ route('exam.index') }}"
                        class="btn btn-secondary"
                    >
                        Back
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
