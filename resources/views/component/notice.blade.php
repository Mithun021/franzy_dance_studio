{{-- Notice Board --}}
<section class="relative overflow-hidden bg-[#080812] py-16 md:py-20" id="notice">

    <div class="pointer-events-none absolute -left-32 top-10 h-72 w-72 rounded-full bg-pink-600/10 blur-3xl"></div>

    <div class="pointer-events-none absolute -right-32 bottom-10 h-72 w-72 rounded-full bg-blue-600/10 blur-3xl"></div>

    @php
        use App\Models\Notice;

        $notices = Notice::where('status', 1)
            ->orderBy('notice_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    @endphp

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <div class="mb-10 text-center">

            <span class="mb-3 inline-block text-sm font-semibold uppercase tracking-[0.25em] text-pink-500">
                Latest Updates
            </span>

            <h2 class="text-3xl font-bold text-white md:text-4xl">
                Notice Board
            </h2>

            <div class="mx-auto mt-4 h-1 w-16 rounded-full bg-gradient-to-r from-pink-500 to-blue-500"></div>

        </div>

        <div class="overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03] shadow-2xl shadow-black/20 backdrop-blur-xl">

            <div class="border-b border-white/10 bg-gradient-to-r from-pink-500/10 to-blue-500/10 px-5 py-4 md:px-7">

                <h3 class="text-lg font-semibold text-white">
                    Latest Notices
                </h3>

            </div>

            @if($notices->count())

                <div class="divide-y divide-white/10">

                    @foreach($notices as $notice)

                        <a href="{{ route('website.notice-details', $notice->id) }}"
                           class="group flex items-center gap-4 px-5 py-5 transition duration-300 hover:bg-white/[0.04] md:px-7">

                            {{-- Date --}}
                            <div class="hidden w-20 shrink-0 text-center sm:block">

                                <div class="rounded-xl border border-pink-500/20 bg-pink-500/5 px-2 py-2">

                                    <div class="text-lg font-bold text-pink-500">
                                        {{ $notice->notice_date->format('d') }}
                                    </div>

                                    <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-500">
                                        {{ $notice->notice_date->format('M Y') }}
                                    </div>

                                </div>

                            </div>

                            {{-- Mobile Date --}}
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-pink-500/10 text-pink-500 sm:hidden">

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

                            {{-- Title --}}
                            <div class="min-w-0 flex-1">

                                <h3 class="text-sm font-medium leading-6 text-gray-200 transition group-hover:text-pink-500 md:text-base">
                                    {{ $notice->title }}
                                </h3>

                                <p class="mt-1 text-xs text-gray-500 sm:hidden">
                                    {{ $notice->notice_date->format('d M Y') }}
                                </p>

                            </div>

                            {{-- Arrow --}}
                            <div class="shrink-0 text-gray-600 transition duration-300 group-hover:translate-x-1 group-hover:text-blue-500">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>

                            </div>

                        </a>

                    @endforeach

                </div>

            @else

                <div class="px-6 py-12 text-center">

                    <p class="text-sm text-gray-500">
                        No notices available.
                    </p>

                </div>

            @endif

        </div>

    </div>

</section>

