@extends('partials.master')

@section('title', 'Admission Form')

@section('content')

@include('component.breadcrumbs')

@php
    $student = auth()->user();
@endphp

{{-- Error Message --}}
    @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-300 bg-red-100 px-4 py-3 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-6 rounded-lg border border-red-300 bg-red-100 px-4 py-3">
            <h5 class="mb-2 font-semibold text-red-700">
                Please fix the following errors:
            </h5>

            <ul class="list-disc pl-5 text-sm text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

{{-- Login Required Overlay --}}
@if (!Auth::check() || Auth::user()->user_type != 'student')

    <style>
        body {
            overflow: hidden;
        }

        #registration-form {
            filter: blur(8px);
            pointer-events: none;
            user-select: none;
        }
    </style>

    <div class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-5">

        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden">

            <div class="bg-gradient-to-r from-pink-500 to-blue-600 p-6 text-center">

                <div class="w-20 h-20 mx-auto rounded-full bg-white flex items-center justify-center text-4xl">
                    🔒
                </div>

                <h2 class="mt-4 text-2xl font-bold text-white">
                    Login Required
                </h2>

            </div>

            <div class="p-8 text-center">

                <p class="text-gray-600 leading-7">
                    Please login with your
                    <span class="font-semibold text-pink-600">
                        Student Account
                    </span>
                    to continue filling the admission form.
                </p>

                <div class="mt-8 flex justify-center gap-4">

                    <a href="{{ route('login') }}"
                        class="px-6 py-3 rounded-xl bg-pink-600 text-white font-semibold hover:bg-pink-700 transition">

                        Login Now

                    </a>

                    <a href="{{ route('student.register') }}"
                        class="px-6 py-3 rounded-xl border border-blue-600 text-blue-600 font-semibold hover:bg-blue-50 transition">

                        Register

                    </a>

                </div>

            </div>

        </div>

    </div>

@endif

