@extends('partials.master')

@section('title','Studio Booking')

@section('content')

@include('component.breadcrumbs')

<section class="py-20">

    <div class="max-w-7xl mx-auto px-5">

        {{-- ========================================================= --}}
        {{-- Page Heading --}}
        {{-- ========================================================= --}}

        <div class="text-center mb-14">

            <span class="inline-flex items-center px-5 py-2 rounded-full bg-pink-500/15 border border-pink-500/30 text-pink-400 text-sm tracking-widest uppercase">
                Studio Reservation
            </span>

            <h2 class="text-5xl font-extrabold text-white mt-5">

                Book Your

                <span class="bg-gradient-to-r from-pink-500 to-blue-500 bg-clip-text text-transparent">

                    Favorite Studio

                </span>

            </h2>

            <p class="text-gray-400 mt-5 max-w-3xl mx-auto">

                Fill out the booking form below. Our team will contact you shortly
                after verifying the availability of your selected studio.

            </p>

        </div>

        <div class="grid lg:grid-cols-3 gap-10">

            {{-- ========================================================= --}}
            {{-- Studio Details --}}
            {{-- ========================================================= --}}

            <div class="lg:col-span-1">

                <div class="sticky top-24">

                    <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl overflow-hidden">

                        {{-- Header --}}
                        <div class="px-8 py-6 border-b border-white/10">

                            <h3 class="text-2xl font-bold text-white">

                                Studio Details

                            </h3>

                            <p class="text-gray-400 text-sm mt-2">

                                Review the selected studio before submitting your booking.

                            </p>

                        </div>

                        {{-- Body --}}
                        <div class="p-8 space-y-8">

                            <div>

                                <p class="text-gray-500 uppercase tracking-widest text-xs mb-2">

                                    Studio Category

                                </p>

                                <h4 class="text-white text-2xl font-semibold">

                                    {{ $studio->category->name }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-gray-500 uppercase tracking-widest text-xs mb-2">

                                    Booking Price

                                </p>

                                <h2 class="text-5xl font-black bg-gradient-to-r from-pink-500 to-blue-500 bg-clip-text text-transparent">

                                    ₹{{ number_format($studio->price,2) }}

                                </h2>

                            </div>

                            <div>

                                <p class="text-gray-500 uppercase tracking-widest text-xs mb-3">

                                    Description

                                </p>

                                <div class="text-gray-300 leading-8">

                                    {!! nl2br(e($studio->description)) !!}

                                </div>

                            </div>

                            <div class="border-t border-white/10 pt-6">

                                <div class="flex justify-between mb-4">

                                    <span class="text-gray-400">

                                        Booking Status

                                    </span>

                                    <span class="px-4 py-1 rounded-full bg-yellow-500/20 text-yellow-400 text-sm">

                                        New Inquiry

                                    </span>

                                </div>

                                <div class="flex justify-between">

                                    <span class="text-gray-400">

                                        Payment

                                    </span>

                                    <span class="text-green-400">

                                        Pay After Confirmation

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- Booking Form --}}
            {{-- ========================================================= --}}

            <div class="lg:col-span-2">

                <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl">

                    {{-- Form Header --}}
                    <div class="px-10 py-8 border-b border-white/10">

                        <h3 class="text-3xl font-bold text-white">

                            Studio Booking Form

                        </h3>

                        <p class="text-gray-400 mt-2">

                            Fields marked with
                            <span class="text-red-500">*</span>
                            are mandatory.

                        </p>

                    </div>

                    <form method="POST"
                          action="{{ route('studio.booking.store',$studio->id) }}">

                        @csrf

                        <div class="p-10">

                            {{-- Validation Errors --}}
                            @if($errors->any())

                                <div class="mb-8 rounded-xl border border-red-500/30 bg-red-500/10 p-5">

                                    <ul class="space-y-2 text-red-400">

                                        @foreach($errors->all() as $error)

                                            <li>• {{ $error }}</li>

                                        @endforeach

                                    </ul>

                                </div>

                            @endif

                            {{-- ========================================================= --}}
                            {{-- Customer Information --}}
                            {{-- ========================================================= --}}

                            <div class="mb-10">

                                <h4 class="text-xl font-semibold text-white border-l-4 border-pink-500 pl-4">

                                    Customer Information

                                </h4>

                                <div class="grid md:grid-cols-2 gap-6 mt-8">

                                    {{-- Name --}}
                                    <div>

                                        <label class="block mb-2 text-gray-300">

                                            Full Name

                                            <span class="text-red-500">*</span>

                                        </label>

                                        <input
                                            type="text"
                                            name="customer_name"
                                            value="{{ old('customer_name',$student->name ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student) readonly @endif>

                                        @error('customer_name')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                    </div>

                                    {{-- Email --}}
                                    <div>

                                        <label class="block mb-2 text-gray-300">

                                            Email Address

                                            <span class="text-red-500">*</span>

                                        </label>

                                        <input
                                            type="email"
                                            name="email"
                                            value="{{ old('email',$student->email ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student) readonly @endif>

                                        @error('email')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                    </div>

                                    {{-- Phone --}}
                                    <div>

                                        <label class="block mb-2 text-gray-300">

                                            Mobile Number

                                            <span class="text-red-500">*</span>

                                        </label>

                                        <input
                                            type="text"
                                            name="phone"
                                            value="{{ old('phone',$student->phone ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student) readonly @endif>

                                        @error('phone')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                    </div>

                                                                        {{-- City --}}
                                    <div>

                                        <label class="block mb-2 text-gray-300">

                                            City

                                            <span class="text-red-500">*</span>

                                        </label>

                                        <input
                                            type="text"
                                            name="city"
                                            value="{{ old('city',$student->city ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student) readonly @endif>

                                        @error('city')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                    </div>

                                    {{-- State --}}
                                    <div>

                                        <label class="block mb-2 text-gray-300">

                                            State

                                            <span class="text-red-500">*</span>

                                        </label>

                                        <input
                                            type="text"
                                            name="state"
                                            value="{{ old('state',$student->state ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student) readonly @endif>

                                        @error('state')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                    </div>

                                    {{-- Pincode --}}
                                    <div>

                                        <label class="block mb-2 text-gray-300">

                                            Pincode

                                            <span class="text-red-500">*</span>

                                        </label>

                                        <input
                                            type="text"
                                            name="pincode"
                                            value="{{ old('pincode',$student->pincode ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student) readonly @endif>

                                        @error('pincode')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                    </div>

                                </div>

                            </div>

                            {{-- ========================================================= --}}
                            {{-- Address Information --}}
                            {{-- ========================================================= --}}

                            <div class="mb-10">

                                <h4 class="text-xl font-semibold text-white border-l-4 border-blue-500 pl-4">

                                    Address Information

                                </h4>

                                <div class="mt-8">

                                    <label class="block mb-2 text-gray-300">

                                        Full Address

                                        <span class="text-red-500">*</span>

                                    </label>

                                    <textarea
                                        name="address"
                                        rows="4"
                                        class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                        @if($student) readonly @endif>{{ old('address',$student->address ?? '') }}</textarea>

                                    @error('address')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror

                                </div>

                            </div>

                            {{-- ========================================================= --}}
                            {{-- Booking Information --}}
                            {{-- ========================================================= --}}

                            <div class="mb-10">

                                <h4 class="text-xl font-semibold text-white border-l-4 border-pink-500 pl-4">

                                    Booking Information

                                </h4>

                                <div class="grid md:grid-cols-2 gap-6 mt-8">

                                    {{-- Booking From --}}
                                    <div>

                                        <label class="block mb-2 text-gray-300">

                                            Booking From

                                            <span class="text-red-500">*</span>

                                        </label>

                                        <input
                                            type="date"
                                            name="booking_from_date"
                                            value="{{ old('booking_from_date') }}"
                                            min="{{ date('Y-m-d') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition">

                                        @error('booking_from_date')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                    </div>

                                    {{-- Booking To --}}
                                    <div>

                                        <label class="block mb-2 text-gray-300">

                                            Booking To

                                            <span class="text-gray-500 text-sm">(Optional)</span>

                                        </label>

                                        <input
                                            type="date"
                                            name="booking_to_date"
                                            value="{{ old('booking_to_date') }}"
                                            min="{{ date('Y-m-d') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition">

                                        @error('booking_to_date')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                    </div>

                                </div>

                            </div>

                            {{-- ========================================================= --}}
                            {{-- Additional Information --}}
                            {{-- ========================================================= --}}

                            <div>

                                <h4 class="text-xl font-semibold text-white border-l-4 border-blue-500 pl-4">

                                    Additional Information

                                </h4>

                                <div class="mt-8">

                                    <label class="block mb-2 text-gray-300">

                                        Remarks / Requirements

                                        <span class="text-red-500">*</span>

                                    </label>

                                    <textarea
                                        name="remarks"
                                        rows="5"
                                        placeholder="Write your booking requirements, event details, preferred timing, special requests, etc."
                                        class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white placeholder:text-gray-500 focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition">{{ old('remarks') }}</textarea>

                                    @error('remarks')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror

                                </div>

                            </div>

                        </div>

                        {{-- ========================================================= --}}
                        {{-- Submit Button --}}
                        {{-- ========================================================= --}}

                        <div class="px-10 py-8 border-t border-white/10 bg-white/[0.02]">

                            <div class="flex flex-col md:flex-row items-center justify-between gap-5">

                                <div>

                                    <h5 class="text-white font-semibold">

                                        Ready to Book?

                                    </h5>

                                    <p class="text-gray-400 text-sm mt-1">

                                        After submission, our team will verify the availability and contact you.

                                    </p>

                                </div>

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center px-10 py-4 rounded-xl font-semibold text-white bg-gradient-to-r from-pink-600 to-blue-600 hover:from-pink-500 hover:to-blue-500 hover:scale-105 transition-all duration-300 shadow-lg shadow-pink-500/30">

                                    Confirm Booking

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
