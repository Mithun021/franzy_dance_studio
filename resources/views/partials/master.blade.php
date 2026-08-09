<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRENZY DANCE STUDIO</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FF008C',
                        secondary: '#7C3AED',
                        accent: '#00E5FF',
                        dark: '#07070A',
                        dark2: '#0D0D12',
                        light: '#F5F5F5'
                    }
                }
            }
        }
    </script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        html{
            scroll-behavior:smooth;
        }

        body{
            font-family:'Poppins',sans-serif;
            background:#07070A;
            color:#fff;
            overflow-x:hidden;
        }

        /* Selection */

        ::selection{
            background:#FF008C;
            color:#fff;
        }

        /* Scrollbar */

        ::-webkit-scrollbar{
            width:8px;
        }

        ::-webkit-scrollbar-track{
            background:#111;
        }

        ::-webkit-scrollbar-thumb{
            background:#FF008C;
            border-radius:20px;
        }

        ::-webkit-scrollbar-thumb:hover{
            background:#ff2aa1;
        }

        /* Desktop Menu */

        .menu-link{

            position:relative;
            color:#fff;
            transition:.35s;

        }

        .menu-link::after{

            content:'';
            position:absolute;
            left:0;
            bottom:-6px;
            width:0;
            height:2px;
            background:#FF008C;
            transition:.35s;

        }

        .menu-link:hover{

            color:#FF008C;

        }

        .menu-link:hover::after{

            width:100%;

        }

        /* Mobile Menu */

        .mobile-menu{

            transition:.35s ease;

        }

        .overlay{

            transition:.35s;

        }

        /* Neon Glow */

        .glow{

            box-shadow:
            0 0 10px rgba(255,0,140,.35),
            0 0 30px rgba(255,0,140,.25);

        }

        .glow-text{

            text-shadow:
            0 0 10px rgba(255,0,140,.6),
            0 0 25px rgba(255,0,140,.4);

        }

    </style>

</head>

<body class="bg-dark">

    <!-- Header -->
    @include('partials.header')

    <!-- Content -->
    <main>

        @yield('content')

    </main>


    <!-- Footer -->
    @include('partials.footer')

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>

    @stack('scripts')


<script>

const menuBtn=document.getElementById('menuBtn');

const closeBtn=document.getElementById('closeBtn');

const mobileMenu=document.getElementById('mobileMenu');

const overlay=document.getElementById('overlay');

menuBtn.addEventListener('click',()=>{

    mobileMenu.style.left="0";

    overlay.classList.remove('opacity-0','invisible');

    overlay.classList.add('opacity-100','visible');

});

function closeMenu(){

    mobileMenu.style.left="-100%";

    overlay.classList.remove('opacity-100','visible');

    overlay.classList.add('opacity-0','invisible');

}

closeBtn.addEventListener('click',closeMenu);

overlay.addEventListener('click',closeMenu);

</script>

</body>
</html>
