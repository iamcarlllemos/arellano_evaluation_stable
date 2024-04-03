<div>
    <div class="overview grid grid-cols-12 gap-4 mt-5">
        <div class="col-span-12">
            <div>
                <h1 class="text-3xl font-semibold">{{$data['message']}}</h1>
                <p class="text-sm font-medium mt-1 text-slate-900">Here are the latest updates.</p>
            </div>
            <div class="mt-8">
                <div class="grid grid-cols-12 gap-3" wire:poll>
                    @forelse ($data['counts'] as $key => $value)
                    <div class="col-span-12 sm:col-span-12 md:col-span-12 lg:col-span-6 xl:col-span-4 2xl:col-span-3 bg-slate-100 shadow-lg rounded-lg text-dark relative overflow-hidden">
                        <div wire:ignore.self class="absolute z-10 top-5 right-3 text-teal-50">
                            <button id="dropdown-button" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>
                            </button>
                            <div wire:ignore.self id="drodown" class="dropdown z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                    <li>
                                        <a wire:navigate href="{{route($value['route'])}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">View</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="h-44 bg-slate-800 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative">
                            <div class="p-5 absolute bottom-2 left-0">
                                <h5 class="text-2xl font-bold tracking-tight text-white uppercase whitespace-break-spaces line-clamp-1">Total {{ucwords($key)}}</h5>
                            </div>
                            <div class="absolute top-6 left-5 p-4 rounded-full text-white backdrop-blur-sm bg-white/30">
                                <p class="text-2xl w-6 h-6 text-white flex justify-center items-center font-bold">{{$value['count']}}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
