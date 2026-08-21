<h5 class="mb-3">
    Payment Entries
</h5>

<div class="card border">

    {{-- Payment Header --}}

    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

        <h6 class="mb-0">

            Payment Entries

        </h6>


        <button
            type="button"
            class="btn btn-light btn-sm"
            id="addPaymentRow">

            <i class="fa fa-plus me-1"></i>

            Add Payment

        </button>

    </div>



    {{-- Payment Table --}}

    <div class="card-body p-0">

        <div class="table-responsive">

            <table
                class="table table-bordered align-middle mb-0"
                id="paymentTable">


                <thead class="table-light">

                    <tr>

                        <th width="17%">
                            Payment Date
                        </th>

                        <th width="17%">
                            Payment Mode
                        </th>

                        <th width="15%">
                            Amount
                        </th>

                        <th width="20%">
                            Transaction No.
                        </th>

                        <th>
                            Remarks
                        </th>

                        <th width="8%">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                    {{-- First Payment Row --}}

                    <tr>


                        {{-- Payment Date --}}

                        <td>

                            <input
                                type="date"
                                name="payment_date[]"
                                class="form-control payment-date"
                                value="{{ date('Y-m-d') }}"
                                required>

                        </td>



                        {{-- Payment Mode --}}

                        <td>

                            <select
                                name="payment_mode[]"
                                class="form-select payment-mode"
                                required>

                                <option value="">
                                    Select
                                </option>

                                <option value="Cash">
                                    Cash
                                </option>

                                <option value="UPI">
                                    UPI
                                </option>

                                <option value="Card">
                                    Card
                                </option>

                                <option value="Bank Transfer">
                                    Bank Transfer
                                </option>

                                <option value="Cheque">
                                    Cheque
                                </option>

                            </select>

                        </td>



                        {{-- Amount --}}

                        <td>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="amount[]"
                                class="form-control payment-amount"
                                placeholder="0.00"
                                required>

                        </td>



                        {{-- Transaction --}}

                        <td>

                            <input
                                type="text"
                                name="transaction_id[]"
                                class="form-control transaction-id"
                                placeholder="Transaction / Ref No">

                        </td>



                        {{-- Remarks --}}

                        <td>

                            <input
                                type="text"
                                name="remarks[]"
                                class="form-control"
                                placeholder="Remarks">

                        </td>



                        {{-- Remove --}}

                        <td class="text-center">

                            <button
                                type="button"
                                class="btn btn-danger btn-sm removeRow"
                                title="Remove">

                                <i class="mdi mdi-delete"></i>

                            </button>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>
{{-- ================================================================
    PAYMENT SUMMARY
================================================================ --}}

<div class="card-footer bg-light">

    <div class="row g-3">

        {{-- Total Billing --}}
        <div class="col-md-4">

            <div class="border rounded p-3 bg-white h-100">

                <small class="text-muted d-block">
                    Total Billing Amount
                </small>

                <strong
                    id="paymentBillingAmount"
                    class="fs-5 text-primary">

                    ₹ 0.00

                </strong>

            </div>

        </div>


        {{-- Total Payment --}}
        <div class="col-md-4">

            <div class="border rounded p-3 bg-white h-100">

                <small class="text-muted d-block">
                    Total Payment Entered
                </small>

                <strong
                    id="paymentPaidAmount"
                    class="fs-5 text-success">

                    ₹ 0.00

                </strong>

            </div>

        </div>


        {{-- Remaining --}}
        <div class="col-md-4">

            <div class="border rounded p-3 bg-white h-100">

                <small class="text-muted d-block">
                    Remaining Amount
                </small>

                <strong
                    id="paymentRemainingAmount"
                    class="fs-5 text-danger">

                    ₹ 0.00

                </strong>

            </div>

        </div>

    </div>

</div>
