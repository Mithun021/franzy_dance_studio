@extends('partials.master')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="mb-8">

        <h1 class="text-2xl sm:text-3xl font-bold text-white">
            Monthly Course Fee
        </h1>

        <p class="text-sm text-slate-400 mt-2">
            Check monthly fee, payment status, late fine and discount.
        </p>

    </div>


    {{-- =========================================================
        FILTER CARD
    ========================================================== --}}

    <div
        class="rounded-2xl
               border border-white/10
               bg-slate-900/70
               shadow-xl
               overflow-hidden"
    >

        <div
            class="px-6 py-5
                   border-b border-white/10
                   bg-gradient-to-r
                   from-pink-500/5
                   to-blue-500/5"
        >

            <h2 class="text-base font-semibold text-white">
                Select Fee Period
            </h2>

            <p class="text-xs text-slate-400 mt-1">
                Select payment date and course
            </p>

        </div>


        <div class="p-6">

            <form id="monthlyFeeForm">

                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    {{-- FROM DATE --}}

                    <div>

                        <label
                            for="from_date"
                            class="block text-sm font-medium text-slate-300 mb-2"
                        >
                            Payment Date From
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            id="from_date"
                            value="{{ now()->format('Y-m-d') }}"
                            class="w-full px-4 py-3
                                   rounded-xl
                                   bg-slate-800/80
                                   border border-white/10
                                   text-white
                                   outline-none
                                   [color-scheme:dark]"
                            required
                        >

                    </div>


                    {{-- TO DATE --}}

                    <div>

                        <label
                            for="to_date"
                            class="block text-sm font-medium text-slate-300 mb-2"
                        >
                            Payment Date To
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            id="to_date"
                            value="{{ now()->format('Y-m-d') }}"
                            class="w-full px-4 py-3
                                   rounded-xl
                                   bg-slate-800/80
                                   border border-white/10
                                   text-white
                                   outline-none
                                   [color-scheme:dark]"
                            required
                        >

                    </div>


                    {{-- COURSE --}}

                    <div>

                        <label
                            for="student_course_id"
                            class="block text-sm font-medium text-slate-300 mb-2"
                        >
                            Select Course
                        </label>

                        <select
                            name="student_course_id"
                            id="student_course_id"
                            class="w-full px-4 py-3
                                   rounded-xl
                                   bg-slate-800/80
                                   border border-white/10
                                   text-white
                                   outline-none"
                            required
                        >

                            <option value="">
                                Select Course
                            </option>

                            @forelse($studentCourses as $studentCourse)

                                <option
                                    value="{{ $studentCourse->id }}"
                                >

                                    {{ $studentCourse->course?->course_name }}

                                    @if($studentCourse->level)
                                        - {{ $studentCourse->level->name }}
                                    @endif

                                    @if($studentCourse->batch)
                                        - {{ $studentCourse->batch->batch_name }}
                                    @endif

                                </option>

                            @empty

                                <option value="">
                                    No enrolled course found
                                </option>

                            @endforelse

                        </select>

                    </div>

                </div>


                {{-- BUTTON --}}

                <div class="mt-6">

                    <button
                        type="submit"
                        id="checkFeeBtn"
                        class="inline-flex
                               items-center
                               justify-center
                               gap-2
                               px-6 py-3
                               rounded-xl
                               text-white
                               font-semibold
                               bg-gradient-to-r
                               from-pink-500
                               to-pink-600
                               transition
                               disabled:opacity-60"
                    >
                        Check Fee
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        RESULT
    ========================================================== --}}

    <div
        id="feeResult"
        class="mt-6 hidden"
    ></div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const form =
            document.getElementById(
                'monthlyFeeForm'
            );

        const result =
            document.getElementById(
                'feeResult'
            );

        const button =
            document.getElementById(
                'checkFeeBtn'
            );

        const fromDateInput =
            document.getElementById(
                'from_date'
            );

        const toDateInput =
            document.getElementById(
                'to_date'
            );

        const courseInput =
            document.getElementById(
                'student_course_id'
            );


        /*
        |--------------------------------------------------------------------------
        | DATE RESTRICTION
        |--------------------------------------------------------------------------
        */

        const today =
            new Date()
                .toISOString()
                .split('T')[0];

        fromDateInput.min =
            today;

        toDateInput.min =
            today;


        fromDateInput.addEventListener(
            'change',
            function () {

                toDateInput.min =
                    this.value;

                if (
                    toDateInput.value &&
                    toDateInput.value < this.value
                ) {

                    toDateInput.value =
                        this.value;
                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | FORM SUBMIT
        |--------------------------------------------------------------------------
        */

        form.addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();

                const fromDate =
                    fromDateInput.value;

                const toDate =
                    toDateInput.value;

                const studentCourseId =
                    courseInput.value;


                if (
                    !fromDate ||
                    !toDate ||
                    !studentCourseId
                ) {

                    showError(
                        'Please select all required fields.'
                    );

                    return;
                }


                if (fromDate > toDate) {

                    showError(
                        'Payment Date To must be greater than or equal to Payment Date From.'
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | LOADING
                |--------------------------------------------------------------------------
                */

                button.disabled =
                    true;

                button.innerHTML =
                    'Checking...';

                result.classList.remove(
                    'hidden'
                );

                result.innerHTML = `

                    <div
                        class="rounded-2xl
                               border border-white/10
                               bg-slate-900/70
                               p-8 text-center"
                    >

                        <div
                            class="w-10 h-10
                                   mx-auto mb-3
                                   rounded-full
                                   border-2
                                   border-pink-500/20
                                   border-t-pink-500
                                   animate-spin"
                        >
                        </div>

                        <p class="text-sm text-slate-400">
                            Calculating fee and late fine...
                        </p>

                    </div>

                `;


                /*
                |--------------------------------------------------------------------------
                | REQUEST
                |--------------------------------------------------------------------------
                */

                try {

                    const response =
                        await fetch(
                            "{{ route('custom-monthly-fee-pay.details') }}",
                            {

                                method: 'POST',

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        document
                                            .querySelector(
                                                'meta[name="csrf-token"]'
                                            )
                                            .getAttribute(
                                                'content'
                                            ),

                                    'Accept':
                                        'application/json'
                                },

                                body:
                                    JSON.stringify({

                                        from_date:
                                            fromDate,

                                        to_date:
                                            toDate,

                                        student_course_id:
                                            studentCourseId
                                    })
                            }
                        );


                    const data =
                        await response.json();


                    /*
                    |--------------------------------------------------------------------------
                    | CONSOLE
                    |--------------------------------------------------------------------------
                    */

                    console.clear();

                    console.log(
                        '===================================================='
                    );

                    console.log(
                        '       MONTHLY COURSE FEE CALCULATION'
                    );

                    console.log(
                        '===================================================='
                    );


                    console.log(
                        'REQUEST:',
                        {
                            from_date:
                                fromDate,

                            to_date:
                                toDate,

                            student_course_id:
                                studentCourseId
                        }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | LATE FINE DEBUG
                    |--------------------------------------------------------------------------
                    */

                    console.group(
                        '🔴 LATE FINE CALCULATION'
                    );

                    console.log(
                        data.late_fine_debug
                    );

                    console.groupEnd();


                    /*
                    |--------------------------------------------------------------------------
                    | LATE FINE SETTINGS
                    |--------------------------------------------------------------------------
                    */

                    console.group(
                        '⚙️ LATE FINE SETTINGS'
                    );

                    console.table(
                        data.late_fine_setting
                            ? [
                                data.late_fine_setting
                            ]
                            : []
                    );

                    console.groupEnd();


                    /*
                    |--------------------------------------------------------------------------
                    | ATTENDANCE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        data.late_fine_debug &&
                        Array.isArray(
                            data.late_fine_debug.attendance_check
                        ) &&
                        data.late_fine_debug.attendance_check.length
                    ) {

                        console.group(
                            '📅 SKIPPED MONTH ATTENDANCE CHECK'
                        );

                        console.table(
                            data
                                .late_fine_debug
                                .attendance_check
                        );

                        console.groupEnd();
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | MEMBERSHIP DEBUG
                    |--------------------------------------------------------------------------
                    */

                    console.group(
                        '🎫 MEMBERSHIP DISCOUNT'
                    );

                    console.log(
                        data.discount
                    );

                    console.groupEnd();


                    /*
                    |--------------------------------------------------------------------------
                    | MONTH CALCULATION
                    |--------------------------------------------------------------------------
                    */

                    console.group(
                        '📋 MONTH-WISE CALCULATION'
                    );

                    console.table(
                        data.months.map(
                            month => ({

                                month:
                                    month.month_name,

                                status:
                                    month.status,

                                monthly_fee:
                                    month.monthly_fee,

                                late_fine:
                                    month.late_fine,

                                payable:
                                    month.payable_amount,

                                gap:
                                    month.gap,

                                skipped_months:
                                    month.months_without_payment,

                                advance:
                                    month.advance_payment,

                                message:
                                    month.message

                            })
                        )
                    );

                    console.groupEnd();


                    /*
                    |--------------------------------------------------------------------------
                    | FINAL SUMMARY
                    |--------------------------------------------------------------------------
                    */

                    console.group(
                        '💰 FINAL SUMMARY'
                    );

                    console.table([
                        {

                            monthly_fee:
                                data.summary.monthly_fee,

                            late_fine:
                                data.summary.late_fine,

                            gross_payable:
                                data.summary.gross_payable,

                            discount:
                                data.summary.discount,

                            final_payable:
                                data.summary.final_payable
                        }
                    ]);

                    console.groupEnd();


                    /*
                    |--------------------------------------------------------------------------
                    | EXACT DECISION
                    |--------------------------------------------------------------------------
                    */

                    console.log(
                        '===================================================='
                    );

                    console.log(
                        'LATE FINE DECISION:',
                        data
                            .late_fine_debug
                            ?.decision
                    );

                    console.log(
                        'LATE FINE AMOUNT:',
                        data
                            .late_fine_debug
                            ?.late_fine_amount
                    );

                    console.log(
                        'REASON:',
                        data
                            .late_fine_debug
                            ?.late_fine_reason
                    );

                    console.log(
                        'MEMBERSHIP PLAN:',
                        data
                            .discount
                            ?.plan_name
                    );

                    console.log(
                        'MEMBERSHIP DISCOUNT:',
                        data
                            .discount
                            ?.amount
                    );

                    console.log(
                        '===================================================='
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | ERROR
                    |--------------------------------------------------------------------------
                    */

                    if (!response.ok) {

                        throw new Error(
                            data.message ??
                            'Unable to calculate fee.'
                        );
                    }


                    if (!data.status) {

                        throw new Error(
                            data.message ??
                            'Something went wrong.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | RENDER
                    |--------------------------------------------------------------------------
                    */

                    renderResult(data);


                } catch (error) {

                    console.error(
                        '❌ Fee Calculation Error:',
                        error
                    );

                    showError(
                        error.message ??
                        'Unable to fetch fee details.'
                    );

                } finally {

                    button.disabled =
                        false;

                    button.innerHTML =
                        'Check Fee';
                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | RENDER RESULT
        |--------------------------------------------------------------------------
        */

        function renderResult(data)
        {

            result.classList.remove(
                'hidden'
            );


            const summary =
                data.summary;

            const debug =
                data.late_fine_debug;

            const discount =
                data.discount;


            let rows = '';


            /*
            |--------------------------------------------------------------------------
            | MONTH ROWS
            |--------------------------------------------------------------------------
            */

            data.months.forEach(
                function (month) {

                    const isPaid =
                        month.status === 'paid';

                    const isAdvance =
                        month.advance_payment === true;


                    let statusText =
                        'Unpaid';

                    if (isPaid) {

                        statusText =
                            'Paid';

                    } else if (isAdvance) {

                        statusText =
                            'Advance';
                    }


                    rows += `

                        <tr
                            class="border-t
                                   border-white/5"
                        >

                            <td class="px-4 py-4">

                                <div
                                    class="font-medium
                                           text-white"
                                >
                                    ${escapeHtml(
                                        month.month_name
                                    )}
                                </div>

                                <div
                                    class="text-xs
                                           text-slate-500
                                           mt-1"
                                >
                                    ${escapeHtml(
                                        month.message ?? ''
                                    )}
                                </div>

                            </td>


                            <td
                                class="px-4 py-4
                                       text-right
                                       text-slate-300"
                            >

                                ${
                                    isPaid
                                        ? '₹0.00'
                                        : '₹' +
                                          formatMoney(
                                              month.monthly_fee
                                          )
                                }

                            </td>


                            <td
                                class="px-4 py-4
                                       text-right"
                            >

                                ${
                                    Number(
                                        month.late_fine
                                    ) > 0

                                        ? `
                                            <span
                                                class="text-red-400
                                                       font-semibold"
                                            >
                                                ₹${formatMoney(
                                                    month.late_fine
                                                )}
                                            </span>
                                          `

                                        : `
                                            <span
                                                class="text-slate-600"
                                            >
                                                ₹0.00
                                            </span>
                                          `
                                }

                            </td>


                            <td
                                class="px-4 py-4
                                       text-right
                                       font-semibold
                                       text-white"
                            >

                                ${
                                    isPaid

                                        ? '₹0.00'

                                        : '₹' +
                                          formatMoney(
                                              month.payable_amount
                                          )
                                }

                            </td>


                            <td class="px-4 py-4">

                                <span
                                    class="px-2.5 py-1
                                           rounded-full
                                           text-xs
                                           ${
                                               isPaid

                                                   ? 'bg-emerald-500/10 text-emerald-400'

                                                   : isAdvance

                                                       ? 'bg-blue-500/10 text-blue-400'

                                                       : 'bg-amber-500/10 text-amber-400'
                                           }"
                                >
                                    ${statusText}
                                </span>

                            </td>

                        </tr>

                    `;
                }
            );


            /*
            |--------------------------------------------------------------------------
            | MEMBERSHIP HTML
            |--------------------------------------------------------------------------
            */

            let membershipHtml = '';

            if (
                discount &&
                discount.applicable &&
                Number(discount.amount) > 0
            ) {

                membershipHtml = `

                    <div
                        class="mt-5
                               rounded-xl
                               border
                               border-emerald-500/20
                               bg-emerald-500/5
                               p-5"
                    >

                        <div
                            class="flex
                                   flex-col
                                   sm:flex-row
                                   sm:items-center
                                   sm:justify-between
                                   gap-4"
                        >

                            <div>

                                <p
                                    class="text-xs
                                           uppercase
                                           tracking-wider
                                           text-emerald-400"
                                >
                                    Membership Discount
                                </p>

                                <p
                                    class="text-sm
                                           font-semibold
                                           text-white
                                           mt-2"
                                >
                                    ${escapeHtml(
                                        discount.plan_name ??
                                        'Membership Plan'
                                    )}
                                </p>

                                <p
                                    class="text-xs
                                           text-slate-400
                                           mt-1"
                                >
                                    ${escapeHtml(
                                        discount.label ??
                                        'Membership discount applied'
                                    )}
                                </p>

                                ${
                                    discount.duration
                                        ? `
                                            <p
                                                class="text-xs
                                                       text-slate-500
                                                       mt-2"
                                            >
                                                Plan:
                                                ${escapeHtml(
                                                    String(
                                                        discount.duration
                                                    )
                                                )}
                                                ${escapeHtml(
                                                    discount.duration_type ??
                                                    ''
                                                )}
                                            </p>
                                          `
                                        : ''
                                }

                            </div>


                            <div
                                class="text-2xl
                                       font-bold
                                       text-emerald-400"
                            >
                                -₹${formatMoney(
                                    discount.amount
                                )}
                            </div>

                        </div>

                    </div>

                `;

            } else {

                membershipHtml = `

                    <div
                        class="mt-5
                               rounded-xl
                               border
                               border-white/10
                               bg-white/[0.02]
                               p-5"
                    >

                        <div
                            class="flex
                                   items-center
                                   justify-between
                                   gap-4"
                        >

                            <div>

                                <p
                                    class="text-xs
                                           uppercase
                                           tracking-wider
                                           text-slate-500"
                                >
                                    Membership Discount
                                </p>

                                <p
                                    class="text-sm
                                           text-slate-400
                                           mt-2"
                                >
                                    No membership discount applicable.
                                </p>

                            </div>

                            <div
                                class="text-lg
                                       font-semibold
                                       text-slate-600"
                            >
                                ₹0.00
                            </div>

                        </div>

                    </div>

                `;
            }


            /*
            |--------------------------------------------------------------------------
            | LATE FINE HTML
            |--------------------------------------------------------------------------
            */

            const hasLateFine =
                Number(
                    summary.late_fine
                ) > 0;


            let lateFineTitle =
                'No Late Fine';

            let lateFineReason =
                'No late fine applicable.';


            if (hasLateFine) {

                lateFineTitle =
                    formatLateFineDecision(
                        debug?.decision
                    );

                lateFineReason =
                    debug?.late_fine_reason ??
                    'Late fine applied.';
            }


            /*
            |--------------------------------------------------------------------------
            | RESULT HTML
            |--------------------------------------------------------------------------
            */

            result.innerHTML = `

                <div
                    class="rounded-2xl
                           border border-white/10
                           bg-slate-900/70
                           overflow-hidden"
                >

                    {{-- HEADER --}}

                    <div
                        class="p-6
                               border-b
                               border-white/10"
                    >

                        <div
                            class="flex
                                   flex-col
                                   sm:flex-row
                                   sm:items-center
                                   sm:justify-between
                                   gap-4"
                        >

                            <div>

                                <p
                                    class="text-xs
                                           uppercase
                                           tracking-wider
                                           text-slate-500"
                                >
                                    Selected Course
                                </p>

                                <h2
                                    class="text-xl
                                           font-bold
                                           text-white
                                           mt-1"
                                >
                                    ${escapeHtml(
                                        data.course.name ?? ''
                                    )}
                                </h2>

                            </div>


                            <div
                                class="px-4 py-2
                                       rounded-xl
                                       bg-blue-500/10
                                       border border-blue-500/20"
                            >

                                <span
                                    class="text-xs
                                           text-slate-400"
                                >
                                    Monthly Fee
                                </span>

                                <span
                                    class="ml-2
                                           font-bold
                                           text-blue-400"
                                >
                                    ₹${formatMoney(
                                        data.course.monthly_fee
                                    )}
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- MONTH TABLE --}}

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead>

                                <tr
                                    class="bg-white/[0.02]"
                                >

                                    <th
                                        class="px-4 py-4
                                               text-left
                                               text-xs
                                               text-slate-500"
                                    >
                                        Fee Month
                                    </th>

                                    <th
                                        class="px-4 py-4
                                               text-right
                                               text-xs
                                               text-slate-500"
                                    >
                                        Monthly Fee
                                    </th>

                                    <th
                                        class="px-4 py-4
                                               text-right
                                               text-xs
                                               text-slate-500"
                                    >
                                        Late Fine
                                    </th>

                                    <th
                                        class="px-4 py-4
                                               text-right
                                               text-xs
                                               text-slate-500"
                                    >
                                        Payable
                                    </th>

                                    <th
                                        class="px-4 py-4
                                               text-left
                                               text-xs
                                               text-slate-500"
                                    >
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>
                                ${rows}
                            </tbody>

                        </table>

                    </div>


                    {{-- =====================================================
                         LATE FINE CALCULATION
                    ====================================================== --}}

                    <div
                        class="p-6
                               border-t
                               border-white/10"
                    >

                        <div
                            class="rounded-xl
                                   border
                                   ${
                                       hasLateFine
                                           ? 'border-red-500/20 bg-red-500/5'
                                           : 'border-white/10 bg-white/[0.02]'
                                   }
                                   p-5"
                        >

                            <div
                                class="flex
                                       flex-col
                                       sm:flex-row
                                       sm:items-start
                                       sm:justify-between
                                       gap-4"
                            >

                                <div>

                                    <p
                                        class="text-xs
                                               uppercase
                                               tracking-wider
                                               ${
                                                   hasLateFine
                                                       ? 'text-red-400'
                                                       : 'text-slate-500'
                                               }"
                                    >
                                        Late Fine Calculation
                                    </p>


                                    <p
                                        class="text-sm
                                               font-semibold
                                               text-white
                                               mt-2"
                                    >
                                        ${escapeHtml(
                                            lateFineTitle
                                        )}
                                    </p>


                                    <p
                                        class="text-xs
                                               text-slate-400
                                               mt-2"
                                    >
                                        ${escapeHtml(
                                            lateFineReason
                                        )}
                                    </p>

                                </div>


                                <div
                                    class="text-2xl
                                           font-bold
                                           ${
                                               hasLateFine
                                                   ? 'text-red-400'
                                                   : 'text-slate-600'
                                           }"
                                >
                                    ₹${formatMoney(
                                        summary.late_fine
                                    )}
                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             SUMMARY CARDS
                        ================================================== --}}

                        <div
                            class="grid
                                   grid-cols-1
                                   sm:grid-cols-2
                                   lg:grid-cols-5
                                   gap-4
                                   mt-5"
                        >

                            {{-- SELECTED MONTHS --}}

                            <div
                                class="rounded-xl
                                       border
                                       border-white/10
                                       p-4"
                            >

                                <p
                                    class="text-xs
                                           text-slate-500"
                                >
                                    Selected Months
                                </p>

                                <p
                                    class="text-xl
                                           font-bold
                                           text-white
                                           mt-1"
                                >
                                    ${summary.selected_months}
                                </p>

                            </div>


                            {{-- COURSE FEE --}}

                            <div
                                class="rounded-xl
                                       border
                                       border-white/10
                                       p-4"
                            >

                                <p
                                    class="text-xs
                                           text-slate-500"
                                >
                                    Course Fee
                                </p>

                                <p
                                    class="text-xl
                                           font-bold
                                           text-blue-400
                                           mt-1"
                                >
                                    ₹${formatMoney(
                                        summary.monthly_fee
                                    )}
                                </p>

                            </div>


                            {{-- LATE FINE --}}

                            <div
                                class="rounded-xl
                                       border
                                       border-red-500/10
                                       p-4"
                            >

                                <p
                                    class="text-xs
                                           text-slate-500"
                                >
                                    Late Fine
                                </p>

                                <p
                                    class="text-xl
                                           font-bold
                                           text-red-400
                                           mt-1"
                                >
                                    ₹${formatMoney(
                                        summary.late_fine
                                    )}
                                </p>

                            </div>


                            {{-- DISCOUNT --}}

                            <div
                                class="rounded-xl
                                       border
                                       border-emerald-500/10
                                       p-4"
                            >

                                <p
                                    class="text-xs
                                           text-slate-500"
                                >
                                    Membership Discount
                                </p>

                                <p
                                    class="text-xl
                                           font-bold
                                           text-emerald-400
                                           mt-1"
                                >
                                    -₹${formatMoney(
                                        summary.discount
                                    )}
                                </p>

                            </div>


                            {{-- GROSS --}}

                            <div
                                class="rounded-xl
                                       border
                                       border-white/10
                                       p-4"
                            >

                                <p
                                    class="text-xs
                                           text-slate-500"
                                >
                                    Gross Payable
                                </p>

                                <p
                                    class="text-xl
                                           font-bold
                                           text-white
                                           mt-1"
                                >
                                    ₹${formatMoney(
                                        summary.gross_payable
                                    )}
                                </p>

                            </div>

                        </div>


                        {{-- =================================================
                             MEMBERSHIP DISCOUNT DETAILS
                        ================================================== --}}

                        ${membershipHtml}


                        {{-- =================================================
                             FINAL AMOUNT
                        ================================================== --}}

                        <div
                            class="mt-5
                                   rounded-xl
                                   border
                                   border-pink-500/20
                                   bg-pink-500/5
                                   p-5"
                        >

                            <div
                                class="flex
                                       flex-col
                                       sm:flex-row
                                       sm:items-center
                                       sm:justify-between
                                       gap-3"
                            >

                                <div>

                                    <p
                                        class="text-sm
                                               font-semibold
                                               text-white"
                                    >
                                        Final Amount Payable
                                    </p>

                                    <p
                                        class="text-xs
                                               text-slate-500
                                               mt-1"
                                    >
                                        Course fee + late fine − membership discount
                                    </p>

                                </div>


                                <div
                                    class="text-2xl
                                           font-bold
                                           text-pink-400"
                                >
                                    ₹${formatMoney(
                                        summary.final_payable
                                    )}
                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             PAYMENT BUTTON
                        ================================================== --}}

                        ${
                            Number(
                                summary.final_payable
                            ) > 0

                                ? `

                                    <div class="mt-5">

                                        <button
                                            type="button"
                                            id="proceedPaymentBtn"
                                            class="px-6 py-3
                                                   rounded-xl
                                                   bg-gradient-to-r
                                                   from-pink-500
                                                   to-pink-600
                                                   text-white
                                                   font-semibold
                                                   hover:opacity-90
                                                   transition"
                                        >
                                            Proceed to Payment
                                        </button>

                                    </div>

                                  `

                                : ''
                        }

                    </div>

                </div>

            `;


            /*
            |--------------------------------------------------------------------------
            | PROCEED PAYMENT
            |--------------------------------------------------------------------------
            */

            const proceedButton =
                document.getElementById(
                    'proceedPaymentBtn'
                );


            if (proceedButton) {

                proceedButton.addEventListener(
                    'click',
                    function () {

                        proceedToPayment(
                            proceedButton
                        );

                    }
                );
            }

        }


        /*
        |--------------------------------------------------------------------------
        | LATE FINE DECISION TEXT
        |--------------------------------------------------------------------------
        */

        function formatLateFineDecision(
            decision
        ) {

            const labels = {

                'SAME_MONTH_LATE_FEE':
                    'Same Month Late Fee',

                'NEXT_MONTH_LATE_FEE':
                    'Next Month Late Fee',

                'NEXT_MONTH_LATE_FEE_PRESENT_FOUND':
                    'Next Month Late Fee',

                'ABSENT_CHARGE_PERCENTAGE':
                    'Absent Charge',

                'NO_LATE_FINE':
                    'No Late Fine',

                'ADVANCE_PAYMENT':
                    'Advance Payment',

                'FIRST_PAYMENT':
                    'First Payment'

            };


            return labels[
                decision
            ] ??
            'Late Fine Applied';

        }


        /*
        |--------------------------------------------------------------------------
        | PROCEED TO PAYMENT
        |--------------------------------------------------------------------------
        */

        async function proceedToPayment(
            paymentButton
        ) {

            const fromDate =
                fromDateInput.value;

            const toDate =
                toDateInput.value;

            const studentCourseId =
                courseInput.value;


            paymentButton.disabled =
                true;

            paymentButton.innerHTML =
                'Preparing Payment...';


            try {

                const response =
                    await fetch(
                        "{{ route('custom-monthly-fee-pay.proceed') }}",
                        {

                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .getAttribute(
                                            'content'
                                        ),

                                'Accept':
                                    'application/json'
                            },

                            body:
                                JSON.stringify({

                                    from_date:
                                        fromDate,

                                    to_date:
                                        toDate,

                                    student_course_id:
                                        studentCourseId
                                })
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ??
                        'Unable to create payment.'
                    );
                }


                if (!data.status) {

                    throw new Error(
                        data.message ??
                        'Unable to create payment.'
                    );
                }


                window.location.href =
                    data.payment_url;


            } catch (error) {

                console.error(
                    'Payment Error:',
                    error
                );

                paymentButton.disabled =
                    false;

                paymentButton.innerHTML =
                    'Proceed to Payment';

                showError(
                    error.message ??
                    'Unable to create payment.'
                );
            }

        }


        /*
        |--------------------------------------------------------------------------
        | ERROR
        |--------------------------------------------------------------------------
        */

        function showError(
            message
        ) {

            result.classList.remove(
                'hidden'
            );

            result.innerHTML = `

                <div
                    class="rounded-2xl
                           border border-red-500/20
                           bg-red-500/5
                           p-5"
                >

                    <h3
                        class="text-sm
                               font-semibold
                               text-red-300"
                    >
                        Unable to continue
                    </h3>

                    <p
                        class="text-sm
                               text-red-400/80
                               mt-2"
                    >
                        ${escapeHtml(
                            message
                        )}
                    </p>

                </div>

            `;
        }


        /*
        |--------------------------------------------------------------------------
        | MONEY FORMAT
        |--------------------------------------------------------------------------
        */

        function formatMoney(
            amount
        ) {

            return Number(
                amount || 0
            ).toLocaleString(
                'en-IN',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | HTML ESCAPE
        |--------------------------------------------------------------------------
        */

        function escapeHtml(
            value
        ) {

            return String(
                value ?? ''
            )
                .replace(
                    /&/g,
                    '&amp;'
                )
                .replace(
                    /</g,
                    '&lt;'
                )
                .replace(
                    />/g,
                    '&gt;'
                )
                .replace(
                    /"/g,
                    '&quot;'
                )
                .replace(
                    /'/g,
                    '&#039;'
                );
        }

    }
);

</script>

@endsection
