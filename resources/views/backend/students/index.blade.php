@extends('backend.partial.master')

@section('title','Students')

@section('backend-content')

<div class="row">

    <div class="col-md-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Student List
                </h4>

                <a href="{{ route('students.create') }}"
                class="btn btn-primary">

                    <i class="fas fa-plus-circle me-1"></i>

                    Add Student

                </a>

            </div>

            <div class="card-body table-responsive">

                <table class="table table-bordered table-hover align-middle"  id="responsive-datatable">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>User Id</th>

                            <th>Photo</th>

                            <th>Name</th>

                            <th>Phone</th>

                            <th>Email</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($students as $key => $student)

                        <tr>

                            <td>{{ ++$key }}</td>

                            <td>{{ $student->user_id }}</td>

                            <td width="70">

                                @if($student->profile_image)

                                    <img src="{{ asset('storage/'.$student->profile_image) }}"
                                         width="50"
                                         height="50"
                                         class="rounded-circle">

                                @else

                                    <img src="{{ asset('backend/assets/images/user.png') }}"
                                         width="50"
                                         height="50"
                                         class="rounded-circle">

                                @endif

                            </td>

                            <td>

                                {{ $student->name }}

                            </td>

                            <td>

                                {{ $student->phone }}

                            </td>

                            <td>

                                {{ $student->email }}

                            </td>

                            <td>

                                @if($student->is_active == 'yes')

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

                                <a href="{{ route('students.view',$student->id) }}"
                                    class="btn btn-primary btn-sm">
                                    View
                                </a>

                                <a href="{{ route('students.edit',$student->id) }}"
                                    class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="{{ route('students.courses',$student->id) }}"
                                    class="btn btn-info btn-sm">
                                    Courses
                                </a>

                                <a href="{{ route('students.add-course',$student->id) }}"
                                    class="btn btn-success btn-sm">
                                    Add Course
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7" class="text-center">

                                No Student Found.

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
