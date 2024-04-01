<div>
    <h1 class="text-3xl font-semibold">{{$form['index']['title']}}</h1>
    <p class="text-sm font-medium mt-1 text-slate-900">{{$form['index']['subtitle']}}</p>
    <div class="relative bg-white rounded-t-xl shadow dark:bg-gray-700 mt-8">
        <div class="md:p-5 border-b rounded-t dark:border-gray-600">
            <form wire:submit='submit'>
                <div class="mb-4 w-full">
                    <label for="search" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">Search By <span class="text-red-900">*</span></label>
                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model.live='type' >
                        <option value="1"> - CHOOSE - </option>
                        <option value="1">Reference Code</option>
                        <option value="2">QR Code</option>
                    </select>
                </div>
                @if ($type == 1)
                    <div class="mb-4">
                        <label for="search" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase" style="font-size: 12px">Reference Code <span class="text-red-900">*</span></label>
                        <div class="flex gap-2">
                            <input type="text" wire:model="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-auto dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="#au_afears_response_" disabled>
                            <input type="text" wire:model="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="01">
                        </div>
                    </div>
                @elseif($type == 2)
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
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or GIF (MAX. 2MB)</p>
                                </div>
                                <input
                                    id="dropzone-file"
                                    wire:model="code"
                                    type="file"
                                    class="hidden"
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
                    @if ($code && method_exists($code, 'getClientOriginalExtension') && in_array($code->getClientOriginalExtension(), ['png', 'jpg', 'jpeg']))
                        <label for="code" class="block mb-1 font-extrabold text-gray-900 dark:text-white uppercase mt-5" style="font-size: 12px">Image Preview</label>
                        <img src="{{ $code->temporaryUrl() }}" class="w-[200px] h-[150px] object-cover object-center rounded-lg">
                    @endif
                @endif
                <div class="flex justify-end">
                    <x-button wire:loading.attr="disabled">
                        {{'Proceed' }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
