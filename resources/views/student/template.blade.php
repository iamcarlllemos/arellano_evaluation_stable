@extends('student.layouts.app')
@section('content')

<div class="px-5">
    <div class="fixed flex md:hidden items-center justify-center left-5 bottom-5 bg-slate-900 w-[30px] h-[30px] rounded-md z-50">
        <button type="button" data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" class="hamburger cursor-pointer text-sky-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
    </div>
    <div class="mt-5 pb-8">
        <nav class="text-xs font-medium mb-2">
            <ol class="list-none p-0 inline-flex mt-4">
                @php
                    $breadcrumbs = explode(',', $data['breadcrumbs']);
                    $count = count($breadcrumbs);
                    foreach($breadcrumbs as $index => $item) {
                        echo '<li class="flex items-center">';
                        echo '<a href="javascript:void(0)" class="text-gray-500">'.ucwords($item).'</a>';
                        if ($index < $count - 1) {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">';
                            echo '<path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />';
                            echo '</svg>';
                        }
                        echo '</li>';
                    }
                @endphp
            </ol>
        </nav>
        @livewire($data['livewire']['component'], $data['livewire']['data'])
    </div>
</div>
@endsection
