@extends('partials.master')

@section('title', $event->event_name)

@section('content')

@include('component.breadcrumbs')

@php

    $documentExtension = $event->document
        ? strtolower(pathinfo($event->document, PATHINFO_EXTENSION))
        : null;

    $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    $isImage = $documentExtension
        ? in_array($documentExtension, $imageExtensions)
        : false;

@endphp


<section class="bg-[#070b18] min-h-screen py-12 md:py-16 text-white">

    <div class="container mx-auto px-4 max-w-6xl">

        {{-- Back --}}
        <div class="mb-7">

            <a href="{{ route('website.events') }}"
               class="inline-flex items-center gap-2
                      text-gray-500 hover:text-pink-500
                      text-sm transition-colors">

                <svg class="w-4 h-4"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>

                Back to Events

            </a>

        </div>


        {{-- Event Header --}}
        <div class="relative overflow-hidden
                    rounded-3xl
                    bg-white/[0.04]
                    border border-white/10
                    p-6 md:p-10 mb-7">

            {{-- Decorative Glow --}}
            <div class="absolute -top-24 -right-20
                        w-64 h-64
                        rounded-full
                        bg-pink-500/10
                        blur-3xl
                        pointer-events-none">
            </div>

            @if($event->is_free)

                <div class="relative inline-flex items-center gap-2
                            px-3 py-1.5
                            rounded-full
                            bg-green-500/10
                            border border-green-500/20
                            text-green-400
                            text-xs font-bold
                            mb-5">

                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>

                    Free Event

                </div>

            @else

                <div class="relative inline-flex items-center gap-2
                            px-3 py-1.5
                            rounded-full
                            bg-yellow-500/10
                            border border-yellow-500/20
                            text-yellow-400
                            text-xs font-bold
                            mb-5">

                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>

                    Paid Event

                </div>

            @endif


            <h1 class="relative text-3xl md:text-5xl
                       font-extrabold leading-tight
                       mb-8 max-w-4xl">

                {{ $event->event_name }}

            </h1>


            {{-- Meta --}}
            <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

                {{-- Date --}}
                <div class="flex items-center gap-3
                            rounded-xl
                            bg-black/20
                            border border-white/[0.06]
                            p-4">

                    <div class="w-10 h-10 shrink-0
                                rounded-lg
                                bg-pink-500/10
                                text-pink-500
                                flex items-center justify-center">

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

                        <span class="block text-gray-600 text-[11px] mb-1">
                            Event Date
                        </span>

                        <strong class="text-sm">
                            {{ $event->event_date->format('d M Y') }}
                        </strong>

                    </div>

                </div>


                {{-- Time --}}
                @if($event->event_time)

                    <div class="flex items-center gap-3
                                rounded-xl
                                bg-black/20
                                border border-white/[0.06]
                                p-4">

                        <div class="w-10 h-10 shrink-0
                                    rounded-lg
                                    bg-blue-500/10
                                    text-blue-400
                                    flex items-center justify-center">

                            <svg class="w-5 h-5"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <circle cx="12" cy="12" r="9"></circle>
                                <polyline points="12 7 12 12 15 15"></polyline>
                            </svg>

                        </div>

                        <div>

                            <span class="block text-gray-600 text-[11px] mb-1">
                                Event Time
                            </span>

                            <strong class="text-sm">
                                {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                            </strong>

                        </div>

                    </div>

                @endif


                {{-- Venue --}}
                <div class="flex items-center gap-3
                            rounded-xl
                            bg-black/20
                            border border-white/[0.06]
                            p-4">

                    <div class="w-10 h-10 shrink-0
                                rounded-lg
                                bg-pink-500/10
                                text-pink-500
                                flex items-center justify-center">

                        <svg class="w-5 h-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 1 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>

                    </div>

                    <div class="min-w-0">

                        <span class="block text-gray-600 text-[11px] mb-1">
                            Venue
                        </span>

                        <strong class="text-sm truncate block">
                            {{ $event->venue_details ?: 'To be announced' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- Content + Join Form --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">

            {{-- Left Content --}}
            <div class="lg:col-span-2">


                {{-- Description --}}
                <div class="rounded-2xl
                            bg-white/[0.04]
                            border border-white/10
                            p-6 md:p-8
                            mb-7">

                    <h2 class="text-2xl font-bold mb-5">
                        About
                        <span class="text-pink-500">Event</span>
                    </h2>

                    <div class="text-gray-400 text-sm md:text-base leading-8">
                        {!! nl2br(e($event->description)) !!}
                    </div>

                </div>


                {{-- Location --}}
                @if($event->venue_details || $event->address || $event->city || $event->state || $event->pincode)

                    <div class="rounded-2xl
                                bg-white/[0.04]
                                border border-white/10
                                p-6 md:p-8
                                mb-7">

                        <h2 class="text-2xl font-bold mb-5">
                            Event
                            <span class="text-pink-500">Location</span>
                        </h2>


                        <div class="flex gap-4
                                    rounded-xl
                                    bg-blue-500/[0.04]
                                    border border-blue-500/10
                                    p-5">

                            <div class="w-10 h-10 shrink-0
                                        rounded-lg
                                        bg-blue-500/10
                                        text-blue-400
                                        flex items-center justify-center">

                                <svg class="w-5 h-5"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 1 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>

                            </div>

                            <div>

                                @if($event->venue_details)

                                    <h3 class="font-bold text-white mb-2">
                                        {{ $event->venue_details }}
                                    </h3>

                                @endif

                                <p class="text-gray-400 text-sm leading-7">

                                    @if($event->address)
                                        {{ $event->address }}
                                    @endif

                                    @if($event->city)
                                        {{ $event->address ? ', ' : '' }}{{ $event->city }}
                                    @endif

                                    @if($event->state)
                                        {{ ($event->address || $event->city) ? ', ' : '' }}{{ $event->state }}
                                    @endif

                                    @if($event->pincode)
                                        {{ ($event->address || $event->city || $event->state) ? ' - ' : '' }}{{ $event->pincode }}
                                    @endif

                                </p>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- Document --}}
                @if($event->document)

                    <div class="rounded-2xl
                                bg-white/[0.04]
                                border border-white/10
                                p-6 md:p-8">

                        <h2 class="text-2xl font-bold mb-5">
                            Event
                            <span class="text-pink-500">Document</span>
                        </h2>


                        {{-- Image --}}
                        @if($isImage)

                            <div class="rounded-xl overflow-hidden
                                        bg-black/30
                                        border border-white/10
                                        mb-5">

                                <img src="{{ asset('events/' . $event->document) }}"
                                     alt="{{ $event->event_name }}"
                                     class="w-full max-h-[500px] object-contain">

                            </div>

                        @endif


                        {{-- Document Box --}}
                        <div class="flex flex-col sm:flex-row
                                    items-start sm:items-center
                                    justify-between gap-4
                                    rounded-xl
                                    bg-white/[0.03]
                                    border border-white/10
                                    p-4">

                            <div class="flex items-center gap-3 min-w-0">

                                <div class="w-11 h-11 shrink-0
                                            rounded-lg
                                            bg-pink-500/10
                                            text-pink-500
                                            flex items-center justify-center">

                                    @if($isImage)

                                        <svg class="w-5 h-5"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>

                                    @else

                                        <svg class="w-5 h-5"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                        </svg>

                                    @endif

                                </div>


                                <div class="min-w-0">

                                    <div class="text-sm font-semibold truncate max-w-[250px]">
                                        {{ $event->document }}
                                    </div>

                                    <div class="text-gray-600 text-[11px] mt-1">
                                        {{ strtoupper($documentExtension) }} Document
                                    </div>

                                </div>

                            </div>


                            <a href="{{ asset('events/' . $event->document) }}"
                               target="_blank"
                               class="w-full sm:w-auto
                                      inline-flex items-center justify-center gap-2
                                      px-4 py-2.5
                                      rounded-lg
                                      bg-blue-500/10
                                      border border-blue-500/20
                                      text-blue-400
                                      hover:bg-blue-500/20
                                      text-xs font-bold
                                      transition">

                                View Document

                                <svg class="w-4 h-4"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>

                            </a>

                        </div>

                    </div>

                @endif

            </div>


            {{-- Join Form --}}
            <div>

                <div class="lg:sticky lg:top-6
                            rounded-2xl
                            bg-white/[0.04]
                            border border-pink-500/20
                            p-6
                            shadow-2xl shadow-black/20">

                    <div class="mb-6">

                        <h2 class="text-xl font-extrabold mb-2">
                            Join This
                            <span class="text-pink-500">Event</span>
                        </h2>

                        <p class="text-gray-500 text-xs leading-6">
                            Fill in your details below to register
                            your interest in this event.
                        </p>

                    </div>

                    <form action="{{ route('website.event-booking.store') }}" method="POST">
                        @csrf

                         <input type="hidden" name="event_id" value="{{ $event->id }}">

                        {{-- Name --}}
                        <div class="mb-4">

                            <label class="block text-gray-300 text-xs font-semibold mb-2">
                                Name
                                <span class="text-pink-500">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   placeholder="Enter your name"
                                   required
                                   class="w-full h-11 px-3
                                          rounded-lg
                                          bg-[#0b1020]
                                          border border-white/10
                                          text-white text-sm
                                          placeholder-gray-600
                                          outline-none
                                          focus:border-pink-500/50
                                          focus:ring-2
                                          focus:ring-pink-500/10
                                          transition">

                        </div>


                        {{-- WhatsApp / Phone --}}
                        <div class="mb-4">

                            <label class="block text-gray-300 text-xs font-semibold mb-2">
                                WhatsApp / Phone Number
                                <span class="text-pink-500">*</span>
                            </label>

                            <input type="tel"
                                   name="phone"
                                   placeholder="Enter phone number"
                                   required
                                   class="w-full h-11 px-3
                                          rounded-lg
                                          bg-[#0b1020]
                                          border border-white/10
                                          text-white text-sm
                                          placeholder-gray-600
                                          outline-none
                                          focus:border-pink-500/50
                                          focus:ring-2
                                          focus:ring-pink-500/10
                                          transition">

                        </div>


                        {{-- Email --}}
                        <div class="mb-4">

                            <label class="block text-gray-300 text-xs font-semibold mb-2">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   placeholder="Enter email address"
                                   class="w-full h-11 px-3
                                          rounded-lg
                                          bg-[#0b1020]
                                          border border-white/10
                                          text-white text-sm
                                          placeholder-gray-600
                                          outline-none
                                          focus:border-pink-500/50
                                          focus:ring-2
                                          focus:ring-pink-500/10
                                          transition">

                        </div>


                        {{-- Father Name --}}
                        <div class="mb-4">

                            <label class="block text-gray-300 text-xs font-semibold mb-2">
                                Father Name
                            </label>

                            <input type="text"
                                   name="father_name"
                                   placeholder="Enter father's name"
                                   class="w-full h-11 px-3
                                          rounded-lg
                                          bg-[#0b1020]
                                          border border-white/10
                                          text-white text-sm
                                          placeholder-gray-600
                                          outline-none
                                          focus:border-pink-500/50
                                          focus:ring-2
                                          focus:ring-pink-500/10
                                          transition">

                        </div>


                        {{-- State + City --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                            <div class="mb-4">

                                <label class="block text-gray-300 text-xs font-semibold mb-2">
                                    State
                                </label>

                                <input type="text"
                                       name="state"
                                       placeholder="State"
                                       class="w-full h-11 px-3
                                              rounded-lg
                                              bg-[#0b1020]
                                              border border-white/10
                                              text-white text-sm
                                              placeholder-gray-600
                                              outline-none
                                              focus:border-pink-500/50
                                              focus:ring-2
                                              focus:ring-pink-500/10
                                              transition">

                            </div>


                            <div class="mb-4">

                                <label class="block text-gray-300 text-xs font-semibold mb-2">
                                    City
                                </label>

                                <input type="text"
                                       name="city"
                                       placeholder="City"
                                       class="w-full h-11 px-3
                                              rounded-lg
                                              bg-[#0b1020]
                                              border border-white/10
                                              text-white text-sm
                                              placeholder-gray-600
                                              outline-none
                                              focus:border-pink-500/50
                                              focus:ring-2
                                              focus:ring-pink-500/10
                                              transition">

                            </div>

                        </div>


                        {{-- Pincode --}}
                        <div class="mb-4">

                            <label class="block text-gray-300 text-xs font-semibold mb-2">
                                Pincode
                            </label>

                            <input type="text"
                                   name="pincode"
                                   placeholder="Pincode"
                                   class="w-full h-11 px-3
                                          rounded-lg
                                          bg-[#0b1020]
                                          border border-white/10
                                          text-white text-sm
                                          placeholder-gray-600
                                          outline-none
                                          focus:border-pink-500/50
                                          focus:ring-2
                                          focus:ring-pink-500/10
                                          transition">

                        </div>


                        {{-- Address --}}
                        <div class="mb-4">

                            <label class="block text-gray-300 text-xs font-semibold mb-2">
                                Address
                            </label>

                            <textarea name="address"
                                      rows="3"
                                      placeholder="Enter your address"
                                      class="w-full px-3 py-3
                                             rounded-lg
                                             bg-[#0b1020]
                                             border border-white/10
                                             text-white text-sm
                                             placeholder-gray-600
                                             outline-none
                                             resize-none
                                             focus:border-pink-500/50
                                             focus:ring-2
                                             focus:ring-pink-500/10
                                             transition"></textarea>

                        </div>


                        {{-- Paid Amount --}}
                        @if(!$event->is_free)

                            <div class="mb-5">

                                <label class="block text-gray-300 text-xs font-semibold mb-2">
                                    Event Amount
                                </label>

                                <div class="relative">

                                    <span class="absolute left-3 top-1/2 -translate-y-1/2
                                                 text-yellow-400 font-bold text-sm">
                                        ₹
                                    </span>

                                    <input type="text"
                                           value="{{ number_format($event->event_amount ?? 0, 2) }}"
                                           readonly
                                           class="w-full h-11 pl-8 pr-3
                                                  rounded-lg
                                                  bg-yellow-500/[0.05]
                                                  border border-yellow-500/20
                                                  text-yellow-400
                                                  font-bold text-sm
                                                  outline-none">

                                </div>

                            </div>

                        @endif


                        {{-- Submit --}}
                        <button type="submit"
                                class="w-full h-12
                                       rounded-lg
                                       bg-gradient-to-r from-pink-500 to-blue-500
                                       hover:from-pink-600 hover:to-blue-600
                                       text-white text-sm font-bold
                                       transition-all duration-300
                                       hover:shadow-lg hover:shadow-pink-500/20">

                            Join Event

                            <svg class="inline-block w-4 h-4 ml-1"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>

                        </button>


                        <p class="text-center text-gray-600 text-[10px] mt-3">
                            <span class="text-pink-500">*</span>
                            Required fields
                        </p>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
