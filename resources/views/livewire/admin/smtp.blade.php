<div>
    <h1 class="text-3xl font-semibold">Update SMTP</h1>
    <p class="text-sm font-medium mt-1 text-slate-900">Insert updated or new smtp account.</p>
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 mt-[50px]">
        <div class="p-3 border-b rounded-t dark:border-gray-600">
            <form wire:submit="{{$form['action']}}" class="p-4 md:p-3">
                <div class="grid gap-4 mb-4 grid-cols-12">
                    @foreach($form[$form['action']]['data'] as $key => $item)
                        @if(in_array($item['type'], ['text', 'email', 'date', 'password']))
                            <div class="{{$item['css']}}">
                                <label for="{{$key}}"
                                    class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase"
                                    style="font-size: 12px">
                                    {{$item['label']}}
                                    {!!($item['required']) ? '<span class="text-red-900">*</span>' : ''!!}
                                </label>
                                <input type="{{$item['type']}}" wire:model.live="{{$key}}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="{{$item['placeholder']}}"
                                    {{($item['disabled']) ? 'disabled' : '' }}
                                    >
                                @error($key)
                                    <p class="text-xs text-red-500 font-bold mt-2">{{$message}}</p>
                                @enderror
                            </div>
                        @elseif(in_array($item['type'], ['select']))
                            <div class="{{$item['css']}}"  wire:ignore.self>
                                <label for="{{$key}}" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">
                                    {{$item['label']}}
                                    {!!($item['required']) ? '<span class="text-red-900">*</span>' : ''!!}
                                </label>
                                <select
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    wire:model.live="{{$key}}"
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
                                            wire:model.live="{{$key}}"
                                            type="{{$item['type']}}"
                                            class="hidden"
                                            {{($item['disabled']) ? 'disabled' : ''}}
                                        />
                                    </label>
                                </div>
                                @if(in_array($form['action'], ['create', 'update']))
                                    <div
                                        x-data="{ uploading: false, progress: 0 }"
                                        x-on:livewire-upload-start="uploading = true"
                                        x-on:livewire-upload-finish="uploading = false"
                                        x-on:livewire-upload-cancel="uploading = false"
                                        x-on:livewire-upload-error="uploading = false"
                                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                                    >
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
                                        <div x-show="uploading" class="relative mt-8">
                                            <div class="absolute bottom-[4px] right-0">
                                                <p class="font-medium text-slate-400" style="font-size: 11px;">Please wait... <label x-text="progress + '%'"></label></p>
                                            </div>
                                            <div class="w-full h-1 bg-slate-100 rounded-lg shadow-inner mt-3">
                                                <div class="bg-sky-400 h-1 rounded-lg" :style="{ width: `${progress}%` }"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                <div class="flex items-center justify-end mt-10">
                    <x-alert-message class="me-3" on="alert" message="{{ session('alert')['message'] ?? '' }}">
                    </x-alert-message>
                    <x-button wire:loading.attr="disabled">
                        {{ $form['action'] }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
