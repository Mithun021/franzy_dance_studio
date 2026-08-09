<style>
    .dropdown-item{

        display:block;

        padding:14px 20px;

        color:#d1d5db;

        transition:.3s;

    }

    .dropdown-item:hover{

        background:rgba(236,72,153,.12);

        color:#ec4899;

        padding-left:28px;

    }

    .mobile-submenu{

        display:block;

        padding:14px 60px;

        color:#d1d5db;

        transition:.3s;

        border-left:3px solid transparent;

    }

    .mobile-submenu:hover{

        color:#ec4899;

        background:rgba(236,72,153,.08);

        border-left-color:#ec4899;

    }
</style>

<!-- ================= HEADER ================= -->
@php

$menus = [

    [
        'title' => 'Home',
        'url'   => '#home',
    ],

    [
        'title' => 'About',
        'url'   => '#about',
    ],

    [
        'title' => 'Admission Form',
        'url'   => route('student.admission-form'),
    ],

    [
        'title' => 'Studio Booking',
        'url'   => route('studio-booking'),
    ],

    [
        'title' => 'Courses',
        'url'   => '#courses',
    ],

    [
        'title' => 'Contact',
        'url'   => '#contact',
    ],

];

// Show My Account only for logged-in students
if (auth()->check() && auth()->user()->user_type == 'student') {

    $menus[] = [

        'title' => 'My Account',

        'children' => [

            [
                'title' => 'My Account',
                'url' => route('student.profile'),
            ],

            [
                'title' => 'ID Card',
                'url' => route('student.id-card'),
            ],

            [
                'title' => 'Certificate',
                'url' => route('student.certificate'),
            ],

            [
                'title' => 'My Courses',
                'url' => route('student.my-courses'),
            ],

            [
                'title' => 'Payment History',
                'url' => route('student.payments'),
            ],
            [
                'title' => 'Logout',
                'url' => route('logout.backend'),
            ],

        ]

    ];

}

@endphp

<header class="fixed top-0 left-0 w-full z-50 bg-[#09090C]/85 backdrop-blur-xl border-b border-white/10">

    <div class="max-w-7xl mx-auto px-5 lg:px-8">

        <div class="flex items-center justify-between h-20">

            <!-- Logo -->

            <a href="/" class="flex items-center gap-3 group">

                {{-- Logo --}}
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Frenzy Dance Studio Logo"
                    class="w-14 h-14 object-contain">

                {{-- Brand --}}
                {{-- <div>

                    <h2 class="text-2xl lg:text-3xl font-extrabold tracking-[4px]">

                        <span class="text-pink-500 group-hover:text-pink-400 transition">
                            FRENZY
                        </span>

                    </h2>

                    <p class="text-[10px] md:text-xs uppercase tracking-[2px] text-gray-400">

                        Dance Studio

                    </p>

                    <div class="mt-1 h-[2px] w-16 rounded-full bg-gradient-to-r from-pink-500 via-purple-500 to-cyan-400"></div>

                </div> --}}

            </a>


            <!-- Desktop Menu -->

            <nav class="hidden lg:flex items-center space-x-10">

                @foreach ($menus as $menu)

                    @if (isset($menu['children']))

                        <div class="relative group">

                            <button class="menu-link font-medium flex items-center gap-2">

                                {{ $menu['title'] }}

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 transition duration-300 group-hover:rotate-180"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"/>

                                </svg>

                            </button>

                            <!-- Dropdown -->

                            <div
                                class="absolute top-full left-0 mt-3 w-64 bg-[#111111] rounded-xl border border-pink-500/20 shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-300 overflow-hidden">

                                @foreach ($menu['children'] as $child)

                                    <a href="{{ $child['url'] }}"
                                        class="dropdown-item">

                                        {{ $child['title'] }}

                                    </a>

                                @endforeach

                            </div>

                        </div>

                    @else

                        <a href="{{ $menu['url'] }}"
                            class="menu-link font-medium">

                            {{ $menu['title'] }}

                        </a>

                    @endif

                @endforeach

            </nav>

            <!-- Right Side -->

            <div class="flex items-center gap-4">

                <!-- Desktop Button -->

                {{-- <a href="{{ route('student.register') }}"
                    class="hidden lg:flex items-center justify-center px-6 py-3 rounded-full bg-pink-600 hover:bg-pink-500 text-white font-semibold transition duration-300 shadow-[0_0_25px_rgba(255,0,140,.35)]">

                    Join Now

                </a> --}}

                @auth

                    @if(auth()->user()->user_type == 'student')

                        <a href="{{ route('student.admission-form') }}"
                        class="hidden lg:flex items-center justify-center px-6 py-3 rounded-full bg-gradient-to-r from-pink-500 to-blue-600 text-white font-semibold transition duration-300 shadow-lg hover:scale-105">

                            👋 Hello {{ explode(' ', auth()->user()->name)[0] }}

                        </a>

                    @else

                        <a href="{{ route('student.register') }}"
                        class="hidden lg:flex items-center justify-center px-6 py-3 rounded-full bg-pink-600 hover:bg-pink-500 text-white font-semibold transition duration-300 shadow-[0_0_25px_rgba(255,0,140,.35)]">

                            Join Now

                        </a>

                    @endif

                @else

                    <a href="{{ route('student.register') }}"
                    class="hidden lg:flex items-center justify-center px-6 py-3 rounded-full bg-pink-600 hover:bg-pink-500 text-white font-semibold transition duration-300 shadow-[0_0_25px_rgba(255,0,140,.35)]">

                        Join Now

                    </a>

                @endauth

                <!-- Mobile Menu -->

                <button id="menuBtn"
                    class="lg:hidden text-white">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />

                    </svg>

                </button>

            </div>

        </div>

    </div>