<div id="registration-form">
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black py-10 px-4">
    <div class="max-w-6xl mx-auto">

        <!-- Form Card -->
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl overflow-hidden">

            <!-- Header -->
            <div class="relative bg-gradient-to-r from-pink-600 via-fuchsia-600 to-blue-600 px-8 py-10 text-center">
                <div class="absolute inset-0 bg-black/10"></div>

                <div class="relative z-10">
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-wide">
                        FRENZY DANCE STUDIO
                    </h1>

                    <p class="text-pink-100 mt-3 text-lg font-medium">
                        A Complete Performing & Fine Art Center
                    </p>

                    <div class="mt-6 inline-block bg-white/15 border border-white/20 px-8 py-3 rounded-full backdrop-blur-md">
                        <h2 class="text-2xl font-bold text-white tracking-wider">
                            ADMISSION FORM
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('student.save-admission-form') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="p-6 md:p-8 space-y-8">
                @csrf

                <!-- Admission Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-semibold text-pink-300 mb-2">
                            Phone No.<span class="text-red-600">*</span>
                        </label>
                        <input type="tel"
                               name="phone"
                               value="{{ old('phone', $student->phone ?? '') }}"
                               {{ !empty($student->phone) ? 'readonly' : '' }}
                               class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition"
                               placeholder="Phone Number">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-pink-300 mb-2">
                            Date of Admission <span class="text-red-600">*</span>
                        </label>
                        <input type="date"
                               name="admission_date"
                               value="{{ old('admission_date', date('Y-m-d')) }}"
                               class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition">
                    </div>
                </div>

                <!-- Course Selection -->
                <div class="bg-slate-800/40 border border-slate-700 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-pink-500 rounded-full"></span>
                        Course Selection
                    </h3>

                    <label class="block text-sm font-semibold text-pink-300 mb-3">
                        Select Course <span class="text-red-600">*</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($courses as $course)
                            <label class="group flex items-center gap-3 p-4 rounded-xl bg-slate-900/60 border border-slate-700 hover:border-pink-500/60 hover:bg-slate-800/60 cursor-pointer transition-all duration-300">
                                <input type="radio"
                                       name="course_id"
                                       value="{{ $course->id }}"
                                       class="course-radio w-5 h-5 rounded border-slate-600 bg-slate-800 text-pink-500 focus:ring-pink-500 focus:ring-2">

                                <div>
                                    <div class="text-white font-semibold group-hover:text-pink-300 transition">
                                        {{ $course->course_name }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ $course->duration }} {{ $course->duration_type }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Level / Category / Batch -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <label class="block text-sm font-semibold text-pink-300 mb-2">
                            Level <span class="text-red-600">*</span>
                        </label>
                        <select name="level_id"
                                id="level_id"
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition">
                            <option value="" class="text-black">Select Level</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}"
                                        class="text-black"
                                        {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-pink-300 mb-2">
                            Category<span class="text-red-600">*</span>
                        </label>
                        <select name="category_id" id="category_id"
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition">
                            <option value="" class="text-black">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        class="text-black"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div>
                        <label class="block text-sm font-semibold text-pink-300 mb-2">
                            Batch Details
                        </label>
                        <select name="batch_id"
                                id="batch_id"
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition"
                                disabled>
                            <option value="" class="text-black">
                                Select Course & Level First
                            </option>
                        </select>
                    </div> --}}
                </div>

                <!-- Batch Details -->
                <div>
                    <label class="block text-sm font-semibold text-pink-300 mb-3">
                        Batch Details
                    </label>

                    <!-- Hidden input for form submit -->
                    <input type="hidden" name="batch_id" id="selected_batch_id">

                    <!-- Batch Cards Container -->
                    <div id="batch_container"
                        class="grid grid-cols-1 gap-3">

                        <!-- Default State -->
                        <div class="p-5 rounded-2xl bg-slate-900/40 border border-dashed border-slate-700 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-slate-400 font-medium">
                                    Select Course & Level First
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Fee Details -->
                <div class="bg-slate-800/40 border border-slate-700 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                        Fee Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- Admission Fee -->
                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Admission Fee
                            </label>

                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">₹</span>

                                <input type="number"
                                    name="admission_fee"
                                    id="admission_fee"
                                    value="{{ old('admission_fee') }}"
                                    min="0"
                                    step="0.01"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                                    placeholder="0.00"
                                    readonly>
                            </div>
                        </div>

                        <!-- Registration Fee -->
                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Registration Fee
                            </label>

                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">₹</span>

                                <input type="number"
                                    name="registration_fee"
                                    id="registration_fee"
                                    value="{{ old('registration_fee') }}"
                                    min="0"
                                    step="0.01"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                                    placeholder="0.00"
                                    readonly>
                            </div>
                        </div>

                        <!-- Monthly Course Fee -->
                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Course Fee (Monthly)
                            </label>

                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">₹</span>

                                <input type="number"
                                    name="monthly_fee"
                                    id="monthly_fee"
                                    value="{{ old('monthly_fee') }}"
                                    min="0"
                                    step="0.01"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition"
                                    placeholder="0.00"
                                    readonly>
                            </div>
                        </div>

                    </div>

                    <!-- Optional Summary -->
                    <div class="mt-6 p-4 rounded-xl bg-gradient-to-r from-pink-500/10 to-blue-500/10 border border-pink-500/20">
                        <p class="text-sm text-slate-300">
                            <span class="font-semibold text-white">Note:</span>
                            Admission & Registration fees are one-time charges, while Course Fee is charged on a monthly basis.
                        </p>
                    </div>
                </div>

                {{-- Syllabus Section --}}
                <div id="syllabus_section"
                    class="bg-slate-800/40 border border-slate-700 rounded-2xl p-6">

                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

                        <div>
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                Course Syllabus
                            </h3>

                            <p class="text-sm text-slate-400 mt-2">
                                View the syllabus for your selected course and level.
                            </p>
                        </div>

                        <button
                            type="button"
                            id="view_syllabus_btn"
                            class="hidden px-6 py-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold shadow-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300">

                            <svg class="w-5 h-5 inline-block mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

                            </svg>

                            View Syllabus

                        </button>

                    </div>

                    {{-- Loading --}}
                    <div id="syllabus_loading"
                        class="hidden mt-4 text-sm text-slate-400">

                        Loading syllabus...

                    </div>

                    {{-- No Syllabus --}}
                    <div id="syllabus_message"
                        class="hidden mt-4 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-300">

                    </div>

                </div>

                <!-- Personal Details -->
                <div class="bg-slate-800/40 border border-slate-700 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        Personal Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Full Name of Candidate<span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $student->name ?? '') }}"
                                   {{ !empty($student->name) ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                                   placeholder="Enter Full Name">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Date of Birth<span class="text-red-600">*</span>
                            </label>
                            <input
                                type="date"
                                name="date_of_birth"
                                value="{{ old('date_of_birth', optional($student?->date_of_birth)->format('Y-m-d')) }}"
                                {{ !empty($student?->date_of_birth) ? 'readonly' : '' }}
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Gender<span class="text-red-600">*</span>
                            </label>
                            <select
                                name="gender"
                                {{ !empty($student?->gender) ? 'disabled' : '' }}
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition">

                                <option value="" class="text-black">Select Gender</option>

                                <option value="Male"
                                    {{ old('gender', $student?->gender) == 'Male' ? 'selected' : '' }}
                                    class="text-black">
                                    Male
                                </option>

                                <option value="Female"
                                    {{ old('gender', $student?->gender) == 'Female' ? 'selected' : '' }}
                                    class="text-black">
                                    Female
                                </option>

                                <option value="Other"
                                    {{ old('gender', $student?->gender) == 'Other' ? 'selected' : '' }}
                                    class="text-black">
                                    Other
                                </option>

                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Religion<span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   name="religion"
                                   value="{{ old('religion', $student->religion ?? '') }}"
                                   {{ !empty($student->religion) ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                                   placeholder="Religion">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Mother Tongue<span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   name="mother_tongue"
                                   value="{{ old('mother_tongue', $student->mother_tongue ?? '') }}"
                                   {{ !empty($student->mother_tongue) ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                                   placeholder="Mother Tongue">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Occupation<span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   name="occupation"
                                   value="{{ old('occupation', $student->occupation ?? '') }}"
                                   {{ !empty($student->occupation) ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                                   placeholder="Occupation">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Qualification<span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   name="qualification"
                                   value="{{ old('qualification', $student->qualification ?? '') }}"
                                   {{ !empty($student->qualification) ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                                   placeholder="Qualification">
                        </div>

                    </div>
                </div>

                <!-- Contact Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-semibold text-pink-300 mb-2">
                            WhatsApp No.<span class="text-red-600">*</span>
                        </label>
                        <input type="tel"
                               name="whatsapp_no"
                               value="{{ old('whatsapp_no', $student->whatsapp_no ?? '') }}"
                               {{ !empty($student->whatsapp_no) ? 'readonly' : '' }}
                               class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition"
                               placeholder="WhatsApp Number">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-pink-300 mb-2">
                            Email Address <span class="text-red-600">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $student->email  ?? '') }}"
                               {{ !empty($student->email ) ? 'readonly' : '' }}
                               class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                               placeholder="Enter Email Address">
                    </div>


                </div>

                <!-- Guardian Details -->
                <div class="bg-slate-800/40 border border-slate-700 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 bg-pink-500 rounded-full"></span>
                        Parent / Guardian Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Father/Husband Name<span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   name="guardian_name"
                                   value="{{ old('guardian_name', $student->guardian_name  ?? '') }}"
                                   {{ !empty($student->guardian_name ) ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                                   placeholder="Guardian Name">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Contact No.<span class="text-red-600">*</span>
                            </label>
                            <input type="tel"
                                   name="guardian_contact"
                                   value="{{ old('guardian_contact', $student->guardian_contact  ?? '') }}"
                                   {{ !empty($student->guardian_contact ) ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                                   placeholder="Guardian Contact">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Occupation<span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   name="guardian_occupation"
                                   value="{{ old('guardian_occupation', $student->guardian_occupation  ?? '') }}"
                                   {{ !empty($student->guardian_occupation ) ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition"
                                   placeholder="Guardian Occupation">
                        </div>

                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-semibold text-pink-300 mb-2">
                        Address<span class="text-red-600">*</span>
                    </label>
                    <textarea name="address"
                              rows="4"
                              {{ !empty($student->address ) ? 'readonly' : '' }}
                              class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition resize-none"
                              placeholder="Enter Complete Address">{{ old('address', $student->address  ?? '') }}</textarea>
                </div>

                <!-- Local Guardian -->
                <div class="bg-slate-800/40 border border-slate-700 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        Local Guardian
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Name of Local Guardian
                            </label>
                            <input type="text"
                                   name="local_guardian_name"
                                   value="{{ old('local_guardian_name', $student->local_guardian_name  ?? '') }}"
                                   {{ !empty($student->local_guardian_name) ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition"
                                   placeholder="Local Guardian Name">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Relation
                            </label>
                            <input type="text"
                                   name="local_guardian_relation"
                                   value="{{ old('local_guardian_relation', $student->local_guardian_relation  ?? '') }}"
                                   {{ !empty($student->local_guardian_relation) ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition"
                                   placeholder="Relation">
                        </div>

                    </div>
                </div>

                <!-- Photo & Signature -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Profile Photo --}}
                    <div>

                        <label class="block text-sm font-semibold text-pink-300 mb-2">
                            Profile Photo (Passport Size)
                        </label>

                        @if(!empty($student->profile_image))

                            <img
                                src="{{ asset('storage/'.$student->profile_image) }}"
                                alt="Profile Photo"
                                class="w-32 h-32 rounded-xl object-cover border-2 border-pink-500 shadow-lg">

                        @else

                            <input
                                type="file"
                                name="profile_image"
                                accept="image/*"
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-pink-600 file:text-white file:font-semibold hover:file:bg-pink-700 file:cursor-pointer cursor-pointer focus:border-pink-500 focus:ring-2 focus:ring-pink-500/30 transition">

                            @error('profile_image')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror

                        @endif

                    </div>

                    {{-- Signature --}}
                    <div>

                        <label class="block text-sm font-semibold text-pink-300 mb-2">
                            Signature
                        </label>

                        @if(!empty($student->signature))

                            <img
                                src="{{ asset('storage/'.$student->signature) }}"
                                alt="Signature"
                                class="h-20 w-auto rounded-lg border border-blue-500 bg-white p-2 shadow-lg">

                        @else

                            <input
                                type="file"
                                name="signature"
                                accept="image/*"
                                class="w-full px-4 py-3 rounded-xl bg-slate-800/70 border border-slate-700 text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:font-semibold hover:file:bg-blue-700 file:cursor-pointer cursor-pointer focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition">

                            @error('signature')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror

                        @endif

                    </div>

                </div>

                <!-- Declaration -->
                <div class="bg-gradient-to-r from-slate-800/60 to-slate-900/60 border border-slate-700 rounded-2xl p-6">
                    <div class="flex items-start gap-3">
                        <input type="checkbox"
                               required
                               class="mt-1 w-5 h-5 rounded border-slate-600 bg-slate-800 text-pink-500 focus:ring-pink-500 focus:ring-2">

                        <p class="text-sm text-slate-300 leading-relaxed">
                            I hereby declare that the information furnished above is true and correct to the best of my knowledge.
                            I agree to abide by all the rules and regulations of <span class="text-pink-300 font-semibold">FRENZY DANCE STUDIO</span>.
                        </p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center pt-4">

                    <button type="submit"
                            class="w-full sm:w-auto px-10 py-3 rounded-xl bg-gradient-to-r from-pink-600 to-blue-600 text-white font-bold shadow-lg shadow-pink-500/25 hover:from-pink-700 hover:to-blue-700 hover:scale-[1.02] transition-all duration-300">
                        Submit Admission
                    </button>

                </div>

            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-slate-400 text-sm">
            © {{ date('Y') }} FRENZY DANCE STUDIO • A Complete Performing & Fine Art Center
        </div>
    </div>
</div>
</div>

{{-- Syllabus Modal --}}
<div id="syllabus_modal"
     class="hidden fixed inset-0 z-[9999] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">

    <div class="w-full max-w-4xl max-h-[90vh] bg-slate-900 border border-slate-700 rounded-3xl shadow-2xl overflow-hidden">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-700">

            <div>
                <h2 class="text-2xl font-bold text-white">
                    Course Syllabus
                </h2>

                <p id="syllabus_modal_subtitle"
                   class="text-sm text-slate-400 mt-1">
                    Syllabus Details
                </p>
            </div>

            <button
                type="button"
                id="close_syllabus_modal"
                class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-red-500/20 text-slate-300 hover:text-red-400 transition">

                <svg class="w-5 h-5 mx-auto"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>

                </svg>

            </button>

        </div>

        {{-- Modal Body --}}
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-90px)]">

            <div id="syllabus_modal_content"
                 class="space-y-4">

            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
$(document).ready(function () {

    function fetchBatches() {

        let courseId = $('input[name="course_id"]:checked').val();
        let levelId  = $('#level_id').val();

        let container = $('#batch_container');
        let hiddenInput = $('#selected_batch_id');

        // Reset selected batch
        hiddenInput.val('');

        // Loading state
        container.html(`
            <div class="p-5 rounded-2xl bg-slate-900/40 border border-slate-700 text-center">
                <div class="flex items-center justify-center gap-3 text-slate-300">
                    <svg class="animate-spin w-5 h-5 text-pink-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span class="font-medium">Loading batches...</span>
                </div>
            </div>
        `);

        // Validation
        if (!courseId || !levelId) {

            container.html(`
                <div class="p-5 rounded-2xl bg-slate-900/40 border border-dashed border-slate-700 text-center">
                    <p class="text-slate-400 font-medium">
                        Select Course & Level First
                    </p>
                </div>
            `);

            return;
        }

        $.ajax({
            url: '{{ route("fetch.batches") }}',
            method: 'GET',
            data: {
                course_id: courseId,
                level_id: levelId
            },

            success: function (response) {

                container.empty();

                if (response.status && response.batches.length > 0) {

                    $.each(response.batches, function (index, batch) {

                        let days = batch.days_text || 'Flexible Days';

                        let card = `
                            <label class="batch-card group cursor-pointer">

                                <input type="radio"
                                    name="batch_radio"
                                    value="${batch.id}"
                                    class="hidden batch-radio"
                                    // ${batch.is_full ? 'disabled' : ''}
                                    >

                                <div class="relative p-5 rounded-2xl bg-slate-900/60 border border-slate-700 hover:border-pink-500/60 hover:bg-slate-800/60 transition-all duration-300 group-has-[input:checked]:border-pink-500 group-has-[input:checked]:ring-2 group-has-[input:checked]:ring-pink-500/30">

                                    <div class="flex items-start justify-between gap-4">

                                        <div class="flex-1">

                                            <div class="flex items-center gap-3 mb-2">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-blue-500 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>

                                                <div>
                                                    <h4 class="text-white font-bold text-lg group-hover:text-pink-300 transition">
                                                        ${batch.batch_name}
                                                    </h4>
                                                    <p class="text-slate-400 text-sm">
                                                        Batch Schedule
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">

                                                <div class="flex items-center gap-2 p-3 rounded-xl bg-slate-800/60 border border-slate-700">
                                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <div>
                                                        <p class="text-xs text-slate-400">Timing</p>
                                                        <p class="text-sm font-semibold text-white">
                                                            ${batch.start_time} - ${batch.end_time}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 p-3 rounded-xl bg-slate-800/60 border border-slate-700">
                                                    <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <div>
                                                        <p class="text-xs text-slate-400">Class Days</p>
                                                        <p class="text-sm font-semibold text-white">
                                                            ${days}
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="mt-4 flex items-center justify-between">
                                               ${batch.is_full ? `

                                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-300 text-xs font-semibold">

                                                        <span class="w-2 h-2 rounded-full bg-red-400"></span>

                                                        Batch Full

                                                    </span>

                                                    ` : `

                                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/10 border border-green-500/20 text-green-300 text-xs font-semibold">

                                                        <span class="w-2 h-2 rounded-full bg-green-400"></span>

                                                        Available

                                                    </span>

                                                    `}


                                                    <span class="text-xs text-slate-400">

                                                    Capacity:
                                                    ${batch.enrolled_students}/${batch.capacity}

                                                    </span>
                                            </div>

                                        </div>

                                        <div class="flex items-center justify-center">
                                            <div class="w-6 h-6 rounded-full border-2 border-slate-500 flex items-center justify-center group-has-[input:checked]:border-pink-500 group-has-[input:checked]:bg-pink-500 transition-all duration-300">
                                                <svg class="w-3 h-3 text-white opacity-0 group-has-[input:checked]:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </label>
                        `;

                        container.append(card);
                    });

                } else {

                    container.html(`
                        <div class="p-6 rounded-2xl bg-slate-900/40 border border-dashed border-slate-700 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 5a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v14a2 2 0 01-2 2H3z"/>
                                </svg>
                                <p class="text-slate-400 font-medium">
                                    No Batch Available for Selected Course & Level
                                </p>
                            </div>
                        </div>
                    `);
                }
            },

            error: function () {

                container.html(`
                    <div class="p-6 rounded-2xl bg-red-500/10 border border-red-500/20 text-center">
                        <p class="text-red-300 font-medium">
                            Failed to load batches. Please try again.
                        </p>
                    </div>
                `);
            }
        });
    }

    // Fetch on course change
    $(document).on('change', 'input[name="course_id"]', function () {
        fetchBatches();
    });

    // Fetch on level change
    $('#level_id').on('change', function () {
        fetchBatches();
    });

    // Select batch card
    $(document).on('change', '.batch-radio', function () {

        // Set hidden input value
        $('#selected_batch_id').val($(this).val());

        // Remove selected class from all
        $('.batch-card .relative').removeClass(
            'border-pink-500 ring-2 ring-pink-500/30 bg-slate-800/70'
        );

        // Add selected class to current
        $(this).closest('.batch-card').find('.relative').addClass(
            'border-pink-500 ring-2 ring-pink-500/30 bg-slate-800/70'
        );
    });

});
</script>

<script>
$(document).ready(function () {

    function fetchFeeStructure() {

        let courseId   = $('input[name="course_id"]:checked').val();
        let levelId    = $('#level_id').val();
        // let categoryId = $('#category_id').val();

        console.log('Fetching fee structure for:', { courseId, levelId});

        // Fee inputs
        let regFee   = $('#registration_fee');
        let admFee   = $('#admission_fee');
        let monthFee = $('#monthly_fee');

        // Reset values
        regFee.val('');
        admFee.val('');
        monthFee.val('');

        // Check all required fields
        if (!courseId || !levelId
        // || !categoryId
        ) {
            return;
        }

        // Loading state
        regFee.val('Loading...');
        admFee.val('Loading...');
        monthFee.val('Loading...');

        $.ajax({
            url: '{{ route("fetch.fee.structure") }}',
            method: 'GET',
            data: {
                course_id: courseId,
                level_id: levelId,
                // category_id: categoryId
            },

            success: function (response) {

                if (response.status) {

                    regFee.val(response.data.registration_fee);
                    admFee.val(response.data.admission_fee);
                    monthFee.val(response.data.monthly_fee);

                } else {

                    regFee.val('');
                    admFee.val('');
                    monthFee.val('');

                    // Optional toast / alert
                    console.log(response.message);
                }
            },

            error: function (xhr) {

                regFee.val('');
                admFee.val('');
                monthFee.val('');

                console.log(xhr.responseText);
            }
        });
    }

    // Trigger on Course change
    $(document).on('change', 'input[name="course_id"]', function () {
        fetchFeeStructure();
    });

    // Trigger on Level change
    $('#level_id').on('change', function () {
        fetchFeeStructure();
    });

    // Trigger on Category change
    // $('#category_id').on('change', function () {
    //     fetchFeeStructure();
    // });

});
</script>

<script>
$(document).ready(function () {

    let syllabusData = [];

    /*
    |--------------------------------------------------------------------------
    | Fetch Syllabus
    |--------------------------------------------------------------------------
    */

    function fetchSyllabus() {

        let courseId = $('input[name="course_id"]:checked').val();
        let levelId  = $('#level_id').val();

        let syllabusBtn = $('#view_syllabus_btn');
        let loading = $('#syllabus_loading');
        let message = $('#syllabus_message');

        // Reset
        syllabusBtn.addClass('hidden');
        loading.addClass('hidden');
        message.addClass('hidden').html('');

        syllabusData = [];

        // Required fields
        if (!courseId || !levelId) {
            return;
        }

        // Loading
        loading.removeClass('hidden');

        $.ajax({

            url: '{{ route("fetch.syllabus") }}',

            method: 'GET',

            data: {
                course_id: courseId,
                level_id: levelId
            },

            success: function (response) {

                loading.addClass('hidden');

                if (response.status) {

                    syllabusData = response.data.details || [];

                    if (syllabusData.length > 0) {

                        syllabusBtn.removeClass('hidden');

                    } else {

                        message
                            .removeClass('hidden')
                            .html('No syllabus details found for the selected Course and Level.');

                    }

                } else {

                    message
                        .removeClass('hidden')
                        .html(response.message || 'Syllabus not found.');

                }
            },

            error: function (xhr) {

                loading.addClass('hidden');

                message
                    .removeClass('hidden')
                    .html('Unable to load syllabus. Please try again.');

                console.log(xhr.responseText);
            }

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Course Change
    |--------------------------------------------------------------------------
    */

    $(document).on('change', 'input[name="course_id"]', function () {

        fetchSyllabus();

    });


    /*
    |--------------------------------------------------------------------------
    | Level Change
    |--------------------------------------------------------------------------
    */

    $('#level_id').on('change', function () {

        fetchSyllabus();

    });


    /*
    |--------------------------------------------------------------------------
    | Open Syllabus Modal
    |--------------------------------------------------------------------------
    */

    $('#view_syllabus_btn').on('click', function () {

        let content = $('#syllabus_modal_content');

        content.empty();

        if (!syllabusData.length) {

            content.html(`
                <div class="p-6 rounded-2xl bg-red-500/10 border border-red-500/20 text-center">
                    <p class="text-red-300">
                        No syllabus available.
                    </p>
                </div>
            `);

            $('#syllabus_modal').removeClass('hidden');

            return;
        }


        $.each(syllabusData, function (index, syllabus) {

            let chapterNumber = syllabus.chapter_no
                ? `Chapter ${syllabus.chapter_no}`
                : `Chapter ${index + 1}`;

            let duration = syllabus.duration
                ? syllabus.duration
                : 'Not specified';

            let title = syllabus.title || 'Untitled Chapter';

            let contentText = syllabus.content || '';


            let card = `
                <div class="bg-slate-800/60 border border-slate-700 rounded-2xl overflow-hidden">

                    <div class="p-5">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">

                            <div>

                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-pink-500/10 border border-pink-500/20 text-pink-300 text-xs font-semibold">

                                    ${chapterNumber}

                                </span>

                                <h4 class="mt-3 text-lg font-bold text-white">

                                    ${title}

                                </h4>

                            </div>


                            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-300 text-sm">

                                <svg class="w-4 h-4"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>

                                </svg>

                                ${duration}

                            </div>

                        </div>


                        ${
                            contentText
                            ? `
                                <div class="mt-4 pt-4 border-t border-slate-700">

                                    <div class="prose prose-invert max-w-none text-slate-300 text-sm leading-7">

                                        ${contentText}

                                    </div>

                                </div>
                            `
                            : ''
                        }

                    </div>

                </div>
            `;

            content.append(card);

        });


        let courseName =
            $('input[name="course_id"]:checked')
                .closest('label')
                .find('.text-white')
                .first()
                .text()
                .trim();

        let levelName =
            $('#level_id option:selected').text().trim();


        $('#syllabus_modal_subtitle').text(
            courseName + ' • ' + levelName
        );


        $('#syllabus_modal').removeClass('hidden');

    });


    /*
    |--------------------------------------------------------------------------
    | Close Modal
    |--------------------------------------------------------------------------
    */

    $('#close_syllabus_modal').on('click', function () {

        $('#syllabus_modal').addClass('hidden');

    });


    /*
    |--------------------------------------------------------------------------
    | Close Modal on Outside Click
    |--------------------------------------------------------------------------
    */

    $('#syllabus_modal').on('click', function (e) {

        if (e.target === this) {

            $(this).addClass('hidden');

        }

    });


    /*
    |--------------------------------------------------------------------------
    | ESC Key
    |--------------------------------------------------------------------------
    */

    $(document).on('keydown', function (e) {

        if (e.key === 'Escape') {

            $('#syllabus_modal').addClass('hidden');

        }

    });

});
</script>

@endpush

@endsection
