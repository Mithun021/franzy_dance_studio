@extends('partials.master')

@section('title', 'Booking Successful')

@section('content')

@include('component.breadcrumbs')

<section class="bg-[#070b18] min-h-screen py-16 text-white">

    <div class="container mx-auto px-4">

        <div class="max-w-xl mx-auto text-center">

            <div class="w-20 h-20 mx-auto mb-6
                        rounded-full
                        bg-green-500/10
                        border border-green-500/20
                        text-green-400
                        flex items-center justify-center">

                <svg class="w-10 h-10"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <polyline points="20 6 9 17 4 12"></polyline>

                </svg>

            </div>


            <span class="text-green-400 text-xs font-bold uppercase tracking-[3px]">
                Booking Confirmed
            </span>


            <h1 class="text-3xl md:text-4xl font-extrabold mt-3 mb-4">

                You're
                <span class="text-pink-500">
                    Registered!
                </span>

            </h1>


            <p class="text-gray-500 text-sm leading-7 mb-8">

                Your registration for
                <span class="text-gray-300 font-semibold">
                    {{ $booking->event->event_name }}
                </span>
                has been successfully completed.

            </p>


            <div class="rounded-2xl
                        bg-white/[0.04]
                        border border-white/10
                        p-6 mb-6">

                <div class="mb-5">

                    <span class="block text-gray-600 text-[11px] mb-2">
                        Booking ID
                    </span>

                    <span class="text-xl font-extrabold text-pink-500">
                        {{ $booking->booking_id }}
                    </span>

                </div>


                <div class="border-t border-white/10 pt-5">

                    <div class="text-sm font-semibold mb-2">
                        {{ $booking->event->event_name }}
                    </div>

                    <div class="text-gray-500 text-xs">

                        {{ $booking->event->event_date->format('d M Y') }}

                        @if($booking->event->event_time)
                            •
                            {{ \Carbon\Carbon::parse($booking->event->event_time)->format('h:i A') }}
                        @endif

                    </div>

                </div>

            </div>


            <a href="{{ route('website.events') }}"
               class="inline-flex items-center gap-2
                      px-6 py-3
                      rounded-lg
                      bg-gradient-to-r from-pink-500 to-blue-500
                      hover:from-pink-600 hover:to-blue-600
                      text-white text-sm font-bold
                      transition">

                Back to Events

                <svg class="w-4 h-4"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>

                </svg>

            </a>

        </div>

    </div>

</section>

@endsection
