@extends('partials.master')

@section('title', 'Edit Profile')

@section('content')

@include('component.breadcrumbs')

<div class="max-w-7xl mx-auto py-10">

    {{-- Success --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-500/30 bg-green-500/10 text-green-300 px-5 py-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error --}}
    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 text-red-300 px-5 py-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 p-5">

            <h4 class="text-red-300 font-bold mb-3">
                Please fix the following errors
            </h4>

            <ul class="list-disc ml-5 text-red-200 space-y-1">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="{{ route('student.update-profile') }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="rounded-3xl overflow-hidden border border-slate-700 bg-slate-900/70 shadow-2xl">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-pink-600 via-fuchsia-600 to-blue-600 px-8 py-8">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-4xl font-bold text-white">

                            Edit Profile

                        </h2>

                        <p class="text-pink-100 mt-2">

                            Update your personal information

                        </p>

                    </div>

                    <a href="{{ route('student.profile') }}"
                        class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white transition">

                        ← Back

                    </a>

                </div>

            </div>

            <div class="p-8 space-y-8">

                {{-- ========================= --}}
                {{-- Personal Information --}}
                {{-- ========================= --}}

                <div class="rounded-2xl border border-slate-700 bg-slate-800/60">

                    <div class="px-6 py-4 border-b border-slate-700">

                        <h3 class="text-xl font-bold text-pink-400">

                            Personal Information

                        </h3>

                    </div>

                    <div class="p-6 grid md:grid-cols-2 gap-6">

                        {{-- Name --}}
                        <div>

                            <label class="block text-sm font-semibold text-pink-300 mb-2">

                                Full Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name',$student->name) }}"
                                readonly
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white focus:ring-2 focus:ring-pink-500">

                            @error('name')

                                <p class="mt-1 text-red-400 text-sm">{{ $message }}</p>

                            @enderror

                        </div>

                        {{-- Email --}}
                        <div>

                            <label class="block text-sm font-semibold text-pink-300 mb-2">

                                Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email',$student->email) }}"
                                readonly
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white focus:ring-2 focus:ring-pink-500">

                            @error('email')

                                <p class="mt-1 text-red-400 text-sm">{{ $message }}</p>

                            @enderror

                        </div>

                        {{-- DOB --}}
                        <div>

                            <label class="block text-sm font-semibold text-pink-300 mb-2">

                                Date of Birth

                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}"
                                readonly
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white focus:ring-2 focus:ring-pink-500">

                            @error('date_of_birth')

                                <p class="mt-1 text-red-400 text-sm">{{ $message }}</p>

                            @enderror

                        </div>

                        {{-- Gender --}}
                        <div>

                            <label class="block text-sm font-semibold text-pink-300 mb-2">

                                Gender

                            </label>

                            <select
                                name="gender"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white focus:ring-2 focus:ring-pink-500">

                                <option value="">Select Gender</option>

                                <option value="Male"
                                    {{ old('gender',$student->gender)=='Male' ? 'selected' : '' }}>
                                    Male
                                </option>

                                <option value="Female"
                                    {{ old('gender',$student->gender)=='Female' ? 'selected' : '' }}>
                                    Female
                                </option>

                                <option value="Other"
                                    {{ old('gender',$student->gender)=='Other' ? 'selected' : '' }}>
                                    Other
                                </option>

                            </select>

                            @error('gender')

                                <p class="mt-1 text-red-400 text-sm">{{ $message }}</p>

                            @enderror

                        </div>

                        {{-- Religion --}}
                        <div>

                            <label class="block text-sm font-semibold text-pink-300 mb-2">

                                Religion

                            </label>

                            <input
                                type="text"
                                name="religion"
                                value="{{ old('religion',$student->religion) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                        </div>

                        {{-- Mother Tongue --}}
                        <div>

                            <label class="block text-sm font-semibold text-pink-300 mb-2">

                                Mother Tongue

                            </label>

                            <input
                                type="text"
                                name="mother_tongue"
                                value="{{ old('mother_tongue',$student->mother_tongue) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                        </div>

                    </div>

                </div>

                {{-- ========================= --}}
                {{-- Contact Information --}}
                {{-- ========================= --}}

                <div class="rounded-2xl border border-slate-700 bg-slate-800/60">

                    <div class="px-6 py-4 border-b border-slate-700">

                        <h3 class="text-xl font-bold text-blue-400">

                            Contact Information

                        </h3>

                    </div>

                    <div class="p-6 grid md:grid-cols-2 gap-6">

                        {{-- Phone --}}
                        <div>

                            <label class="block text-sm font-semibold text-blue-300 mb-2">

                                Mobile Number

                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone',$student->phone) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                        </div>

                        {{-- WhatsApp --}}
                        <div>

                            <label class="block text-sm font-semibold text-blue-300 mb-2">

                                WhatsApp Number

                            </label>

                            <input
                                type="text"
                                name="whatsapp_no"
                                value="{{ old('whatsapp_no',$student->whatsapp_no) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                        </div>

                    </div>

                </div>

                                {{-- ========================= --}}
                {{-- Education & Occupation --}}
                {{-- ========================= --}}

                <div class="rounded-2xl border border-slate-700 bg-slate-800/60">

                    <div class="px-6 py-4 border-b border-slate-700">

                        <h3 class="text-xl font-bold text-pink-400">

                            Education & Occupation

                        </h3>

                    </div>

                    <div class="p-6 grid md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Occupation
                            </label>

                            <input type="text"
                                name="occupation"
                                value="{{ old('occupation',$student->occupation) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Qualification
                            </label>

                            <input type="text"
                                name="qualification"
                                value="{{ old('qualification',$student->qualification) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                        </div>

                    </div>

                </div>

                {{-- ========================= --}}
                {{-- Guardian --}}
                {{-- ========================= --}}

                <div class="rounded-2xl border border-slate-700 bg-slate-800/60">

                    <div class="px-6 py-4 border-b border-slate-700">

                        <h3 class="text-xl font-bold text-blue-400">

                            Guardian Information

                        </h3>

                    </div>

                    <div class="p-6 grid md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-semibold text-blue-300 mb-2">
                                Guardian Name
                            </label>

                            <input type="text"
                                name="guardian_name"
                                value="{{ old('guardian_name',$student->guardian_name) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-blue-300 mb-2">
                                Guardian Contact
                            </label>

                            <input type="text"
                                name="guardian_contact"
                                value="{{ old('guardian_contact',$student->guardian_contact) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-blue-300 mb-2">
                                Guardian Occupation
                            </label>

                            <input type="text"
                                name="guardian_occupation"
                                value="{{ old('guardian_occupation',$student->guardian_occupation) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">
                        </div>

                    </div>

                </div>

                {{-- ========================= --}}
                {{-- Local Guardian --}}
                {{-- ========================= --}}

                <div class="rounded-2xl border border-slate-700 bg-slate-800/60">

                    <div class="px-6 py-4 border-b border-slate-700">

                        <h3 class="text-xl font-bold text-pink-400">

                            Local Guardian

                        </h3>

                    </div>

                    <div class="p-6 grid md:grid-cols-2 gap-6">

                        <div>

                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Local Guardian Name
                            </label>

                            <input type="text"
                                name="local_guardian_name"
                                value="{{ old('local_guardian_name',$student->local_guardian_name) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-pink-300 mb-2">
                                Relationship
                            </label>

                            <input type="text"
                                name="local_guardian_relation"
                                value="{{ old('local_guardian_relation',$student->local_guardian_relation) }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                        </div>

                    </div>

                </div>

                {{-- ========================= --}}
                {{-- Address --}}
                {{-- ========================= --}}

                <div class="rounded-2xl border border-slate-700 bg-slate-800/60">

                    <div class="px-6 py-4 border-b border-slate-700">

                        <h3 class="text-xl font-bold text-blue-400">

                            Address Information

                        </h3>

                    </div>

                    <div class="p-6 space-y-6">

                        <div>

                            <label class="block text-sm font-semibold text-blue-300 mb-2">
                                Address
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">{{ old('address',$student->address) }}</textarea>

                        </div>

                        <div class="grid md:grid-cols-4 gap-6">

                            <div>

                                <label class="block text-sm font-semibold text-blue-300 mb-2">
                                    City
                                </label>

                                <input type="text"
                                    name="city"
                                    value="{{ old('city',$student->city) }}"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                            </div>

                            <div>

                                <label class="block text-sm font-semibold text-blue-300 mb-2">
                                    State
                                </label>

                                <input type="text"
                                    name="state"
                                    value="{{ old('state',$student->state) }}"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                            </div>

                            <div>

                                <label class="block text-sm font-semibold text-blue-300 mb-2">
                                    Country
                                </label>

                                <input type="text"
                                    name="country"
                                    value="{{ old('country',$student->country) }}"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                            </div>

                            <div>

                                <label class="block text-sm font-semibold text-blue-300 mb-2">
                                    Pincode
                                </label>

                                <input type="text"
                                    name="pincode"
                                    value="{{ old('pincode',$student->pincode) }}"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white">

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ========================= --}}
                {{-- Images --}}
                {{-- ========================= --}}

                <div class="grid md:grid-cols-2 gap-8">

                    {{-- Profile --}}
                    <div class="rounded-2xl bg-slate-800/60 border border-slate-700 p-6">

                        <h3 class="text-lg font-bold text-pink-400 mb-5">

                            Profile Photo

                        </h3>

                        @if($student->profile_image)

                            <img
                                src="{{ asset('storage/'.$student->profile_image) }}"
                                class="w-40 h-40 rounded-2xl object-cover border-4 border-pink-500 mb-5">

                        @endif

                        <input type="file"
                            name="profile_image"
                            class="w-full rounded-xl bg-slate-900 border border-slate-700 text-white p-3">

                    </div>

                    {{-- Signature --}}
                    <div class="rounded-2xl bg-slate-800/60 border border-slate-700 p-6">

                        <h3 class="text-lg font-bold text-blue-400 mb-5">

                            Signature

                        </h3>

                        @if($student->signature)

                            <img
                                src="{{ asset('storage/'.$student->signature) }}"
                                class="h-28 bg-white rounded-xl p-2 mb-5">

                        @endif

                        <input type="file"
                            name="signature"
                            class="w-full rounded-xl bg-slate-900 border border-slate-700 text-white p-3">

                    </div>

                </div>

                {{-- Buttons --}}

                <div class="flex justify-end gap-4 pt-4">

                    <a href="{{ route('student.profile') }}"
                        class="px-6 py-3 rounded-xl border border-slate-600 text-slate-300 hover:bg-slate-700 transition">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="px-8 py-3 rounded-xl bg-gradient-to-r from-pink-600 to-blue-600 text-white font-semibold hover:scale-105 transition">

                        Update Profile

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection
