<div>
    <div class="grid grid-cols-12 gap-3 mt-10">
        @forelse ($school_year as $collection)
            <div wire:poll class="col-span-12 md:col-span-4 bg-slate-100 shadow-lg rounded-lg text-dark relative overflow-hidden">
                <div class="max-w-sms border h-[300px] border-gray-200 rounded-lg shadow bg-slate-800 dark:border-gray-700 relative">
                    <div class="p-5 absolute bottom-2 left-0 w-full">
                        <h5 class="text-2xl font-bold tracking-tight text-white uppercase whitespace-break-spaces line-clamp-1">{{$collection->name}}</h5>
                        <p class="text-sm text-white font-bold line-clamp-2">{{$collection->start_year . '-' . $collection->end_year . ' ('.to_ordinal($collection->semester, 'year').')'}}</p>
                        <div class="mt-3">
                            {!!to_status($collection->status)!!}
                        </div>
                        <hr class="my-4">
                        @if ($collection->status == 1)
                            <div class="mt-3 flex justify-end">
                                <a wire:navigate href="{{route('faculty.subject', ['evaluate' => $collection->id, 'semester' => $collection->semester])}}" class=" bg-blue-100 text-blue-800 p-2 px-4 text-sm font-bold rounded-lg">Open</a>
                            </div>
                        @else
                            <div class="mt-3 flex justify-end">
                                <a href="javascript:void(0)" class=" bg-orange-100 text-orange-800 p-2 px-4 text-sm font-bold rounded-lg">Closed</a>
                            </div>
                        @endif
                    </div>
                    <div class="absolute top-6 left-5 p-4 rounded-full text-slate-100 backdrop-blur-sm bg-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-12">
                <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                    <span class="font-medium">No school years yet.</span>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
