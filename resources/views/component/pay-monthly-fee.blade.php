{{-- Pay Monthly Fee --}}
<div class="w-full mb-6">
    <div class="relative overflow-hidden rounded-2xl border border-slate-700/70 bg-slate-900/90 p-6 shadow-2xl">

        {{-- Background Glow --}}
        <div class="pointer-events-none absolute -right-20 -top-20 h-48 w-48 rounded-full bg-pink-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 right-1/4 h-44 w-44 rounded-full bg-blue-500/10 blur-3xl"></div>

        <div class="relative z-10 flex flex-col gap-5 sm:flex-row sm:items-center">

            {{-- SVG Icon --}}
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl
                        border border-pink-400/20
                        bg-gradient-to-br from-pink-500/20 to-pink-600/10
                        text-pink-400
                        shadow-lg shadow-pink-500/10">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-8 w-8"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.7">

                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 10h18"></path>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M7 15h3"></path>

                </svg>
            </div>


            {{-- Content --}}
            <div class="flex-1">

                <div class="mb-1 flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>

                    <span class="text-xs font-bold uppercase tracking-widest text-blue-400">
                        Fees Payment
                    </span>
                </div>

                <h3 class="text-2xl font-bold text-white">
                    Pay Monthly Fee
                </h3>

                <p class="mt-1 max-w-xl text-sm leading-6 text-slate-400">
                    Pay your monthly course fee securely and conveniently online.
                </p>

            </div>


            {{-- Button --}}
            <div class="shrink-0">

                <a href="{{ route('custom-monthly-fee-pay') }}"
                   class="group inline-flex items-center justify-center gap-2
                          rounded-xl
                          bg-gradient-to-r from-pink-500 to-pink-600
                          px-6 py-3
                          text-sm font-bold text-white
                          shadow-lg shadow-pink-500/20
                          transition-all duration-200
                          hover:-translate-y-0.5
                          hover:from-pink-600 hover:to-pink-700
                          hover:shadow-xl hover:shadow-pink-500/30
                          focus:outline-none focus:ring-2 focus:ring-pink-500/40">

                    <span>Pay Monthly Fee</span>

                    {{-- Arrow SVG --}}
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 transition-transform duration-200 group-hover:translate-x-1"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 12h14"></path>

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m13 6 6 6-6 6"></path>

                    </svg>

                </a>

            </div>

        </div>
    </div>
</div>
