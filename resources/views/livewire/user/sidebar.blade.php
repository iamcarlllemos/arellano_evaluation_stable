<aside id="sidebar" class="sidebar fixed top-0 left-0 z-40 w-[280px] md:w-[320px] h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar" style="background-color: #192231">
    <img src="{{asset('/images/logo-banner.png')}}" alt="" srcset="" class="px-3 w-100 h-[100px] md:h-[110px] mt-8 mb-5">
    @persist('scrollbar')
    <div class="overflow-y-auto py-2" wire:scroll style="height: calc(100vh - 160px)">
        <ul class="list ms-6">
            <li class="py-3">
                <label class="font-display text-xs text-slate-200 uppercase font-bold" style="font-size: 10px">Menu</label>
                <ul class="mt-2 ">
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{route('user.dashboard')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                            </div>                         
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Home</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="py-3">
                <label class="font-display text-xs text-slate-200 uppercase font-bold" style="font-size: 10px">Settings</label>
                <ul class="mt-2">
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">My Profile</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{ route('user.logout') }}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                </svg>
                            </div>                                                                                                                 
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Sign Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    @endpersist
 </aside>
