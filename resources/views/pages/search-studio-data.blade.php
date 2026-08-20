@extends('partials.master')

@section('title', 'Studio Booking Details')

@section('content')

<div class="min-h-screen bg-black py-16">

    <div class="container mx-auto px-4">

        {{-- =========================================================
            PAGE HEADER
        ========================================================== --}}

        <div class="text-center mb-12">

            <span class="inline-flex items-center gap-2
                         px-4 py-2
                         rounded-full
                         bg-blue-500/10
                         border border-blue-500/20
                         text-blue-400
                         text-xs
                         tracking-widest
                         uppercase">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>

                </svg>

                Booking Search Results

            </span>


            <h1 class="text-3xl md:text-4xl font-bold text-white mt-5">

                Studio

                <span class="bg-gradient-to-r from-pink-500 to-blue-500
                             bg-clip-text text-transparent">

                    Booking Details

                </span>

            </h1>


            <p class="text-gray-400 mt-3">

                We found {{ $bookings->count() }} booking(s)
                matching your search.

            </p>

        </div>


        {{-- =========================================================
            SEARCH SUMMARY
        ========================================================== --}}

        <div class="max-w-6xl mx-auto mb-8">

            <div class="rounded-2xl
                        border border-white/10
                        bg-white/5
                        backdrop-blur-xl
                        p-5">

                <div class="flex flex-wrap items-center gap-4">

                    @if($bookingId)

                        <div class="inline-flex items-center gap-2
                                    px-4 py-2
                                    rounded-lg
                                    bg-pink-500/10
                                    border border-pink-500/20">

                            <span class="text-gray-400 text-sm">

                                Booking ID:

                            </span>

                            <span class="text-white font-semibold">

                                {{ $bookingId }}

                            </span>

                        </div>

                    @endif


                    @if($phone)

                        <div class="inline-flex items-center gap-2
                                    px-4 py-2
                                    rounded-lg
                                    bg-blue-500/10
                                    border border-blue-500/20">

                            <span class="text-gray-400 text-sm">

                                Phone:

                            </span>

                            <span class="text-white font-semibold">

                                {{ $phone }}

                            </span>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =========================================================
            BOOKING RECORDS
        ========================================================== --}}

        <div class="max-w-6xl mx-auto space-y-6">

            @foreach($bookings as $booking)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | Booking Type
                    |--------------------------------------------------------------------------
                    */

                    $bookingType = strtolower($booking->booking_type ?? 'day');

                    $isHourly = in_array($bookingType, [
                        'hour',
                        'hourly'
                    ]);

                    $bookingTypeLabel = $isHourly
                        ? 'Per Hour'
                        : 'Per Day';


                    /*
                    |--------------------------------------------------------------------------
                    | Rate
                    |--------------------------------------------------------------------------
                    */

                    $rate = $booking->rate
                        ?? $booking->studio_amount
                        ?? 0;


                    /*
                    |--------------------------------------------------------------------------
                    | Duration
                    |--------------------------------------------------------------------------
                    */

                    if ($isHourly) {

                        $duration = $booking->booking_duration ?? 1;

                        $durationText = $duration == 1
                            ? '1 Hour'
                            : number_format($duration, 2) . ' Hours';

                    } else {

                        $duration = $booking->booking_duration
                            ?? $booking->total_days
                            ?? 1;

                        $durationText = $duration == 1
                            ? '1 Day'
                            : $duration . ' Days';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Successful Payment
                    |--------------------------------------------------------------------------
                    */

                    $successfulPayment = $booking->payments
                        ->first(function ($payment) {

                            return strtolower(
                                trim($payment->payment_status ?? '')
                            ) === 'success';

                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Payment Totals
                    |--------------------------------------------------------------------------
                    */

                    $bookingAmount = $booking->booking_amount;

                    $totalPaid = $booking->total_paid;

                    $dueAmount = $booking->due_amount;

                @endphp


                {{-- =================================================
                    BOOKING CARD
                ================================================== --}}

                <div class="relative overflow-hidden
                            rounded-3xl
                            border border-white/10
                            bg-white/5
                            backdrop-blur-xl
                            shadow-2xl">


                    {{-- Gradient Top Line --}}

                    <div class="h-1 bg-gradient-to-r
                                from-pink-500
                                via-purple-500
                                to-blue-500">
                    </div>


                    <div class="p-6 md:p-8">


                        {{-- =========================================
                            TOP SECTION
                        ========================================== --}}

                        <div class="flex flex-col lg:flex-row
                                    lg:items-center
                                    lg:justify-between
                                    gap-5
                                    mb-7">

                            <div>

                                <p class="text-xs
                                          uppercase
                                          tracking-widest
                                          text-gray-500
                                          mb-2">

                                    Booking ID

                                </p>


                                <h2 class="text-2xl
                                           md:text-3xl
                                           font-bold
                                           text-white">

                                    {{ $booking->booking_id }}

                                </h2>

                            </div>


                            {{-- Status --}}

                            <div class="flex flex-wrap gap-3">

                                {{-- Booking Status --}}

                                <span class="inline-flex
                                             items-center
                                             gap-2
                                             px-4
                                             py-2
                                             rounded-full
                                             bg-blue-500/10
                                             border border-blue-500/20
                                             text-blue-400
                                             text-sm
                                             font-semibold">

                                    <span class="w-2 h-2
                                                 rounded-full
                                                 bg-blue-400">
                                    </span>

                                    {{ $booking->enquiry_status }}

                                </span>


                                {{-- Payment Status --}}

                                @if($successfulPayment)

                                    <span class="inline-flex
                                                 items-center
                                                 gap-2
                                                 px-4
                                                 py-2
                                                 rounded-full
                                                 bg-green-500/10
                                                 border border-green-500/20
                                                 text-green-400
                                                 text-sm
                                                 font-semibold">

                                        <span class="w-2 h-2
                                                     rounded-full
                                                     bg-green-400">
                                        </span>

                                        Payment Successful

                                    </span>

                                @else

                                    <span class="inline-flex
                                                 items-center
                                                 gap-2
                                                 px-4
                                                 py-2
                                                 rounded-full
                                                 bg-yellow-500/10
                                                 border border-yellow-500/20
                                                 text-yellow-400
                                                 text-sm
                                                 font-semibold">

                                        <span class="w-2 h-2
                                                     rounded-full
                                                     bg-yellow-400">
                                        </span>

                                        Payment Pending

                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- =========================================
                            BOOKING INFORMATION
                        ========================================== --}}

                        <div class="grid
                                    sm:grid-cols-2
                                    lg:grid-cols-4
                                    gap-4
                                    mb-8">


                            {{-- Customer --}}

                            <div class="rounded-xl
                                        bg-black/20
                                        border border-white/5
                                        p-4">

                                <p class="text-xs
                                          uppercase
                                          tracking-wider
                                          text-gray-500
                                          mb-1">

                                    Customer

                                </p>

                                <p class="text-white
                                          font-semibold
                                          break-words">

                                    {{ $booking->customer_name }}

                                </p>

                            </div>


                            {{-- Phone --}}

                            <div class="rounded-xl
                                        bg-black/20
                                        border border-white/5
                                        p-4">

                                <p class="text-xs
                                          uppercase
                                          tracking-wider
                                          text-gray-500
                                          mb-1">

                                    Phone

                                </p>

                                <p class="text-white
                                          font-semibold">

                                    {{ $booking->phone }}

                                </p>

                            </div>


                            {{-- Studio --}}

                            <div class="rounded-xl
                                        bg-black/20
                                        border border-white/5
                                        p-4">

                                <p class="text-xs
                                          uppercase
                                          tracking-wider
                                          text-gray-500
                                          mb-1">

                                    Studio

                                </p>

                                <p class="text-white
                                          font-semibold">

                                    {{ $booking->studio->category->name ?? 'N/A' }}

                                </p>

                            </div>


                            {{-- Booking Type --}}

                            <div class="rounded-xl
                                        bg-black/20
                                        border border-white/5
                                        p-4">

                                <p class="text-xs
                                          uppercase
                                          tracking-wider
                                          text-gray-500
                                          mb-1">

                                    Booking Type

                                </p>

                                <p class="text-white
                                          font-semibold">

                                    {{ $bookingTypeLabel }}

                                </p>

                            </div>

                        </div>


                        {{-- =========================================
                            LOCATION
                        ========================================== --}}

                        <div class="grid
                                    sm:grid-cols-2
                                    lg:grid-cols-3
                                    gap-4
                                    mb-8">


                            {{-- City --}}

                            <div class="rounded-xl
                                        bg-black/20
                                        border border-white/5
                                        p-4">

                                <p class="text-xs
                                          uppercase
                                          tracking-wider
                                          text-gray-500
                                          mb-1">

                                    City

                                </p>

                                <p class="text-white font-semibold">

                                    {{ $booking->city ?? 'N/A' }}

                                </p>

                            </div>


                            {{-- State --}}

                            <div class="rounded-xl
                                        bg-black/20
                                        border border-white/5
                                        p-4">

                                <p class="text-xs
                                          uppercase
                                          tracking-wider
                                          text-gray-500
                                          mb-1">

                                    State

                                </p>

                                <p class="text-white font-semibold">

                                    {{ $booking->state ?? 'N/A' }}

                                </p>

                            </div>


                            {{-- Pincode --}}

                            <div class="rounded-xl
                                        bg-black/20
                                        border border-white/5
                                        p-4">

                                <p class="text-xs
                                          uppercase
                                          tracking-wider
                                          text-gray-500
                                          mb-1">

                                    Pincode

                                </p>

                                <p class="text-white font-semibold">

                                    {{ $booking->pincode ?? 'N/A' }}

                                </p>

                            </div>

                        </div>


                        {{-- =========================================
                            BOOKING DATE / TIME
                        ========================================== --}}

                        <div class="grid
                                    md:grid-cols-3
                                    gap-4
                                    mb-8">


                            {{-- From --}}

                            <div class="rounded-xl
                                        border border-white/5
                                        bg-gradient-to-br
                                        from-pink-500/10
                                        to-transparent
                                        p-5">

                                <p class="text-xs
                                          uppercase
                                          tracking-wider
                                          text-gray-500
                                          mb-2">

                                    Booking From

                                </p>


                                <p class="text-lg
                                          font-bold
                                          text-white">

                                    {{ $booking->booking_from_date
                                        ? \Carbon\Carbon::parse(
                                            $booking->booking_from_date
                                        )->format('d M Y')
                                        : 'N/A'
                                    }}

                                </p>


                                @if($booking->booking_from_time)

                                    <p class="text-sm text-pink-400 mt-2">

                                        <span class="text-gray-500">
                                            Time:
                                        </span>

                                        {{ \Carbon\Carbon::parse(
                                            $booking->booking_from_time
                                        )->format('h:i A') }}

                                    </p>

                                @endif

                            </div>


                            {{-- To --}}

                            <div class="rounded-xl
                                        border border-white/5
                                        bg-gradient-to-br
                                        from-blue-500/10
                                        to-transparent
                                        p-5">

                                <p class="text-xs
                                          uppercase
                                          tracking-wider
                                          text-gray-500
                                          mb-2">

                                    Booking To

                                </p>


                                <p class="text-lg
                                          font-bold
                                          text-white">

                                    {{ $booking->booking_to_date
                                        ? \Carbon\Carbon::parse(
                                            $booking->booking_to_date
                                        )->format('d M Y')
                                        : 'N/A'
                                    }}

                                </p>


                                @if($booking->booking_to_time)

                                    <p class="text-sm text-blue-400 mt-2">

                                        <span class="text-gray-500">
                                            Time:
                                        </span>

                                        {{ \Carbon\Carbon::parse(
                                            $booking->booking_to_time
                                        )->format('h:i A') }}

                                    </p>

                                @endif

                            </div>


                            {{-- Duration --}}

                            <div class="rounded-xl
                                        border border-white/5
                                        bg-gradient-to-br
                                        from-purple-500/10
                                        to-transparent
                                        p-5">

                                <p class="text-xs
                                          uppercase
                                          tracking-wider
                                          text-gray-500
                                          mb-2">

                                    Total Duration

                                </p>


                                <p class="text-lg
                                          font-bold
                                          text-white">

                                    {{ $durationText }}

                                </p>


                                <p class="text-sm text-purple-400 mt-2">

                                    {{ $bookingTypeLabel }}

                                </p>

                            </div>

                        </div>


                        {{-- =========================================
                            RATE & CALCULATION
                        ========================================== --}}

                        <div class="rounded-2xl
                                    border border-white/10
                                    bg-black/20
                                    p-5
                                    mb-8">

                            <div class="grid
                                        sm:grid-cols-2
                                        lg:grid-cols-4
                                        gap-5">


                                {{-- Rate --}}

                                <div>

                                    <p class="text-sm text-gray-500">

                                        {{ $isHourly
                                            ? 'Price / Hour'
                                            : 'Price / Day'
                                        }}

                                    </p>

                                    <p class="text-xl
                                              font-bold
                                              text-white
                                              mt-1">

                                        ₹{{ number_format($rate, 2) }}

                                    </p>

                                </div>


                                {{-- Duration --}}

                                <div>

                                    <p class="text-sm text-gray-500">

                                        Duration

                                    </p>

                                    <p class="text-xl
                                              font-bold
                                              text-white
                                              mt-1">

                                        {{ $durationText }}

                                    </p>

                                </div>


                                {{-- Calculation --}}

                                <div>

                                    <p class="text-sm text-gray-500">

                                        Calculation

                                    </p>

                                    <p class="text-xl
                                              font-bold
                                              text-blue-400
                                              mt-1">

                                        ₹{{ number_format($rate, 2) }}

                                        ×

                                        {{ $isHourly
                                            ? number_format($duration, 2)
                                            : $duration
                                        }}

                                    </p>

                                </div>


                                {{-- Booking Amount --}}

                                <div>

                                    <p class="text-sm text-gray-500">

                                        Booking Amount

                                    </p>

                                    <p class="text-xl
                                              font-bold
                                              text-pink-400
                                              mt-1">

                                        ₹{{ number_format(
                                            $bookingAmount,
                                            2
                                        ) }}

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =========================================
                            PAYMENT SUMMARY
                        ========================================== --}}

                        <div class="border-t
                                    border-white/10
                                    pt-6">


                            <div class="grid
                                        sm:grid-cols-3
                                        gap-4">


                                {{-- Booking Amount --}}

                                <div>

                                    <p class="text-sm text-gray-500">

                                        Booking Amount

                                    </p>

                                    <p class="text-xl
                                              font-bold
                                              text-white
                                              mt-1">

                                        ₹{{ number_format(
                                            $bookingAmount,
                                            2
                                        ) }}

                                    </p>

                                </div>


                                {{-- Total Paid --}}

                                <div>

                                    <p class="text-sm text-gray-500">

                                        Total Paid

                                    </p>

                                    <p class="text-xl
                                              font-bold
                                              text-green-400
                                              mt-1">

                                        ₹{{ number_format(
                                            $totalPaid,
                                            2
                                        ) }}

                                    </p>

                                </div>


                                {{-- Due --}}

                                <div>

                                    <p class="text-sm text-gray-500">

                                        Due Amount

                                    </p>

                                    <p class="text-xl
                                              font-bold
                                              mt-1
                                              {{ $dueAmount > 0
                                                    ? 'text-yellow-400'
                                                    : 'text-green-400' }}">

                                        ₹{{ number_format(
                                            $dueAmount,
                                            2
                                        ) }}

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =========================================
                            PAYMENT MESSAGE
                        ========================================== --}}

                        <div class="mt-7
                                    pt-6
                                    border-t
                                    border-white/10
                                    flex flex-col
                                    sm:flex-row
                                    sm:items-center
                                    sm:justify-between
                                    gap-4">


                            <div>

                                @if($successfulPayment)

                                    <p class="text-sm text-green-400">

                                        ✓ Payment has been successfully
                                        received.

                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">

                                        Payment ID:
                                        {{ $successfulPayment->payment_id }}

                                    </p>

                                @else

                                    <p class="text-sm text-yellow-400">

                                        Payment is pending.

                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">

                                        Please complete the payment to
                                        proceed with your booking.

                                    </p>

                                @endif

                            </div>


                            {{-- =====================================
                                SUCCESS PAYMENT
                            ====================================== --}}

                            @if($successfulPayment)

                                <a href="{{ route(
                                    'studio.invoice.download',
                                    $successfulPayment->id
                                ) }}"
                                   target="_blank"
                                   class="inline-flex
                                          items-center
                                          justify-center
                                          gap-2
                                          px-6
                                          py-3
                                          rounded-xl
                                          bg-gradient-to-r
                                          from-green-600
                                          to-emerald-600
                                          hover:scale-105
                                          active:scale-95
                                          transition
                                          duration-300
                                          text-white
                                          font-semibold
                                          shadow-lg
                                          shadow-green-500/20">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M12 4v12m0 0l-4-4m4 4l4-4M5 20h14"/>

                                    </svg>

                                    Download Invoice

                                </a>


                            {{-- =====================================
                                NO SUCCESS PAYMENT
                            ====================================== --}}

                            @else

                                <a href="{{ route(
                                    'studio.booking.payment',
                                    $booking->id
                                ) }}"
                                   class="inline-flex
                                          items-center
                                          justify-center
                                          gap-2
                                          px-6
                                          py-3
                                          rounded-xl
                                          bg-gradient-to-r
                                          from-pink-600
                                          to-blue-600
                                          hover:scale-105
                                          active:scale-95
                                          transition
                                          duration-300
                                          text-white
                                          font-semibold
                                          shadow-lg
                                          shadow-pink-500/20">

                                    Proceed to Payment

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M13 7l5 5m0 0l-5 5m5-5H6"/>

                                    </svg>

                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- =========================================================
            BACK BUTTON
        ========================================================== --}}

        <div class="max-w-6xl mx-auto mt-8">

            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-2
                      text-sm
                      text-gray-400
                      hover:text-white
                      transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 19l-7-7 7-7"/>

                </svg>

                Back to Search

            </a>

        </div>

    </div>

</div>

@endsection
