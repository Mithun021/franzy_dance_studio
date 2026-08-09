@extends('partials.master')

@section('title','My Courses')

@section('content')

<div class="max-w-7xl mx-auto px-5 py-10">

    <!-- Header -->

    <div
        class="mb-8 rounded-3xl bg-gradient-to-r from-pink-600 via-fuchsia-600 to-blue-600 p-8 shadow-xl">

        <h2 class="text-4xl font-bold text-white">

            My Courses

        </h2>

        <p class="text-pink-100 mt-2">

            View all your enrolled dance courses.

        </p>

    </div>

    @if($courses->count())

        <div class="grid lg:grid-cols-2 gap-6">

            @foreach($courses as $course)

                @php

                    $statusColor = match($course->status){

                        'ongoing' => 'green',

                        'completed' => 'blue',

                        'discontinued' => 'red',

                        default => 'gray'

                    };

                @endphp

                <div
                    class="rounded-3xl bg-slate-900 border border-slate-800 overflow-hidden shadow-xl">

                    <!-- Top -->

                    <div
                        class="bg-gradient-to-r from-pink-600 to-blue-600 p-5 flex justify-between items-center">

                        <div>

                            <h3 class="text-2xl font-bold text-white">

                                {{ optional($course->course)->course_name }}

                            </h3>

                            <p class="text-pink-100 text-sm">

                                Admission No :
                                {{ $course->admission_no }}

                            </p>

                        </div>

                        <span
                            class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($statusColor=='green')
                                bg-green-500/20 text-green-300
                            @elseif($statusColor=='blue')
                                bg-blue-500/20 text-blue-300
                            @elseif($statusColor=='red')
                                bg-red-500/20 text-red-300
                            @else
                                bg-gray-500/20 text-gray-300
                            @endif">

                            {{ ucfirst($course->status) }}

                        </span>

                    </div>

                    <!-- Body -->

                    <div class="p-6">

                        <div class="grid grid-cols-2 gap-5">

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Level

                                </p>

                                <h4 class="text-white font-semibold">

                                    {{ optional($course->level)->name }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Category

                                </p>

                                <h4 class="text-white font-semibold">

                                    {{ optional($course->category)->name }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Batch

                                </p>

                                <h4 class="text-white font-semibold">

                                    {{ optional($course->batch)->batch_name }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Instructor

                                </p>

                                <h4 class="text-white font-semibold">

                                    {{ optional($course->instructor)->name ?? 'Not Assigned' }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Admission Date

                                </p>

                                <h4 class="text-white font-semibold">

                                    {{ \Carbon\Carbon::parse($course->admission_date)->format('d M Y') }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Monthly Fee

                                </p>

                                <h4 class="text-green-400 font-bold">

                                    ₹ {{ number_format($course->course_fee,2) }}

                                </h4>

                            </div>

                        </div>

                    </div>

                    <!-- Footer -->

                    <div
                        class="border-t border-slate-800 px-6 py-4 flex justify-between items-center">

                        <div>

                            @if($course->is_enroll)

                                <span
                                    class="px-3 py-1 rounded-full bg-green-500/20 text-green-300 text-xs">

                                    Enrolled

                                </span>

                            @else

                                <span
                                    class="px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-300 text-xs">

                                    Payment Pending

                                </span>

                            @endif

                        </div>

                        <div class="flex gap-3">

                            <a href="{{ route('student.id-card') }}"
                               class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm">

                                ID Card

                            </a>

                            <a href="{{ route('student.course-details',$course->id) }}"
                               class="px-4 py-2 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-sm">

                                View Details

                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div
            class="rounded-3xl bg-slate-900 border border-dashed border-slate-700 py-24 text-center">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="mx-auto w-16 h-16 text-slate-500"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"/>

            </svg>

            <h3 class="mt-4 text-2xl text-white font-bold">

                No Courses Found

            </h3>

            <p class="text-slate-400 mt-2">

                You haven't enrolled in any course yet.

            </p>

        </div>

    @endif

</div>

@endsection
