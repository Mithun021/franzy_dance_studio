@extends('partials.master')

@section('title','Course Details')

@section('content')

<div class="max-w-7xl mx-auto px-5 py-10">

    <!-- =========================
            HEADER
    ========================== -->

    <div id="printArea">

        <div class="rounded-3xl overflow-hidden shadow-xl">

            <div class="bg-gradient-to-r from-pink-600 via-fuchsia-600 to-blue-600 px-8 py-8">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                    <div>

                        <h2 class="text-4xl font-bold text-white">

                            Course Details

                        </h2>

                        <p class="text-pink-100 mt-2">

                            Complete information about your enrolled course.

                        </p>

                    </div>

                    <a href="{{ route('student.my-courses') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white transition">

                        ← Back To My Courses

                    </a>

                </div>

            </div>

        </div>



        <!-- =========================
                CONTENT
        ========================== -->

        <div class="grid lg:grid-cols-3 gap-8 mt-8">

            <!-- =========================
                    LEFT SIDE
            ========================== -->

            <div>

                <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">

                    <div class="bg-gradient-to-r from-pink-600 to-blue-600 py-8">

                        <div class="flex justify-center">

                            @if($studentCourse->student->profile_image)

                                <img
                                    src="{{ asset('storage/'.$studentCourse->student->profile_image) }}"
                                    class="w-36 h-36 rounded-full object-cover border-4 border-white shadow-xl">

                            @else

                                <div
                                    class="w-36 h-36 rounded-full bg-slate-300 flex items-center justify-center border-4 border-white">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-16 h-16 text-slate-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>

                                    </svg>

                                </div>

                            @endif

                        </div>

                    </div>

                    <div class="p-6 text-center">

                        <h3 class="text-2xl font-bold text-white">

                            {{ $studentCourse->student->name }}

                        </h3>

                        <p class="text-slate-400 mt-2">

                            {{ $studentCourse->student->email }}

                        </p>

                        <p class="text-slate-400">

                            {{ $studentCourse->student->phone }}

                        </p>

                        <div class="mt-6">

                            @if($studentCourse->status=="ongoing")

                                <span class="px-5 py-2 rounded-full bg-green-500/20 text-green-300 border border-green-500/30">

                                    Ongoing

                                </span>

                            @elseif($studentCourse->status=="completed")

                                <span class="px-5 py-2 rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30">

                                    Completed

                                </span>

                            @else

                                <span class="px-5 py-2 rounded-full bg-red-500/20 text-red-300 border border-red-500/30">

                                    Discontinued

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>



            <!-- =========================
                RIGHT SIDE
            ========================== -->

            <div class="lg:col-span-2">

                <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow-xl">

                    <div class="border-b border-slate-800 px-8 py-6">

                        <h3 class="text-2xl font-bold text-white">

                            Course Summary

                        </h3>

                    </div>

                    <div class="p-8">

                        <div class="grid md:grid-cols-2 gap-6">

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Admission Number

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->admission_no }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Admission Date

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->admission_date->format('d M Y') }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Course

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ optional($studentCourse->course)->course_name }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Level

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ optional($studentCourse->level)->name }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Category

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ optional($studentCourse->category)->name }}

                                </h4>

                            </div>



                            <div>

                                <p class="text-slate-400 text-sm">

                                    Instructor

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ optional($studentCourse->instructor)->name ?? 'Not Assigned' }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Enrollment

                                </p>

                                <h4 class="mt-1">

                                    @if($studentCourse->is_enroll)

                                        <span class="text-green-400 font-bold">

                                            Enrolled

                                        </span>

                                    @else

                                        <span class="text-yellow-400 font-bold">

                                            Payment Pending

                                        </span>

                                    @endif

                                </h4>

                            </div>

                                                    <div>

                                <p class="text-slate-400 text-sm">

                                    Completion Date

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->completion_date ? $studentCourse->completion_date->format('d M Y') : 'Running' }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- =========================
                        Batch Information
                ========================= -->

                <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow-xl mt-8">

                    <div class="border-b border-slate-800 px-8 py-5">

                        <h3 class="text-2xl font-bold text-white">

                            Batch Information

                        </h3>

                    </div>

                    <div class="p-8">

                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                            <div>
                                <p class="text-slate-400 text-sm">Batch Name</p>
                                <h4 class="text-white font-semibold mt-1">
                                    {{ optional($studentCourse->batch)->batch_name }}
                                </h4>
                            </div>

                            <div>
                                <p class="text-slate-400 text-sm">Class Days</p>
                                <h4 class="text-white font-semibold mt-1">

                                    @if($studentCourse->batch && is_array($studentCourse->batch->class_days))
                                        {{ implode(', ', $studentCourse->batch->class_days) }}
                                    @else
                                        N/A
                                    @endif

                                </h4>
                            </div>

                            <div>
                                <p class="text-slate-400 text-sm">Class Timing</p>
                                <h4 class="text-white font-semibold mt-1">

                                    {{ optional($studentCourse->batch)->start_time }}
                                    -
                                    {{ optional($studentCourse->batch)->end_time }}

                                </h4>
                            </div>

                            {{-- <div>
                                <p class="text-slate-400 text-sm">Batch Capacity</p>
                                <h4 class="text-green-400 font-bold mt-1">

                                    {{ optional($studentCourse->batch)->capacity }}

                                </h4>
                            </div>

                            <div>
                                <p class="text-slate-400 text-sm">Enrolled Students</p>
                                <h4 class="text-pink-400 font-bold mt-1">

                                    {{ optional($studentCourse->batch)->enrolled_students_count }}

                                </h4>
                            </div>

                            <div>
                                <p class="text-slate-400 text-sm">Available Seats</p>

                                @php
                                    $capacity = optional($studentCourse->batch)->capacity ?? 0;
                                    $enrolled = optional($studentCourse->batch)->enrolled_students_count ?? 0;
                                    $available = max($capacity - $enrolled, 0);
                                @endphp

                                <h4 class="font-bold mt-1 {{ $available > 0 ? 'text-green-400' : 'text-red-400' }}">

                                    {{ $available }}

                                </h4>
                            </div> --}}

                        </div>

                    </div>

                </div>

                <!-- =========================
                        Fee Details
                ========================== -->

                <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow-xl mt-8">

                    <div class="border-b border-slate-800 px-8 py-5">

                        <h3 class="text-2xl font-bold text-white">

                            Fee Details

                        </h3>

                    </div>

                    <div class="p-8">

                        <div class="grid md:grid-cols-3 gap-6">

                            <div class="rounded-2xl bg-slate-800 border border-slate-700 p-6">

                                <p class="text-slate-400">

                                    Registration Fee

                                </p>

                                <h2 class="text-3xl font-bold text-green-400 mt-3">

                                    ₹ {{ number_format($studentCourse->registration_fee,2) }}

                                </h2>

                            </div>

                            <div class="rounded-2xl bg-slate-800 border border-slate-700 p-6">

                                <p class="text-slate-400">

                                    Admission Fee

                                </p>

                                <h2 class="text-3xl font-bold text-pink-400 mt-3">

                                    ₹ {{ number_format($studentCourse->admission_fee,2) }}

                                </h2>

                            </div>

                            <div class="rounded-2xl bg-slate-800 border border-slate-700 p-6">

                                <p class="text-slate-400">

                                    Monthly Fee

                                </p>

                                <h2 class="text-3xl font-bold text-blue-400 mt-3">

                                    ₹ {{ number_format($studentCourse->course_fee,2) }}

                                </h2>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- =========================
                    Student Information
                ========================== -->

                <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow-xl mt-8">

                    <div class="border-b border-slate-800 px-8 py-5">

                        <h3 class="text-2xl font-bold text-white">

                            Student Information

                        </h3>

                    </div>

                    <div class="p-8">

                        <div class="grid md:grid-cols-2 gap-6">

                            <div>

                                <p class="text-slate-400 text-sm">Gender</p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->gender ?? 'N/A' }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">Date of Birth</p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ optional($studentCourse->student->date_of_birth)->format('d M Y') }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">Religion</p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->religion }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">Mother Tongue</p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->mother_tongue }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">Qualification</p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->qualification }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">Occupation</p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->occupation }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- =========================
                    Guardian Details
                ========================== -->

                <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow-xl mt-8">

                    <div class="border-b border-slate-800 px-8 py-5">

                        <h3 class="text-2xl font-bold text-white">

                            Guardian Details

                        </h3>

                    </div>

                    <div class="p-8">

                        <div class="grid md:grid-cols-2 gap-6">

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Guardian Name

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->guardian_name }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Guardian Contact

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->guardian_contact }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Guardian Occupation

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->guardian_occupation }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Local Guardian

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->local_guardian_name }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Relation

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->local_guardian_relation }}

                                </h4>

                            </div>

                            <div>

                                <p class="text-slate-400 text-sm">

                                    Address

                                </p>

                                <h4 class="text-white font-semibold mt-1">

                                    {{ $studentCourse->student->address }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- =========================
                        Actions
                ========================== -->

                <div class="flex flex-wrap gap-4 mt-8">

                    <a href="{{ route('student.id-card') }}"
                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-pink-600 to-blue-600 text-white font-semibold hover:opacity-90 transition">

                        Student ID Card

                    </a>

                    <button
                        type="button"
                        id="printBtn"
                        class="px-6 py-3 rounded-xl bg-slate-800 border border-slate-700 text-white hover:bg-slate-700 transition">

                        Print Details

                    </button>

                </div>

            </div>

        </div>

    </div>
</div>

@push('scripts')
    <script>
        document.getElementById('printBtn').addEventListener('click', function () {

            let printContents = document.getElementById('printArea').innerHTML;

            let printWindow = window.open('', '', 'width=1200,height=900');

            printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Course Details</title>

        <script src="https://cdn.tailwindcss.com"><\/script>

        <style>
        body{
            background:#fff;
            margin:0;
            padding:25px;
            -webkit-print-color-adjust:exact !important;
            print-color-adjust:exact !important;
        }

        *{
            -webkit-print-color-adjust:exact !important;
            print-color-adjust:exact !important;
        }

        @page{
            size:A4;
            margin:10mm;
        }

        img{
            max-width:100%;
        }
        </style>

        </head>

        <body>

        ${printContents}

        </body>

        </html>
        `);

            printWindow.document.close();

            setTimeout(function () {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }, 700);

        });
    </script>
@endpush


@endsection
