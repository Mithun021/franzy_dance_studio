@extends('partials.master')

@section('title', 'Studio Booking')

@section('content')

@include('component.breadcrumbs')

<section class="py-20">

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
