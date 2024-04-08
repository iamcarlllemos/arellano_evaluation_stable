<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arellano Afears</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body>
    <nav class="bg-white dark:bg-gray-900 fixed w-full z-50 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="https://www.arellano.edu.ph/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="{{ asset('images/aulogo.png') }}" class="h-12 w-auto" alt="Arellano Evaluation Logo">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white" style="color: #328ede; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">ARELLANO UNIVERSITY</span>
            </a>
            <div class="flex gap-3 md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <a href="{{route('student.login')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Student Login</a>
                <a href="{{route('faculty.login')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Faculty Login</a>
                <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a href="/" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="#about" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">About</a>
                    </li>
                    <li>
                        <a href="#contact" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="bg-white dark:bg-gray-900 mt-20 relative z-0">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
            <div class="mr-auto place-self-center lg:col-span-7">
                <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white" style="color: #328ede; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">ARELLANO UNIVERSITY</h1>
                <hr class="mb-6 border-b-2 border-black-300 dark:border-black-700">
                <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400" style="color: #e10032;">FACULTY EVALUATION AND ASSESSMENT RATING SYSTEM</p>
            </div>

            <div class="lg:mt-0 lg:col-span-5 lg:flex">
                <div id="default-carousel" class="relative w-full" data-carousel="slide">
                    <div class="relative h-64 overflow-hidden rounded-lg md:h-96">
                        <div class="duration-700 ease-in-out" data-carousel-item>
                            <img src="{{ asset('images/au-pasig.jpg') }}" class="absolute block w-full h-full object-cover" alt="Slide 1">
                        </div>
                        <div class="duration-700 ease-in-out" data-carousel-item>
                            <img src="{{ asset('images/au-main_1.jpg') }}" class="absolute block w-full h-full object-cover" alt="Slide 2">
                        </div>
                        <div class="duration-700 ease-in-out" data-carousel-item>
                            <img src="{{ asset('images/au-malabon-elisa.jpg') }}" class="absolute block w-full h-full object-cover" alt="Slide 3">
                        </div>
                        <div class="duration-700 ease-in-out" data-carousel-item>
                            <img src="{{ asset('images/au-malabon-rizal.jpg') }}" class="absolute block w-full h-full object-cover" alt="Slide 4">
                        </div>
                        <div class="duration-700 ease-in-out" data-carousel-item>
                            <img src="{{ asset('images/au-pasay-abad.jpg') }}" class="absolute block w-full h-full object-cover" alt="Slide 5">
                        </div>
                        <div class="duration-700 ease-in-out" data-carousel-item>
                            <img src="{{ asset('images/au-pasay-mabini.jpg') }}" class="absolute block w-full h-full object-cover" alt="Slide 6">
                        </div>
                        <div class="duration-700 ease-in-out" data-carousel-item>
                            <img src="{{ asset('images/au-plaridel-1.jpg') }}" class="absolute block w-full h-full object-cover" alt="Slide 7">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="bg-white dark:bg-gray-900">
        <div class="gap-16 items-center py-8 px-4 mx-auto max-w-screen-xl lg:grid lg:grid-cols-2 lg:py-16 lg:px-6">
            <div class="font-light text-gray-500 sm:text-lg dark:text-gray-400">
                <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">ABOUT IN ARELLANO EVALUATION</h2>
                <p class="mb-4">Welcome to the Arellano University Faculty Evaluation and Assessment Rating System, an innovative platform designed to enhance the educational experience across all branches of Arellano University. Our system empowers students to provide valuable feedback on faculty members, ensuring continuous improvement in teaching quality and academic performance.</p>
                <p>At Arellano University, we understand the critical role that faculty members play in shaping the educational journey of our students. With this in mind, we have developed a comprehensive evaluation and assessment system that fosters accountability, transparency, and excellence in teaching. Through this platform, students can anonymously evaluate their professors based on various criteria such as teaching effectiveness, communication skills, responsiveness, and overall engagement in the classroom.</p>
                <p>The Faculty Evaluation and Assessment Rating System is accessible to students across all branches of Arellano University, providing a standardized approach to gather feedback and assess faculty performance. By actively participating in the evaluation process, students contribute to the continuous enhancement of teaching methodologies and academic standards within our institution.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 mt-8">
                <img class="w-full lg:w-3/4 rounded-lg mx-auto order-1" src="{{ asset('images/pic1.png') }}" alt="system content 1">
                <img class="mt-4 w-full lg:w-3/4 rounded-lg mx-auto order-2" src="{{ asset('images/pic2.png') }}" alt="system content 2">
            </div>
        </div>
    </section>

    <footer id="contact" class="p-4 bg-white sm:p-6 dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="https://www.arellano.edu.ph/" class="flex items-center">
                        <img src="{{ asset('images/aulogo.png') }}" class="mr-3 h-8" alt="Au Logo" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">ARELLANO UNIVERSITY</span>
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase dark:text-white">Resources</h2>
                        <ul class="text-gray-600 dark:text-gray-400">
                            <li>
                                <a href="https://flowbite.com" class="hover:underline">Flowbite</a>
                            </li>
                            <li>
                                <a href="https://tailwindcss.com/" class="hover:underline">Tailwind CSS</a>
                            </li>
                            <li>
                                <a href="https://laravel.com/" class="hover:underline">Laravel </a>
                            </li>
                            <li>
                                <a href="https://jetstream.laravel.com/introduction.html" class="hover:underline">Laravel Jetstream</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-3 text-sm font-semibold text-gray-900 uppercase dark:text-white">Follow us</h2>
                        <ul class="text-gray-600 dark:text-gray-400">
                            <li>
                                <a href="https://facebook.com" class="hover:underline">Facebook</a>
                            </li>
                            <li>
                                <a href="https://discord.com" class="hover:underline">Instagram</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-3 text-sm font-semibold text-gray-900 uppercase dark:text-white">Legal</h2>
                        <ul class="text-gray-600 dark:text-gray-400">
                            <li>
                                <a href="#" class="hover:underline">Privacy Policy</a>
                            </li>
                            <li>
                                <a href="#" class="hover:underline">Terms &amp; Conditions</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">2024 <a href="#" class="hover:underline">&copy;Powernap</a>.
                </span>
                <div class="flex mt-4 space-x-6 sm:justify-center sm:mt-0">
                    <a href="https://twitter.com" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="https://linkedin.com" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12s4.477 10 10 10c5.523 0 10-4.477 10-10S17.523 2 12 2zm-1.607 15.562h-2.24V9.712h2.24v7.85zm-1.12-8.923a1.87 1.87 0 110-3.74 1.87 1.87 0 010 3.74zM16.62 16.46h-2.24v-3.32c0-.34-.028-.683-.1-1.005h.07l.07-.024h2.27v4.328h-.07v-.006zm-10.3-4.33h2.26v4.33h-2.26z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="https://instagram.com" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12s4.477 10 10 10c5.523 0 10-4.477 10-10S17.523 2 12 2zm5.52 6.06c.295 0 .535.24.535.536v8.807a.54.54 0 01-.535.535H6.48a.54.54 0 01-.536-.535V8.596c0-.296.24-.536.536-.536h11.04zm-5.52 1.988a3.058 3.058 0 100 6.116 3.058 3.058 0 000-6.116zM12 15.4a3.418 3.418 0 110-6.836 3.418 3.418 0 11.4 0 0012 15.4zm5.113-9.626a1.24 1.24 0 00-.743-.743c-.289-.108-.616-.172-.97-.172s-.681.064-.97.172a1.24 1.24 0 00-.743.743c-.108.289-.172.616-.172.97s.064.681.172.97a1.24 1.24 0 00.743.743c.289.108.616.172.97.172s.681-.064.97-.172a1.24 1.24 0 00.743-.743c.108-.289.172-.616.172-.97s-.064-.681-.172-.97zm-2.115 4.494c-.255.182-.567.308-.904.365-.189.037-.374.055-.553.055-.64 0-1.165-.227-1.577-.681a2.063 2.063 0 01-.607-1.493c0-.592.206-1.085.62-1.478.413-.392.944-.589 1.592-.589.639 0 1.168.198 1.586.594.418.396.628.88.628 1.454 0 .172-.013.36-.038.563-.027.202-.072.4-.137.593zM12 6.27a1.398 1.398 0 00-1.393 1.393A1.398 1.398 0 0012 9.057a1.398 1.398 0 001.393-1.394A1.398 1.398 0 0012 6.27zm5.117-1.86a.75.75 0 100-1.5.75.75 0 000 1.5zM17.5 5h-1V4a.5.5 0 00-.5-.5h-1a.5.5 0 00-.5.5v1h-1a.5.5 0 00-.5.5v1a.5.5 0 00.5.5h1v1a.5.5 0 00.5.5h1a.5.5 0 00.5-.5v-1h1a.5.5 0 00.5-.5v-1a.5.5 0 00-.5-.5z" clip-rule="evenodd"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>


</body>
</html>
