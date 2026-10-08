@extends('partials.master')

@section('title', 'Event Booking Payment')

@section('content')

@include('component.breadcrumbs')

<section class="bg-[#070b18] min-h-screen py-12 md:py-16 text-white">

    <div class="container mx-auto px-4 max-w-5xl">

        {{-- Header --}}
        <div class="max-w-2xl mx-auto text-center mb-10">

            <span class="inline-block text-pink-500 text-xs md:text-sm font-bold uppercase tracking-[3px] mb-3">
                Complete Registration
            </span>

            <h1 class="text-3xl md:text-4xl font-extrabold mb-3">
                Event
                <span class="text-pink-500">Payment</span>
            </h1>

            <p class="text-gray-500 text-sm leading-6">
                Your booking has been created successfully.
                Complete the payment to confirm your event registration.
            </p>

        </div>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


            {{-- Event Details --}}
            <div class="lg:col-span-2">

                <div class="rounded-2xl
                            bg-white/[0.04]
                            border border-white/10
                            p-6 md:p-8">

                    <div class="flex items-start justify-between gap-4 mb-7">

                        <div>

                            <span class="text-gray-600 text-xs block mb-2">
                                Event
                            </span>

                            <h2 class="text-xl md:text-2xl font-bold">
                                {{ $booking->event->event_name }}
                            </h2>

                        </div>

                        <div class="shrink-0">

                            <span class="inline-flex items-center gap-2
                                         px-3 py-1.5
                                         rounded-full
                                         bg-yellow-500/10
                                         border border-yellow-500/20
                                         text-yellow-400
                                         text-xs font-bold">

                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>

                                Payment Pending

                            </span>

                        </div>

                    </div>


                    {{-- Booking ID --}}
                    <div class="rounded-xl
                                bg-black/20
                                border border-white/[0.06]
                                p-4 mb-5">

                        <div class="flex items-center justify-between gap-3">

                            <div>

                                <span class="block text-gray-600 text-[11px] mb-1">
                                    Booking ID
                                </span>

                                <span class="text-sm font-bold text-white">
                                    {{ $booking->booking_id }}
                                </span>

                            </div>

                            <div class="w-10 h-10
                                        rounded-lg
                                        bg-pink-500/10
                                        text-pink-500
                                        flex items-center justify-center">

                                <svg class="w-5 h-5"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path d="M20 7h-9"></path>
                                    <path d="M20 12h-9"></path>
                                    <path d="M20 17h-9"></path>
                                    <path d="M4 7h.01"></path>
                                    <path d="M4 12h.01"></path>
                                    <path d="M4 17h.01"></path>

                                </svg>

                            </div>

                        </div>

                    </div>


                    {{-- Event Information --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="rounded-xl
                                    bg-black/20
                                    border border-white/[0.06]
                                    p-4">

                            <span class="block text-gray-600 text-[11px] mb-2">
                                Event Date
                            </span>

                            <span class="text-sm font-semibold">
                                {{ $booking->event->event_date->format('d M Y') }}
                            </span>

                        </div>


                        @if($booking->event->event_time)

                            <div class="rounded-xl
                                        bg-black/20
                                        border border-white/[0.06]
                                        p-4">

                                <span class="block text-gray-600 text-[11px] mb-2">
                                    Event Time
                                </span>

                                <span class="text-sm font-semibold">
                                    {{ \Carbon\Carbon::parse($booking->event->event_time)->format('h:i A') }}
                                </span>

                            </div>

                        @endif


                        @if($booking->event->venue_details)

                            <div class="rounded-xl
                                        bg-black/20
                                        border border-white/[0.06]
                                        p-4 sm:col-span-2">

                                <span class="block text-gray-600 text-[11px] mb-2">
                                    Venue
                                </span>

                                <span class="text-sm font-semibold">
                                    {{ $booking->event->venue_details }}
                                </span>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- Participant Details --}}
                <div class="rounded-2xl
                            bg-white/[0.04]
                            border border-white/10
                            p-6 md:p-8 mt-6">

                    <h2 class="text-xl font-bold mb-5">
                        Participant
                        <span class="text-pink-500">Details</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <span class="block text-gray-600 text-[11px] mb-1">
                                Name
                            </span>

                            <span class="text-sm text-gray-300">
                                {{ $booking->name }}
                            </span>
                        </div>


                        <div>
                            <span class="block text-gray-600 text-[11px] mb-1">
                                Phone
                            </span>

                            <span class="text-sm text-gray-300">
                                {{ $booking->phone }}
                            </span>
                        </div>


                        @if($booking->email)

                            <div>
                                <span class="block text-gray-600 text-[11px] mb-1">
                                    Email
                                </span>

                                <span class="text-sm text-gray-300">
                                    {{ $booking->email }}
                                </span>
                            </div>

                        @endif


                        @if($booking->father_name)

                            <div>
                                <span class="block text-gray-600 text-[11px] mb-1">
                                    Father Name
                                </span>

                                <span class="text-sm text-gray-300">
                                    {{ $booking->father_name }}
                                </span>
                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Payment --}}
            <div>

                <div class="lg:sticky lg:top-6
                            rounded-2xl
                            bg-white/[0.04]
                            border border-pink-500/20
                            p-6
                            shadow-2xl shadow-black/20">

                    <div class="mb-6">

                        <span class="text-gray-600 text-xs">
                            Amount Payable
                        </span>

                        <div class="flex items-end gap-1 mt-2">

                            <span class="text-gray-400 text-lg">
                                ₹
                            </span>

                            <span class="text-4xl font-extrabold text-white">
                                {{ number_format($booking->amount, 2) }}
                            </span>

                        </div>

                    </div>


                    <div class="border-t border-white/10 pt-5 mb-6">

                        <div class="flex items-center justify-between text-sm mb-3">

                            <span class="text-gray-500">
                                Event Fee
                            </span>

                            <span class="text-gray-300">
                                ₹{{ number_format($booking->amount, 2) }}
                            </span>

                        </div>


                        <div class="flex items-center justify-between
                                    text-base font-bold">

                            <span>
                                Total
                            </span>

                            <span class="text-pink-500">
                                ₹{{ number_format($booking->amount, 2) }}
                            </span>

                        </div>

                    </div>


                    {{-- Razorpay will be integrated here --}}
                    <button type="button"
                            disabled
                            class="w-full h-12
                                   rounded-lg
                                   bg-gradient-to-r from-pink-500 to-blue-500
                                   text-white text-sm font-bold
                                   opacity-60
                                   cursor-not-allowed">

                        Proceed to Payment

                        <svg class="inline-block w-4 h-4 ml-1"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>

                        </svg>

                    </button>


                    <div class="flex items-start gap-2 mt-4">

                        <svg class="w-4 h-4 shrink-0 text-gray-600 mt-0.5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>

                        </svg>

                        <p class="text-gray-600 text-[10px] leading-5">
                            Secure online payment will be available here.
                            Do not refresh or close the page during payment.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
