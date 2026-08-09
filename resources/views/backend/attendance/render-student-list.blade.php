<form action="{{ route('attendance.store') }}" method="POST">

    @csrf

    <input type="hidden" name="course_id" value="{{ request('course_id') }}">
    <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
    <input type="hidden" name="attendance_date" value="{{ request('attendance_date') }}">

    <div class="card shadow-sm">

        <div class="card-header">
            <h5 class="mb-0">Student List</h5>
        </div>

        <div class="card-body p-0">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-light">
                    <tr>
                        <th width="60">SL</th>
                        <th>Admission No</th>
                        <th>Student Name</th>
                        <th>Phone</th>
                        <th width="220">Attendance</th>
                        <th width="250">Remarks</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($students as $key => $student)

                    <tr>

                        <td>{{ $key+1 }}</td>

                        <td>{{ $student->admission_no }}</td>

                        <td>{{ $student->student->name }}</td>

                        <td>{{ $student->student->phone }}</td>

                        <td>

                            {{-- <label class="me-3">
                                <input type="radio"
                                       name="attendance[{{ $student->user_id }}]"
                                       value="Present"
                                       checked>

                                Present
                            </label>

                            <label>
                                <input type="radio"
                                       name="attendance[{{ $student->user_id }}]"
                                       value="Absent">

                                Absent
                            </label> --}}

                            @if($holiday)

                                <span class="badge bg-warning text-dark">
                                    Holiday
                                </span>

                                <input type="hidden"
                                    name="attendance[{{ $student->user_id }}]"
                                    value="Holiday">

                            @else

                                <label class="me-3">
                                    <input type="radio"
                                        name="attendance[{{ $student->user_id }}]"
                                        value="Present"
                                        checked>
                                    Present
                                </label>

                                <label>
                                    <input type="radio"
                                        name="attendance[{{ $student->user_id }}]"
                                        value="Absent">
                                    Absent
                                </label>

                            @endif

                        </td>

                        <td>

                            {{-- <input type="text"
                                   class="form-control"
                                   name="remarks[{{ $student->user_id }}]"
                                   placeholder="Remarks"> --}}

                            <input type="text"
                                class="form-control"
                                name="remarks[{{ $student->user_id }}]"
                                value="{{ $holiday ? $holiday->holiday_name : '' }}"
                                placeholder="Remarks"
                                {{ $holiday ? 'readonly' : '' }}>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center text-danger">

                            No Enrolled Student Found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{-- @if($students->count())

        <div class="card-footer text-end">

            <button type="submit" class="btn btn-success">

                <i data-feather="save"></i>

                Save Attendance

            </button>

        </div>

        @endif --}}

        @if($students->count())

            <div class="card-footer text-end">

                @if($holiday)

                    <button type="button"
                            class="btn btn-warning"
                            disabled>

                        Holiday - Attendance Not Allowed

                    </button>

                @else

                    <button type="submit"
                            class="btn btn-success">

                        <i data-feather="save"></i>

                        Save Attendance

                    </button>

                @endif

            </div>

            @endif

    </div>

</form>
