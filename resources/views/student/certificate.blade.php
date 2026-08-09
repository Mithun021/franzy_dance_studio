@extends('partials.master')

@section('title','My Certificates')

@section('content')

@include('component.breadcrumbs')

<section class="py-12 bg-slate-950 min-h-screen">

    <div class="max-w-7xl mx-auto px-4">

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-800">

                <h2 class="text-2xl font-bold text-white flex items-center gap-2">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7 text-pink-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m5-2a2 2 0 00-2-2h-1.172a2 2 0 01-1.414-.586L14.586 4.586A2 2 0 0013.172 4H10.83a2 2 0 00-1.414.586L8.586 5.414A2 2 0 017.172 6H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V8z"/>

                    </svg>

                    My Certificates

                </h2>

            </div>

            @if($certificates->count())

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-800">

                        <thead class="bg-slate-800">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-300">
                                    #
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-300">
                                    Course
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-300">
                                    Uploaded On
                                </th>

                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-300">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-300">
                                    Download
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @foreach($certificates as $certificate)

                                <tr class="hover:bg-slate-800/40 transition">

                                    <td class="px-6 py-4 text-gray-300">

                                        {{ $loop->iteration }}

                                    </td>

                                    <td class="px-6 py-4">

                                        <span class="font-semibold text-white">

                                            {{ $certificate->course->course_name ?? '-' }}

                                        </span>

                                    </td>

                                    <td class="px-6 py-4 text-gray-300">

                                        {{ $certificate->created_at->format('d M Y') }}

                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-600/20 text-green-400 text-sm font-medium">

                                            Available

                                        </span>

                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        <a href="{{ asset('uploads/certificates/'.$certificate->certificate_file) }}"
                                           download
                                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-pink-500 to-blue-600 hover:from-pink-600 hover:to-blue-700 text-white font-medium transition">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-5 h-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor">

                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 4v10m0 0l-4-4m4 4l4-4M5 20h14"/>

                                            </svg>

                                            Download

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="py-20 text-center">

                    <div class="text-6xl mb-4">
                        📜
                    </div>

                    <h3 class="text-2xl font-bold text-white">

                        No Certificate Available

                    </h3>

                    <p class="mt-3 text-gray-400">

                        Your certificates will appear here after course completion.

                    </p>

                </div>

            @endif

        </div>

    </div>

</section>

@endsection
