@extends('partials.master')

@section('title','My Payments')

@section('content')

@include('component.breadcrumbs')

<section class="py-12 bg-slate-950 min-h-screen">

    <div class="max-w-7xl mx-auto px-4">

        <div class="flex justify-between items-center mb-8">

            <div>

                <h2 class="text-3xl font-bold text-white">

                    Payment History

                </h2>

                <p class="text-gray-400 mt-2">

                    View all successful payments and download invoices.

                </p>

            </div>

            <div>

                <span class="bg-pink-600 text-white px-4 py-2 rounded-full">

                    Total Payments :
                    {{ $payments->count() }}

                </span>

            </div>

        </div>


        @if($payments->count())

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @foreach($payments as $payment)

            <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-lg overflow-hidden hover:border-pink-500 transition duration-300">

                <div class="bg-gradient-to-r from-pink-600 to-blue-600 p-4">

                    <div class="flex justify-between">

                        <div>

                            <p class="text-white text-sm">

                                Invoice No

                            </p>

                            <h3 class="font-bold text-xl text-white">

                                INV-{{ str_pad($payment->id,6,'0',STR_PAD_LEFT) }}

                            </h3>

                        </div>

                        <div>

                            <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full">

                                {{ ucfirst($payment->status) }}

                            </span>

                        </div>

                    </div>

                </div>


                <div class="p-5 space-y-3">

                    <div class="flex justify-between">

                        <span class="text-gray-400">

                            Course

                        </span>

                        <span class="font-semibold text-white">

                            {{ $payment->studentCourse->course->course_name }}

                        </span>

                    </div>


                    <div class="flex justify-between">

                        <span class="text-gray-400">

                            Payment Date

                        </span>

                        <span class="text-white">

                            {{ $payment->payment_date->format('d M Y') }}

                        </span>

                    </div>


                    <div class="flex justify-between">

                        <span class="text-gray-400">

                            Payment Mode

                        </span>

                        <span class="text-white">

                            {{ ucfirst($payment->payment_mode) }}

                        </span>

                    </div>


                    <div class="flex justify-between">

                        <span class="text-gray-400">

                            Transaction ID

                        </span>

                        <span class="text-white">

                            {{ $payment->transaction_id ?: '-' }}

                        </span>

                    </div>


                    <div class="border-t border-slate-700 pt-3">

                        <div class="flex justify-between items-center">

                            <span class="text-lg font-semibold text-gray-300">

                                Paid Amount

                            </span>

                            <span class="text-2xl font-bold text-green-400">

                                ₹ {{ number_format($payment->amount,2) }}

                            </span>

                        </div>

                    </div>

                </div>


                <div class="border-t border-slate-800 p-4 flex gap-3">

                    <a href="{{ route('student.payment.invoice',$payment->id) }}"
                       class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">

                        View Invoice

                    </a>

                    <a href="{{ route('student.payment.invoice',$payment->id) }}"
                       target="_blank"
                       class="flex-1 text-center bg-pink-600 hover:bg-pink-700 text-white py-2 rounded-lg transition">

                        Download

                    </a>

                </div>

            </div>

            @endforeach

        </div>

        @else

        <div class="bg-slate-900 rounded-xl p-16 text-center">

            <div class="text-6xl mb-5">

                💳

            </div>

            <h3 class="text-2xl font-bold text-white">

                No Payment History Found

            </h3>

            <p class="text-gray-400 mt-2">

                Your successful payments will appear here.

            </p>

        </div>

        @endif

    </div>

</section>

@endsection
