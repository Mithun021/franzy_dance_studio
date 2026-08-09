@extends('backend.partial.master')
@section('title', 'Course Master')

@section('backend-content')

<div class="row">

    <div class="col-12">
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
    </div>

    {{-- Add Course --}}
    <div class="col-md-4">

        <div class="card">
            <div class="card-header">
                <h4>Add Course</h4>
            </div>

            <div class="card-body">

                <form action="{{ route('courses.store') }}" method="POST">

                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            Course Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="course_name"
                               class="form-control"
                               value="{{ old('course_name') }}"
                               placeholder="Enter Course Name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Duration <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="duration"
                               class="form-control"
                               value="{{ old('duration') }}"
                               min="1"
                               placeholder="Enter Duration">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Duration Type <span class="text-danger">*</span>
                        </label>

                        <select name="duration_type" class="form-select">

                            <option value="">Select</option>

                            {{-- <option value="Days" {{ old('duration_type')=='Days'?'selected':'' }}>
                                Days
                            </option> --}}

                            <option value="Months" {{ old('duration_type')=='Months'?'selected':'' }}>
                                Months
                            </option>

                            {{-- <option value="Years" {{ old('duration_type')=='Years'?'selected':'' }}>
                                Years
                            </option> --}}

                        </select>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Total Classes <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="total_classes"
                               class="form-control"
                               value="{{ old('total_classes') }}"
                               min="1"
                               placeholder="Enter Total Classes">
                    </div>

                    <button class="btn btn-primary">
                        Save
                    </button>

                    <button type="reset" class="btn btn-secondary">
                        Reset
                    </button>

                </form>

            </div>
        </div>

    </div>


    {{-- Course List --}}
    <div class="col-md-8">

        <div class="card">

            <div class="card-header">
                <h4>Course List</h4>
            </div>

            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>

                        <tr>

                            <th width="60">SL</th>

                            <th>Course</th>

                            <th>Duration</th>

                            <th>Total Classes</th>

                            <th width="180">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($courses as $key=>$course)

                        <tr>

                            <td>{{ $key+1 }}</td>

                            <td>{{ $course->course_name }}</td>

                            <td>
                                {{ $course->duration }}
                                {{ $course->duration_type }}
                            </td>

                            <td>{{ $course->total_classes }}</td>

                            <td>

                                <a href="{{ route('courses.edit',$course->id) }}"
                                   class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <form action="{{ route('courses.destroy',$course->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure want to delete this course?')">

                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center text-danger">

                                No Course Found

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
