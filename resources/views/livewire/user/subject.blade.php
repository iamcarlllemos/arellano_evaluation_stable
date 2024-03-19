<div>
    <h1 class="text-3xl font-semibold">All Subjects</h1>
    <p class="text-sm font-medium mt-1 text-slate-900">Lists of all subjects related to this school year's semester.</p>
    <div class="w-100 flex justify-between items-center gap-2">
        <div class="mt-[29px]">
            <a wire:navigate href="{{route('user.dashboard')}}" class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Go Back</a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-4 mt-10" wire:poll>
        @forelse ($subjects as $item)
            <div class="col-span-12 md:col-span-6 bg-slate-100 shadow-lg rounded-lg text-dark relative overflow-hidden">                        
                <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative w-full">
                    <img class="relative rounded-lg  object-cover brightness-50" src="{{asset('storage/images/branches/' . $item->courses->departments->branches->image)}}" alt="" />
                    <div class="p-5 absolute bottom-0 left-0 w-full">
                        <h5 class="text-2xl font-bold tracking-tight text-white uppercase whitespace-break-spaces line-clamp-1">{{$item->subjects->name}}</h5>
                        <p class="text text-white font-bold line-clamp-2">{{$item->subjects->code}}</p>
                        <div wire:ignore>
                            @if ($item->is_exists)
                                <span class="uppercase px-4 mt-3 p-2 inline-flex justify-center items-center bg-green-100 text-green-800 text-xs font-medium rounded-full dark:bg-green-900 dark:text-green-300">
                                    <span class="w-2 h-2 me-1 bg-green-500 rounded-full"></span>
                                    Response recorded
                                </span>
                            @else
                                <span class="uppercase px-4 mt-3 p-2 inline-flex justify-center items-center bg-red-100 text-red-800 text-xs font-medium rounded-full dark:bg-red-900 dark:text-red-300">
                                    <span class="w-2 h-2 me-1 bg-red-500 rounded-full"></span>
                                    No response yet
                                </span>
                            @endif
                            <hr class="my-4">
                            @if (!$item->is_exists)
                                <div class="mt-3 flex justify-end">
                                    <a wire:navigate href="{{route('user.evaluate', ['evaluate' => $evaluate, 'template' => $item->id, 'semester' => $semester, 'step' => 1])}}" class=" bg-blue-100 text-blue-800 p-2 px-4 text-sm font-bold rounded-lg uppercase">Start</a>
                                </div>
                            @else
                                <div class="mt-3 flex justify-end">
                                    <a wire:navigate href="{{route('user.evaluate', ['evaluate' => $evaluate, 'template' => $item->id, 'semester' => $semester, 'step' => 1])}}" class=" bg-orange-100 text-orange-800 p-2 px-4 text-sm font-bold rounded-lg uppercase">View Response</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="absolute top-6 left-5 p-4 rounded-full text-slate-100 backdrop-blur-sm bg-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776"></path>
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
                    <span class="font-medium">No records found.</span>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
