<!-- ================= ADMISSION CTA ================= -->

<section class="relative py-16 md:py-24 bg-[#07070A] overflow-hidden">

    <!-- Background Glow -->

    <div class="absolute -top-20 -left-20 w-72 h-72 bg-pink-600/20 rounded-full blur-[120px]"></div>

    <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-cyan-500/20 rounded-full blur-[120px]"></div>


    <div class="relative max-w-5xl mx-auto px-5">

        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-8 md:p-14 shadow-[0_0_40px_rgba(255,0,140,.15)]">

            <!-- Top Label -->

            <span class="inline-block px-4 py-2 rounded-full bg-pink-500/10 border border-pink-500/30 text-pink-400 text-xs md:text-sm uppercase tracking-[3px]">

                Admissions Open

            </span>


            <!-- Heading -->

            <h2 class="mt-6 text-3xl md:text-5xl font-bold leading-tight text-white">

                Turn Your Passion Into
                <span class="text-pink-500">
                    Performance
                </span>

            </h2>


            <!-- Description -->

            <p class="mt-6 text-gray-300 text-sm md:text-lg leading-7 max-w-3xl">

                Join <span class="text-white font-semibold">FRENZY DANCE STUDIO</span> and learn from experienced mentors in Dance, Music, Fine Arts, Yoga, and Stage Performance. Build confidence, improve your skills, and perform on professional stages.

            </p>


            <!-- Button -->

            <div class="mt-8">

                <a href="{{ route('student.admission-form') }}"
                    class="inline-flex items-center gap-3 px-7 md:px-9 py-3 md:py-4 rounded-full bg-gradient-to-r from-pink-600 to-fuchsia-600 text-white font-semibold shadow-[0_0_25px_rgba(255,0,140,.35)] hover:scale-105 transition duration-300">

                    Admission Form

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7" />

                    </svg>

                </a>

            </div>

        </div>

    </div>

</section>
