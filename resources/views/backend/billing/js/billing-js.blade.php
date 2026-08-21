<script>
$(document).ready(function() {

    let isFirstPayment = false;
    let monthlyCourseFee = 0;
    let firstPaymentRuleRunning = false;

    function number(value) {
        let result = parseFloat(value);
        return isNaN(result) ? 0 : result;
    }

    function money(value) {
        return number(value).toFixed(2);
    }

    function getTodayDate() {
        let date = new Date();

        return date.getFullYear() +
            '-' +
            String(date.getMonth() + 1).padStart(2, '0') +
            '-' +
            String(date.getDate()).padStart(2, '0');
    }

    function getTotalBillingAmount() {

        let amount = number($('#total_billing_amount').val());

        if (amount <= 0) {

            let totalText = $('#total_fee')
                .text()
                .replace(/[₹,\s]/g, '');

            amount = number(totalText);
        }

        return amount;
    }

    function getTotalPaymentAmount() {

        let totalPayment = 0;

        $('#paymentTable tbody .payment-amount').each(function() {
            totalPayment += number($(this).val());
        });

        return totalPayment;
    }

    function updatePaymentSummary() {

        let billingAmount = getTotalBillingAmount();
        let paymentAmount = getTotalPaymentAmount();

        if (billingAmount > 0 && paymentAmount > billingAmount) {

            let excess = paymentAmount - billingAmount;

            let rows = $('#paymentTable tbody .payment-amount')
                .get()
                .reverse();

            $(rows).each(function() {

                if (excess <= 0) {
                    return false;
                }

                let currentAmount = number($(this).val());

                if (currentAmount <= 0) {
                    return;
                }

                let reduceAmount =
                    Math.min(currentAmount, excess);

                let newAmount =
                    currentAmount - reduceAmount;

                $(this).val(
                    newAmount > 0
                        ? money(newAmount)
                        : ''
                );

                excess -= reduceAmount;
            });

            paymentAmount = getTotalPaymentAmount();
        }

        let remainingAmount =
            billingAmount - paymentAmount;

        if (remainingAmount < 0) {
            remainingAmount = 0;
        }

        $('#paymentBillingAmount').text(
            '₹ ' + money(billingAmount)
        );

        $('#paymentPaidAmount').text(
            '₹ ' + money(paymentAmount)
        );

        $('#paymentRemainingAmount').text(
            '₹ ' + money(remainingAmount)
        );
    }

    function restrictPaymentAmount(input) {

        let billingAmount =
            getTotalBillingAmount();

        if (billingAmount <= 0) {

            $(input).val('');

            updatePaymentSummary();

            return;
        }

        let currentValue =
            number($(input).val());

        if (currentValue < 0) {

            currentValue = 0;

            $(input).val('');
        }

        let otherPayments = 0;

        $('#paymentTable tbody .payment-amount').each(function() {

            if (this !== input) {
                otherPayments += number($(this).val());
            }

        });

        let remainingForThisRow =
            billingAmount - otherPayments;

        if (remainingForThisRow < 0) {
            remainingForThisRow = 0;
        }

        if (currentValue > remainingForThisRow) {

            currentValue =
                remainingForThisRow;

            $(input).val(
                currentValue > 0
                    ? money(currentValue)
                    : ''
            );
        }

        updatePaymentSummary();
    }

    function resetSummary() {

        $('#summaryCourseFee').text('₹ 0.00');
        $('#summaryLateFine').text('₹ 0.00');
        $('#summaryPenaltyFee').text('₹ 0.00');
        $('#summaryRegistrationFee').text('₹ 0.00');
        $('#summaryAdmissionFee').text('₹ 0.00');

        $('#summaryLateFineRow').addClass('d-none');
        $('#summaryPenaltyRow').addClass('d-none');
        $('#summaryRegistrationRow').addClass('d-none');
        $('#summaryAdmissionRow').addClass('d-none');

        $('#coursePenaltyAmount').text('₹ 0.00');

        $('#paymentBillingAmount').text('₹ 0.00');
        $('#paymentPaidAmount').text('₹ 0.00');
        $('#paymentRemainingAmount').text('₹ 0.00');

        $('#firstPaymentRuleSection').addClass('d-none');
        $('#firstPaymentRuleApplied').text('Current Rule: -');
    }

    function resetLateFine() {

        $('#lateFineSection').addClass('d-none');
        $('#noFineSection').addClass('d-none');
        $('#fineLoadingSection').addClass('d-none');
        $('#billingStatusSection').addClass('d-none');

        $('#fineType').text('-');
        $('#fineMonth').text('-');
        $('#fineAmount').text('₹ 0.00');
        $('#lastPaidMonth').text('-');
        $('#previousPaymentDate').text('-');
        $('#fineDueDate').text('-');
        $('#fineMonthDifference').text('0');
        $('#attendanceMonth').text('-');
        $('#attendanceStatus').text('-');
        $('#attendanceCount').text('0');
        $('#presentCount').text('0');
        $('#absentPercentage').text('-');
        $('#fineMessage').text('-');

        $('#noFineMessage').text(
            'No late fine applicable.'
        );

        $('#late_fine').val('0');
        $('#fine_type').val('');
        $('#fine_current_month').val('');
        $('#course_penalty_fee').val('0');
        $('#penalty_type').val('');
        $('#total_course_fee').val('0');
        $('#total_billing_amount').val('0');
        $('#billingStatusMessage').text('');

        $('#alreadyPaidMonthsList').empty();
        $('#pendingMonthsList').empty();

        $('#alreadyPaidMonthsSection').addClass('d-none');
        $('#pendingMonthsSection').addClass('d-none');

        resetSummary();

        updatePaymentSummary();
    }

    function resetCourseDetails() {

        $('#admission_no').val('');
        $('#admission_date').val('');
        $('#course_duration').val('');
        $('#batch_name').val('');
        $('#level_name').val('');
        $('#category_name').val('');
        $('#course_name').val('');
        $('#registration_fee').val('');
        $('#admission_fee').val('');
        $('#monthly_fee').val('');
        $('#billing_monthly_fee').val('₹ 0.00');
        $('#billing_course_fee').val('₹ 0.00');
        $('#total_fee').text('₹ 0.00');

        $('#billing_course_fee').val('0');
        $('#billing_total_course_fee').val('0');
        $('#billing_total_amount').val('0');
        $('#billing_registration_fee').val('0');
        $('#billing_admission_fee').val('0');
        $('#total_course_fee').val('0');

        monthlyCourseFee = 0;
        isFirstPayment = false;

        resetSummary();
        updatePaymentSummary();
    }

    function fillCourseDetails(data) {

        $('#admission_no').val(data.admission_no ?? '');
        $('#admission_date').val(data.admission_date ?? '');

        $('#course_duration').val(
            (data.course_duration ?? '') +
            ' ' +
            (data.duration_type ?? '')
        );

        $('#batch_name').val(data.batch ?? '');
        $('#level_name').val(data.level ?? '');
        $('#category_name').val(data.category ?? '');
        $('#course_name').val(data.course_name ?? '');

        monthlyCourseFee =
            number(data.monthly_fee);

        $('#registration_fee').val(
            money(data.registration_fee)
        );

        $('#admission_fee').val(
            money(data.admission_fee)
        );

        $('#monthly_fee').val(
            money(data.monthly_fee)
        );

        $('#billing_monthly_fee').val(
            '₹ ' + money(data.monthly_fee)
        );

        $('#billing_course_fee').val(
            '₹ ' + money(data.monthly_fee)
        );

        $('#total_fee').text(
            '₹ ' + money(data.total_fee)
        );

        $('#billing_course_fee').val(
            monthlyCourseFee
        );

        $('#billing_registration_fee').val(
            number(data.registration_fee)
        );

        $('#billing_admission_fee').val(
            number(data.admission_fee)
        );

        updatePaymentSummary();
    }

    function calculateBillingMonths() {

        let from = $('#billing_from').val();
        let to = $('#billing_to').val();

        if (!from || !to) {

            $('#billing_month_count').val('0');
            $('#billing_months').val('0 Month');
            $('#billingMonthSummary').addClass('d-none');

            return 0;
        }

        let fromDate =
            new Date(from + 'T00:00:00');

        let toDate =
            new Date(to + 'T00:00:00');

        if (toDate < fromDate) {

            $('#billing_month_count').val('0');
            $('#billing_months').val('Invalid Date Range');
            $('#billingMonthSummary').addClass('d-none');

            return 0;
        }

        let months =
            (toDate.getFullYear() - fromDate.getFullYear()) * 12 +
            (toDate.getMonth() - fromDate.getMonth()) +
            1;

        $('#billing_month_count').val(months);

        $('#billing_months').val(
            months +
            (months === 1 ? ' Month' : ' Months')
        );

        $('#billingMonthCount').text(months);
        $('#billingMonthSummary').removeClass('d-none');

        return months;
    }

    function formatMonth(value) {

        if (!value) return '-';

        let date =
            new Date(value + '-01T00:00:00');

        if (isNaN(date.getTime())) {
            return value;
        }

        return date.toLocaleString(
            'en-US',
            {
                month: 'long',
                year: 'numeric'
            }
        );
    }

    function renderMonthList(
        selector,
        months,
        className = ''
    ) {

        let container = $(selector);

        container.empty();

        if (
            !Array.isArray(months) ||
            months.length === 0
        ) {
            return;
        }

        months.forEach(function(month) {

            let text =
                typeof month === 'string'
                    ? formatMonth(month)
                    : (
                        month.month_name ??
                        month.name ??
                        month.month ??
                        month.fee_month ??
                        '-'
                    );

            container.append(
                '<span class="badge ' +
                className +
                '">' +
                text +
                '</span>'
            );
        });
    }

    function updatePaidMonthDisplay(response) {

        let paidMonths =
            response.already_paid_months ??
            response.paid_months ??
            [];

        let pendingMonths =
            response.pending_months ??
            response.unpaid_months ??
            [];

        renderMonthList(
            '#alreadyPaidMonthsList',
            paidMonths,
            'bg-success'
        );

        renderMonthList(
            '#pendingMonthsList',
            pendingMonths,
            'bg-primary'
        );

        $('#alreadyPaidMonthsSection')
            .toggleClass(
                'd-none',
                paidMonths.length === 0
            );

        $('#pendingMonthsSection')
            .toggleClass(
                'd-none',
                pendingMonths.length === 0
            );

        $('#pendingMonthCount').text(
            response.pending_month_count ??
            pendingMonths.length
        );

        $('#alreadyPaidMonthCount').text(
            response.already_paid_month_count ??
            paidMonths.length
        );

        $('#monthDifference').text(
            response.month_difference ?? '0'
        );
    }

    function updateBillingSummary(response) {

        let monthCount =
            number(response.billing_month_count);

        let monthlyFee =
            number(
                response.course_fee ??
                response.monthly_fee
            );

        let totalCourseFee =
            number(
                response.total_course_fee ??
                (
                    monthlyFee *
                    number(
                        response.pending_month_count ??
                        monthCount
                    )
                )
            );

        let registrationFee =
            number(response.registration_fee);

        let admissionFee =
            number(response.admission_fee);

        let lateFine =
            number(response.late_fine);

        let penaltyFee =
            number(
                response.penalty_fee ??
                response.course_penalty_fee
            );

        let totalAmount =
            number(
                response.total_billing_amount ??
                response.total_amount
            );

        if (
            !response.total_billing_amount &&
            !response.total_amount
        ) {

            totalAmount =
                totalCourseFee +
                registrationFee +
                admissionFee +
                lateFine +
                penaltyFee;
        }

        $('#billing_month_count').val(monthCount);

        $('#billing_months').val(
            monthCount +
            (monthCount === 1 ? ' Month' : ' Months')
        );

        $('#billingMonthCount').text(monthCount);

        $('#billing_course_fee').val(
            totalCourseFee
        );

        $('#billing_total_course_fee').val(
            totalCourseFee
        );

        $('#billing_registration_fee').val(
            registrationFee
        );

        $('#billing_admission_fee').val(
            admissionFee
        );

        $('#billing_total_amount').val(
            totalAmount
        );

        $('#total_course_fee').val(
            totalCourseFee
        );

        $('#total_billing_amount').val(
            totalAmount
        );

        $('#late_fine').val(lateFine);
        $('#course_penalty_fee').val(penaltyFee);

        $('#total_fee').text(
            '₹ ' + money(totalAmount)
        );

        $('#billing_monthly_fee').val(
            '₹ ' + money(monthlyFee)
        );

        $('#summaryCourseFee').text(
            '₹ ' + money(totalCourseFee)
        );

        $('#summaryLateFine').text(
            '₹ ' + money(lateFine)
        );

        $('#summaryPenaltyFee').text(
            '₹ ' + money(penaltyFee)
        );

        $('#summaryRegistrationFee').text(
            '₹ ' + money(registrationFee)
        );

        $('#summaryAdmissionFee').text(
            '₹ ' + money(admissionFee)
        );

        $('#coursePenaltyAmount').text(
            '₹ ' + money(penaltyFee)
        );

        $('#summaryLateFineRow').toggleClass(
            'd-none',
            lateFine <= 0
        );

        $('#summaryPenaltyRow').toggleClass(
            'd-none',
            penaltyFee <= 0
        );

        $('#summaryRegistrationRow').toggleClass(
            'd-none',
            registrationFee <= 0
        );

        $('#summaryAdmissionRow').toggleClass(
            'd-none',
            admissionFee <= 0
        );

        updatePaymentSummary();
    }

    function applyFirstPaymentRule() {

        if (!isFirstPayment || firstPaymentRuleRunning) {
            return;
        }

        let paymentDate =
            $('#paymentTable tbody tr:first .payment-date').val();

        if (!paymentDate) {
            return;
        }

        let date =
            new Date(paymentDate + 'T00:00:00');

        let day =
            date.getDate();

        let billingFrom;
        let billingTo;
        let feeMultiplier;
        let ruleText;

        /*
         * FIRST PAYMENT RULE
         *
         * 1 - 15:
         * Full Course Fee
         * Billing From = Payment Date
         * Billing To   = Payment Date
         *
         * 16 - 25:
         * 50% Course Fee
         * Billing From = Payment Date
         * Billing To   = Payment Date
         *
         * 26 - Month End:
         * Full Course Fee
         * Billing shifts to next month
         * Billing From = Next Month 1st
         * Billing To   = Next Month Last Date
         */

        if (day >= 1 && day <= 15) {

            feeMultiplier = 1;

            ruleText =
                '1 - 15: Full Course Fee';

            billingFrom =
                new Date(
                    date.getFullYear(),
                    date.getMonth(),
                    date.getDate()
                );

            billingTo =
                new Date(
                    date.getFullYear(),
                    date.getMonth(),
                    date.getDate()
                );

        } else if (day >= 16 && day <= 25) {

            feeMultiplier = 0.5;

            ruleText =
                '16 - 25: 50% Course Fee';

            billingFrom =
                new Date(
                    date.getFullYear(),
                    date.getMonth(),
                    date.getDate()
                );

            billingTo =
                new Date(
                    date.getFullYear(),
                    date.getMonth(),
                    date.getDate()
                );

        } else {

            feeMultiplier = 1;

            ruleText =
                '26 - Month End: Full Course Fee, Next Month Billing';

            billingFrom =
                new Date(
                    date.getFullYear(),
                    date.getMonth() + 1,
                    1
                );

            billingTo =
                new Date(
                    date.getFullYear(),
                    date.getMonth() + 2,
                    0
                );
        }

        function formatDate(date) {

            return date.getFullYear() +
                '-' +
                String(date.getMonth() + 1).padStart(2, '0') +
                '-' +
                String(date.getDate()).padStart(2, '0');
        }

        $('#billing_from').val(
            formatDate(billingFrom)
        );

        $('#billing_to').val(
            formatDate(billingTo)
        );

        $('#firstPaymentRuleSection')
            .removeClass('d-none');

        $('#firstPaymentRuleApplied')
            .text(
                'Current Rule: ' +
                ruleText +
                ' | Billing From: ' +
                formatDate(billingFrom) +
                ' | Billing To: ' +
                formatDate(billingTo)
            );

        firstPaymentRuleRunning = true;

        calculateLateFine(true, feeMultiplier);
    }

    /*
     * First payment amount:
     *
     * 1-15  = Full Course Fee + Registration + Admission
     * 16-25 = 50% Course Fee + Registration + Admission
     * 26-End = Full Course Fee + Registration + Admission
     */
    function applyFirstPaymentFee(
        feeMultiplier
    ) {

        if (!isFirstPayment) {
            return;
        }

        let courseFee =
            monthlyCourseFee * feeMultiplier;

        let registrationFee =
            number($('#billing_registration_fee').val());

        if (registrationFee <= 0) {

            registrationFee =
                number($('#registration_fee').val());
        }

        let admissionFee =
            number($('#billing_admission_fee').val());

        if (admissionFee <= 0) {

            admissionFee =
                number($('#admission_fee').val());
        }

        let lateFine =
            number($('#late_fine').val());

        let penaltyFee =
            number($('#course_penalty_fee').val());

        let totalAmount =
            courseFee +
            registrationFee +
            admissionFee +
            lateFine +
            penaltyFee;

        $('#billing_course_fee').val(
            courseFee
        );

        $('#billing_total_course_fee').val(
            courseFee
        );

        $('#total_course_fee').val(
            courseFee
        );

        $('#billing_registration_fee').val(
            registrationFee
        );

        $('#billing_admission_fee').val(
            admissionFee
        );

        $('#billing_total_amount').val(
            totalAmount
        );

        $('#total_billing_amount').val(
            totalAmount
        );

        $('#total_fee').text(
            '₹ ' + money(totalAmount)
        );

        $('#summaryCourseFee').text(
            '₹ ' + money(courseFee)
        );

        $('#summaryRegistrationFee').text(
            '₹ ' + money(registrationFee)
        );

        $('#summaryAdmissionFee').text(
            '₹ ' + money(admissionFee)
        );

        $('#summaryRegistrationRow').toggleClass(
            'd-none',
            registrationFee <= 0
        );

        $('#summaryAdmissionRow').toggleClass(
            'd-none',
            admissionFee <= 0
        );

        $('#billing_monthly_fee').val(
            '₹ ' + money(monthlyCourseFee)
        );

        updatePaymentSummary();
    }

    function showBillingStatus(response) {

        let message =
            response.message ??
            'Billing calculated successfully.';

        $('#billingStatusSection')
            .removeClass('d-none');

        $('#billingStatusMessage')
            .removeClass(
                'alert-success alert-warning alert-danger alert-info'
            )
            .addClass('alert-info')
            .text(message);
    }

    function showNoFine(response) {

        $('#lateFineSection').addClass('d-none');
        $('#noFineSection').removeClass('d-none');

        $('#fineAmount').text('₹ 0.00');
        $('#fineType').text('No Fine');

        $('#fineMonth').text(
            response.current_billing_month ??
            response.billing_month ??
            '-'
        );

        $('#lastPaidMonth').text(
            response.previous_paid_month ??
            response.last_paid_month ??
            '-'
        );

        $('#previousPaymentDate').text(
            response.previous_payment_date ?? '-'
        );

        $('#fineDueDate').text(
            response.due_date ?? '-'
        );

        $('#fineMonthDifference').text(
            response.month_difference ?? '0'
        );

        $('#attendanceMonth').text(
            response.attendance_month ?? '-'
        );

        $('#attendanceStatus').text(
            response.attendance_status ?? '-'
        );

        $('#attendanceCount').text(
            response.attendance_count ?? '0'
        );

        $('#presentCount').text(
            response.present_count ?? '0'
        );

        $('#absentPercentage').text(
            response.absent_percentage !== undefined
                ? response.absent_percentage + '%'
                : '-'
        );

        $('#noFineMessage').text(
            response.message ??
            'No late fine applicable.'
        );

        $('#late_fine').val('0');
        $('#fine_type').val('');
        $('#course_penalty_fee').val('0');
        $('#penalty_type').val('');

        $('#coursePenaltyAmount').text(
            '₹ 0.00'
        );
    }

    function showLateFine(response) {

        let fine =
            number(response.late_fine);

        let penalty =
            number(
                response.penalty_fee ??
                response.course_penalty_fee
            );

        $('#lateFineSection').removeClass('d-none');
        $('#noFineSection').addClass('d-none');

        $('#fineType').text(
            response.fine_heading ??
            response.fine_type ??
            'Late Fine'
        );

        $('#fineMonth').text(
            response.current_billing_month ??
            response.billing_month ??
            '-'
        );

        $('#fineAmount').text(
            '₹ ' + money(fine)
        );

        $('#coursePenaltyAmount').text(
            '₹ ' + money(penalty)
        );

        $('#lastPaidMonth').text(
            response.previous_paid_month ??
            response.last_paid_month ??
            '-'
        );

        $('#previousPaymentDate').text(
            response.previous_payment_date ?? '-'
        );

        $('#fineDueDate').text(
            response.due_date ?? '-'
        );

        $('#fineMonthDifference').text(
            response.month_difference ?? '0'
        );

        $('#attendanceMonth').text(
            response.attendance_month ?? '-'
        );

        $('#attendanceStatus').text(
            response.attendance_status ?? '-'
        );

        $('#attendanceCount').text(
            response.attendance_count ?? '0'
        );

        $('#presentCount').text(
            response.present_count ?? '0'
        );

        $('#absentPercentage').text(
            response.absent_percentage !== undefined
                ? response.absent_percentage + '%'
                : '-'
        );

        $('#fineMessage').text(
            response.message ??
            'Late fine applicable.'
        );

        $('#late_fine').val(fine);

        $('#fine_type').val(
            response.fine_type ?? ''
        );

        $('#fine_current_month').val(
            response.current_billing_month ??
            response.billing_month ??
            ''
        );

        $('#course_penalty_fee').val(penalty);
    }

    function showPenalty(response) {

        let penalty =
            number(
                response.penalty_fee ??
                response.course_penalty_fee
            );

        $('#lateFineSection').removeClass('d-none');
        $('#noFineSection').addClass('d-none');

        $('#fineType').text(
            response.penalty_heading ??
            'Course Penalty Fee'
        );

        $('#fineMonth').text(
            response.current_billing_month ??
            response.billing_month ??
            '-'
        );

        $('#fineAmount').text('₹ 0.00');

        $('#coursePenaltyAmount').text(
            '₹ ' + money(penalty)
        );

        $('#lastPaidMonth').text(
            response.previous_paid_month ??
            response.last_paid_month ??
            '-'
        );

        $('#previousPaymentDate').text(
            response.previous_payment_date ?? '-'
        );

        $('#fineDueDate').text(
            response.due_date ?? '-'
        );

        $('#fineMonthDifference').text(
            response.month_difference ?? '0'
        );

        $('#attendanceMonth').text(
            response.attendance_month ?? '-'
        );

        $('#attendanceStatus').text(
            response.attendance_status ??
            'Absent'
        );

        $('#attendanceCount').text(
            response.attendance_count ?? '0'
        );

        $('#presentCount').text(
            response.present_count ?? '0'
        );

        $('#absentPercentage').text(
            response.absent_percentage !== undefined
                ? response.absent_percentage + '%'
                : '-'
        );

        $('#fineMessage').text(
            response.message ??
            'Course penalty fee applicable.'
        );

        $('#late_fine').val('0');
        $('#fine_type').val('');
        $('#course_penalty_fee').val(penalty);

        $('#penalty_type').val(
            response.penalty_type ??
            'course_penalty_fee'
        );
    }

    function calculateLateFine(
        skipFirstPaymentRule = false,
        feeMultiplier = 1
    ) {

        let studentId =
            $('#student_id').val();

        let studentCourseId =
            $('#student_course_id').val();

        let billingFrom =
            $('#billing_from').val();

        let billingTo =
            $('#billing_to').val();

        if (
            !studentId ||
            !studentCourseId ||
            !billingFrom ||
            !billingTo
        ) {

            resetLateFine();

            firstPaymentRuleRunning = false;

            return;
        }

        if (billingTo < billingFrom) {

            resetLateFine();

            firstPaymentRuleRunning = false;

            return;
        }

        let monthCount =
            calculateBillingMonths();

        if (monthCount <= 0) {

            resetLateFine();

            firstPaymentRuleRunning = false;

            return;
        }

        $('#fineLoadingSection')
            .removeClass('d-none');

        $('#lateFineSection')
            .addClass('d-none');

        $('#noFineSection')
            .addClass('d-none');

        $('#billingStatusSection')
            .addClass('d-none');

        $.ajax({

            url: "{{ route('billing.calculate-late-fine') }}",

            type: "GET",

            data: {

                student_id:
                    studentId,

                student_course_id:
                    studentCourseId,

                billing_from:
                    billingFrom,

                billing_to:
                    billingTo
            },

            success: function(response) {

                $('#fineLoadingSection')
                    .addClass('d-none');

                updateBillingSummary(response);
                updatePaidMonthDisplay(response);
                showBillingStatus(response);

                isFirstPayment =
                    response.is_first_payment === true;

                if (isFirstPayment && !skipFirstPaymentRule) {

                    firstPaymentRuleRunning = false;

                    applyFirstPaymentRule();

                    return;
                }

                if (isFirstPayment && skipFirstPaymentRule) {

                    applyFirstPaymentFee(
                        feeMultiplier
                    );

                    let paymentDate =
                        $('#paymentTable tbody tr:first .payment-date').val();

                    if (paymentDate) {

                        let day =
                            new Date(
                                paymentDate + 'T00:00:00'
                            ).getDate();

                        let ruleText;

                        if (day <= 15) {

                            ruleText =
                                '1 - 15: Full Course Fee';

                        } else if (day <= 25) {

                            ruleText =
                                '16 - 25: 50% Course Fee';

                        } else {

                            ruleText =
                                '26 - Month End: Full Course Fee, Next Month Billing';
                        }

                        $('#firstPaymentRuleSection')
                            .removeClass('d-none');

                        $('#firstPaymentRuleApplied')
                            .text(
                                'Current Rule: ' +
                                ruleText
                            );
                    }

                    firstPaymentRuleRunning = false;

                    return;
                }

                $('#firstPaymentRuleSection')
                    .addClass('d-none');

                if (
                    response.penalty_applied === true ||
                    number(
                        response.penalty_fee ??
                        response.course_penalty_fee
                    ) > 0
                ) {

                    showPenalty(response);

                    return;
                }

                if (
                    response.apply === true &&
                    number(response.late_fine) > 0
                ) {

                    showLateFine(response);

                    return;
                }

                showNoFine(response);
            },

            error: function(xhr) {

                $('#fineLoadingSection')
                    .addClass('d-none');

                $('#lateFineSection')
                    .addClass('d-none');

                $('#noFineSection')
                    .removeClass('d-none');

                let message =
                    'Unable to calculate billing. Please try again.';

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;
                }

                $('#noFineMessage')
                    .text(message);

                firstPaymentRuleRunning = false;

                console.error(
                    'BILLING CALCULATION ERROR',
                    xhr.responseText
                );
            }
        });
    }

    $('#student_id').on('change', function() {

        let studentId =
            $(this).val();

        let courseDropdown =
            $('#student_course_id');

        resetCourseDetails();
        resetLateFine();

        if (!studentId) {

            courseDropdown
                .html(
                    '<option value="">Select Student First</option>'
                )
                .prop('disabled', true);

            return;
        }

        courseDropdown
            .html(
                '<option value="">Loading Courses...</option>'
            )
            .prop('disabled', true);

        $.ajax({

            url: "{{ route('billing.student-courses') }}",

            type: "GET",

            data: {
                student_id: studentId
            },

            success: function(response) {

                courseDropdown
                    .empty()
                    .append(
                        '<option value="">Select Course</option>'
                    );

                if (
                    response.status &&
                    response.courses &&
                    response.courses.length > 0
                ) {

                    $.each(
                        response.courses,
                        function(index, row) {

                            let courseName =
                                row.course?.course_name ??
                                'Course';

                            let admissionNo =
                                row.admission_no ??
                                'N/A';

                            courseDropdown.append(
                                `<option value="${row.id}">
                                    ${courseName}
                                    (Admission No : ${admissionNo})
                                </option>`
                            );
                        }
                    );

                    courseDropdown
                        .prop('disabled', false);

                } else {

                    courseDropdown
                        .append(
                            '<option value="">No Course Found</option>'
                        )
                        .prop('disabled', true);
                }
            },

            error: function(xhr) {

                console.error(
                    'STUDENT COURSE AJAX ERROR',
                    xhr.responseText
                );

                courseDropdown
                    .html(
                        '<option value="">Unable to Load Courses</option>'
                    )
                    .prop('disabled', true);

                alert(
                    'Unable to load student courses.'
                );
            }
        });
    });

    $('#student_course_id').on('change', function() {

        let studentCourseId =
            $(this).val();

        resetCourseDetails();
        resetLateFine();

        if (!studentCourseId) {
            return;
        }

        $.ajax({

            url: "{{ route('billing.course-details') }}",

            type: "GET",

            data: {
                student_course_id:
                    studentCourseId
            },

            success: function(response) {

                if (response.status) {

                    fillCourseDetails(
                        response.data
                    );

                    calculateLateFine();

                } else {

                    alert(
                        response.message ??
                        'Unable to fetch course details.'
                    );
                }
            },

            error: function(xhr) {

                console.error(
                    'COURSE DETAILS AJAX ERROR',
                    xhr.responseText
                );

                alert(
                    'Unable to fetch course details.'
                );
            }
        });
    });

    $('#billing_from, #billing_to').on(
        'change',
        function() {

            let from =
                $('#billing_from').val();

            let to =
                $('#billing_to').val();

            if (
                from &&
                to &&
                to < from
            ) {

                alert(
                    'Billing Date To cannot be before Billing Date From.'
                );

                $('#billing_to')
                    .val(from);
            }

            calculateBillingMonths();

            if (
                $('#student_course_id').val()
            ) {

                if (isFirstPayment) {

                    firstPaymentRuleRunning = false;

                    applyFirstPaymentRule();

                } else {

                    calculateLateFine();
                }
            }
        }
    );

    $('#addPaymentRow').on('click', function() {

        let billingAmount =
            getTotalBillingAmount();

        let currentPayment =
            getTotalPaymentAmount();

        let remainingAmount =
            billingAmount - currentPayment;

        if (remainingAmount <= 0) {

            alert(
                'Full billing amount of ₹ ' +
                money(billingAmount) +
                ' has already been entered.'
            );

            return;
        }

        let today =
            getTodayDate();

        let row = `
            <tr>
                <td>
                    <input
                        type="date"
                        name="payment_date[]"
                        class="form-control payment-date"
                        value="${today}"
                        required>
                </td>

                <td>
                    <select
                        name="payment_mode[]"
                        class="form-select payment-mode"
                        required>

                        <option value="">Select</option>
                        <option value="Cash">Cash</option>
                        <option value="UPI">UPI</option>
                        <option value="Card">Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>

                    </select>
                </td>

                <td>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        max="${money(remainingAmount)}"
                        name="amount[]"
                        class="form-control payment-amount"
                        placeholder="0.00"
                        required>
                </td>

                <td>
                    <input
                        type="text"
                        name="transaction_id[]"
                        class="form-control transaction-id"
                        placeholder="Transaction / Ref No">
                </td>

                <td>
                    <input
                        type="text"
                        name="remarks[]"
                        class="form-control"
                        placeholder="Remarks">
                </td>

                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-danger btn-sm removeRow"
                        title="Remove">

                        <i class="mdi mdi-delete"></i>

                    </button>
                </td>
            </tr>
        `;

        $('#paymentTable tbody')
            .append(row);

        updatePaymentSummary();
    });

    $(document).on(
        'click',
        '.removeRow',
        function() {

            let totalRows =
                $('#paymentTable tbody tr').length;

            if (totalRows <= 1) {

                alert(
                    'At least one payment row is required.'
                );

                return;
            }

            $(this)
                .closest('tr')
                .remove();

            updatePaymentSummary();
        }
    );

    $(document).on(
        'change',
        '.payment-mode',
        function() {

            let mode =
                $(this).val();

            let transactionBox =
                $(this)
                    .closest('tr')
                    .find('.transaction-id');

            if (mode === 'Cash') {

                transactionBox
                    .val('')
                    .prop('readonly', true)
                    .attr(
                        'placeholder',
                        'Not Required'
                    );

            } else {

                transactionBox
                    .prop('readonly', false)
                    .attr(
                        'placeholder',
                        'Transaction / Ref No'
                    );
            }
        }
    );

    $(document).on(
        'input',
        '.payment-amount',
        function() {

            restrictPaymentAmount(this);
        }
    );

    $(document).on(
        'change',
        '.payment-amount',
        function() {

            restrictPaymentAmount(this);
        }
    );

    $(document).on(
        'change',
        '.payment-date',
        function() {

            if (
                isFirstPayment &&
                this === $('#paymentTable tbody tr:first .payment-date')[0]
            ) {

                firstPaymentRuleRunning = false;

                applyFirstPaymentRule();
            }
        }
    );

    $('#paymentTable tbody tr:first')
        .find('.payment-mode')
        .val('Cash')
        .trigger('change');

    updatePaymentSummary();

    $('#billingForm').on(
        'submit',
        function(e) {

            let studentId =
                $('#student_id').val();

            let studentCourseId =
                $('#student_course_id').val();

            let billingFrom =
                $('#billing_from').val();

            let billingTo =
                $('#billing_to').val();

            if (!studentId) {

                e.preventDefault();

                alert(
                    'Please select student.'
                );

                return false;
            }

            if (!studentCourseId) {

                e.preventDefault();

                alert(
                    'Please select course.'
                );

                return false;
            }

            if (!billingFrom) {

                e.preventDefault();

                alert(
                    'Please select Billing Date From.'
                );

                return false;
            }

            if (!billingTo) {

                e.preventDefault();

                alert(
                    'Please select Billing Date To.'
                );

                return false;
            }

            if (billingTo < billingFrom) {

                e.preventDefault();

                alert(
                    'Billing Date To cannot be before Billing Date From.'
                );

                return false;
            }

            let monthCount =
                calculateBillingMonths();

            if (monthCount <= 0) {

                e.preventDefault();

                alert(
                    'Invalid billing date range.'
                );

                return false;
            }

            let expectedAmount =
                getTotalBillingAmount();

            let paymentAmount =
                getTotalPaymentAmount();

            if (
                expectedAmount > 0 &&
                paymentAmount > expectedAmount
            ) {

                e.preventDefault();

                alert(
                    'Total payment cannot be greater than the billing amount of ₹ ' +
                    money(expectedAmount) +
                    '.'
                );

                updatePaymentSummary();

                return false;
            }

            if (
                expectedAmount > 0 &&
                paymentAmount < expectedAmount
            ) {

                e.preventDefault();

                let remaining =
                    expectedAmount - paymentAmount;

                alert(
                    'Payment amount is incomplete. Remaining amount is ₹ ' +
                    money(remaining) +
                    '.'
                );

                updatePaymentSummary();

                return false;
            }

            if (paymentAmount <= 0) {

                e.preventDefault();

                alert(
                    'Payment amount must be greater than 0.'
                );

                return false;
            }

            let valid = true;
            let errorMessage = '';

            $('#paymentTable tbody tr').each(
                function(index) {

                    let row =
                        $(this);

                    let mode =
                        row.find('.payment-mode').val();

                    let amount =
                        number(
                            row.find('.payment-amount').val()
                        );

                    let transaction =
                        $.trim(
                            row.find('.transaction-id').val() || ''
                        );

                    if (mode === '') {

                        valid = false;

                        errorMessage =
                            'Please select payment mode for row ' +
                            (index + 1) +
                            '.';

                        return false;
                    }

                    if (amount <= 0) {

                        valid = false;

                        errorMessage =
                            'Payment amount must be greater than 0 in row ' +
                            (index + 1) +
                            '.';

                        return false;
                    }

                    if (
                        mode !== 'Cash' &&
                        transaction === ''
                    ) {

                        valid = false;

                        errorMessage =
                            'Transaction / Reference No is required for ' +
                            mode +
                            ' payment in row ' +
                            (index + 1) +
                            '.';

                        return false;
                    }
                }
            );

            if (!valid) {

                e.preventDefault();

                alert(errorMessage);

                return false;
            }

            if (
                expectedAmount > 0 &&
                Math.abs(
                    paymentAmount - expectedAmount
                ) > 0.009
            ) {

                e.preventDefault();

                alert(
                    'Total payment must be exactly ₹ ' +
                    money(expectedAmount) +
                    '.'
                );

                return false;
            }

            updatePaymentSummary();

            return true;
        }
    );

    let today =
        getTodayDate();

    $('#billing_from')
        .val(today);

    if (!$('#billing_to').val()) {

        $('#billing_to')
            .val(today);
    }

    calculateBillingMonths();
    updatePaymentSummary();

});
</script>
