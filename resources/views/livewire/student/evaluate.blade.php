<div>
    <h1 class="text-3xl font-semibold">Start Evaluation</h1>
    <p class="text-sm font-medium mt-1 text-slate-900 w-[75%]">Faculty evaluation aims to improve teaching quality, support professional development, enhance student learning, ensure accountability, and satisfy stakeholderss.</p>
    <div class="w-100 flex justify-between items-center gap-2">
        <div class="mt-[29px]">
            <button wire:click='go_back' class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Go Back</button>
        </div>
    </div>
    <div class="mt-10">
        @php
            $step = session('response')['step'] ?? 1;
        @endphp

        <ol class="p-5 sm:flex justify-center gap-5  items-center w-full bg-white border rounded-lg shadow text-sm font-medium text-center text-gray-500 dark:text-gray-400 sm:text-base dark:bg-gray-800 dark:border-gray-700 sm:p-4 sm:space-x-4 rtl:space-x-reverse">
            @for ($i = 1; $i <= 4; $i++)
                @php
                    $isActive = $i <= $step;
                    $class = $isActive ? 'text-blue-600 dark:text-blue-500' : 'text-gray-500 dark:text-gray-400';
                    $borderClass = $isActive ? 'border-blue-600 dark:border-blue-500' : 'border-gray-500 dark:border-gray-400';
                    $textColor = $isActive ? 'text-blue-600 dark:text-blue-500' : 'text-gray-500 dark:text-gray-400';
                    $arrowColor = $isActive ? 'text-blue-600 dark:text-blue-500' : 'text-gray-500 dark:text-gray-400';
                @endphp

                <li class="flex items-center mt-3 sm:mt-0 text-center ms-6">
                    <span class="flex items-center justify-center w-5 h-5 me-2 text-xs border {{ $borderClass }} rounded-full shrink-0 {{ $class }}">
                        {{ $i }}
                    </span>
                    <span class="{{ $textColor }}">
                        @if ($i == 1)
                            Choose Faculty
                        @elseif ($i == 2)
                            Evaluation Form
                        @elseif ($i == 3)
                            Preview Responses
                        @else
                            Finished!
                        @endif
                    </span>
                    <span class="hidden sm:inline-flex"></span>
                    <svg class="w-3 h-3 ms-2 sm:ms-4 rtl:rotate-180 {{ $arrowColor }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 9 4-4-4-4M1 9l4-4-4-4"/>
                    </svg>
                </li>
            @endfor
        </ol>

    </div>
    <div class="bg-white border shadow rounded-lg mt-5 p-5">
        @if (session()->has('response') && session('response')['step'] == 1)
            <h4 class="text-2xl font-bold">Faculty Name &amp; Schedule</h4>
            <p class="my-2 text-sm font-medium">Note: Please ensure that the faculty and schedule align with the details provided in your registration form. This evaluation will be part of your <span class="uppercase font-bold underline">clearance requirements</span>.</p>
            <form wire:submit="move(2)" class="mt-10">
                <div class="grid gap-4 mb-4 grid-cols-12">
                    @foreach($form[$form['action']]['data'] as $key => $item)
                        @if(in_array($item['type'], ['text', 'email', 'date', 'time', 'password']))
                            <div class="{{$item['css']}}">
                                <label for="{{$key}}"
                                    class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase"
                                    style="font-size: 12px">
                                    {{$item['label']}}
                                    {!!($item['required']) ? '<span class="text-red-900">*</span>' : ''!!}
                                </label>
                                <input type="{{$item['type']}}" wire:model="{{$key}}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="{{$item['placeholder']}}"
                                    {{($item['disabled']) ? 'disabled' : '' }}
                                    >
                                @error($key)
                                    <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                @enderror
                            </div>
                        @elseif(in_array($item['type'], ['select']))
                            <div class="{{$item['css']}}" >
                                <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">
                                    {{$item['label']}}
                                    {!!($item['required']) ? '<span class="text-red-900">*</span>' : ''!!}
                                </label>
                                <select
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    wire:model="{{$key}}"
                                    {{($item['disabled']) ? 'disabled' : ''}}>
                                    @if(count($item['options']['data']) > 0)
                                        @if($item['options']['is_from_db'])
                                            <option value=""> - CHOOSE - </option>
                                            @foreach($item['options']['data'] as $option_key => $options)
                                                @if (property_exists($options, $item['options']['group']))
                                                    <optgroup label="{{$options->name}}">
                                                        @foreach ($options->{$item['options']['group']} as $data)
                                                            <option value="{{$data->id}}">{{ucwords($data->name)}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @else
                                                    <option value="{{$options->id}}">{{ucwords($options->name)}}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option value=""> - CHOOSE - </option>
                                            @foreach($item['options']['data'] as $option_key => $options)
                                                <option value="{{$option_key}}">{{$options}}</option>
                                            @endforeach
                                        @endif
                                    @else
                                        <option value=""> - {{$item['options']['no_data']}} - </option>
                                    @endif
                                </select>
                                @error($key)
                                    <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                @enderror
                            </div>
                        @elseif(in_array($item['type'], ['file']))
                            <div class="{{$item['css']}}">
                                <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">
                                    {{$item['label']}}
                                    {!!($item['required']) ? '<span class="text-red-900">*</span>' : ''!!}
                                </label>
                                @if ($image && !method_exists($image, 'getClientOriginalExtension'))
                                    <img src="{{ asset('storage/images/branches/' . $image) }}" class="w-[200px] h-[150px] object-cover object-center rounded-lg">
                                @elseif(session()->has('flash') && session('flash')['status'] == 'success')
                                    <img src="{{ asset('storage/images/branches/' . $image) }}" class="w-[200px] h-[150px] object-cover object-center rounded-lg">
                                @endif
                                <div class="flex items-center justify-center w-full mt-3">
                                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or GIF (MAX. 5MB)</p>
                                        </div>
                                        <input
                                            id="dropzone-file"
                                            wire:model="{{$key}}"
                                            type="{{$item['type']}}"
                                            class="hidden"
                                            {{($item['disabled']) ? 'disabled' : ''}}
                                        />
                                    </label>
                                </div>
                                <div wire:loading wire:target="{{$key}}">Uploading...</div>
                                @if ($image && method_exists($image, 'getClientOriginalExtension') && in_array($image->getClientOriginalExtension(), ['png', 'jpg', 'jpeg']))
                                    <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase mt-5" style="font-size: 12px">Image Preview</label>
                                    <img src="{{ $image->temporaryUrl() }}" class="w-[200px] h-[150px] object-cover object-center rounded-lg">
                                @endif
                                @error($key)
                                    <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                @enderror
                            </div>
                        @endif
                    @endforeach
                </div>
                <label class="text-gray-700 mt-2 font-bold" style="font-size: 14px">Note: Make sure schedule is <span class="uppercase underline">appropriate</span> to your registration form.</label>
                <div class="flex justify-end mt-10">
                    <button
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                        Next
                    </button>
                </div>
            </form>
            </div>
        @elseif(session()->has('response') && session('response')['step'] == 2)
            <h4 class="text-2xl font-bold">Evaluation Form</h4>
            <div class="p-4 mt-5 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="font-bold uppercase">Rating Legend!</span>
                </div>
                <hr class="mt-2">
                <div class="overflow-x-auto py-4">
                    <ul class="mx-1 mt-2">
                        <li class="whitespace-nowrap"><span class="uppercase font-bold">4. Strongly Agree</span> - <span class="font-semibold underline">Exemplary, passionate, dedicated.</span></li>
                        <li class="whitespace-nowrap"><span class="uppercase font-bold">3. Agree</span> - <span class="font-semibold underline">Competent, engaging lectures.</span></li>
                        <li class="whitespace-nowrap"><span class="uppercase font-bold">2. Neutral</span> - <span class="font-semibold underline">Adequate, neither impressive nor disappointing.</span></li>
                        <li class="whitespace-nowrap"><span class="uppercase font-bold">1. Disgree</span> - <span class="font-semibold underline">Lacks expertise, outdated methods.</span></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="p-4 mt-5 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                    <h1 class="text-1xl font-bold uppercase">{{$questionnaire->name}}</h1>
                </div>
                <form wire:submit='move(3)'>
                    <div class="relative overflow-x-auto rounded-lg shadow-lg">
                        @forelse ($questionnaire->sorted_items as $item)
                            <table class="border w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="mt-5  mb-5 rounded-t px-6 py-2 text-sm text-blue-800  border bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                                    <tr>
                                        <th class="px-6 py-3">
                                            <div class="w-80">
                                                {{ucwords($item['criteria_name'])}}
                                            </div>
                                        </th>
                                        <th class="px-14 py-3 text-center">
                                            4
                                        </th>
                                        <th class="px-14 py-3 text-center">
                                            3
                                        </th>
                                        <th class="px-14 py-3 text-center">
                                            2
                                        </th>
                                        <th class="px-14 py-3 text-center">
                                            1
                                        </th>
                                    </tr>
                                </thead>
                                @foreach($item['item'] as $index => $questionnaire)
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="px-6 py-4 text-sm text-slate-800 font-medium text-justify">
                                                <div>
                                                    {{$questionnaire['name']}}
                                                </div>
                                                @if(in_array($questionnaire['id'], session('error') ?? ['dum']))
                                                    <div class="mt-2">
                                                        <label class="text-red-900 font-bold mt-3 uppercase" style="font-size:11px">*This field is required</label>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-14 py-4 text-center font-medium whitespace-nowrap dark:text-white">
                                                <input type="radio" wire:model="responses.{{$questionnaire['id']}}" value="4">
                                            </td>
                                            <td class="px-14 py-4 text-center">
                                                <input type="radio" wire:model="responses.{{$questionnaire['id']}}" value="3">
                                            </td>
                                            <td class="px-14 py-4 text-center">
                                                <input type="radio" wire:model="responses.{{$questionnaire['id']}}" value="2">
                                            </td>
                                            <td class="px-14 py-4 text-center">
                                                <input type="radio" wire:model="responses.{{$questionnaire['id']}}" value="1">
                                            </td>
                                        </tr>
                                    </tbody>

                                @endforeach
                            </table>
                        @empty
                            <div class="col-span-12">
                                <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800" role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                    <span class="font-medium">Currently no survery questionnaires added.</span>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </form>
                <div class="flex justify-between mt-10">
                    <button
                        wire:click='move(1)'
                        class="inline-flex items-center border border-sky-700 text-slate-900 bg-transparent focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                        Previous
                    </button>
                    <button wire:click='move(3)'
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                        Next
                    </button>
                </div>
            </div>
        @elseif(session()->has('response') && session('response')['step'] == 3)
            <h4 class="text-2xl font-bold">Preview Response</h4>
            <div class="mb-4 mt-5">
                <div class="p-4 text-sm text-blue-800 border-t border-r border-l rounded-t-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                    <h1 class="text-1xl font-bold uppercase">Faculty Information</h1>
                </div>
                <div class="bg-white p-4 border shadow-lg rounded-b text-sm font-medium">
                    <ul>
                        <li>Name: {{$faculty['name']}}</li>
                        <li>Subject: {{$faculty['subject']}}</li>
                        <li>Academic Year: {{$faculty['academic_year']}}</li>
                    </ul>
                </div>
            </div>
            <div class="p-4 mt-5 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="font-bold uppercase">Rating Legend!</span>
                </div>
                <hr class="mt-2">
                <div class="overflow-x-auto py-4">
                    <ul class="mx-1 mt-2">
                        <li class="whitespace-nowrap"><span class="uppercase font-bold">4. Strongly Agree</span> - <span class="font-semibold underline">Exemplary, passionate, dedicated.</span></li>
                        <li class="whitespace-nowrap"><span class="uppercase font-bold">3. Agree</span> - <span class="font-semibold underline">Competent, engaging lectures.</span></li>
                        <li class="whitespace-nowrap"><span class="uppercase font-bold">2. Neutral</span> - <span class="font-semibold underline">Adequate, neither impressive nor disappointing.</span></li>
                        <li class="whitespace-nowrap"><span class="uppercase font-bold">1. Disgree</span> - <span class="font-semibold underline">Lacks expertise, outdated methods.</span></li>
                    </ul>
                </div>
            </div>
            <div class="p-4 mt-5 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                <h1 class="text-1xl font-bold uppercase">{{$questionnaire->name}}</h1>
            </div>
            <form wire:submit='move(3)'>
                <div class="relative overflow-x-auto rounded-lg shadow-lg">
                    @forelse ($questionnaire->sorted_items as $item)
                        <table class="border w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="mt-5  mb-5 rounded-t px-6 py-2 text-sm text-blue-800  border bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                                <tr>
                                    <th class="px-6 py-3">
                                        <div class="w-80">
                                            {{ucwords($item['criteria_name'])}}
                                        </div>
                                    </th>
                                    <th class="px-14 py-3 text-center">
                                        4
                                    </th>
                                    <th class="px-14 py-3 text-center">
                                        3
                                    </th>
                                    <th class="px-14 py-3 text-center">
                                        2
                                    </th>
                                    <th class="px-14 py-3 text-center">
                                        1
                                    </th>
                                </tr>
                            </thead>
                            @foreach($item['item'] as $index => $questionnaire)
                                <tbody>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4 text-sm text-slate-800 font-medium text-justify">
                                            {{$questionnaire['name']}}
                                        </td>
                                        <td class="px-14 py-4 text-center font-medium whitespace-nowrap dark:text-white">
                                            <input type="radio" wire:model="responses.{{$questionnaire['id']}}" value="4" disabled>
                                        </td>
                                        <td class="px-14 py-4 text-center">
                                            <input type="radio" wire:model="responses.{{$questionnaire['id']}}" value="3" disabled>
                                        </td>
                                        <td class="px-14 py-4 text-center">
                                            <input type="radio" wire:model="responses.{{$questionnaire['id']}}" value="2" disabled>
                                        </td>
                                        <td class="px-14 py-4 text-center">
                                            <input type="radio" wire:model="responses.{{$questionnaire['id']}}" value="1" disabled>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    @empty
                        <div class="col-span-12">
                            <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800" role="alert">
                                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                </svg>
                                <span class="sr-only">Info</span>
                                <div>
                                <span class="font-medium">Currently no survery questionnaires added.</span>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="mb-4 mt-5">
                    <div class="p-4 text-sm text-blue-800 border-t border-r border-l rounded-t-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                        <h1 class="text-1xl font-bold uppercase">User Comments</h1>
                    </div>
                    <div class="bg-white p-4 border shadow-lg rounded-b text-sm font-medium">
                        <textarea
                            wire:model='comments'
                            name="comments"
                            id="comnents"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 resize-none" rows="8"
                            placeholder="Write something..."
                            {{session('response')['faculty']['is_preview'] ? 'disabled' : '' }}
                            >
                        </textarea>
                        @if(session('error') && session('error')[0] == 'comment')
                            <div class="mt-2">
                                <label class="text-red-900 font-bold mt-3 uppercase" style="font-size:11px">*This field is required</label>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
            <div class="flex justify-{{session('response')['faculty']['is_preview'] ? 'end' : 'between'}} mt-10">
                <button
                    wire:click='move(2)'
                    class="inline-flex items-center border border-sky-700 text-slate-900 bg-transparent focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 {{session('response')['faculty']['is_preview'] ? 'hidden' : '456'}}"
                    >
                    Previous
                </button>
                <button wire:click='move(4)'
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                    Next
                </button>
            </div>
        @elseif(session()->has('response') && session('response')['step'] == 4)
            <div class="px-5">
                <h4 class="text-2xl uppercase font-bold">Thank you for your evaluation</h4>
                <h6 class="text-md mt-1 mb-3 font-medium">Your response has been recorded.</h6>
                <hr class="my-3">
                <p class="text-sm text-slate-900 font-medium">Thank you for completing the faculty evaluation. Your feedback is valuable in enhancing the teaching and learning experience. Your participation contributes to the continuous improvement of our educational environment. We appreciate your time and thoughtful responses.</p>
                <p class="mt-3 text-sm text-slate-900 font-medium">Please note: The QR code provided below serves as a means to trace your response. It can be utilized as evidence of your submission, a prerequisite for clearance requirements.</p>
                <div class="mt-5">
                    {{session('response')['faculty']['qr_code']}}
                    <a href="javascript:void(0)" wire:click='download_qr' class="text-xs uppercase mt-2 underline text-sky-800">Download Qr</a>
                </div>
                <div class="mt-5">
                    <p class="text-xs uppercase font-bold">Reference: #{{session('response')['faculty']['reference']}}</p>
                    <p class="text-xs uppercase font-bold text-slate-800">Submitted {{session('response')['faculty']['date_submitted']}}</p>
                </div>
                <div class="sm:flex justify-between mt-10 w-full">
                    <button
                        wire:click='move(3)'
                        class="w-full sm:w-100 items-center border border-sky-700 text-slate-900 bg-transparent focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                        Previous
                    </button>
                    <button wire:navigate
                        href="{{route('student.subject', ['evaluate' => $evaluate, 'semester' => $semester])}}"
                        class="w-full sm:w-100 mt-3 sm:mt-0 text-white  items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                        Return Subjects
                </button>
                </div>
            </div>
        @endif
    </div>
</div>
