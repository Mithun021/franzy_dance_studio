@php

$courses = [

    [
        'title' => 'Bollywood Style',
        'image' => 'images/bollywood.png',
        'description' => 'Learn energetic Bollywood choreography, expressions, stage performance and confidence.'
    ],

    [
        'title' => 'Contemporary',
        'image' => 'images/contemporary.png',
        'description' => 'Improve flexibility, emotions, balance and graceful movement through contemporary dance.'
    ],

    [
        'title' => 'B-Boying',
        'image' => 'images/b-boying.png',
        'description' => 'Learn breaking, footwork, freezes, power moves and freestyle techniques.'
    ],

    [
        'title' => 'Stunt',
        'image' => 'images/stunt.png',
        'description' => 'Train in flips, acrobatics and professional stunt techniques safely.'
    ],

    [
        'title' => 'Gymnastics',
        'image' => 'images/gymnastics.png',
        'description' => 'Build flexibility, strength and body coordination with expert coaching.'
    ],

    [
        'title' => 'Painting',
        'image' => 'images/painting.png',
        'description' => 'Explore sketching, watercolor, acrylic and creative art with professional guidance.'
    ],

    [
        'title' => 'Zumba',
        'image' => 'images/zumba.png',
        'description' => 'Fun cardio workouts combining dance and fitness for all age groups.'
    ],

];

@endphp


<section class="py-24 bg-black">

    <div class="max-w-7xl mx-auto px-4 lg:px-8">

        <!-- Heading -->

        <div class="text-center mb-16">

            <span
                class="inline-flex px-5 py-2 rounded-full bg-pink-600/20 text-pink-500 font-semibold tracking-widest uppercase">

                Our Courses

            </span>

            <h2 class="text-4xl lg:text-5xl font-bold text-white mt-5">

                Explore Our Training Programs

            </h2>

            <div class="w-24 h-1 bg-pink-500 mx-auto mt-5 rounded-full"></div>

            <p class="text-gray-400 mt-6 max-w-3xl mx-auto">

                Master your skills with professional instructors. Whether your
                passion is dance, fitness, gymnastics, or creativity, we have
                the perfect course for you.

            </p>

        </div>

        <!-- Courses -->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

            @foreach($courses as $course)

                <div
                    class="group bg-[#111111] rounded-2xl overflow-hidden border border-gray-800 hover:border-pink-500 transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_0_30px_rgba(236,72,153,.25)]">

                    <div class="overflow-hidden">

                        <img src="{{ asset($course['image']) }}"
                            alt="{{ $course['title'] }}"
                            class="w-full h-64 object-cover group-hover:scale-110 duration-500">

                    </div>

                    <div class="p-6">

                        <h3
                            class="text-2xl font-bold text-white group-hover:text-pink-500 duration-300">

                            {{ $course['title'] }}

                        </h3>

                        <p class="text-gray-400 mt-3">

                            {{ $course['description'] }}

                        </p>

                    </div>

                </div>

            @endforeach


            <!-- CTA Card -->

            <div
                class="rounded-2xl overflow-hidden bg-gradient-to-br from-pink-600 via-pink-500 to-purple-600 flex flex-col justify-center items-center text-center p-8 shadow-[0_0_35px_rgba(236,72,153,.35)]">

                <h3 class="text-3xl font-bold text-white">

                    Join Today

                </h3>

                <p class="text-pink-100 mt-4">

                    Start your journey with India's professional dance academy.

                </p>

                <a href="{{ route('student.admission-form') }}"
                    class="mt-8 bg-white text-pink-600 px-8 py-3 rounded-full font-bold hover:bg-black hover:text-white border border-transparent hover:border-white duration-300">

                    Enroll Now

                </a>

            </div>

        </div>

    </div>

</section>
