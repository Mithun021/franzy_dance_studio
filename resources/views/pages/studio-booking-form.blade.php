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

                                <p class="text-gray-500 uppercase tracking-widest text-xs mb-3">
                                    Booking Price
                                </p>

                                <div class="flex flex-wrap gap-4">

                                    {{-- Per Day --}}
                                    @if($studio->price_per_day)
                                        <div>
                                            <p class="text-gray-400 text-sm mb-1">
                                                Per Day
                                            </p>

                                            <h2 class="text-4xl font-black bg-gradient-to-r from-pink-500 to-blue-500 bg-clip-text text-transparent">
                                                ₹{{ number_format($studio->price_per_day, 2) }}
                                            </h2>
                                        </div>
                                    @endif

                                    {{-- Per Hour --}}
                                    @if($studio->price_per_hour)
                                        <div>
                                            <p class="text-gray-400 text-sm mb-1">
                                                Per Hour
                                            </p>

                                            <h2 class="text-4xl font-black bg-gradient-to-r from-blue-500 to-cyan-400 bg-clip-text text-transparent">
                                                ₹{{ number_format($studio->price_per_hour, 2) }}
                                            </h2>
                                        </div>
                                    @endif

                                </div>

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
                        action="{{ route('studio.booking.store',$studio->id) }}"
                        id="studioBookingForm">

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
                                            value="{{ old('customer_name', $student->name ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student && filled($student->name)) readonly @endif>

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
                                            value="{{ old('email', $student->email ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student && filled($student->email)) readonly @endif>

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
                                            value="{{ old('phone', $student->phone ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student && filled($student->phone)) readonly @endif>

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
                                            value="{{ old('city', $student->city ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student && filled($student->city)) readonly @endif>

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
                                            value="{{ old('state', $student->state ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student && filled($student->state)) readonly @endif>

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
                                            value="{{ old('pincode', $student->pincode ?? '') }}"
                                            class="w-full rounded-xl border border-gray-700 bg-black/30 px-5 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition"
                                            @if($student && filled($student->pincode)) readonly @endif>

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
                                            @if($student && filled($student->address)) readonly @endif>{{ old('address', $student->address ?? '') }}</textarea>

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


                                {{-- Booking Type --}}
                                <div class="mt-8">

                                    <label class="block mb-3 text-gray-300">
                                        Booking For
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div class="grid md:grid-cols-2 gap-5">

                                        {{-- Per Day --}}
                                        <label
                                            class="booking-type-option cursor-pointer rounded-xl border border-white/10 bg-white/5 p-5 transition hover:border-pink-500/50">

                                            <input
                                                type="radio"
                                                name="booking_type"
                                                value="day"
                                                class="hidden booking-type"
                                                {{ old('booking_type','day') == 'day' ? 'checked' : '' }}>

                                            <div class="flex items-center gap-4">

                                                <div class="booking-radio w-5 h-5 rounded-full border-2 border-gray-500 flex items-center justify-center">

                                                    <div class="booking-radio-dot hidden w-2.5 h-2.5 rounded-full bg-pink-500"></div>

                                                </div>

                                                <div>

                                                    <p class="text-white font-semibold">
                                                        Per Day
                                                    </p>

                                                    <p class="text-gray-400 text-sm mt-1">
                                                        ₹{{ number_format($studio->price_per_day,2) }} / day
                                                    </p>

                                                </div>

                                            </div>

                                        </label>


                                        {{-- Per Hour --}}
                                        <label
                                            class="booking-type-option cursor-pointer rounded-xl border border-white/10 bg-white/5 p-5 transition hover:border-blue-500/50">

                                            <input
                                                type="radio"
                                                name="booking_type"
                                                value="hour"
                                                class="hidden booking-type"
                                                {{ old('booking_type') == 'hour' ? 'checked' : '' }}>

                                            <div class="flex items-center gap-4">

                                                <div class="booking-radio w-5 h-5 rounded-full border-2 border-gray-500 flex items-center justify-center">

                                                    <div class="booking-radio-dot hidden w-2.5 h-2.5 rounded-full bg-blue-500"></div>

                                                </div>

                                                <div>

                                                    <p class="text-white font-semibold">
                                                        Per Hour
                                                    </p>

                                                    <p class="text-gray-400 text-sm mt-1">
                                                        ₹{{ number_format($studio->price_per_hour,2) }} / hour
                                                    </p>

                                                </div>

                                            </div>

                                        </label>

                                    </div>

                                    @error('booking_type')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror

                                </div>


                                {{-- ========================================================= --}}
                                {{-- Date & Time --}}
                                {{-- ========================================================= --}}

                                <div class="grid md:grid-cols-2 gap-6 mt-8">

                                    {{-- Booking From --}}
                                    <div>

                                        <label class="block mb-2 text-gray-300">
                                            Booking From
                                            <span class="text-red-500">*</span>
                                        </label>

                                        <div class="grid grid-cols-2 gap-3">

                                            <input
                                                type="date"
                                                name="booking_from_date"
                                                id="booking_from_date"
                                                value="{{ old('booking_from_date') }}"
                                                min="{{ date('Y-m-d') }}"
                                                required
                                                class="w-full rounded-xl border border-gray-700 bg-black/30 px-4 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition">

                                            <input
                                                type="time"
                                                name="booking_from_time"
                                                id="booking_from_time"
                                                value="{{ old('booking_from_time','09:00') }}"
                                                required
                                                class="w-full rounded-xl border border-gray-700 bg-black/30 px-4 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition">

                                        </div>

                                        @error('booking_from_date')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                        @error('booking_from_time')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                    </div>


                                    {{-- Booking To --}}
                                    <div>

                                        <label class="block mb-2 text-gray-300">
                                            Booking To
                                            <span class="text-red-500">*</span>
                                        </label>

                                        <div class="grid grid-cols-2 gap-3">

                                            <input
                                                type="date"
                                                name="booking_to_date"
                                                id="booking_to_date"
                                                value="{{ old('booking_to_date') }}"
                                                min="{{ date('Y-m-d') }}"
                                                required
                                                class="w-full rounded-xl border border-gray-700 bg-black/30 px-4 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition">

                                            <input
                                                type="time"
                                                name="booking_to_time"
                                                id="booking_to_time"
                                                value="{{ old('booking_to_time','18:00') }}"
                                                required
                                                class="w-full rounded-xl border border-gray-700 bg-black/30 px-4 py-3 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500 transition">

                                        </div>

                                        @error('booking_to_date')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                        @error('booking_to_time')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror

                                    </div>

                                </div>


                                {{-- ========================================================= --}}
                                {{-- Calculated Booking Amount --}}
                                {{-- ========================================================= --}}

                                <div
                                    id="bookingCalculation"
                                    class="mt-8 rounded-2xl border border-white/10 bg-gradient-to-r from-pink-500/10 to-blue-500/10 p-6">

                                    <div class="flex flex-col md:flex-row justify-between gap-6">

                                        <div>

                                            <p class="text-gray-400 text-sm uppercase tracking-widest">
                                                Booking Summary
                                            </p>

                                            <p
                                                id="calculationText"
                                                class="text-white text-lg font-semibold mt-2">
                                                Select booking dates and time
                                            </p>

                                        </div>


                                        <div class="text-left md:text-right">

                                            <p class="text-gray-400 text-sm">
                                                Calculated Amount
                                            </p>

                                            <h3
                                                id="calculatedAmount"
                                                class="text-4xl font-black mt-1 bg-gradient-to-r from-pink-500 to-blue-500 bg-clip-text text-transparent">
                                                ₹0
                                            </h3>

                                            <p class="text-gray-500 text-xs mt-1">
                                                Rounded off amount
                                            </p>

                                        </div>

                                    </div>

                                </div>


                                {{-- Hidden Calculated Amount --}}
                                <input
                                    type="hidden"
                                    name="calculated_amount"
                                    id="calculated_amount"
                                    value="0">

                                {{-- Hidden Calculated Duration --}}
                                <input
                                    type="hidden"
                                    name="calculated_duration"
                                    id="calculated_duration"
                                    value="0">

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
                                        required
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


