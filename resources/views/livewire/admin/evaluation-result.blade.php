<div>
    <h1 class="text-3xl font-semibold">{{$form['index']['title']}}</h1>
    <p class="text-sm font-medium mt-1 text-slate-900">{{$form['index']['subtitle']}}</p>
    @if(in_array($form['action'], ['view']))
        <div class="w-100 block md:flex justify-between items-center gap-2 mt-5">
            <div>
                <a wire:navigate href="{{route('admin.programs.results', ['id' => $form['id']])}}" class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Go Back</a>
            </div>
        </div>
        <div class="bg-white border shadow rounded-lg mt-8 p-8">
            <div class="mb-4">
                <div class="p-4 text-sm text-blue-800 border-t border-r border-l rounded-t-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                    <h1 class="text-1xl font-bold uppercase">Faculty Information</h1>
                </div>
                <div class="bg-white p-4 border shadow-lg rounded-b text-sm font-medium">
                    <ul>
                        <li>Name: {{$view['faculty']['firstname'] . ' ' . $view['faculty']['lastname']}}</li>
                        <li>Subject: {{
                                $view['faculty']['templates'][0]['curriculum_template'][0]['subjects']['name'] . ' (' .
                                $view['faculty']['templates'][0]['curriculum_template'][0]['subjects']['code'] . ')'
                            }}
                        </li>
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
                <ul class="mx-1 mt-2">
                    <li><span class="uppercase font-bold">4. Strongly Agree</span> - <span class="font-semibold underline">Exemplary, passionate, dedicated.</span></li>
                    <li><span class="uppercase font-bold">3. Agree</span> - <span class="font-semibold underline">Competent, engaging lectures.</span></li>
                    <li><span class="uppercase font-bold">2. Neutral</span> - <span class="font-semibold underline">Adequate, neither impressive nor disappointing.</span></li>
                    <li><span class="uppercase font-bold">1. Disgree</span> - <span class="font-semibold underline">Lacks expertise, outdated methods.</span></li>
                </ul>
            </div>

            @forelse ($view['evaluation_result'] as $questionnaire)
                <div class="relative overflow-x-auto shadow rounded-lg">
                    <table class="w-full text-sm text-left">
                        <thead class="p-4 text-sm text-blue-800 border-cyan-50 bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                            <tr>
                                <th class="px-6 py-3 uppercase">
                                    {{ucwords($questionnaire['criteria_name'])}}
                                </th>
                                <th class="px-14 py-3 text-center whitespace-nowrap">
                                    4
                                </th>
                                <th class="px-14 py-3 text-center whitespace-nowrap">
                                    3
                                </th>
                                <th class="px-14 py-3 text-center whitespace-nowrap">
                                    2
                                </th>
                                <th class="px-14 py-3 text-center whitespace-nowrap">
                                    1
                                </th>
                                <th class="px-6 py-3 text-center whitespace-nowrap">
                                    Weighted Mean
                                </th>
                                <th class="px-6 py-3 text-center whitespace-nowrap">
                                    (Mean)²
                                </th>
                                <th class="px-6 py-3 text-center whitespace-nowrap">
                                    σ (Standard Deviation)
                                </th>
                                <th class="px-6 py-3 text-center whitespace-nowrap">
                                    Interpretation
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($questionnaire['items'] as $items)
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-4 text-start">
                                        <div class="w-80 uppercase text-xs font-medium">
                                            {{$items['name']}}
                                        </div>
                                    </td>
                                    <td class="px-14 py-4 text-center font-bold">
                                        {{number_format($items['tally'][4], 2)}}
                                    </td>
                                    <td class="px-14 py-4 text-center font-bold">
                                        {{number_format($items['tally'][3], 2)}}
                                    </td>
                                    <td class="px-14 py-4 text-center font-bold">
                                        {{number_format($items['tally'][2], 2)}}
                                    </td>
                                    <td class="px-14 py-4 text-center font-bold">
                                        {{number_format($items['tally'][1], 2)}}
                                    </td>
                                    <td class="px-14 py-4 text-center font-bold">
                                        {{number_format($items['weighted_mean'], 2)}}
                                    </td>
                                    <td class="px-14 py-4 text-center font-bold">
                                        {{number_format($items['mean_squared'], 2)}}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold">
                                        {{number_format($items['standard_deviation'], 2)}}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold whitespace-nowrap">
                                        {!!$items['interpretation']!!}
                                    </td>
                                </tr>
                            @empty
                            <td colspan="12" class="mt-3">
                                <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 border border-yellow-300 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800" role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                    <span class="font-medium">No responses yet.</span>
                                    </div>
                                </div>
                            </td>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="relative overflow-x-auto shadow rounded-lg mt-5">
                    <table class="w-full text-sm text-left">
                        <thead class="p-4 text-sm text-blue-800 border-cyan-50 bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                            <tr>
                                <th class="px-6 py-3 uppercase">
                                    All Comments
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($questionnaire['comments'] as $key => $comments)
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-4 text-start">
                                        <div class="w-100 uppercase text-xs font-medium">
                                            {{$key + 1 . '). ' . $comments}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            <td colspan="12" class="mt-3">
                                <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 border border-yellow-300 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800" role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                    <span class="font-medium">No comments yet.</span>
                                    </div>
                                </div>
                            </td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
    @else
        <div class="w-100 block md:flex justify-between items-center gap-2 mt-5">
            <div>
                <a wire:navigate href="{{route('admin.programs.school-year')}}" class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Go Back</a>
            </div>
            <div class="w-100 md:flex justify-end md:gap-3 mt-10 md:mt-0">
                <select wire:ignore.self wire:model.live='search.select' class="text-sm bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 w-full mb-5 md:mb-0">
                    @if(count($data['departments']) > 0)
                        <option value=""> - All - </option>
                        @foreach($data['departments'] as $branches)
                            @if (array_key_exists('departments', $branches))
                                <optgroup label="{{$branches['name']}}">
                                    @foreach ($branches['departments'] as $departments)
                                        <option value="{{$departments['id']}}">{{ucwords($departments['name'])}}</option>
                                    @endforeach
                                </optgroup>
                            @else
                                <option value="{{$branches['id']}}">{{ucwords($branches['name'])}}</option>
                            @endif
                        @endforeach
                    @else
                        <option value=""> - create a department first - </option>
                    @endif
                </select>
                <input wire:ignore.self type="search" wire:model.live="search.type" class="bg-transparent rounded-md w-full" placeholder="Search here...">
            </div>
        </div>
        <div class="mt-10">
            <div class="relative shadow-md sm:rounded-lg">
                <table class="w-full overflow-x-hidden text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 whitespace-nowrap">
                                Profile
                            </th>
                            <th class="px-6 py-3 whitespace-nowrap">
                                Employee Number
                            </th>
                            <th class="px-6 py-3 whitespace-nowrap">
                                Faculty Name
                            </th>
                            <th class="px-6 py-3 whitespace-nowrap">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data['faculty'] as $collection)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap dark:text-white">
                                    <img src="{{$collection->image ? asset('storage/images/faculty/' . $collection->image) : 'https://ui-avatars.com/api/?name='.$collection->firstname.'&length=2&bold=true&color=ff0000&background=random'}}" class="rounded-lg w-[50px] h-[50px]">
                                </td>
                                <th scope="row" class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap dark:text-white">
                                    {{'#' . $collection->employee_number}}
                                </th>
                                <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap dark:text-white">
                                    {{ucwords($collection->firstname . ' ' . $collection->lastname)}}
                                </td>
                                <td class="px-6 py-4 font-bold whitespace-nowrap dark:text-white relative">
                                    <button id="dropdown-button" class="bg-slate-800 px-4 py-2">
                                        View
                                    </button>
                                    <div wire:ignore.self id="drodown" class="dropdown absolute w-100 top-[100px] right-[100px] z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700">
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                            <div class="mt-2">
                                                <label for="" class="text-xs px-3 mt-3 uppercase">Select subjects</label>
                                            </div>
                                            <hr class="mt-2">
                                            @foreach ($collection->templates as $template)
                                                <li class="text-xs">
                                                    <a wire:navigate href="{{route('admin.programs.results', ['id' => $form['id'], 'action' => 'view', 'faculty' => $collection->id, 'template' => $template->curriculum_template[0]->id, 'subject' => $template->curriculum_template[0]->subject_id])}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        {{$template->curriculum_template[0]->subjects->name}}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
