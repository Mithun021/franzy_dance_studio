@extends('partials.master')

@section('title','Payment Submitted Successfully')

@section('content')

@include('component.breadcrumbs')

<section class="py-20">

    <div class="max-w-7xl mx-auto px-5">

        {{-- ========================================================= --}}
        {{-- Success Banner --}}
        {{-- ========================================================= --}}

        <div class="mb-10 rounded-3xl overflow-hidden border border-green-500/20 bg-gradient-to-r from-green-600/20 via-emerald-500/10 to-blue-600/20 backdrop-blur-xl">

            <div class="p-10">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                    <div class="flex items-center gap-6">

                        <div class="w-24 h-24 rounded-full bg-green-500 flex items-center justify-center shadow-2xl shadow-green-500/40">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-12 h-12 text-white"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2.5">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M5 13l4 4L19 7"/>

                            </svg>

                        </div>

                        <div>

                            <p class="uppercase tracking-[4px] text-green-400 text-sm font-semibold">

                                Payment Submitted

                            </p>

                            <h1 class="text-4xl lg:text-5xl font-extrabold text-white mt-2">

                                Thank You!

                            </h1>

                            <p class="mt-4 text-gray-300 leading-8 max-w-2xl">

                                Your offline payment request has been successfully submitted.
                                Our accounts team will verify your payment shortly.
                                Once approved, your studio booking will be confirmed automatically.

                            </p>

                        </div>

                    </div>

                    <div class="rounded-2xl border border-green-400/20 bg-black/20 px-8 py-6 text-center min-w-[260px]">

                        <p class="text-gray-400 text-sm">

                            Payment ID

                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-green-400 break-all">

                            {{ $payment->payment_id }}

                        </h2>

                        <span class="inline-flex items-center mt-5 rounded-full bg-yellow-500/20 px-5 py-2 text-yellow-300 font-semibold">

                            {{ $payment->payment_status }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- Main Content --}}
        {{-- ========================================================= --}}

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- ========================================================= --}}
            {{-- Left Side --}}
            {{-- ========================================================= --}}

            <div class="lg:col-span-2 space-y-8">

                {{-- ========================================================= --}}
                {{-- Payment Information --}}
                {{-- ========================================================= --}}

                <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl overflow-hidden">

                    <div class="border-b border-white/10 px-8 py-6">

                        <h2 class="text-2xl font-bold text-white">

                            Payment Information

                        </h2>

                    </div>

                    <div class="p-8">

                        <table class="w-full">

                            <tbody class="divide-y divide-white/10">

                                <tr>

                                    <td class="py-4 text-gray-400 w-1/2">

                                        Payment ID

                                    </td>

                                    <td class="py-4 text-right text-white font-semibold">

                                        {{ $payment->payment_id }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Payment Date

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $payment->payment_date->format('d M Y h:i A') }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Payment Method

                                    </td>

                                    <td class="py-4 text-right">

                                        <span class="rounded-full bg-blue-500/20 px-4 py-1 text-blue-300">

                                            {{ $payment->payment_method }}

                                        </span>

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Payment Type

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $payment->payment_type }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Amount Paid

                                    </td>

                                    <td class="py-4 text-right text-3xl font-bold text-pink-400">

                                        ₹{{ number_format($payment->amount,2) }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Verification Status

                                    </td>

                                    <td class="py-4 text-right">

                                        <span class="rounded-full bg-yellow-500/20 px-4 py-1 text-yellow-300 font-semibold">

                                            Pending Verification

                                        </span>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                                {{-- ========================================================= --}}
                {{-- Booking Information --}}
                {{-- ========================================================= --}}

                <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl overflow-hidden">

                    <div class="border-b border-white/10 px-8 py-6">

                        <h2 class="text-2xl font-bold text-white">

                            Booking Information

                        </h2>

                    </div>

                    <div class="p-8">

                        <table class="w-full">

                            <tbody class="divide-y divide-white/10">

                                <tr>

                                    <td class="py-4 text-gray-400 w-1/2">

                                        Booking ID

                                    </td>

                                    <td class="py-4 text-right text-white font-semibold">

                                        {{ $payment->booking->booking_id }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Studio Category

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $payment->booking->studio->category->name }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Booking From

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ \Carbon\Carbon::parse($payment->booking->booking_from_date)->format('d M Y') }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Booking To

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        @if($payment->booking->booking_to_date)

                                            {{ \Carbon\Carbon::parse($payment->booking->booking_to_date)->format('d M Y') }}

                                        @else

                                            Single Day Booking

                                        @endif

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Booking Status

                                    </td>

                                    <td class="py-4 text-right">

                                        <span class="inline-flex rounded-full bg-yellow-500/20 px-4 py-1 text-yellow-300 font-semibold">

                                            {{ $payment->booking->enquiry_status }}

                                        </span>

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Daily Studio Charge

                                    </td>

                                    <td class="py-4 text-right text-pink-400 font-bold">

                                        ₹{{ number_format($payment->booking->studio_amount,2) }}

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- Customer Information --}}
                {{-- ========================================================= --}}

                <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl overflow-hidden">

                    <div class="border-b border-white/10 px-8 py-6">

                        <h2 class="text-2xl font-bold text-white">

                            Customer Information

                        </h2>

                    </div>

                    <div class="p-8">

                        <table class="w-full">

                            <tbody class="divide-y divide-white/10">

                                <tr>

                                    <td class="py-4 text-gray-400 w-1/2">

                                        Customer Name

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $payment->booking->customer_name }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Email Address

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $payment->booking->email }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Mobile Number

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $payment->booking->phone }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        City

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $payment->booking->city }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        State

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $payment->booking->state }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400">

                                        Pincode

                                    </td>

                                    <td class="py-4 text-right text-white">

                                        {{ $payment->booking->pincode }}

                                    </td>

                                </tr>

                                <tr>

                                    <td class="py-4 text-gray-400 align-top">

                                        Full Address

                                    </td>

                                    <td class="py-4 text-right text-white leading-7">

                                        {{ $payment->booking->address }}

                                    </td>

                                </tr>

                                @if(!empty($payment->booking->remarks))

                                <tr>

                                    <td class="py-4 text-gray-400 align-top">

                                        Booking Remarks

                                    </td>

                                    <td class="py-4 text-right text-white leading-7">

                                        {{ $payment->booking->remarks }}

                                    </td>

                                </tr>

                                @endif

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- Right Sidebar Starts --}}
            {{-- ========================================================= --}}

            <div class="space-y-8">
                                {{-- ========================================================= --}}
                {{-- Payment Proof --}}
                {{-- ========================================================= --}}

                <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl overflow-hidden">

                    <div class="border-b border-white/10 px-8 py-6">

                        <h2 class="text-2xl font-bold text-white">

                            Uploaded Payment Proof

                        </h2>

                    </div>

                    <div class="p-8">

                        @if($payment->payment_proof)

                            @php
                                $extension = strtolower(pathinfo($payment->payment_proof, PATHINFO_EXTENSION));
                            @endphp

                            @if(in_array($extension,['jpg','jpeg','png','webp']))

                                <img
                                    src="{{ asset('storage/'.$payment->payment_proof) }}"
                                    class="w-full rounded-2xl border border-white/10 shadow-lg">

                                <a href="{{ asset('storage/'.$payment->payment_proof) }}"
                                   target="_blank"
                                   class="mt-5 inline-flex items-center rounded-xl bg-pink-600 hover:bg-pink-700 px-6 py-3 text-white font-semibold transition">

                                    View Full Image

                                </a>

                            @elseif($extension == "pdf")

                                <div class="text-center py-10">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-20 h-20 mx-auto text-red-500"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M7 7V3h8v4m-8 0h8m-8 0v14h8V7"/>

                                    </svg>

                                    <p class="mt-5 text-gray-300">

                                        Payment receipt uploaded in PDF format.

                                    </p>

                                    <a href="{{ asset('storage/'.$payment->payment_proof) }}"
                                       target="_blank"
                                       class="inline-flex mt-6 rounded-xl bg-pink-600 hover:bg-pink-700 px-6 py-3 text-white font-semibold transition">

                                        Open PDF

                                    </a>

                                </div>

                            @endif

                        @else

                            <div class="rounded-2xl border border-dashed border-gray-700 py-12 text-center">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-16 h-16 mx-auto text-gray-500"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.8"
                                          d="M9 13h6m-6 4h6M7 3h7l5 5v13H7z"/>

                                </svg>

                                <p class="text-gray-400 mt-5">

                                    No payment proof available.

                                </p>

                            </div>

                        @endif

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- Verification Timeline --}}
                {{-- ========================================================= --}}

                <div class="rounded-3xl border border-blue-500/20 bg-blue-500/5 backdrop-blur-xl overflow-hidden">

                    <div class="border-b border-blue-500/20 px-8 py-6">

                        <h2 class="text-2xl font-bold text-white">

                            Verification Process

                        </h2>

                    </div>

                    <div class="p-8">

                        <div class="space-y-8">

                            <div class="flex items-start gap-5">

                                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">

                                    ✓

                                </div>

                                <div>

                                    <h4 class="font-semibold text-white">

                                        Booking Submitted

                                    </h4>

                                    <p class="text-gray-400 text-sm mt-1">

                                        Your booking has been received successfully.

                                    </p>

                                </div>

                            </div>

                            <div class="flex items-start gap-5">

                                <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center text-white font-bold">

                                    2

                                </div>

                                <div>

                                    <h4 class="font-semibold text-white">

                                        Payment Verification

                                    </h4>

                                    <p class="text-gray-400 text-sm mt-1">

                                        Our finance team will verify your uploaded payment proof.

                                    </p>

                                </div>

                            </div>

                            <div class="flex items-start gap-5">

                                <div class="w-10 h-10 rounded-full bg-pink-600 flex items-center justify-center text-white font-bold">

                                    3

                                </div>

                                <div>

                                    <h4 class="font-semibold text-white">

                                        Booking Confirmation

                                    </h4>

                                    <p class="text-gray-400 text-sm mt-1">

                                        After successful verification, your studio booking will be confirmed.

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                                {{-- ========================================================= --}}
                {{-- Payment Summary --}}
                {{-- ========================================================= --}}

                <div class="rounded-3xl border border-pink-500/20 bg-gradient-to-br from-pink-600/10 to-blue-600/10 backdrop-blur-xl overflow-hidden">

                    <div class="border-b border-white/10 px-8 py-6">

                        <h2 class="text-2xl font-bold text-white">

                            Payment Summary

                        </h2>

                    </div>

                    <div class="p-8 space-y-5">

                        <div class="flex justify-between">

                            <span class="text-gray-400">

                                Payment Amount

                            </span>

                            <span class="text-white font-semibold">

                                ₹{{ number_format($payment->amount,2) }}

                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-gray-400">

                                Payment Method

                            </span>

                            <span class="text-blue-400 font-semibold">

                                {{ $payment->payment_method }}

                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-gray-400">

                                Payment Status

                            </span>

                            <span class="text-yellow-400 font-semibold">

                                {{ $payment->payment_status }}

                            </span>

                        </div>

                        <div class="border-t border-white/10 pt-5 flex justify-between">

                            <span class="text-xl font-bold text-white">

                                Booking Amount

                            </span>

                            <span class="text-3xl font-extrabold text-pink-400">

                                ₹{{ number_format($payment->amount,2) }}

                            </span>

                        </div>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- Important Notice --}}
                {{-- ========================================================= --}}

                <div class="rounded-3xl border border-yellow-500/20 bg-yellow-500/5 backdrop-blur-xl overflow-hidden">

                    <div class="border-b border-yellow-500/20 px-8 py-5">

                        <h2 class="text-xl font-bold text-yellow-300">

                            Important Information

                        </h2>

                    </div>

                    <div class="p-8">

                        <ul class="space-y-4 text-gray-300 text-sm leading-7">

                            <li>

                                • Your payment is currently under verification.

                            </li>

                            <li>

                                • Verification generally takes 1–24 hours.

                            </li>

                            <li>

                                • Please keep your Payment ID safe for future reference.

                            </li>

                            <li>

                                • If payment verification fails, our team will contact you.

                            </li>

                            <li>

                                • Once approved, your booking confirmation will be shared through Email / SMS / Phone.

                            </li>

                        </ul>

                    </div>

                </div>

                {{-- ========================================================= --}}
                {{-- Action Buttons --}}
                {{-- ========================================================= --}}

                <div class="space-y-4">

                    <a
                    href="{{ route('studio.invoice.download',$payment->id) }}"
                    class="block w-full text-center rounded-2xl bg-white/5 border border-pink-500/30 hover:bg-pink-600 hover:border-pink-600 transition py-4 font-semibold text-white">

                    Download Invoice

                    </a>

                    <a href="{{ route('studio-booking') }}"
                       class="block w-full text-center rounded-2xl bg-gradient-to-r from-pink-600 to-blue-600 hover:from-pink-500 hover:to-blue-500 transition-all duration-300 py-4 font-bold text-lg text-white shadow-lg">

                        Book Another Studio

                    </a>

                    <a href="{{ url('/') }}"
                       class="block w-full text-center rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition py-4 font-semibold text-white">

                        Back To Home

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