{{-- ========================================================= --}}
{{-- Booking Calculation JavaScript --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const bookingForm = document.getElementById('studioBookingForm');

    const bookingTypes = document.querySelectorAll('.booking-type');
    const bookingOptions = document.querySelectorAll('.booking-type-option');

    const fromDate = document.getElementById('booking_from_date');
    const fromTime = document.getElementById('booking_from_time');

    const toDate = document.getElementById('booking_to_date');
    const toTime = document.getElementById('booking_to_time');

    const calculatedAmount = document.getElementById('calculatedAmount');
    const calculatedAmountInput = document.getElementById('calculated_amount');
    const calculatedDurationInput = document.getElementById('calculated_duration');
    const calculationText = document.getElementById('calculationText');


    /*
    |--------------------------------------------------------------------------
    | Studio Prices
    |--------------------------------------------------------------------------
    */

    const pricePerDay = {{ (float) $studio->price_per_day }};
    const pricePerHour = {{ (float) $studio->price_per_hour }};


    /*
    |--------------------------------------------------------------------------
    | Format Currency
    |--------------------------------------------------------------------------
    */

    function formatCurrency(amount) {

        return '₹' + Math.round(amount).toLocaleString('en-IN');

    }


    /*
    |--------------------------------------------------------------------------
    | Get Selected Booking Type
    |--------------------------------------------------------------------------
    */

    function getBookingType() {

        const selected = document.querySelector(
            'input[name="booking_type"]:checked'
        );

        return selected ? selected.value : null;

    }


    /*
    |--------------------------------------------------------------------------
    | Update Booking Type UI
    |--------------------------------------------------------------------------
    */

    function updateBookingTypeUI() {

        bookingOptions.forEach(option => {

            const radio = option.querySelector('.booking-type');

            const dot = option.querySelector('.booking-radio-dot');

            const circle = option.querySelector('.booking-radio');


            if (radio.checked) {

                dot.classList.remove('hidden');

                circle.classList.remove('border-gray-500');

                circle.classList.add('border-pink-500');

                option.classList.add(
                    'border-pink-500/50',
                    'bg-pink-500/10'
                );

            } else {

                dot.classList.add('hidden');

                circle.classList.remove('border-pink-500');

                circle.classList.add('border-gray-500');

                option.classList.remove(
                    'border-pink-500/50',
                    'bg-pink-500/10'
                );

            }

        });

        calculateBooking();

    }


    /*
    |--------------------------------------------------------------------------
    | Reset Calculation
    |--------------------------------------------------------------------------
    */

    function resetCalculation(message = 'Select booking dates and time') {

        calculatedAmount.textContent = '₹0';

        calculationText.textContent = message;

        calculatedAmountInput.value = 0;

        calculatedDurationInput.value = 0;

    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Booking
    |--------------------------------------------------------------------------
    */

    function calculateBooking() {

        const bookingType = getBookingType();

        const startDateValue = fromDate.value;
        const startTimeValue = fromTime.value;

        const endDateValue = toDate.value;
        const endTimeValue = toTime.value;


        /*
        |--------------------------------------------------------------------------
        | Booking Type Check
        |--------------------------------------------------------------------------
        */

        if (!bookingType) {

            resetCalculation('Please select booking type');

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Date & Time Check
        |--------------------------------------------------------------------------
        */

        if (
            !startDateValue ||
            !startTimeValue ||
            !endDateValue ||
            !endTimeValue
        ) {

            resetCalculation('Select booking dates and time');

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Create Date Objects
        |--------------------------------------------------------------------------
        */

        const start = new Date(
            `${startDateValue}T${startTimeValue}`
        );

        const end = new Date(
            `${endDateValue}T${endTimeValue}`
        );


        /*
        |--------------------------------------------------------------------------
        | Invalid Date
        |--------------------------------------------------------------------------
        */

        if (
            isNaN(start.getTime()) ||
            isNaN(end.getTime())
        ) {

            resetCalculation('Invalid booking date or time');

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | End Must Be After Start
        |--------------------------------------------------------------------------
        */

        if (end <= start) {

            resetCalculation(
                'Booking To must be after Booking From'
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Difference
        |--------------------------------------------------------------------------
        */

        const differenceMilliseconds =
            end.getTime() - start.getTime();

        const differenceMinutes =
            differenceMilliseconds / (1000 * 60);

        const differenceHours =
            differenceMinutes / 60;

        const differenceDays =
            differenceHours / 24;


        let amount = 0;

        let duration = 0;

        let summary = '';


        /*
        |--------------------------------------------------------------------------
        | PER DAY
        |--------------------------------------------------------------------------
        */

        if (bookingType === 'day') {

            /*
             * Example:
             *
             * 20 Aug 09:00 → 21 Aug 09:00
             * = 24 Hours
             * = 1 Day
             *
             * 20 Aug 09:00 → 22 Aug 09:00
             * = 48 Hours
             * = 2 Days
             *
             * 20 Aug 09:00 → 21 Aug 18:00
             * = 33 Hours
             * = 1.375 Days
             * = 2 Days (ceil)
             */

            duration = Math.max(
                1,
                Math.ceil(differenceDays)
            );


            /*
             * Calculate Amount
             */

            amount = duration * pricePerDay;


            /*
             * Summary
             */

            summary =
                `${duration} ${duration === 1 ? 'Day' : 'Days'} × ${formatCurrency(pricePerDay)}`;

        }


        /*
        |--------------------------------------------------------------------------
        | PER HOUR
        |--------------------------------------------------------------------------
        */

        if (bookingType === 'hour') {

            /*
             * Exact hourly calculation.
             *
             * Example:
             *
             * 09:00 → 10:00 = 1 Hour
             * 09:00 → 11:30 = 2 Hours 30 Minutes
             */

            duration = differenceHours;


            /*
             * Calculate Amount
             */

            amount = duration * pricePerHour;


            /*
             * Display Hours & Minutes
             */

            const totalMinutes =
                Math.round(differenceMinutes);

            const fullHours =
                Math.floor(totalMinutes / 60);

            const minutes =
                totalMinutes % 60;


            if (minutes > 0) {

                summary =
                    `${fullHours} ${fullHours === 1 ? 'Hour' : 'Hours'} ${minutes} Minutes × ${formatCurrency(pricePerHour)}`;

            } else {

                summary =
                    `${fullHours} ${fullHours === 1 ? 'Hour' : 'Hours'} × ${formatCurrency(pricePerHour)}`;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ROUND OFF
        |--------------------------------------------------------------------------
        |
        | Example:
        | ₹1250.75 → ₹1251
        | ₹1250.25 → ₹1250
        |
        */

        amount = Math.round(amount);


        /*
        |--------------------------------------------------------------------------
        | Update UI
        |--------------------------------------------------------------------------
        */

        calculatedAmount.textContent =
            formatCurrency(amount);

        calculationText.textContent =
            summary;


        /*
        |--------------------------------------------------------------------------
        | Hidden Fields
        |--------------------------------------------------------------------------
        */

        calculatedAmountInput.value =
            amount;

        calculatedDurationInput.value =
            duration;

    }


    /*
    |--------------------------------------------------------------------------
    | Booking Type Change
    |--------------------------------------------------------------------------
    */

    bookingTypes.forEach(radio => {

        radio.addEventListener('change', function () {

            updateBookingTypeUI();

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Date / Time Change Events
    |--------------------------------------------------------------------------
    */

    fromDate.addEventListener('change', function () {

        /*
         * To date cannot be before From date
         */

        toDate.min = fromDate.value;


        /*
         * If To Date is empty,
         * automatically set it equal to From Date
         */

        if (!toDate.value) {

            toDate.value = fromDate.value;

        }


        /*
         * If existing To Date is before From Date,
         * reset it
         */

        if (
            toDate.value &&
            toDate.value < fromDate.value
        ) {

            toDate.value = fromDate.value;

        }


        calculateBooking();

    });


    fromTime.addEventListener(
        'change',
        calculateBooking
    );


    toDate.addEventListener(
        'change',
        calculateBooking
    );


    toTime.addEventListener(
        'change',
        calculateBooking
    );


    /*
    |--------------------------------------------------------------------------
    | Form Submit Validation
    |--------------------------------------------------------------------------
    */

    bookingForm.addEventListener('submit', function (event) {

        /*
         * Calculate one final time before submit
         */

        calculateBooking();


        const amount =
            parseFloat(
                calculatedAmountInput.value || 0
            );


        const bookingType =
            getBookingType();


        /*
         * Booking Type Validation
         */

        if (!bookingType) {

            event.preventDefault();

            alert(
                'Please select whether you want to book Per Day or Per Hour.'
            );

            return false;

        }


        /*
         * Amount Validation
         */

        if (amount <= 0) {

            event.preventDefault();

            alert(
                'Please select valid booking dates and time.'
            );

            return false;

        }


        /*
         * Date Validation
         */

        const start = new Date(
            `${fromDate.value}T${fromTime.value}`
        );

        const end = new Date(
            `${toDate.value}T${toTime.value}`
        );


        if (
            isNaN(start.getTime()) ||
            isNaN(end.getTime())
        ) {

            event.preventDefault();

            alert(
                'Please select valid booking dates and time.'
            );

            return false;

        }


        /*
         * End must be after Start
         */

        if (end <= start) {

            event.preventDefault();

            alert(
                'Booking To date/time must be after Booking From date/time.'
            );

            return false;

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Initial State
    |--------------------------------------------------------------------------
    */

    updateBookingTypeUI();

    calculateBooking();

});

</script>

@endsection
