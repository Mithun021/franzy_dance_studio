
{{-- ================================================================
    BILLING PERIOD
================================================================ --}}

<hr class="my-4">

<h5 class="mb-3">
    Billing Period
</h5>

<div class="card border">

    <div class="card-body">

        <div class="row">

            {{-- Billing From --}}
            <div class="col-md-6 mb-3">

                <label
                    for="billing_from"
                    class="form-label fw-bold">

                    Billing Date From
                    <span class="text-danger">*</span>

                </label>

                <input
                    type="date"
                    name="billing_from"
                    id="billing_from"
                    class="form-control"
                    value="{{ date('Y-m-d') }}"
                    required>

            </div>


            {{-- Billing To --}}
            <div class="col-md-6 mb-3">

                <label
                    for="billing_to"
                    class="form-label fw-bold">

                    Billing Date To
                    <span class="text-danger">*</span>

                </label>

                <input
                    type="date"
                    name="billing_to"
                    id="billing_to"
                    class="form-control"
                    value="{{ date('Y-m-d') }}"
                    required>

            </div>

        </div>


        {{-- ========================================================
            BILLING MONTH SUMMARY
        ========================================================= --}}

        <div
            id="billingMonthSummary"
            class="d-none mt-2">

            <div class="row g-3">

                {{-- Total Months --}}
                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Billing Months
                        </small>

                        <strong
                            id="billingMonthCount"
                            class="fs-5">

                            0

                        </strong>

                    </div>

                </div>


                {{-- Pending Months --}}
                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Pending Months
                        </small>

                        <strong
                            id="pendingMonthCount"
                            class="fs-5 text-primary">

                            0

                        </strong>

                    </div>

                </div>


                {{-- Already Paid --}}
                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Already Paid
                        </small>

                        <strong
                            id="alreadyPaidMonthCount"
                            class="fs-5 text-success">

                            0

                        </strong>

                    </div>

                </div>


                {{-- Month Gap --}}
                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Payment Gap
                        </small>

                        <strong
                            id="monthDifference"
                            class="fs-5">

                            0

                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            ALREADY PAID MONTHS
        ========================================================= --}}

        <div
            id="alreadyPaidMonthsSection"
            class="d-none mt-3">

            <div class="alert alert-success mb-0">

                <div class="fw-bold mb-2">

                    <i class="mdi mdi-check-circle me-1"></i>

                    Already Paid Months

                </div>

                <div
                    id="alreadyPaidMonthsList"
                    class="d-flex flex-wrap gap-2">

                </div>

            </div>

        </div>


        {{-- ========================================================
            PENDING MONTHS
        ========================================================= --}}

        <div
            id="pendingMonthsSection"
            class="d-none mt-3">

            <div class="alert alert-primary mb-0">

                <div class="fw-bold mb-2">

                    <i class="mdi mdi-calendar-clock me-1"></i>

                    Months Being Billed

                </div>

                <div
                    id="pendingMonthsList"
                    class="d-flex flex-wrap gap-2">

                </div>

            </div>

        </div>


        {{-- ========================================================
            BILLING STATUS
        ========================================================= --}}

        <div
            id="billingStatusSection"
            class="d-none mt-3">

            <div
                id="billingStatusMessage"
                class="alert mb-0">

            </div>

        </div>


        {{-- Hidden Billing Values --}}

        <input
            type="hidden"
            name="billing_month_count"
            id="billing_month_count"
            value="1">

        <input
            type="hidden"
            name="billing_months"
            id="billing_months"
            value="1 Month">


        {{-- ========================================================
            FINE LOADING
        ========================================================= --}}

        <div
            id="fineLoadingSection"
            class="d-none mt-3">

            <div class="alert alert-info mb-0">

                <i class="mdi mdi-loading mdi-spin me-1"></i>

                Calculating billing amount and late fine...

            </div>

        </div>


        {{-- ========================================================
            LATE FINE / PENALTY RESULT
        ========================================================= --}}

        <div
            id="lateFineSection"
            class="d-none mt-3">

            <div
                id="lateFineAlert"
                class="alert alert-warning mb-0">

                <div class="row g-3">

                    {{-- Fine Type --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Fee Type
                        </small>

                        <strong id="fineType">
                            -
                        </strong>

                    </div>


                    {{-- Current Billing Month --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Current Billing Month
                        </small>

                        <strong id="fineMonth">
                            -
                        </strong>

                    </div>


                    {{-- Previous Paid Month --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Previous Paid Month
                        </small>

                        <strong id="lastPaidMonth">
                            -
                        </strong>

                    </div>


                    {{-- Month Difference --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Payment Gap
                        </small>

                        <strong id="fineMonthDifference">
                            0
                        </strong>

                    </div>


                    {{-- Late Fine --}}
                    <div
                        id="lateFineAmountBox"
                        class="col-md-3">

                        <small class="text-muted d-block">
                            Late Fine
                        </small>

                        <strong
                            id="fineAmount"
                            class="text-danger">

                            ₹ 0.00

                        </strong>

                    </div>


                    {{-- Course Penalty --}}
                    <div
                        id="coursePenaltyAmountBox"
                        class="col-md-3">

                        <small class="text-muted d-block">
                            Course Penalty Fee
                        </small>

                        <strong
                            id="coursePenaltyAmount"
                            class="text-warning">

                            ₹ 0.00

                        </strong>

                    </div>


                    {{-- Previous Payment Date --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Previous Payment Date
                        </small>

                        <span id="previousPaymentDate">
                            -
                        </span>

                    </div>


                    {{-- Due Date --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Due Date
                        </small>

                        <span id="fineDueDate">
                            -
                        </span>

                    </div>


                    {{-- Attendance Month --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Attendance Month
                        </small>

                        <span id="attendanceMonth">
                            -
                        </span>

                    </div>


                    {{-- Attendance Status --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Attendance Status
                        </small>

                        <span id="attendanceStatus">
                            -
                        </span>

                    </div>


                    {{-- Attendance Records --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Attendance Records
                        </small>

                        <span id="attendanceCount">
                            0
                        </span>

                    </div>


                    {{-- Present --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Present Count
                        </small>

                        <span id="presentCount">
                            0
                        </span>

                    </div>


                    {{-- Absent Percentage --}}
                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Penalty Percentage
                        </small>

                        <span id="absentPercentage">
                            -
                        </span>

                    </div>


                    {{-- Calculation Message --}}
                    <div class="col-md-12">

                        <div
                            class="border-top pt-3 mt-2">

                            <strong>
                                Calculation Message
                            </strong>

                            <div
                                id="fineMessage"
                                class="mt-1">

                                -

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            NO FINE SECTION
        ========================================================= --}}

        <div
            id="noFineSection"
            class="d-none mt-3">

            <div
                id="noFineAlert"
                class="alert alert-success mb-0">

                <div class="fw-bold">

                    <i class="mdi mdi-check-circle me-1"></i>

                    Billing Status

                </div>

                <div
                    id="noFineMessage"
                    class="mt-1">

                    No late fine applicable.

                </div>

            </div>

        </div>


        {{-- ========================================================
            HIDDEN FINE VALUES
        ========================================================= --}}

        <input
            type="hidden"
            name="late_fine"
            id="late_fine"
            value="0">

        <input
            type="hidden"
            name="course_penalty_fee"
            id="course_penalty_fee"
            value="0">

        <input
            type="hidden"
            name="fine_type"
            id="fine_type"
            value="">

        <input
            type="hidden"
            name="fine_current_month"
            id="fine_current_month"
            value="">

        <input
            type="hidden"
            name="total_course_fee"
            id="total_course_fee"
            value="0">

        <input
            type="hidden"
            name="total_billing_amount"
            id="total_billing_amount"
            value="0">

    </div>

</div>
