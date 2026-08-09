@extends('partials.master')

@section('title', 'Student Registration')

@section('content')

@include('component.breadcrumbs')

<div class="min-h-screen bg-gradient-to-br from-pink-50 via-white to-blue-50 py-8">

    <div class="max-w-6xl mx-auto px-4">

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-pink-100">

            <!-- Header -->
            <div class="bg-gradient-to-r from-pink-500 via-pink-600 to-blue-600 px-8 py-6">

                <div class="flex items-center justify-between">

                    <div>
                        <h1 class="text-3xl font-bold text-white">
                            Student Registration
                        </h1>

                        <p class="text-pink-100 mt-1">
                            Create a new student account
                        </p>
                    </div>

                    <div
                        class="hidden md:flex h-16 w-16 rounded-full bg-white/20 items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-8 h-8 text-white"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>

                        </svg>

                    </div>

                </div>

            </div>

            <!-- Form -->
            <form action="{{ route('student.store.register') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="p-8">

                    <div class="grid lg:grid-cols-3 gap-8">

                        <!-- ===================================== -->
                        <!-- LEFT SIDE -->
                        <!-- ===================================== -->

                        <div>

                            <div class="bg-pink-50 border border-pink-200 rounded-2xl p-6">

                                <div class="mb-5">

                                    <h2 class="text-lg font-bold text-gray-800">
                                        Profile Picture
                                    </h2>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Upload a square image (Recommended Size: <span class="font-medium text-pink-600">500 × 500 px</span>)
                                    </p>

                                </div>

                                <!-- Preview -->

                                <div class="flex justify-center">

                                    <img
                                        id="preview-image"
                                        src="https://placehold.co/180x180?text=Photo"
                                        class="h-44 w-44 rounded-full border-4 border-pink-300 object-cover shadow-lg">

                                </div>

                                <!-- Upload -->

                                <div class="mt-6">

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">

                                        Profile Image

                                    </label>

                                    <input
                                        type="file"
                                        name="profile_image"
                                        id="profile_image"
                                        accept="image/*"
                                        class="block w-full rounded-xl border border-gray-300
                                        text-gray-700
                                        file:bg-pink-500
                                        file:text-white
                                        file:border-0
                                        file:px-4
                                        file:py-2
                                        file:mr-4
                                        file:rounded-lg
                                        hover:file:bg-pink-600">

                                    @error('profile_image')

                                        <span class="text-red-500 text-sm">

                                            {{ $message }}

                                        </span>

                                    @enderror

                                </div>

                            </div>

                        </div>

                        <!-- ===================================== -->
                        <!-- RIGHT SIDE -->
                        <!-- ===================================== -->

                        <div class="lg:col-span-2">

                            <div class="grid md:grid-cols-2 gap-6">

                                <!-- Name -->

                                <div>

                                    <label class="block mb-2 text-sm font-medium text-gray-700">
                                        Full Name
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="Enter Full Name"
                                        class="w-full h-14 px-4 rounded-xl border border-gray-300 bg-white
                                            text-gray-700
                                            focus:border-pink-500
                                            focus:ring-2
                                            focus:ring-pink-200
                                            outline-none
                                            transition duration-200">

                                    @error('name')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror

                                </div>

                                <!-- Email -->

                                <div>

                                    <label class="block mb-2 text-sm font-medium text-gray-700">
                                        Email Address
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="Enter Email Address"
                                        class="w-full h-14 px-4 rounded-xl border border-gray-300 bg-white
                                            text-gray-700
                                            focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                            outline-none transition duration-200">

                                    @error('email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror

                                </div>

                                <!-- Password -->

                                <div>

                                    <label class="block mb-2 text-sm font-medium text-gray-700">
                                        Password
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div class="relative">

                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            placeholder="Enter Password"
                                            class="w-full h-14 px-4 pr-12 rounded-xl border border-gray-300 bg-white
                                                text-gray-700 focus:border-pink-500 focus:ring-2 focus:ring-pink-200
                                                outline-none transition duration-200">

                                        <button
                                            type="button"
                                            id="togglePassword"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-pink-500">

                                            👁

                                        </button>

                                    </div>

                                    @error('password')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror

                                </div>

                                <div>

                                    <label class="block mb-2 text-sm font-medium text-gray-700">
                                        Confirm Password
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div class="relative">

                                        <input
                                            type="password"
                                            name="password_confirmation"
                                            id="password_confirmation"
                                            placeholder="Confirm Password"
                                            class="w-full h-14 px-4 pr-12 rounded-xl border border-gray-300 bg-white
                                                text-gray-700 focus:border-pink-500 focus:ring-2 focus:ring-pink-200
                                                outline-none transition duration-200">

                                        <button
                                            type="button"
                                            id="toggleConfirmPassword"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-pink-500">

                                            👁

                                        </button>

                                    </div>

                                </div>

                                <!-- Phone -->

                                <div>

                                    <label class="block mb-2 text-sm font-medium text-gray-700">
                                        Phone Number
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        placeholder="Enter Phone Number"
                                        class="w-full h-14 px-4 rounded-xl border border-gray-300 bg-white
                                            text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                            outline-none transition duration-200">

                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror

                                </div>

                                <!-- WhatsApp Number -->
                                <div>

                                    <label class="block mb-2 text-sm font-medium text-gray-700">
                                        WhatsApp Number
                                    </label>

                                    <input
                                        type="text"
                                        name="whatsapp_no"
                                        value="{{ old('whatsapp_no') }}"
                                        placeholder="Enter WhatsApp Number"
                                        class="w-full h-14 px-4 rounded-xl border border-gray-300 bg-white
                                            text-gray-700 focus:border-green-500 focus:ring-2 focus:ring-green-200
                                            outline-none transition duration-200">

                                    @error('whatsapp_no')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror

                                </div>

                                <!-- Date of Birth -->
                                <div>

                                    <label class="block mb-2 text-sm font-medium text-gray-700">
                                        Date of Birth
                                    </label>

                                    <input
                                        type="date"
                                        name="date_of_birth"
                                        value="{{ old('date_of_birth') }}"
                                        class="w-full h-14 px-4 rounded-xl border border-gray-300 bg-white
                                            text-gray-700 focus:border-pink-500 focus:ring-2 focus:ring-pink-200
                                            outline-none transition duration-200">

                                    @error('date_of_birth')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror

                                </div>

                                <!-- Gender -->

                            <div>

                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    Gender
                                </label>

                                <select
                                    name="gender"
                                    class="w-full h-14 px-4 rounded-xl border border-gray-300 bg-white
                                        text-gray-700 focus:border-pink-500 focus:ring-2 focus:ring-pink-200
                                        outline-none transition duration-200">

                                    <option value="">Select Gender</option>

                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>
                                        Male
                                    </option>

                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                        Female
                                    </option>

                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>
                                        Other
                                    </option>

                                </select>

                                @error('gender')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror

                            </div>

                            </div>

                            <!-- Buttons -->

                            <div class="mt-10 border-t pt-6">

                                <div class="flex flex-col md:flex-row justify-between items-center gap-4">

                                    <!-- Left Side -->
                                    {{-- <button
                                        type="reset"
                                        class="w-full md:w-auto px-8 h-12 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-100 transition">

                                        Reset

                                    </button> --}}

                                    <!-- Right Side -->
                                    <div class="flex flex-col sm:flex-row items-center gap-4">

                                        <p class="text-sm text-gray-600">
                                            Already have an account?
                                            <a href="{{ route('login') }}"
                                            class="font-semibold text-pink-600 hover:text-blue-600 transition">
                                                Login Now
                                            </a>
                                        </p>

                                        <button
                                            type="submit"
                                            class="w-full sm:w-auto px-8 h-12 rounded-xl bg-gradient-to-r from-pink-500 to-blue-600 text-white font-semibold hover:from-pink-600 hover:to-blue-700 transition shadow-lg">

                                            Register Student

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Image Preview --}}
<script>

document.getElementById('profile_image').addEventListener('change', function(e){

    const file = e.target.files[0];

    if(file){

        document.getElementById('preview-image').src = URL.createObjectURL(file);

    }

});

</script>

{{-- Password Toggle --}}
<script>

const togglePassword = document.getElementById('togglePassword');

const password = document.getElementById('password');

togglePassword.addEventListener('click', function () {

    if(password.type === 'password'){

        password.type = 'text';

        this.innerHTML = '🙈';

    }else{

        password.type = 'password';

        this.innerHTML = '👁';

    }

});

</script>

<script>

const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

const confirmPassword = document.getElementById('password_confirmation');

toggleConfirmPassword.addEventListener('click', function () {

    if (confirmPassword.type === 'password') {

        confirmPassword.type = 'text';

        this.innerHTML = '🙈';

    } else {

        confirmPassword.type = 'password';

        this.innerHTML = '👁';

    }

});

</script>

@endsection
