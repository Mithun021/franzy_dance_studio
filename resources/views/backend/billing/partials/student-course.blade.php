<h5 class="mb-3">
    Student & Course
</h5>


<div class="row">


    {{-- Student --}}

    <div class="col-md-6 mb-3">

        <label
            for="student_id"
            class="form-label">

            Student

            <span class="text-danger">*</span>

        </label>


        <select
            name="student_id"
            id="student_id"
            class="form-select"
            required>

            <option value="">
                Select Student
            </option>


            @foreach($students as $student)

                <option
                    value="{{ $student->id }}">

                    {{ $student->name }}

                    @if($student->phone)
                        ({{ $student->phone }})
                    @endif

                </option>

            @endforeach

        </select>

    </div>



    {{-- Student Course --}}

    <div class="col-md-6 mb-3">

        <label
            for="student_course_id"
            class="form-label">

            Course

            <span class="text-danger">*</span>

        </label>


        <select
            name="student_course_id"
            id="student_course_id"
            class="form-select"
            required
            disabled>

            <option value="">
                Select Student First
            </option>

        </select>

    </div>

</div>
