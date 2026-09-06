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
            class="form-select select2"
            required>

            <option value="">
                Select Student
            </option>


            @foreach($students as $student)

                <option
                    value="{{ $student->id }}">

                    {{ $student->name }}
                    - {{ $student->user_id }}

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


{{-- Payment History --}}
<div class="row">

    <div class="col-12">

        <div id="paymentHistoryAction" class="d-none">

            <button
                type="button"
                class="btn btn-outline-primary"
                id="viewPaymentHistoryBtn">

                <i class="fa fa-history me-1"></i>

                View Payment History

                <span
                    class="badge bg-primary ms-1"
                    id="paymentHistoryCount">
                    0
                </span>

            </button>

        </div>

        <div id="noPaymentHistory" class="d-none">

            <span class="text-muted">

                <i class="fa fa-info-circle me-1"></i>

                No payment exists.

            </span>

        </div>

    </div>

</div>


<div
    class="modal fade"
    id="paymentHistoryModal"
    tabindex="-1"
    aria-labelledby="paymentHistoryModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title"
                        id="paymentHistoryModalLabel">

                        Payment History

                    </h5>

                    <small
                        class="text-muted"
                        id="paymentHistoryPeriod">
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div
                    id="paymentHistoryLoading"
                    class="text-center py-4 d-none">

                    <div
                        class="spinner-border text-primary">
                    </div>

                    <div class="mt-2">
                        Loading payment history...
                    </div>

                </div>

                <div id="paymentHistoryContent"></div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>
