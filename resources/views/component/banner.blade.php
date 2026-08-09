<!-- ================= HERO SECTION ================= -->

<section class="relative h-screen flex items-center justify-center overflow-hidden">

    <!-- Background -->

    <div class="absolute inset-0">

        <img src="{{ asset('images/banner.png') }}"
            class="w-full h-full object-cover object-center"
            alt="Frenzy Dance Studio">

        <!-- Dark Overlay -->

        <div class="absolute inset-0 bg-black/70"></div>

        <!-- Pink Gradient -->

        <div class="absolute inset-0 bg-gradient-to-r from-[#FF008C]/25 via-transparent to-[#7C3AED]/20"></div>

        <!-- Bottom Gradient -->

        <div class="absolute inset-0 bg-gradient-to-t from-[#07070A] via-transparent to-transparent"></div>

    </div>



    <!-- Neon Glows -->

    <div class="absolute -top-32 -left-32 w-[420px] h-[420px] rounded-full bg-pink-600/20 blur-[150px]"></div>

    <div class="absolute -bottom-40 -right-32 w-[450px] h-[450px] rounded-full bg-purple-600/20 blur-[170px]"></div>

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[350px] h-[350px] rounded-full bg-cyan-500/10 blur-[130px]"></div>



    <!-- Hero Content -->

    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">

        <!-- Welcome -->

        <span class="inline-flex items-center px-5 py-2 rounded-full border border-pink-500/30 bg-white/5 backdrop-blur-md text-pink-300 uppercase tracking-[4px] text-xs md:text-sm">

            ✦ Welcome To

        </span>


        <!-- Heading -->

        <h1 class="mt-8 text-3xl md:text-5xl lg:text-6xl font-extrabold uppercase leading-none tracking-wide">

            <span class="text-white">

                FRENZY

            </span>

            <br>

            <span class="bg-gradient-to-r from-pink-500 via-fuchsia-400 to-cyan-400 bg-clip-text text-transparent">

                DANCE STUDIO

            </span>

        </h1>


        <!-- Subtitle -->

        <h2 class="mt-5 text-lg md:text-3xl text-gray-200 font-light tracking-wide">

            A Complete Performing & Fine Art Center

        </h2>


        <!-- Description -->

        <p class="mt-8 max-w-2xl mx-auto text-gray-300 text-sm md:text-lg leading-7 md:leading-8">

            Master Dance, Music, Fine Arts & Yoga with expert mentors.
            Discover your talent and perform with confidence.

        </p>



        <!-- Buttons -->

        <div class="mt-12 flex justify-center gap-4 flex-nowrap">

            <a href="{{ route('login') }}"
                class="px-5 md:px-10 py-3 md:py-4 rounded-full bg-gradient-to-r from-pink-600 to-fuchsia-600 text-white text-sm md:text-base font-semibold shadow-[0_0_30px_rgba(255,0,140,.45)] hover:scale-105 transition duration-300">

                Login / Signup

            </a>


            <a href="{{ route('student.admission-form') }}"
                class="px-5 md:px-10 py-3 md:py-4 rounded-full border border-cyan-400/50 bg-white/5 backdrop-blur-md text-white text-sm md:text-base font-semibold hover:bg-cyan-400 hover:text-black transition duration-300">

                Admission Form

            </a>

        </div>

    </div>



    <!-- Scroll -->

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">

        <div class="w-8 h-12 border-2 border-white/40 rounded-full flex justify-center">

            <div class="w-1 h-3 bg-pink-500 rounded-full mt-2 animate-pulse"></div>

        </div>

    </div>

</section>
