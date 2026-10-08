@extends('partials.master')

@section('title', 'Events')

@section('content')

@include('component.breadcrumbs')

<section class="bg-[#070b18] min-h-screen py-14 md:py-20 text-white">

    <div class="container mx-auto px-4">

        {{-- Header --}}
        <div class="max-w-3xl mx-auto text-center mb-12 md:mb-16">

            <span class="inline-block text-pink-500 text-xs md:text-sm font-bold uppercase tracking-[3px] mb-3">
                What's Happening
            </span>

            <h1 class="text-3xl md:text-5xl font-extrabold mb-4">
                Upcoming
                <span class="text-pink-500">Events</span>
            </h1>

            <p class="text-gray-400 text-sm md:text-base leading-7">
                Discover our upcoming events, workshops and programs.
                Join us and be a part of something meaningful.
            </p>

        </div>


        @if($events->count())

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($events as $event)

                    <div class="group h-full rounded-2xl overflow-hidden
                                bg-white/[0.04]
                                border border-white/10
                                hover:border-pink-500/40
                                transition-all duration-300
                                hover:-translate-y-2
                                shadow-xl shadow-black/10">

                        {{-- Top Gradient --}}
                        <div class="h-1 bg-gradient-to-r from-pink-500 to-blue-500"></div>

                        <div class="p-6">

                            {{-- Date --}}
                            <div class="flex items-center gap-3 mb-5">

                                <div class="w-12 h-12 shrink-0 rounded-xl
                                            bg-pink-500/10
                                            border border-pink-500/20
                                            flex items-center justify-center
                                            text-pink-500">

                                    <svg class="w-5 h-5"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>

                                </div>

                                <div>

                                    <div class="font-semibold text-white text-sm">
                                        {{ $event->event_date->format('d M Y') }}
                                    </div>

                                    <div class="text-gray-500 text-xs mt-1">

                                        @if($event->event_time)
                                            {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                                        @else
                                            Event Date
                                        @endif

                                    </div>

                                </div>

                            </div>


                            {{-- Event Name --}}
                            <h2 class="text-xl font-bold leading-7 mb-3
                                       text-white
                                       group-hover:text-pink-400
                                       transition-colors duration-300">

                                {{ $event->event_name }}

                            </h2>


                            {{-- Description --}}
                            <p class="text-gray-400 text-sm leading-6 mb-5">

                                {{ \Illuminate\Support\Str::words(strip_tags($event->description), 25, '...') }}

                            </p>


                            {{-- Venue --}}
                            <div class="flex items-center gap-2 text-gray-400 text-sm mb-6">

                                <svg class="w-4 h-4 shrink-0 text-blue-400"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 1 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>

                                <span class="truncate">
                                    {{ $event->venue_details ?: ($event->city ?: 'Venue details available') }}
                                </span>

                            </div>


                            {{-- Footer --}}
                            <div class="border-t border-white/10 pt-5
                                        flex items-center justify-between gap-3">

                                {{-- Event Type --}}
                                <div>

                                    @if($event->is_free)

                                        <span class="text-green-400 text-sm font-bold">
                                            Free Event
                                        </span>

                                        <span class="block text-gray-600 text-[11px] mt-1">
                                            No registration fee
                                        </span>

                                    @else

                                        <span class="text-yellow-400 text-sm font-bold">
                                            ₹{{ number_format($event->event_amount ?? 0, 2) }}
                                        </span>

                                        <span class="block text-gray-600 text-[11px] mt-1">
                                            Registration Fee
                                        </span>

                                    @endif

                                </div>


                                {{-- Details Button --}}
                                <a href="{{ route('website.event-details', [$event->slug, $event->id]) }}"
                                   class="inline-flex items-center gap-2
                                          px-4 py-2.5
                                          rounded-lg
                                          bg-gradient-to-r from-pink-500 to-blue-500
                                          hover:from-pink-600 hover:to-blue-600
                                          text-white text-xs font-bold
                                          transition-all duration-300
                                          hover:shadow-lg hover:shadow-pink-500/20">

                                    View Details

                                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"
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

                    </div>

                @endforeach

            </div>

        @else

            {{-- No Events --}}
            <div class="max-w-xl mx-auto text-center
                        py-16 px-6
                        rounded-2xl
                        bg-white/[0.03]
                        border border-dashed border-white/10">

                <div class="w-16 h-16 mx-auto mb-5
                            rounded-2xl
                            bg-pink-500/10
                            flex items-center justify-center
                            text-pink-500">

                    <svg class="w-8 h-8"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.5">
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>

                </div>

                <h3 class="text-xl font-bold mb-2">
                    No Events Available
                </h3>

                <p class="text-gray-500 text-sm">
                    There are currently no upcoming events.
                    Please check back later.
                </p>

            </div>

        @endif

    </div>

</section>

@endsection
