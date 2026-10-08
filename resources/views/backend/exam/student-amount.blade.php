@extends('backend.partial.master')

@section('title', 'Student Exam Amount')

@section('backend-content')

<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-header">

                <h4 class="card-title mb-0">
                    Student Exam Amount
                </h4>

            </div>

            <div class="card-body">

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif


                {{-- Validation Errors --}}
                @if($errors->any())

                    <div class="alert alert-danger">

                        <ul class="mb-0">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- ===================================================== --}}
                {{-- FILTER FORM --}}
                {{-- ===================================================== --}}

                <form
                    action="{{ route('exam.student-amount') }}"
                    method="GET"
                >

                    <div class="row g-3 align-items-end">

                        {{-- Admission No --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Student Admission No
                            </label>

                            <input
                                type="text"
                                name="admission_no"
                                class="form-control"
                                value="{{ request('admission_no') }}"
                                placeholder="Enter admission no"
                            >

                        </div>


                        {{-- Level --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Level
                            </label>

                            <select
                                name="level_id"
                                class="form-select"
                            >

                                <option value="">
                                    Select Level
                                </option>

                                @foreach($levels as $level)

                                    <option
                                        value="{{ $level->id }}"
                                        {{ request('level_id') == $level->id ? 'selected' : '' }}
                                    >
                                        {{ $level->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Filter --}}
                        <div class="col-md-4">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Filter Students
                            </button>

                            <a
                                href="{{ route('exam.student-amount') }}"
                                class="btn btn-secondary"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </form>


                @if(request()->hasAny(['admission_no', 'level_id']))


                    <hr class="my-4">


                    {{-- ================================================= --}}
                    {{-- APPLY AMOUNT --}}
                    {{-- ================================================= --}}

                    <div class="row align-items-end mb-4">

                        <div class="col-md-4">

                            <label class="form-label">
                                Amount
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="apply_amount"
                                class="form-control"
                                placeholder="Enter exam fee"
                            >

                        </div>

                        <div class="col-md-4">

                            <button
                                type="button"
                                id="applyAmountBtn"
                                class="btn btn-success"
                            >
                                Apply On All Students
                            </button>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- STUDENT TABLE --}}
                    {{-- ================================================= --}}

                    <form
                        action="{{ route('exam.student-amount.save') }}"
                        method="POST"
                        id="studentAmountForm"
                    >

                        @csrf


                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h5 class="mb-0">
                                Student List
                            </h5>

                            <span class="badge bg-primary">
                                Total Students: {{ $students->count() }}
                            </span>

                        </div>


                        <div class="table-responsive">

                            <table
                                class="table table-bordered table-striped align-middle"
                                id="responsive-datatable"
                            >

                                <thead>

                                    <tr>

                                        <th width="60">
                                            <div class="form-check">

                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="checkAll"
                                                >

                                            </div>
                                        </th>

                                        <th width="60">
                                            #
                                        </th>

                                        <th>
                                            Student Name
                                        </th>

                                        <th>
                                            Admission No
                                        </th>

                                        <th>
                                            Level
                                        </th>

                                        <th width="200">
                                            Exam Fee
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse($students as $key => $student)

                                        <tr>

                                            {{-- Checkbox --}}
                                            <td>

                                                <input
                                                    type="checkbox"
                                                    class="form-check-input student-checkbox"
                                                    name="students[{{ $key }}][selected]"
                                                    value="1"
                                                >

                                            </td>


                                            {{-- ID --}}
                                            <td>
                                                {{ $key + 1 }}
                                            </td>


                                            {{-- Student Name --}}
                                            <td>

                                                {{ $student->student->name ?? 'N/A' }}

                                            </td>


                                            {{-- Admission No --}}
                                            <td>

                                                {{ $student->admission_no }}

                                            </td>


                                            {{-- Level --}}
                                            <td>

                                                {{ $student->level->name ?? 'N/A' }}

                                            </td>


                                            {{-- Hidden Student ID --}}
                                            <td style="display:none;">

                                                <input
                                                    type="hidden"
                                                    name="students[{{ $key }}][student_id]"
                                                    value="{{ $student->user_id }}"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="students[{{ $key }}][level_id]"
                                                    value="{{ $student->level_id }}"
                                                >

                                            </td>


                                            {{-- Exam Fee --}}
                                            <td>

                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    class="form-control exam-fee-input"
                                                    name="students[{{ $key }}][exam_fee]"
                                                    value="{{ $student->existing_exam_fee }}"
                                                    placeholder="Enter exam fee"
                                                >

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="6"
                                                class="text-center py-4"
                                            >
                                                No students found.
                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>


                        {{-- SAVE --}}
                        @if($students->count() > 0)

                            <div class="mt-3">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Save Selected Students
                                </button>

                            </div>

                        @endif

                    </form>

                @endif

            </div>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Check / Uncheck All
    |--------------------------------------------------------------------------
    */

    const checkAll = document.getElementById('checkAll');

    if (checkAll) {

        checkAll.addEventListener('change', function () {

            document
                .querySelectorAll('.student-checkbox')
                .forEach(function (checkbox) {

                    checkbox.checked = checkAll.checked;

                });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Apply Amount On All Students
    |--------------------------------------------------------------------------
    */

    const applyAmountBtn = document.getElementById('applyAmountBtn');

    if (applyAmountBtn) {

        applyAmountBtn.addEventListener('click', function () {

            const amountInput =
                document.getElementById('apply_amount');

            const amount = amountInput.value.trim();

            if (amount === '') {

                alert('Please enter exam fee amount.');

                amountInput.focus();

                return;

            }


            if (parseFloat(amount) < 0) {

                alert('Exam fee cannot be negative.');

                amountInput.focus();

                return;

            }


            document
                .querySelectorAll('.exam-fee-input')
                .forEach(function (input) {

                    input.value = amount;

                });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Select Only Checked Students On Submit
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById('studentAmountForm');

    if (form) {

        form.addEventListener('submit', function (event) {

            const checkedStudents =
                document.querySelectorAll(
                    '.student-checkbox:checked'
                );

            if (checkedStudents.length === 0) {

                event.preventDefault();

                alert('Please select at least one student.');

                return;

            }


            /*
            | Make unchecked student's fields disabled
            | so they are not submitted.
            */

            document
                .querySelectorAll('.student-checkbox')
                .forEach(function (checkbox) {

                    const row =
                        checkbox.closest('tr');

                    if (!checkbox.checked) {

                        row
                            .querySelectorAll(
                                'input[name^="students"]'
                            )
                            .forEach(function (input) {

                                input.disabled = true;

                            });

                    }

                });

        });

    }

});

</script>

@endsection
