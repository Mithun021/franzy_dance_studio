@extends('partials.master')

@section('title', 'Notice Details')

@section('content')

<section class="relative overflow-hidden bg-[#080812] py-16 md:py-20">

    {{-- Background Glow --}}
    <div class="pointer-events-none absolute -left-32 top-10 h-72 w-72 rounded-full bg-pink-600/10 blur-3xl"></div>

    <div class="pointer-events-none absolute -right-32 bottom-10 h-72 w-72 rounded-full bg-blue-600/10 blur-3xl"></div>

    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        {{-- Back --}}
        <div class="mb-6">

            <a href="{{ url('/') }}#notice"
               class="group inline-flex items-center gap-2 text-sm text-gray-500 transition hover:text-pink-500">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4 transition group-hover:-translate-x-1"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>

                Back to Notices

            </a>

        </div>


        {{-- Notice Card --}}
        <div class="overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03] shadow-2xl shadow-black/30 backdrop-blur-xl">

            {{-- Top Gradient --}}
            <div class="h-1 bg-gradient-to-r from-pink-500 via-pink-500 to-blue-500"></div>

            <div class="p-6 md:p-10">

                {{-- Date --}}
                <div class="mb-5 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-500/10 text-pink-500">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 5.25h13.5A1.5 1.5 0 0120.25 6.75v12A1.5 1.5 0 0118.75 20.25H5.25a1.5 1.5 0 01-1.5-1.5v-12a1.5 1.5 0 011.5-1.5z" />
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500">
                            Notice Date
                        </p>

                        <p class="text-sm font-medium text-gray-300">
                            {{ $notice->notice_date->format('d M Y') }}
                        </p>

                    </div>

                </div>


                {{-- Title --}}
                <h1 class="text-2xl font-bold leading-tight text-white md:text-4xl">
                    {{ $notice->title }}
                </h1>


                {{-- Divider --}}
                <div class="my-7 h-px bg-gradient-to-r from-pink-500/40 via-white/10 to-transparent"></div>


                {{-- Description --}}
                @if($notice->description)

                    <div class="prose prose-invert max-w-none text-sm leading-7 text-gray-300 md:text-base">

                        {!! nl2br(e($notice->description)) !!}

                    </div>

                @else

                    <p class="text-sm text-gray-500">
                        No additional information is available for this notice.
                    </p>

                @endif


                {{-- Attachment --}}
                @if($notice->files)

                    <div class="mt-8 border-t border-white/10 pt-7">

                        <div class="flex flex-col gap-4 rounded-xl border border-blue-500/20 bg-blue-500/5 p-5 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex items-center gap-3">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue-500/10 text-blue-400">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M19.5 14.25v-7.5a2.25 2.25 0 00-2.25-2.25h-6.69a2.25 2.25 0 00-1.59.66l-4.53 4.53a2.25 2.25 0 00-.66 1.59v8.22A2.25 2.25 0 006.03 21h11.22a2.25 2.25 0 002.25-2.25v-4.5z" />
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M8.25 3.75v5.25H3" />
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-gray-200">
                                        Notice Attachment
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        View attached document
                                    </p>

                                </div>

                            </div>

                            <a href="{{ asset('notices/' . $notice->files) }}"
                               target="_blank"
                               class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-pink-500 to-pink-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-pink-500/20 transition hover:-translate-y-0.5 hover:shadow-pink-500/30">

                                View Attachment

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>

                            </a>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</section>

@endsection
