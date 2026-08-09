<form action="{{ route('certificate.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <input type="hidden"
           name="course_id"
           value="{{ request('course_id', $students->first()->course_id ?? '') }}">

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i data-feather="users"></i>

                Completed Student List

            </h5>

            <span class="badge bg-success">

                Total : {{ $students->count() }}

            </span>

        </div>

        <div class="card-body p-0">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="60">SL</th>

                        <th>Admission No</th>

                        <th>Student Name</th>

                        <th>Phone</th>

                        <th>Course</th>

                        <th width="280">Certificate</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($students as $key => $student)

                    <tr>

                        <td>

                            {{ $key + 1 }}

                        </td>

                        <td>

                            {{ $student->admission_no }}

                        </td>

                        <td>

                            {{ $student->student->name }}

                        </td>

                        <td>

                            {{ $student->student->phone }}

                        </td>

                        <td>

                            {{ $student->course->course_name }}

                        </td>

                        <td>

                            <input
                                type="file"
                                class="form-control"
                                name="certificate_file[{{ $student->user_id }}]"
                                accept=".pdf,.jpg,.jpeg,.png">

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center text-danger py-4">

                            <strong>

                                No Completed Student Found.

                            </strong>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if($students->count())

        <div class="card-footer text-end">

            <button
                class="btn btn-success">

                <i data-feather="upload"></i>

                Upload Certificate

            </button>

        </div>

        @endif

    </div>

</form>

<script>

    feather.replace();

</script>
