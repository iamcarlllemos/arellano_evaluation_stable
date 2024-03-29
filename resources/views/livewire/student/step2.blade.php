<div>
    <h4 class="text-2xl font-bold">Evaluation Form</h4>
<div class="p-4 mt-5 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
    <div class="flex items-center">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
          </svg>
          <span class="font-bold uppercase">Rating Legend!</span>
    </div>
    <hr class="mt-2">
    <ul class="mx-1 mt-2">
        <li><span class="uppercase font-bold">4. Strongly Agree</span> - <span class="font-semibold underline">Exemplary, passionate, dedicated.</span></li>
        <li><span class="uppercase font-bold">3. Agree</span> - <span class="font-semibold underline">Competent, engaging lectures.</span></li>
        <li><span class="uppercase font-bold">2. Neutral</span> - <span class="font-semibold underline">Adequate, neither impressive nor disappointing.</span></li>
        <li><span class="uppercase font-bold">1. Disgree</span> - <span class="font-semibold underline">Lacks expertise, outdated methods.</span></li>
    </ul>
</div>
<div class="p-4 mt-5 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
    <h1 class="text-1xl font-bold uppercase">{{$questionnaire->name}}</h1>
</div>
<div>
    {{$questionnaire}}
    {{-- @forelse ($questionnaire->sorted_items as $item)
        <div class="mt-5" role="alert">
            <div class="px-6 py-2 text-sm text-blue-800 rounded-t-lg border bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                <div class="flex items-center justify-between my-3 me-2 font-bold ">
                    <div class="uppercase">{{ucwords($item['criteria_name'])}}</div>
                    <div class="flex items-center">
                        <div style="margin-left: 108px;">4</div>
                        <div style="margin-left: 108px;">3</div>
                        <div style="margin-left: 108px;">2</div>
                        <div style="margin-left: 108px;">1</div>
                    </div>
                </div>
            </div>
            @foreach($item['item'] as $index => $questionnaire)
                <div class="flex items-center justify-between bg-white border-b border-r border-l px-6 py-5{{ $loop->last ? ' rounded-b-lg shadow' : '' }}">
                    <div class="text-sm font-medium text-justify">{{$questionnaire['name']}}</div>
                    <div class="flex items-center">
                        <div class="flex-fill text-center" style="margin-left: 100px;">
                            <input type="radio" name="response[{{$questionnaire['id']}}]" value="4" class="">
                        </div>
                    
                        <div class="flex-fill text-center" style="margin-left: 100px;">
                            <input type="radio" name="response[{{$questionnaire['id']}}]" value="3" class="">
                        </div>
                    
                        <div class="flex-fill text-center" style="margin-left: 100px;">
                            <input type="radio" name="response[{{$questionnaire['id']}}]" value="2" class="">
                        </div>
                    
                        <div class="flex-fill text-center" style="margin-left: 100px;">
                            <input type="radio" name="response[{{$questionnaire['id']}}]" value="1" class="">
                        </div>
                    </div>
                </div>
            @endforeach
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
    @endforelse --}}
</div>
<div class="flex justify-between mt-10">
    <button 
        wire:click='move(1)'
        class="inline-flex items-center border border-sky-700 text-slate-900 bg-transparent focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        >
        Previous
    </button>
    <button 
        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        >
        Next
    </button>
</div>
</div>