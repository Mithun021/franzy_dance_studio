@extends('partials.master')

@section('title', 'Studio Booking Payment')

@section('content')

@include('component.breadcrumbs')

<section class="py-20">



    <div class="max-w-7xl mx-auto px-5">

        @if(session('error'))

        <div class="mb-6 rounded-xl border border-red-500/40 bg-red-500/10 p-5 text-red-300">

            {{ session('error') }}

        </div>

        @endif

        {{-- ========================================================= --}}
        {{-- Validation Errors --}}
        {{-- ========================================================= --}}

        @if ($errors->any())

            <div class="mb-8 rounded-2xl border border-red-500/30 bg-red-500/10 p-6">

                <div class="flex items-center mb-4">

                    <svg class="w-6 h-6 text-red-400 mr-2"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67
                            1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46
                            0L3.34 16c-.77 1.33.19 3 1.73 3z"/>

                    </svg>

                    <h4 class="text-red-400 font-semibold text-lg">

                        Please fix the following errors

                    </h4>

                </div>

                <ul class="space-y-2 text-red-200">

                    @foreach ($errors->all() as $error)

                        <li>

                            • {{ $error }}

                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- Success Message --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="mb-8 rounded-2xl border border-green-500/30 bg-green-500/10 p-6">

                <div class="flex items-center">

                    <svg class="w-7 h-7 text-green-400 mr-3"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"/>

                    </svg>

                    <p class="text-green-300 text-lg">

                        {{ session('success') }}

                    </p>

                </div>

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- Main Grid --}}
        {{-- ========================================================= --}}

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- ========================================================= --}}
            {{-- Left Side --}}
            {{-- ========================================================= --}}

            <div class="lg:col-span-2 space-y-8">

                {{-- ========================================================= --}}
                {{-- Booking Success --}}
                {{-- ========================================================= --}}

                <div class="rounded-3xl border border-green-500/30 bg-green-500/10 backdrop-blur-xl p-8">

                    <div class="flex items-center gap-5">

                        <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center">

                            <svg class="w-9 h-9 text-white"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 13l4 4L19 7"/>

                            </svg>

                        </div>

                        <div>

                            <h2 class="text-3xl font-bold text-white">

                                Booking Created Successfully

                            </h2>

                            <p class="text-gray-300 mt-2">

                                Your booking has been generated successfully.
                                Please complete your payment to confirm your studio reservation.

                            </p>

                        </div>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- Booking Information --}}
                {{-- ========================================================= --}}

                <div class="rounded-3xl bg-white/5 border border-white/10 backdrop-blur-xl">

                    <div class="border-b border-white/10 px-8 py-6">

                        <h3 class="text-2xl font-bold text-white">

                            Booking Information

                        </h3>

                    </div>

                    <div class="p-8">

                        <table class="w-full">

                            <tbody class="divide-y divide-white/10">

                                <tr>

                                    <td class="py-4 text-gray-400 w-1/2">

                                        Booking ID

                                    </td>

                                    <td class="py-4 text-right">

                                        <span class="font-semibold text-pink-400">

                                            {{ $booking->booking_id }}

                                        </span>

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Studio Category

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $booking->studio->category->name }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Booking Status

                                    </td>

                                    <td class="py-4 text-right">

                                        <span class="inline-flex rounded-full bg-yellow-500/20 px-4 py-1 text-sm font-semibold text-yellow-400">

                                            {{ $booking->enquiry_status }}

                                        </span>

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Booking From

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ \Carbon\Carbon::parse($booking->booking_from_date)->format('d M Y') }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Booking To

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        @if($booking->booking_to_date)

                                            {{ \Carbon\Carbon::parse($booking->booking_to_date)->format('d M Y') }}

                                        @else

                                            -

                                        @endif

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Created On

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $booking->created_at->format('d M Y h:i A') }}

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- Customer Details Card --}}
                {{-- Part-2 Starts From Here --}}
                {{-- ========================================================= --}}

                                {{-- ========================================================= --}}
                {{-- Customer Details --}}
                {{-- ========================================================= --}}

                <div class="rounded-3xl bg-white/5 border border-white/10 backdrop-blur-xl">

                    <div class="border-b border-white/10 px-8 py-6">

                        <h3 class="text-2xl font-bold text-white">

                            Customer Information

                        </h3>

                    </div>

                    <div class="p-8">

                        <table class="w-full">

                            <tbody class="divide-y divide-white/10">

                                <tr>

                                    <td class="py-4 text-gray-400 w-1/2">

                                        Customer Name

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $booking->customer_name }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Email Address

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $booking->email }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Mobile Number

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $booking->phone }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        City

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $booking->city }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        State

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $booking->state }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Pincode

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $booking->pincode }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400 align-top">

                                        Address

                                    </td>

                                    <td class="py-4 text-right text-white leading-7">

                                        {{ $booking->address }}

                                    </td>

                                </tr>

                                @if($booking->remarks)

                                <tr>

                                    <td class="py-4 text-gray-400 align-top">

                                        Remarks

                                    </td>

                                    <td class="py-4 text-right text-white leading-7">

                                        {{ $booking->remarks }}

                                    </td>

                                </tr>

                                @endif

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- Right Sidebar --}}
            {{-- ========================================================= --}}

            <div>

                <div class="sticky top-24 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-xl overflow-hidden">

                    {{-- Header --}}

                    <div class="px-8 py-6 border-b border-white/10">

                        <h3 class="text-2xl font-bold text-white">

                            Payment Summary

                        </h3>

                    </div>

                    {{-- Payment Form Starts --}}

                    <form
                        action="{{ route('studio.payment.store') }}"
                        method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <input
                            type="hidden"
                            name="booking_id"
                            value="{{ $booking->id }}">

                        <div class="p-8">

                            {{-- Per Day Charge --}}

                            <div class="flex justify-between items-start mb-6">

                                <div>

                                    <p class="text-gray-400">

                                        Per Day Charge

                                    </p>

                                    <span class="text-xs text-gray-500">

                                        Studio Rental

                                    </span>

                                </div>

                                <div class="text-right">

                                    <p class="text-white font-semibold">

                                        ₹{{ number_format($booking->studio_amount,2) }}

                                    </p>

                                </div>

                            </div>

                            {{-- Total Days --}}

                            <div class="flex justify-between items-start mb-6">

                                <div>

                                    <p class="text-gray-400">

                                        Total Days

                                    </p>

                                </div>

                                <div>

                                    <span class="text-blue-400 font-bold">

                                        {{ $totalDays }}

                                        {{ $totalDays > 1 ? 'Days' : 'Day' }}

                                    </span>

                                </div>

                            </div>

                            {{-- Total Amount --}}

                            <div class="flex justify-between items-start mb-6">

                                <div>

                                    <p class="text-gray-400">

                                        Total Amount

                                    </p>

                                </div>

                                <div>

                                    <span class="text-white text-lg font-bold">

                                        ₹{{ number_format($totalAmount,2) }}

                                    </span>

                                </div>

                            </div>

                            {{-- Paid Amount --}}

                            <div class="flex justify-between items-start mb-6">

                                <div>

                                    <p class="text-gray-400">

                                        Paid Amount

                                    </p>

                                </div>

                                <div>

                                    <span class="text-green-400 font-semibold">

                                        ₹{{ number_format($paidAmount,2) }}

                                    </span>

                                </div>

                            </div>

                            {{-- Due Amount --}}

                            <div class="flex justify-between items-center border-t border-white/10 pt-6">

                                <div>

                                    <p class="text-lg text-white font-semibold">

                                        Due Amount

                                    </p>

                                </div>

                                <div>

                                    <span class="text-3xl font-bold text-pink-400">

                                        ₹{{ number_format($dueAmount,2) }}

                                    </span>

                                </div>

                            </div>

                            {{-- ========================================================= --}}
                            {{-- Payment Method --}}
                            {{-- Part 3 Starts From Here --}}
                            {{-- ========================================================= --}}

                                                        {{-- ========================================================= --}}
                            {{-- Payment Method --}}
                            {{-- ========================================================= --}}

                            <div class="mt-10">

                                <label class="block text-white text-lg font-semibold mb-5">

                                    Select Payment Method
                                    <span class="text-red-500">*</span>

                                </label>

                                <div class="space-y-4">

                                    {{-- Online Payment --}}
                                    <label class="flex items-center gap-4 rounded-xl border border-white/10 bg-white/5 px-5 py-4 cursor-pointer hover:border-pink-500 transition">

                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="online"
                                            class="paymentMethod w-5 h-5 accent-pink-500"
                                            checked>

                                        <div>

                                            <h4 class="text-white font-semibold">

                                                Online Payment

                                            </h4>

                                            <p class="text-gray-400 text-sm">

                                                Secure payment using Payment Gateway

                                            </p>

                                        </div>

                                    </label>

                                    {{-- QR Payment --}}
                                    <label class="flex items-center gap-4 rounded-xl border border-white/10 bg-white/5 px-5 py-4 cursor-pointer hover:border-pink-500 transition">

                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="qr"
                                            class="paymentMethod w-5 h-5 accent-pink-500">

                                        <div>

                                            <h4 class="text-white font-semibold">

                                                QR Payment

                                            </h4>

                                            <p class="text-gray-400 text-sm">

                                                Scan QR Code and upload payment proof

                                            </p>

                                        </div>

                                    </label>

                                    {{-- Bank Transfer --}}
                                    <label class="flex items-center gap-4 rounded-xl border border-white/10 bg-white/5 px-5 py-4 cursor-pointer hover:border-pink-500 transition">

                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="Bank Transfer"
                                            class="paymentMethod w-5 h-5 accent-pink-500">

                                        <div>

                                            <h4 class="text-white font-semibold">

                                                Bank Transfer

                                            </h4>

                                            <p class="text-gray-400 text-sm">

                                                Transfer to our bank account and upload receipt

                                            </p>

                                        </div>

                                    </label>

                                </div>

                            </div>

                            {{-- ========================================================= --}}
                            {{-- QR PAYMENT SECTION --}}
                            {{-- ========================================================= --}}

                            <div id="qrSection" class="hidden mt-10">

                                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">

                                    <h4 class="text-xl text-white font-semibold mb-5">

                                        Scan QR Code

                                    </h4>

                                    <img
                                        src="{{ asset('images/qr.jpg') }}"
                                        alt="QR Code"
                                        class="rounded-xl border border-white/10 w-full">

                                </div>

                            </div>

                            {{-- ========================================================= --}}
                            {{-- BANK SECTION --}}
                            {{-- ========================================================= --}}

                            <div id="bankSection" class="hidden mt-10">

                                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">

                                    <h4 class="text-xl text-white font-semibold mb-5">

                                        Bank Account Details

                                    </h4>

                                    <img
                                        src="{{ asset('images/bank-details.jpg') }}"
                                        alt="Bank Details"
                                        class="rounded-xl border border-white/10 w-full">

                                </div>

                            </div>

                            {{-- ========================================================= --}}
                            {{-- SCREENSHOT --}}
                            {{-- ========================================================= --}}

                            <div id="paymentProofSection" class="hidden mt-8">

                                <label class="block text-white font-medium mb-3">

                                    Upload Payment Screenshot

                                    <span class="text-red-500">*</span>

                                </label>

                                <input
                                    type="file"
                                    name="payment_proof"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    class="block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white">

                                @error('payment_proof')

                                    <p class="text-red-400 mt-2 text-sm">

                                        {{ $message }}

                                    </p>

                                @enderror

                            </div>

                            {{-- ========================================================= --}}
                            {{-- Submit --}}
                            {{-- ========================================================= --}}

                            <button
                                id="paymentBtn"
                                type="submit"
                                class="mt-10 w-full rounded-xl bg-gradient-to-r from-pink-600 to-blue-600 py-4 text-lg font-bold text-white transition duration-300 hover:scale-[1.02]">

                                Pay Securely

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- ========================================================= --}}
{{-- JavaScript --}}
{{-- Part 4 --}}
{{-- ========================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const paymentMethods = document.querySelectorAll('.paymentMethod');

    const qrSection = document.getElementById('qrSection');

    const bankSection = document.getElementById('bankSection');

    const paymentProofSection = document.getElementById('paymentProofSection');

    const paymentBtn = document.getElementById('paymentBtn');

    const paymentProof = document.querySelector('input[name="payment_proof"]');


    /*
    |--------------------------------------------------------------------------
    | Toggle Payment Method
    |--------------------------------------------------------------------------
    */

    function togglePaymentSection() {

        const selected = document.querySelector('.paymentMethod:checked').value;

        // Reset

        qrSection.classList.add('hidden');

        bankSection.classList.add('hidden');

        paymentProofSection.classList.add('hidden');

        paymentProof.removeAttribute('required');


        /*
        |--------------------------------------------------------------------------
        | Online Payment
        |--------------------------------------------------------------------------
        */

        if (selected === 'online') {

            paymentBtn.innerHTML = 'Proceed To Secure Payment';

        }


        /*
        |--------------------------------------------------------------------------
        | QR Payment
        |--------------------------------------------------------------------------
        */

        else if (selected === 'qr') {

            qrSection.classList.remove('hidden');

            paymentProofSection.classList.remove('hidden');

            paymentProof.setAttribute('required', true);

            paymentBtn.innerHTML = 'Submit Payment Proof';

        }


        /*
        |--------------------------------------------------------------------------
        | Bank Transfer
        |--------------------------------------------------------------------------
        */

        else if (selected === 'Bank Transfer') {

            bankSection.classList.remove('hidden');

            paymentProofSection.classList.remove('hidden');

            paymentProof.setAttribute('required', true);

            paymentBtn.innerHTML = 'Submit Payment Proof';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Change Event
    |--------------------------------------------------------------------------
    */

    paymentMethods.forEach(function (item) {

        item.addEventListener('change', togglePaymentSection);

    });


    /*
    |--------------------------------------------------------------------------
    | Default Load
    |--------------------------------------------------------------------------
    */

    togglePaymentSection();

});

</script>

@endpush

@endsection
