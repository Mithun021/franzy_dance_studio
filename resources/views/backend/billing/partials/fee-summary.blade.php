{{-- ================================================================
    FEE SUMMARY
================================================================ --}}

<hr class="my-4">

<h5 class="mb-3">
    Fee Summary
</h5>

<div class="card border">

    <div class="card-body">

        <div class="row">

            {{-- Registration Fee --}}
            <div class="col-md-3 mb-3">

                <label
                    for="registration_fee"
                    class="fw-bold">

                    Registration Fee

                </label>

                <input
                    type="text"
                    id="registration_fee"
                    class="form-control bg-light"
                    value="₹ 0.00"
                    readonly>

            </div>


            {{-- Admission Fee --}}
            <div class="col-md-3 mb-3">

                <label
                    for="admission_fee"
                    class="fw-bold">

                    Admission Fee

                </label>

                <input
                    type="text"
                    id="admission_fee"
                    class="form-control bg-light"
                    value="₹ 0.00"
                    readonly>

            </div>


            {{-- Monthly Course Fee --}}
            <div class="col-md-3 mb-3">

                <label
                    for="monthly_fee"
                    class="fw-bold">

                    Monthly Course Fee

                </label>

                <input
                    type="text"
                    id="monthly_fee"
                    class="form-control bg-light"
                    value="₹ 0.00"
                    readonly>

            </div>


            {{-- Billing Course Fee --}}
            <div class="col-md-3 mb-3">

                <label
                    for="billing_course_fee"
                    class="fw-bold">

                    Billing Course Fee

                </label>

                <input
                    type="text"
                    id="billing_course_fee"
                    class="form-control bg-light"
                    value="₹ 0.00"
                    readonly>

            </div>

        </div>


        {{-- ========================================================
            FIRST PAYMENT RULE
        ========================================================= --}}

        <div
            id="firstPaymentRuleSection"
            class="alert alert-info mt-2 mb-3 d-none">

            <div class="fw-bold mb-2">
                First Payment Course Fee Rule
            </div>

            <div class="small mb-1">
                <strong>1 - 15:</strong>
                Full Course Fee will be charged.
            </div>

            <div class="small mb-1">
                <strong>16 - 25:</strong>
                50% of Course Fee will be charged.
            </div>

            <div class="small mb-2">
                <strong>26 - Month End:</strong>
                Full Course Fee will be charged, but the payment month
                will be counted as the next month.
            </div>

            <div
                id="firstPaymentRuleApplied"
                class="fw-bold text-primary">

                Current Rule: -

            </div>

        </div>


        {{-- ========================================================
            TOTAL BILLING AMOUNT
        ========================================================= --}}

        <div class="row mt-2">

            <div class="col-md-5 offset-md-7">

                <div class="border-top pt-3">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Course Fee
                        </span>

                        <strong id="summaryCourseFee">
                            ₹ 0.00
                        </strong>

                    </div>


                    <div
                        id="summaryLateFineRow"
                        class="d-flex justify-content-between mb-2 d-none">

                        <span class="text-danger">
                            Late Fine
                        </span>

                        <strong
                            id="summaryLateFine"
                            class="text-danger">

                            ₹ 0.00

                        </strong>

                    </div>


                    <div
                        id="summaryPenaltyRow"
                        class="d-flex justify-content-between mb-2 d-none">

                        <span class="text-warning">
                            Course Penalty Fee
                        </span>

                        <strong
                            id="summaryPenaltyFee"
                            class="text-warning">

                            ₹ 0.00

                        </strong>

                    </div>


                    <div
                        id="summaryRegistrationRow"
                        class="d-flex justify-content-between mb-2 d-none">

                        <span>
                            Registration Fee
                        </span>

                        <strong id="summaryRegistrationFee">
                            ₹ 0.00
                        </strong>

                    </div>


                    <div
                        id="summaryAdmissionRow"
                        class="d-flex justify-content-between mb-2 d-none">

                        <span>
                            Admission Fee
                        </span>

                        <strong id="summaryAdmissionFee">
                            ₹ 0.00
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">

                        <span class="fw-bold fs-5">
                            Total Billing Amount
                        </span>

                        <span
                            id="total_fee"
                            class="fw-bold text-primary fs-4">

                            ₹ 0.00

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
