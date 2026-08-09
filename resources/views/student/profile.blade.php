@extends('partials.master')

@section('title','Student Profile')

@section('content')

@include('component.breadcrumbs')

<div class="max-w-7xl mx-auto py-10">

    <div class="rounded-3xl bg-slate-900/70 border border-slate-700 shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-pink-600 via-fuchsia-600 to-blue-600 px-8 py-8">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                <div>

                    <h2 class="text-4xl font-bold text-white">

                        Student Profile

                    </h2>

                    <p class="text-pink-100 mt-2">

                        Welcome Back,
                        {{ $student->name }}

                    </p>

                </div>

                <div class="flex items-center gap-3">

                    @if($student->is_active)

                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-500/20 text-green-300 border border-green-500/30">

                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>

                            Active

                        </span>

                    @else

                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-500/20 text-red-300 border border-red-500/30">

                            <span class="w-2 h-2 rounded-full bg-red-400"></span>

                            Inactive

                        </span>

                    @endif

                    <a href="{{ route('student.edit-profile') }}"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-white/10 hover:bg-gradient-to-r hover:from-pink-600 hover:to-blue-600 text-white border border-white/20 transition-all duration-300">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L12 15l-4 1 1-4 8.414-8.414z"/>

                        </svg>

                        Edit Profile

                    </a>

                </div>

            </div>

        </div>

        <div class="p-8">

            <div class="grid lg:grid-cols-3 gap-8">

                {{-- Left Card --}}
                <div class="space-y-6">

                    <div class="rounded-2xl bg-slate-800/60 border border-slate-700 p-6 text-center">

                        @if($student->profile_image)

                            <img
                                src="{{ asset('storage/'.$student->profile_image) }}"
                                class="w-44 h-44 rounded-full mx-auto object-cover border-4 border-pink-500">

                        @endif

                        <h3 class="text-2xl font-bold text-white mt-5">

                            {{ $student->name }}

                        </h3>

                        <p class="text-slate-400">

                            Student

                        </p>

                    </div>

                    <div class="rounded-2xl bg-slate-800/60 border border-slate-700 p-6">

                        <h4 class="text-lg font-semibold text-pink-400 mb-4">

                            Signature

                        </h4>

                        @if($student->signature)

                            <img
                                src="{{ asset('storage/'.$student->signature) }}"
                                class="bg-white rounded-lg p-3">

                        @else

                            <p class="text-slate-500">

                                No Signature Uploaded

                            </p>

                        @endif

                    </div>

                </div>

                {{-- Right --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Personal --}}
                    <div class="rounded-2xl bg-slate-800/60 border border-slate-700">

                        <div class="px-6 py-4 border-b border-slate-700">

                            <h3 class="text-xl font-bold text-pink-400">

                                Personal Information

                            </h3>

                        </div>

                        <div class="grid md:grid-cols-2 gap-6 p-6">

                            @foreach([
                                'Email'=>$student->email,
                                'Phone'=>$student->phone,
                                'WhatsApp'=>$student->whatsapp_no,
                                'Date of Birth'=>optional($student->date_of_birth)->format('d M Y'),
                                'Gender'=>$student->gender,
                                'Religion'=>$student->religion,
                                'Mother Tongue'=>$student->mother_tongue,
                                'Occupation'=>$student->occupation,
                                'Qualification'=>$student->qualification
                            ] as $label=>$value)

                            <div>

                                <p class="text-slate-400 text-sm">

                                    {{ $label }}

                                </p>

                                <p class="text-white font-semibold mt-1">

                                    {{ $value ?: '-' }}

                                </p>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    {{-- Guardian --}}
                    <div class="rounded-2xl bg-slate-800/60 border border-slate-700">

                        <div class="px-6 py-4 border-b border-slate-700">

                            <h3 class="text-xl font-bold text-blue-400">

                                Guardian Information

                            </h3>

                        </div>

                        <div class="grid md:grid-cols-2 gap-6 p-6">

                            <div>

                                <p class="text-slate-400">

                                    Guardian Name

                                </p>

                                <p class="text-white">

                                    {{ $student->guardian_name ?: '-' }}

                                </p>

                            </div>

                            <div>

                                <p class="text-slate-400">

                                    Contact

                                </p>

                                <p class="text-white">

                                    {{ $student->guardian_contact ?: '-' }}

                                </p>

                            </div>

                            <div>

                                <p class="text-slate-400">

                                    Occupation

                                </p>

                                <p class="text-white">

                                    {{ $student->guardian_occupation ?: '-' }}

                                </p>

                            </div>

                            <div>

                                <p class="text-slate-400">

                                    Local Guardian

                                </p>

                                <p class="text-white">

                                    {{ $student->local_guardian_name ?: '-' }}

                                </p>

                            </div>

                            <div>

                                <p class="text-slate-400">

                                    Relation

                                </p>

                                <p class="text-white">

                                    {{ $student->local_guardian_relation ?: '-' }}

                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- Address --}}
                    <div class="rounded-2xl bg-slate-800/60 border border-slate-700">

                        <div class="px-6 py-4 border-b border-slate-700">

                            <h3 class="text-xl font-bold text-pink-400">

                                Address

                            </h3>

                        </div>

                        <div class="p-6">

                            <p class="text-white">

                                {{ $student->address ?: '-' }}

                            </p>

                            <div class="grid md:grid-cols-4 gap-6 mt-6">

                                <div>

                                    <p class="text-slate-400">

                                        City

                                    </p>

                                    <p class="text-white">

                                        {{ $student->city ?: '-' }}

                                    </p>

                                </div>

                                <div>

                                    <p class="text-slate-400">

                                        State

                                    </p>

                                    <p class="text-white">

                                        {{ $student->state ?: '-' }}

                                    </p>

                                </div>

                                <div>

                                    <p class="text-slate-400">

                                        Country

                                    </p>

                                    <p class="text-white">

                                        {{ $student->country ?: '-' }}

                                    </p>

                                </div>

                                <div>

                                    <p class="text-slate-400">

                                        Pincode

                                    </p>

                                    <p class="text-white">

                                        {{ $student->pincode ?: '-' }}

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
