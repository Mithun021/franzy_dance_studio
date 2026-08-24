@extends('partials.master')

@section('title','My Payments')

@section('content')

@include('component.breadcrumbs')


<section class="py-12 bg-slate-950 min-h-screen">

    <div class="max-w-7xl mx-auto px-4">


        {{-- ============================================================
            HEADER
        ============================================================= --}}

        <div class="flex justify-between items-center mb-8 flex-wrap gap-4">

            <div>

                <h2 class="text-3xl font-bold text-white">

                    Payment History

                </h2>

                <p class="text-gray-400 mt-2">

                    View all your payment attempts and successful payment invoices.

                </p>

            </div>


            <div>

                <span class="bg-pink-600 text-white px-4 py-2 rounded-full">

                    Total Attempts :
                    {{ $payments->count() }}

                </span>

            </div>

        </div>



        {{-- ============================================================
            PAYMENT HISTORY
        ============================================================= --}}

        @if($payments->count())


            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">


                @foreach($payments as $payment)


                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | Payment Status
                        |--------------------------------------------------------------------------
                        */

                        $status = strtolower($payment->status ?? 'unknown');


                        /*
                        |--------------------------------------------------------------------------
                        | Status Design
                        |--------------------------------------------------------------------------
                        */

                        $statusClass = match($status) {

                            'success' => 'bg-green-500 text-white',

                            'failed' => 'bg-red-500 text-white',

                            'pending' => 'bg-yellow-500 text-black',

                            'cancelled', 'canceled'
                                => 'bg-gray-500 text-white',

                            default
                                => 'bg-blue-500 text-white'

                        };


                        /*
                        |--------------------------------------------------------------------------
                        | Status Icon
                        |--------------------------------------------------------------------------
                        */

                        $statusIcon = match($status) {

                            'success'
                                => '✓',

                            'failed'
                                => '✕',

                            'pending'
                                => '⏳',

                            'cancelled', 'canceled'
                                => '✕',

                            default
                                => '•'

                        };


                        /*
                        |--------------------------------------------------------------------------
                        | Payment Date
                        |--------------------------------------------------------------------------
                        */

                        $paymentDate = $payment->payment_date
                            ? $payment->payment_date->format('d M Y')
                            : '-';


                        /*
                        |--------------------------------------------------------------------------
                        | Course
                        |--------------------------------------------------------------------------
                        */

                        $courseName =
                            optional($payment->studentCourse?->course)
                                ->course_name
                            ?? 'Course Not Found';

                    @endphp



                    {{-- ====================================================
                        PAYMENT CARD
                    ===================================================== --}}

                    <div
                        class="bg-slate-900 rounded-2xl border border-slate-800 shadow-lg overflow-hidden hover:border-pink-500 transition duration-300">


                        {{-- =================================================
                            CARD HEADER
                        ================================================== --}}

                        <div
                            class="bg-gradient-to-r from-pink-600 to-blue-600 p-4">

                            <div class="flex justify-between items-start gap-3">


                                {{-- Invoice / Attempt ID --}}

                                <div>

                                    <p class="text-white text-sm">

                                        Payment Attempt

                                    </p>


                                    <h3 class="font-bold text-xl text-white">

                                        #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}

                                    </h3>

                                </div>


                                {{-- Status --}}

                                <div>

                                    <span
                                        class="{{ $statusClass }} text-xs px-3 py-1 rounded-full font-semibold">

                                        {{ $statusIcon }}

                                        {{ ucfirst($status) }}

                                    </span>

                                </div>

                            </div>

                        </div>



                        {{-- =================================================
                            CARD BODY
                        ================================================== --}}

                        <div class="p-5 space-y-4">


                            {{-- Course --}}

                            <div class="flex justify-between gap-4">

                                <span class="text-gray-400">

                                    Course

                                </span>

                                <span
                                    class="font-semibold text-white text-right">

                                    {{ $courseName }}

                                </span>

                            </div>


                            {{-- Level --}}

                            @if($payment->studentCourse?->level)

                                <div class="flex justify-between gap-4">

                                    <span class="text-gray-400">

                                        Level

                                    </span>

                                    <span class="text-white text-right">

                                        {{ $payment->studentCourse->level->name }}

                                    </span>

                                </div>

                            @endif


                            {{-- Batch --}}

                            @if($payment->studentCourse?->batch)

                                <div class="flex justify-between gap-4">

                                    <span class="text-gray-400">

                                        Batch

                                    </span>

                                    <span class="text-white text-right">

                                        {{ $payment->studentCourse->batch->batch_name }}

                                    </span>

                                </div>

                            @endif


                            {{-- Payment Date --}}

                            <div class="flex justify-between gap-4">

                                <span class="text-gray-400">

                                    Payment Date

                                </span>

                                <span class="text-white text-right">

                                    {{ $paymentDate }}

                                </span>

                            </div>


                            {{-- Payment Mode --}}

                            <div class="flex justify-between gap-4">

                                <span class="text-gray-400">

                                    Payment Mode

                                </span>

                                <span class="text-white text-right">

                                    {{ $payment->payment_mode
                                        ? ucfirst($payment->payment_mode)
                                        : '-' }}

                                </span>

                            </div>


                            {{-- Transaction ID --}}

                            <div class="flex justify-between gap-4">

                                <span class="text-gray-400">

                                    Transaction ID

                                </span>

                                <span
                                    class="text-white text-right break-all">

                                    {{ $payment->transaction_id ?: '-' }}

                                </span>

                            </div>


                            {{-- =================================================
                                AMOUNT
                            ================================================== --}}

                            <div
                                class="border-t border-slate-700 pt-4">

                                <div
                                    class="flex justify-between items-center">

                                    <span
                                        class="text-lg font-semibold text-gray-300">

                                        Amount

                                    </span>


                                    <span
                                        class="
                                            text-2xl
                                            font-bold
                                            {{ $status === 'success'
                                                ? 'text-green-400'
                                                : 'text-white'
                                            }}
                                        ">

                                        ₹ {{ number_format((float) $payment->amount, 2) }}

                                    </span>

                                </div>

                            </div>


                            {{-- =================================================
                                STATUS MESSAGE
                            ================================================== --}}

                            @if($status === 'success')

                                <div
                                    class="bg-green-500/10 border border-green-500/20 rounded-lg px-4 py-3">

                                    <p class="text-green-400 text-sm">

                                        ✓ Payment completed successfully.

                                    </p>

                                </div>


                            @elseif($status === 'failed')

                                <div
                                    class="bg-red-500/10 border border-red-500/20 rounded-lg px-4 py-3">

                                    <p class="text-red-400 text-sm">

                                        ✕ This payment attempt was unsuccessful.

                                    </p>

                                </div>


                            @elseif($status === 'pending')

                                <div
                                    class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg px-4 py-3">

                                    <p class="text-yellow-400 text-sm">

                                        ⏳ Payment is currently pending.

                                    </p>

                                </div>


                            @elseif(
                                $status === 'cancelled' ||
                                $status === 'canceled'
                            )

                                <div
                                    class="bg-gray-500/10 border border-gray-500/20 rounded-lg px-4 py-3">

                                    <p class="text-gray-400 text-sm">

                                        Payment attempt was cancelled.

                                    </p>

                                </div>


                            @endif

                        </div>



                        {{-- =================================================
                            CARD FOOTER
                        ================================================== --}}

                        <div
                            class="border-t border-slate-800 p-4">


                            @if($status === 'success')


                                {{-- Successful Payment --}}

                                <div class="flex gap-3">


                                    {{-- View Invoice --}}

                                    <a
                                        href="{{ route(
                                            'student.payment.invoice',
                                            $payment->id
                                        ) }}"
                                        class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">

                                        View Invoice

                                    </a>


                                    {{-- Download Invoice --}}

                                    <a
                                        href="{{ route(
                                            'student.payment.invoice',
                                            $payment->id
                                        ) }}"
                                        target="_blank"
                                        class="flex-1 text-center bg-pink-600 hover:bg-pink-700 text-white py-2 rounded-lg transition">

                                        Download

                                    </a>

                                </div>


                            @elseif($status === 'failed')


                                {{-- Failed Payment --}}

                                @if($payment->studentCourse)

                                    <a
                                        href="{{ route(
                                            'student.payment-page',
                                            $payment->studentCourse->id
                                        ) }}"
                                        class="block w-full text-center bg-pink-600 hover:bg-pink-700 text-white py-2 rounded-lg transition">

                                        Try Again

                                    </a>

                                @else

                                    <div
                                        class="text-center text-gray-500 text-sm">

                                        Payment course unavailable

                                    </div>

                                @endif


                            @elseif($status === 'pending')


                                {{-- Pending Payment --}}

                                <div
                                    class="text-center text-yellow-400 text-sm py-2">

                                    Payment Processing...

                                </div>


                            @else


                                {{-- Other Status --}}

                                <div
                                    class="text-center text-gray-500 text-sm py-2">

                                    No action available

                                </div>


                            @endif


                        </div>

                    </div>


                @endforeach


            </div>


        @else


            {{-- ============================================================
                NO PAYMENT HISTORY
            ============================================================= --}}

            <div
                class="bg-slate-900 rounded-xl p-16 text-center border border-slate-800">


                <div class="text-6xl mb-5">

                    💳

                </div>


                <h3
                    class="text-2xl font-bold text-white">

                    No Payment History Found

                </h3>


                <p
                    class="text-gray-400 mt-2">

                    Your payment attempts will appear here.

                </p>


            </div>


        @endif

    </div>

</section>

@endsection