</header>

<div class="h-20"></div>



<!-- ================= OVERLAY ================= -->

<div id="overlay"
class="overlay fixed inset-0 bg-black/70 backdrop-blur-sm opacity-0 invisible z-40">
</div>





<!-- ================= MOBILE MENU ================= -->

<div id="mobileMenu"
class="mobile-menu fixed top-0 left-[-100%] w-[80%] h-screen bg-[#101014] border-r border-pink-500/20 z-50 shadow-2xl overflow-y-auto">

    <!-- Top -->

    <div class="flex items-center justify-between px-6 py-6 border-b border-white/10">

        <div>

            <h2 class="text-2xl font-extrabold tracking-[3px] text-pink-500">

                FRENZY

            </h2>

            <p class="text-xs text-gray-400 uppercase tracking-widest">

                Dance Studio

            </p>

        </div>

        <button id="closeBtn"
            class="text-white hover:text-pink-500 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-8 h-8"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>

            </svg>

        </button>

    </div>



    <!-- Menu -->

    <nav class="mt-6">

        @foreach ($menus as $menu)

            @if (isset($menu['children']))

                <div>

                    <button
                        id="accountBtn"
                        class="accountBtn w-full flex items-center justify-between px-8 py-5 text-gray-300 hover:text-pink-500 hover:bg-pink-500/10 transition border-l-4 border-transparent hover:border-pink-500">

                        <span>

                            {{ $menu['title'] }}

                        </span>

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="accountArrow w-5 h-5 duration-300"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"/>

                        </svg>

                    </button>

                    <div class="accountMenu hidden bg-[#17171C]">

                        @foreach ($menu['children'] as $child)

                            <a href="{{ $child['url'] }}"
                                class="mobile-submenu">

                                {{ $child['title'] }}

                            </a>

                        @endforeach

                    </div>

                </div>

            @else

                <a href="{{ $menu['url'] }}"
                    class="flex items-center px-8 py-5 text-gray-300 hover:text-pink-500 hover:bg-pink-500/10 transition border-l-4 border-transparent hover:border-pink-500">

                    {{ $menu['title'] }}

                </a>

            @endif

        @endforeach

    </nav>



    <!-- Bottom Button -->

    {{-- <div class="px-6 mt-10">

        <a href="{{ route('student.register') }}"
            class="w-full flex justify-center items-center rounded-full py-4 bg-pink-600 hover:bg-pink-500 transition font-semibold shadow-[0_0_20px_rgba(255,0,140,.35)]">

            Join Now

        </a>

    </div> --}}

    <div class="px-6 mt-10">

        @auth

            @if(auth()->user()->user_type == 'student')

                <a href="{{ route('student.admission-form') }}"
                    class="w-full flex justify-center items-center rounded-full py-4 bg-gradient-to-r from-pink-500 to-blue-600 text-white font-semibold transition duration-300 shadow-lg">

                    👋 Hello {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}

                </a>

            @else

                <a href="{{ route('student.register') }}"
                    class="w-full flex justify-center items-center rounded-full py-4 bg-pink-600 hover:bg-pink-500 text-white font-semibold transition duration-300 shadow-[0_0_20px_rgba(255,0,140,.35)]">

                    Join Now

                </a>

            @endif

        @else

            <a href="{{ route('student.register') }}"
                class="w-full flex justify-center items-center rounded-full py-4 bg-pink-600 hover:bg-pink-500 text-white font-semibold transition duration-300 shadow-[0_0_20px_rgba(255,0,140,.35)]">

                Join Now

            </a>

        @endauth

    </div>

</div>


<script>
    document.querySelectorAll('.accountBtn').forEach(function (button) {

        button.addEventListener('click', function () {

            const parent = button.parentElement;

            const menu = parent.querySelector('.accountMenu');

            const arrow = parent.querySelector('.accountArrow');

            menu.classList.toggle('hidden');

            arrow.classList.toggle('rotate-180');

        });

    });
</script>
