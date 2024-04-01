<x-guest-layout>
    <x-authentication-card>

        <h1 class="text-2xl font-bold border-l-4 ps-3 border-slate-800 uppercase">Faculty Login</h1>

        @if(session()->has('error'))
            <div class="flex items-center p-4 mt-8 mb-5 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <div>
                    <span class="font-medium uppercase text-xs">{{session('error')}}.</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('faculty.login') }}" class="mt-10">
            @csrf
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="username" id="username" value="{{old('username')}}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off"/>
                <label for="username" class="peer-focus:font-bold absolute text-xs text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 uppercase">Username</label>
                @error('username')
                    <p class="uppercase text-[10px] font-extrabold text-red-500 mt-2">{{$message}}</p>
                @enderror
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                <label for="password" class="peer-focus:font-bold absolute text-xs text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 uppercase">Password</label>
                @error('password')
                    <p class="uppercase text-[10px] font-extrabold text-red-500 mt-2">{{$message}}</p>
                @enderror
            </div>
            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="block md:flex items-center justify-end mt-4">
                {{-- @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif --}}
                <x-button class="w-full flex justify-center mt-5 md:mt-0 md:w-auto md:block sm:ms-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
