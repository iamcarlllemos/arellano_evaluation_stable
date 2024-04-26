<div>
    <h1 class="text-3xl font-semibold">{{$form['index']['title']}}</h1>
    <p class="text-sm font-medium mt-1 text-slate-900">{{$form['index']['subtitle']}}</p>
    <div class="w-100 block md:flex justify-between items-center gap-2 mt-5">
        <div>
            <a wire:navigate href="{{route('faculty.subject',
                ['evaluate' => $form['id'],
                'semester' => $form['semester']])}}" class="bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md">Go Back</a>
        </div>
    </div>
    <div class="bg-white border shadow rounded-lg mt-8 p-4 sm:p-8">
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
                    <li>Academic Stage: {{
                        to_ordinal($view['faculty']['templates'][0]['curriculum_template'][0]['year_level'], 'year') . ' & ' .
                        to_ordinal($view['faculty']['templates'][0]['curriculum_template'][0]['subject_sem'], 'semester')
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
        <div class="relative my-8 w-100 sm:flex gap-3 justify-end">
            <div class="sm:flex gap-3">
                <div class="relative  text-teal-50 mb-2">
                    <button id="dropdown-button" class="flex items-center gap-1 bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md w-full sm:w-100 justify-center">
                        Settings
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div wire:ignore.self id="dropdown" class="dropdown z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-100 dark:bg-gray-700"
                        style="transform: translate3d(0px, 54px, 0px) !important">
                        <ul class="py-2 px-4 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                            <li class="mt-2">
                                <div class="text-xs uppercase font-bold whitespace-nowrap">
                                    Result Settings
                                </div>
                            </li>
                            <hr class="my-3">
                            <li class="my-2">
                                <div class="flex items-center">
                                    <input id="checkbox-coms" wire:model='display.comments' type="checkbox" wire:change='result_settings()' value="comments" class="w-4 h-4 cursor-pointer text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-coms" class="ms-2 text-sm whitespace-nowrap font-medium text-gray-900 dark:text-gray-300 cursor-pointer">Comments</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="relative z-10 text-teal-50 mb-2">
                    <button id="dropdown-button" class="flex items-center gap-1 bg-slate-900 py-2 px-6 text-white text-sm font-bold rounded-md w-full sm:w-100 justify-center">
                        Download
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div wire:ignore.self id="dropdown" class="w-100 dropdown z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-100 dark:bg-gray-700">
                        <ul class="py-2 px-4 text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap" aria-labelledby="dropdownDefaultButton">
                            <li class="mt-2">
                                <div class="text-xs uppercase font-bold whitespace-nowrap">
                                    Download as
                                </div>
                            </li>
                            <hr class="my-3">
                            <li class="my-2">
                                <a href="javascript:void(0)" wire:click='save_pdf' download>Save as PDF (.pdf)</a>
                            </li>
                            <li class="my-2">
                                <a href="javascript:void(0" wire:click='save_excel'>Save as EXCEL (.xlsx)</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow rounded-lg">
            <table class="w-full text-sm text-left" wire:poll='result_view'>
                <thead class="p-4 text-sm uppercase text-blue-800 border-cyan-50 bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                    <tr>
                        <th class="px-6 py-5 uppercase border-b border-slate-500 bg-white">
                            Total Responses:
                            <span class="bg-orange-900 ms-2 rounded-md px-3 py-2 text-white">
                                {{$view['evaluation_result']['total_responses']}}
                            </span>
                        </th>
                        <th class="px-6 py-5 text-center whitespace-nowrap border-b border-l border-r border-slate-500">
                            Weighted Mean
                        </th>
                        <th class="px-6 py-5 text-center whitespace-nowrap bg-violet-400 text-violet-800 border-b border-l border-r border-slate-500">
                            Mean²
                        </th>
                        <th class="px-6 py-5 text-center whitespace-nowrap border-b border-l border-r border-slate-500">
                            𝜎 (Standard Deviation)
                        </th>
                        <th class="px-6 py-5 text-center whitespace-nowrap border-b border-slate-500">
                            Interpretation
                        </th>
                    </tr>
                </thead>
                @forelse ($view['evaluation_result']['stats'] as $questionnaire)
                    <thead class="p-4 text-sm uppercase text-blue-800 border-cyan-50 bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                        <tr>
                            <th class="px-6 py-3 uppercase border-b border-slate-500">
                                <div class="w-96">
                                    {{ucwords($questionnaire['criteria_name'])}}
                                </div>
                            </th>
                            <th class="px-6 py-3 text-center whitespace-nowrap border-b border-l border-r border-slate-500">

                            </th>
                            <th class="px-6 py-3 text-center whitespace-nowrap bg-violet-400 text-violet-800 border-b border-l border-r border-slate-500">

                            </th>
                            <th class="px-6 py-3 text-center whitespace-nowrap border-b border-l border-r border-slate-500">

                            </th>
                            <th class="px-6 py-3 text-center whitespace-nowrap border-b border-slate-500">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($questionnaire['items'] as $items)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 text-start border-b border-slate-500">
                                    <div class="w-80 uppercase text-xs font-medium">
                                        {{$items['name']}}
                                    </div>
                                </td>
                                <td class="px-14 py-4 text-center font-bold border border-slate-500">
                                    {{number_format($items['weighted_mean'], 2)}}
                                </td>
                                <td class="px-14 py-4 text-center font-bold border bg-violet-400 text-violet-800 border-slate-500">
                                    {{number_format($items['mean_squared'], 2)}}
                                </td>
                                <td class="px-6 py-4 text-center font-bold border border-slate-500">
                                    {{number_format($items['standard_deviation'], 2)}}
                                </td>
                                <td class="px-6 py-4 text-center font-bold border-b border-slate-500 whitespace-nowrap">
                                    {!!to_interpret($items['interpretation'])!!}
                                </td>
                            </tr>
                        @empty
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 text-start border-b border-slate-500">
                                <div class="text-xs font-bold uppercase text-red-500">
                                    No responses yet.
                                </div>
                            </td>
                            <td class="px-14 py-4 text-center font-bold border border-slate-500">
                                {{number_format(0, 2)}}
                            </td>
                            <td class="px-14 py-4 text-center font-bold border bg-violet-400 text-violet-800 border-slate-500">
                                {{number_format(0, 2)}}
                            </td>
                            <td class="px-6 py-4 text-center font-bold border border-slate-500">
                                {{number_format(0, 2)}}
                            </td>
                            <td class="px-6 py-4 text-center font-bold border-b border-slate-500 whitespace-nowrap">
                                {{ 'No responses yet.' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
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
                <tfoot>
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4 text-start border-b border-slate-500">
                            <p class="w-full text-center font-bold tracking-widest text-slate-600">
                                AVERAGES
                            </p>
                        </td>
                        <td class="px-14 py-4 text-center font-bold border border-slate-500">
                            {{number_format($view['evaluation_result']['averages']['mean'], 2)}}
                        </td>
                        <td class="px-14 py-4 text-center font-bold border bg-violet-400 text-violet-800 border-slate-500">
                            {{number_format($view['evaluation_result']['averages']['squared_mean'], 2)}}
                        </td>
                        <td class="px-6 py-4 text-center font-bold border border-slate-500">
                            {{number_format($view['evaluation_result']['averages']['standard_deviation'], 2)}}
                        </td>
                        <td class="px-6 py-4 text-center font-bold border-b border-slate-500 whitespace-nowrap">
                            {!!to_interpret($view['evaluation_result']['averages']['descriptive_interpretation'])!!}
                        </td>
                    </tr>
                    <tr class="bg-white border-b">
                        @if ($view['evaluation_result']['total_responses'] > 0)
                            <td class="px-6 py-4 text-start border-b border-slate-500">
                                <p class="w-full uppercase text-center font-bold tracking-widest text-slate-600">
                                    Descriptive Interpretation
                                </p>
                            </td>
                            <td colspan="12" class="px-6 py-4 text-start border-l border-b border-slate-500">
                                <p class="w-full text-sm font-medium text-slate-600">
                                    The collective weighted mean registers at
                                    <span class="underline">{{number_format($view['evaluation_result']['averages']['mean'], 2)}}</span>,
                                    accompanied by a mean squared figure of <span class="underline">{{number_format($view['evaluation_result']['averages']['squared_mean'], 2)}}</span>
                                    and a standard deviation resting at <span class="underline">{{number_format($view['evaluation_result']['averages']['standard_deviation'], 2)}}</span>.
                                    In essence, the overall interpretation tends towards
                                    <span class="underline uppercase font-bold">{!!strip_tags(to_interpret($view['evaluation_result']['averages']['descriptive_interpretation'])) !!}</span>
                                </p>
                            </td>
                        @else
                            <td colspan="3" class="px-6 py-4 text-start border-b border-slate-500">
                                <p class="w-full uppercase text-center font-bold tracking-widest text-slate-600">
                                    Descriptive Interpretation
                                </p>
                            </td>
                            <td colspan="12" class="px-6 py-4 text-start border-l border-b border-slate-500">
                                <p class="w-full text-sm font-medium text-slate-600">
                                    No responses yet.
                                </p>
                            </td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="grid grid-cols-12 gap-4" wire:poll='result_view'>
            @if ($display['comments'])
                <div class="col-span-12">
                    <div class="p-4 mt-5 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                        <div class="uppercase font-bold">All Comments</div>
                    </div>
                    <div class="relative overflow-hidden shadow rounded-lg mt-2 max-h-56">
                        <table class="w-full text-sm text-left">
                            <tbody>
                                @forelse ($view['evaluation_result']['comments'] as $key => $comments)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 text-start">
                                            <div class="w-100 flex items-center gap-8 uppercase text-xs font-medium">
                                                <div class="hidden sm:block">
                                                    <svg class="w-6 h-6" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools --> <title>ic_fluent_incognito_24_regular</title> <desc>Created with Sketch.</desc> <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="ic_fluent_incognito_24_regular" fill="#212121" fill-rule="nonzero"> <path d="M17.5,12 C19.9852814,12 22,14.0147186 22,16.5 C22,18.9852814 19.9852814,21 17.5,21 C15.3591076,21 13.5674006,19.5049595 13.1119514,17.5019509 L10.8880486,17.5019509 C10.4325994,19.5049595 8.64089238,21 6.5,21 C4.01471863,21 2,18.9852814 2,16.5 C2,14.0147186 4.01471863,12 6.5,12 C8.81637876,12 10.7239814,13.7501788 10.9725684,16.000297 L13.0274316,16.000297 C13.2760186,13.7501788 15.1836212,12 17.5,12 Z M6.5,13.5 C4.84314575,13.5 3.5,14.8431458 3.5,16.5 C3.5,18.1568542 4.84314575,19.5 6.5,19.5 C8.15685425,19.5 9.5,18.1568542 9.5,16.5 C9.5,14.8431458 8.15685425,13.5 6.5,13.5 Z M17.5,13.5 C15.8431458,13.5 14.5,14.8431458 14.5,16.5 C14.5,18.1568542 15.8431458,19.5 17.5,19.5 C19.1568542,19.5 20.5,18.1568542 20.5,16.5 C20.5,14.8431458 19.1568542,13.5 17.5,13.5 Z M12,9.25 C15.3893368,9.25 18.5301001,9.58954198 21.4217795,10.2699371 C21.8249821,10.3648083 22.0749341,10.7685769 21.9800629,11.1717795 C21.8851917,11.5749821 21.4814231,11.8249341 21.0782205,11.7300629 C18.3032332,11.0771247 15.2773298,10.75 12,10.75 C8.72267018,10.75 5.69676679,11.0771247 2.9217795,11.7300629 C2.51857691,11.8249341 2.11480832,11.5749821 2.01993712,11.1717795 C1.92506593,10.7685769 2.17501791,10.3648083 2.5782205,10.2699371 C5.46989988,9.58954198 8.61066315,9.25 12,9.25 Z M15.7002538,3.25 C16.7230952,3.25 17.6556413,3.81693564 18.1297937,4.71158956 L18.2132356,4.88311922 L19.6853587,8.19539615 C19.8535867,8.57390929 19.683117,9.0171306 19.3046038,9.18535866 C18.9576335,9.33956772 18.5562903,9.20917654 18.3622308,8.89482229 L18.3146413,8.80460385 L16.8425183,5.49232692 C16.6601304,5.08195418 16.2735894,4.80422037 15.8336777,4.75711483 L15.7002538,4.75 L8.29974618,4.75 C7.85066809,4.75 7.43988259,4.99042719 7.21817192,5.37329225 L7.15748174,5.49232692 L5.68535866,8.80460385 C5.5171306,9.18311699 5.07390929,9.35358672 4.69539615,9.18535866 C4.34842577,9.03114961 4.17626965,8.64586983 4.27956492,8.29117594 L4.31464134,8.19539615 L5.78676442,4.88311922 C6.20217965,3.94843495 7.09899484,3.32651789 8.10911143,3.25658537 L8.29974618,3.25 L15.7002538,3.25 Z"> </path> </g> </g> </g></svg>
                                                </div>
                                                <div>
                                                    <div class="relative
                                                    before:content-['❝'] before:text-lg before:pl-[-0.5rem] before:text-slate-500
                                                    after:content-['❞'] after:text-lg after:pl-[-0.5rem] after:text-slate-500">
                                                    {{$comments['comment']}}
                                                    </div>
                                                    <div>
                                                        {{ '- ' . $comments['commented_by']}}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-3 text-start">
                                        <div class="text-xs font-bold uppercase text-red-500">
                                            No comments yet.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            <div class="col-span-12">
                <div class="p-4 mt-5 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                    <div class="uppercase font-bold">Graphs / Statistics</div>
                </div>
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 sm:col-span-12 md:col-span-12 lg:col-span-6 xl:col-span-6 2xl:col-span-4" wire:ignore>
                        <div class="relative overflow-hidden shadow rounded-lg mt-2 h-100 p-4">
                            <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                                <div>
                                    Percentage distribution of responses categorized as 'strongly agree,' 'agree,' 'neutral,' and 'disagree' in accordance with the survey questions.
                                </div>
                            </div>
                            <div id="chart-percentage-questions"></div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-12 md:col-span-12 lg:col-span-6 xl:col-span-6 2xl:col-span-4" wire:ignore>
                        <div class="relative overflow-hidden shadow rounded-lg mt-2 h-100 p-4">
                            <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                                <div>
                                    Percentage of students who have provided responses, as opposed to those who haven't responded, with consideration given to the entire pool of respondents.
                                </div>
                            </div>
                            <div id="chart-percentage-responses"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @script('scripts')
    <script type="module">
        $(document).ready(function() {

            chart_fi('#chart-percentage-questions');

            chart_se('#chart-percentage-responses')

            function chart_fi(elem) {
                const options = {
                    chart: {
                        type: 'donut'
                    },
                    series: [
                        {{$view['evaluation_result']['total_interpretation'][4]}},
                        {{$view['evaluation_result']['total_interpretation'][3]}},
                        {{$view['evaluation_result']['total_interpretation'][2]}},
                        {{$view['evaluation_result']['total_interpretation'][1]}},
                    ],
                    labels: ['Strongly Agree', 'Agree', 'Neutral', 'Disagreee'],
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200,
                                height: 400
                            },
                            legend: {
                            position: 'bottom'
                            }
                        }
                    }],
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                },
                                size: '50%'
                            }
                        }
                    },
                    noData: {
                        text: undefined,
                        align: 'center',
                        verticalAlign: 'middle',
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                            color: undefined,
                            fontSize: '14px',
                            fontFamily: undefined
                        }
                    }
                }

                const allZeroValues = options.series.every(value => value === 0);

                if(allZeroValues) {
                    document.querySelector(elem).innerHTML = `
                    <div class="text-xs font-bold uppercase text-red-500">
                        No responses yet.
                    </div>`;
                } else {
                    const chart = new ApexCharts(document.querySelector(elem), options);
                    chart.render();
                }

            }

            function chart_se(elem) {
                const options = {
                    chart: {
                        type: 'bar'
                    },
                    series: [{
                        data: [{
                            x: 'Already responded',
                            y: {{$view['evaluation_result']['respondents']['total_respondents']}},
                        }, {
                            x: 'Not yet responded',
                            y: {{$view['evaluation_result']['respondents']['respondents']}},
                        }, {
                            x: 'Total respondents',
                            y: {{$view['evaluation_result']['respondents']['not_responded']}},
                        }]
                    }],
                    labels: ['Assumed respondents', 'Already responded', 'Not yet responded'],
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200,
                                height: 600
                            },
                            legend: {
                            position: 'bottom'
                            }
                        }
                    }],
                }

                const allZeroValues = options.series.every(value => value === 0);

                if(allZeroValues) {
                    document.querySelector(elem).innerHTML = `
                    <div class="text-xs font-bold uppercase text-red-500">
                        No responses yet.
                    </div>`;
                } else {
                    const chart = new ApexCharts(document.querySelector(elem), options);
                    chart.render();
                }
            }

        });
    </script>
    @endscript
</div>


