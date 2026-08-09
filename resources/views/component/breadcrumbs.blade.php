<section class="pt-8 pb-8 bg-gradient-to-b from-[#101014] to-[#09090C] border-b border-white/10">
    <div class="max-w-7xl mx-auto px-5 lg:px-8">

        <div class="flex flex-col gap-2">

            <h1 class="text-3xl md:text-4xl font-bold text-white">
                @yield('title', 'Frenzy Dance')
            </h1>

            <nav class="flex items-center text-sm">

                <a href="/"
                    class="text-gray-400 hover:text-pink-400 transition">
                    Home
                </a>

                <span class="mx-3 text-cyan-400">/</span>

                <span class="text-pink-400 font-medium">
                    @yield('title', 'Frenzy Dance')
                </span>

            </nav>

        </div>

    </div>
</section>
