@extends('partials.master')

@section('title', 'Studio Booking')

@section('content')

@include('component.breadcrumbs')

<section class="py-20">

    {{-- =========================================================
        BOOKING SEARCH
    ========================================================= --}}
    <div class="mb-16">

        <div class="max-w-4xl mx-auto">

            <div class="relative rounded-3xl border border-white/10
                        bg-white/5 backdrop-blur-xl
                        shadow-2xl overflow-hidden">

                {{-- Top Gradient Line --}}
                <div class="h-1 bg-gradient-to-r from-pink-500 via-purple-500 to-blue-500"></div>

                <div class="p-8 md:p-10">

                    {{-- Heading --}}
                    <div class="text-center mb-7">

                        <span class="inline-flex items-center gap-2
                                    px-4 py-2 rounded-full
                                    bg-blue-500/10
                                    border border-blue-500/20
                                    text-blue-400 text-xs
                                    tracking-widest uppercase">

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

                            Booking Search

                        </span>

                        <h3 class="text-2xl md:text-3xl font-bold text-white mt-4">

                            Check Your

                            <span class="bg-gradient-to-r from-pink-500 to-blue-500
                                        bg-clip-text text-transparent">

                                Studio Booking

                            </span>

                        </h3>

                        <p class="text-gray-400 mt-2 text-sm md:text-base">

                            Search your booking details using your Booking ID
                            or registered phone number.

                        </p>

                    </div>


                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('studio.booking.search') }}"
                        class="space-y-5">

                        <div class="grid md:grid-cols-2 gap-5">

                            {{-- Booking ID --}}
                            <div>

                                <label class="block text-sm font-medium text-gray-300 mb-2">

                                    Booking ID

                                </label>

                                <div class="relative">

                                    <div class="absolute inset-y-0 left-0
                                                flex items-center pl-4
                                                pointer-events-none">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-gray-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 7h10M7 11h10M7 15h6M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>

                                        </svg>

                                    </div>

                                    <input type="text"
                                        name="booking_id"
                                        value="{{ request('booking_id') }}"
                                        placeholder="Enter Booking ID"
                                        class="w-full pl-12 pr-4 py-4
                                                rounded-xl
                                                bg-black/30
                                                border border-white/10
                                                text-white
                                                placeholder-gray-500
                                                focus:outline-none
                                                focus:border-pink-500
                                                focus:ring-1
                                                focus:ring-pink-500
                                                transition">

                                </div>

                            </div>


                            {{-- Phone Number --}}
                            <div>

                                <label class="block text-sm font-medium text-gray-300 mb-2">

                                    Phone Number

                                </label>

                                <div class="relative">

                                    <div class="absolute inset-y-0 left-0
                                                flex items-center pl-4
                                                pointer-events-none">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-gray-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.49a1 1 0 01-.5 1.18l-2.1 1.05a11.04 11.04 0 005.5 5.5l1.05-2.1a1 1 0 011.18-.5l4.49 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C10.82 21 3 13.18 3 3V5z"/>

                                        </svg>

                                    </div>

                                    <input type="tel"
                                        name="phone"
                                        value="{{ request('phone') }}"
                                        placeholder="Enter Phone Number"
                                        class="w-full pl-12 pr-4 py-4
                                                rounded-xl
                                                bg-black/30
                                                border border-white/10
                                                text-white
                                                placeholder-gray-500
                                                focus:outline-none
                                                focus:border-blue-500
                                                focus:ring-1
                                                focus:ring-blue-500
                                                transition">

                                </div>

                            </div>

                        </div>


                        {{-- Search Button --}}
                        <div class="flex justify-center pt-2">

                            <button type="submit"
                                    class="inline-flex items-center justify-center
                                        gap-3 px-10 py-4
                                        rounded-xl
                                        font-semibold text-white
                                        bg-gradient-to-r from-pink-600 to-blue-600
                                        hover:scale-105
                                        active:scale-95
                                        duration-300
                                        shadow-lg
                                        shadow-pink-500/20">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>

                                </svg>

                                Search Booking

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <div class="max-w-7xl mx-auto px-5">

        {{-- Heading --}}
        <div class="text-center mb-16">

            <span class="inline-flex items-center px-5 py-2 rounded-full bg-pink-500/15 border border-pink-500/30 text-pink-400 text-sm tracking-widest uppercase">

                Studio Booking

            </span>

            <h2 class="text-5xl font-extrabold text-white mt-5">

                Choose Your

                <span class="bg-gradient-to-r from-pink-500 to-blue-500 bg-clip-text text-transparent">

                    Perfect Studio

                </span>

            </h2>

            <p class="text-gray-400 mt-5 max-w-3xl mx-auto">

                Explore our premium studios with modern interiors, professional lighting,
                spacious environment and affordable pricing.

            </p>

        </div>

        @forelse($studios as $studio)

            <div class="mb-12 rounded-3xl overflow-hidden border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl hover:border-pink-500/40 transition duration-500">

                @if($studio->thumbnail)

                    <div class="grid lg:grid-cols-2">

                        {{-- Image --}}
                        <div class="relative overflow-hidden">

                            <img
                                src="{{ asset('storage/'.$studio->thumbnail) }}"
                                class="w-full h-full object-cover min-h-[480px] hover:scale-110 duration-700"
                                alt="{{ $studio->category->name }}">

                            <div class="absolute inset-0 bg-gradient-to-r from-black/30 via-transparent to-transparent"></div>

                        </div>

                        {{-- Details --}}
                        <div class="p-10 flex flex-col justify-between">

                            <div>

                                {{-- <span class="inline-flex px-4 py-2 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300 text-sm">

                                    {{ $studio->category->name }}

                                </span> --}}

                                <h2 class="text-4xl font-bold text-white mt-6">

                                    {{ $studio->category->name }}

                                </h2>

                                <div class="w-24 h-1 bg-gradient-to-r from-pink-500 to-blue-500 rounded-full mt-5"></div>

                                <div class="mt-8 text-gray-300 leading-8">

                                    {!! nl2br(e($studio->description)) !!}

                                </div>

                            </div>

                            <div class="mt-12 flex flex-wrap justify-between items-center gap-6">

                                <div>

                                    <p class="text-gray-500 uppercase tracking-widest text-xs">

                                        Starting Price

                                    </p>

                                    <h3 class="text-5xl font-black mt-2 bg-gradient-to-r from-pink-500 to-blue-500 bg-clip-text text-transparent">

                                        ₹{{ number_format($studio->price,2) }}

                                    </h3>

                                </div>

                                <a href="{{ route('studio.booking.form',$studio->id) }}"

                                   class="inline-flex items-center gap-3 px-8 py-4 rounded-xl font-semibold text-white bg-gradient-to-r from-pink-600 to-blue-600 hover:scale-105 duration-300 shadow-lg shadow-pink-500/30">

                                    Book Now

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M9 5l7 7-7 7"/>

                                    </svg>

                                </a>

                            </div>

                        </div>

                    </div>

                @else

                    {{-- No Image Layout --}}

                    <div class="p-12">

                        <span class="inline-flex px-4 py-2 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300">

                            {{ $studio->category->name }}

                        </span>

                        <h2 class="text-4xl font-bold text-white mt-5">

                            {{ $studio->category->name }}

                        </h2>

                        <div class="w-24 h-1 bg-gradient-to-r from-pink-500 to-blue-500 rounded-full mt-5"></div>

                        <div class="mt-8 text-gray-300 leading-8">

                            {!! nl2br(e($studio->description)) !!}

                        </div>

                        <div class="mt-12 flex flex-wrap justify-between items-center gap-6">

                            <div>

                                <p class="text-gray-500 uppercase tracking-widest text-xs">

                                    Starting Price

                                </p>

                                <h3 class="text-5xl font-black mt-2 bg-gradient-to-r from-pink-500 to-blue-500 bg-clip-text text-transparent">

                                    ₹{{ number_format($studio->price,2) }}

                                </h3>

                            </div>

                            <a href="{{ route('studio.booking.form',$studio->id) }}"

                               class="inline-flex items-center gap-3 px-8 py-4 rounded-xl font-semibold text-white bg-gradient-to-r from-pink-600 to-blue-600 hover:scale-105 duration-300 shadow-lg shadow-pink-500/30">

                                Book Now

                            </a>

                        </div>

                    </div>

                @endif

            </div>

        @empty

            <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-20 text-center">

                <div class="text-7xl mb-6">

                    🎥

                </div>

                <h3 class="text-3xl font-bold text-white">

                    No Studio Available

                </h3>

                <p class="text-gray-400 mt-3">

                    New studios will be available very soon.

                </p>

            </div>

        @endforelse

    </div>

</section>

@endsection
