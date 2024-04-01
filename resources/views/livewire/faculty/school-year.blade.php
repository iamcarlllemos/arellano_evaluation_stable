<div>
    <div class="grid grid-cols-12 gap-3 mt-10">
        <div wire:poll class="col-span-12 md:col-span-4 bg-slate-100 shadow-lg rounded-lg text-dark relative overflow-hidden">
            <div class="max-w-sms border h-[300px] border-gray-200 rounded-lg shadow bg-slate-800 dark:border-gray-700 relative">
                <div class="p-5 absolute bottom-2 left-0 w-full">
                    <h5 class="text-2xl font-bold tracking-tight text-white uppercase whitespace-break-spaces line-clamp-1">{{$school_year->name}}</h5>
                    <p class="text-sm text-white font-bold line-clamp-2">{{$school_year->start_year . '-' . $school_year->end_year . ' ('.to_ordinal($school_year->semester, 'year').')'}}</p>
                    <div class="mt-3">
                        {!!to_status($school_year->status)!!}
                    </div>
                    <hr class="my-4">
                    <div class="mt-3 flex justify-end">
                        <a wire:navigate href="{{route('faculty.subject', ['evaluate' => $school_year->id, 'semester' => $school_year->semester])}}" class=" bg-blue-100 text-blue-800 p-2 px-4 text-sm font-bold rounded-lg">Open</a>
                    </div>
                </div>
                <div class="absolute top-6 left-5 p-4 rounded-full text-slate-100 backdrop-blur-sm bg-white/30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
