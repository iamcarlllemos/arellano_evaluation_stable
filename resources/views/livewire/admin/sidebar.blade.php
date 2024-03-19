<aside id="sidebar" class="sidebar fixed top-0 left-0 z-40 w-[280px] md:w-[320px] h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar" style="background-color: #192231">
    <img src="{{asset('/images/logo-banner.png')}}" alt="" srcset="" class="px-3 w-100 h-[100px] md:h-[110px] mt-8 mb-5">
    @persist('scrollbar')
    <div class="overflow-y-auto py-2" wire:scroll style="height: calc(100vh - 160px)">
        <ul class="list ms-6">
            <li class="py-3">
                <label class="font-display text-xs text-slate-200 uppercase font-bold" style="font-size: 10px">Menu</label>
                <ul class="mt-2 ">
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{route('admin.dashboard')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="py-3">
                <label class="font-display text-xs text-slate-200 uppercase font-bold" style="font-size: 10px">Linking</label>
                <ul class="mt-2 ">
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{route('admin.linking.faculty-template')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Faculty -> Subject</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="#" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Student->Subject</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{route('admin.linking.curriculum-template')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Curriculum Template</span>
                        </a>
                    </li>
                </ul>
            </li>
            @if (auth()->guard('admins')->user()->role === 'superadmin')
            <li class="py-3">
                <label class="font-display text-xs text-slate-200 uppercase font-bold" style="font-size: 10px">Survey</label>
                <ul class="mt-2">
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{route('admin.programs.criteria')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672Zm-7.518-.267A8.25 8.25 0 1 1 20.25 10.5M8.288 14.212A5.25 5.25 0 1 1 17.25 10.5" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Criteria</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{route('admin.programs.questionnaire')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Questionnaire</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <li class="py-3">
                <ul class="mt-3">
                    <label class="font-display text-xs text-slate-200 uppercase font-bold" style="font-size: 10px">Programs</label>
                    @if(auth()->guard('admins')->user()->role === 'superadmin')
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{route('admin.programs.branches')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Branches</span>
                        </a>
                    </li>
                    @endif
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{route('admin.programs.departments')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Departments</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{route('admin.programs.courses')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Courses</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{route('admin.programs.subjects')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Subjects</span>
                        </a>
                    </li>
                    @if(auth()->guard('admins')->user()->role === 'superadmin')
                        <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                            <a wire:navigate href="{{route('admin.programs.school-year')}}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                                <div class="p-2 rounded-lg bg-slate-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                    </svg>
                                </div>
                                <span class="text-xs uppercase font-bold" style="font-size: 10px;">School Year</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="py-3">
                <ul class="mt-3">
                    <label class="font-display text-xs text-slate-200 uppercase font-bold" style="font-size: 10px">Accounts</label>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{ route('admin.accounts.student') }}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Students</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{ route('admin.accounts.faculty') }}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Faculty</span>
                        </a>
                    </li>
                    @if(auth()->guard('admins')->user()->role === 'superadmin')
                        <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                            <a wire:navigate href="{{ route('admin.accounts.administrator') }}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                                <div class="p-2 rounded-lg bg-slate-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </div>
                                <span class="text-xs uppercase font-bold" style="font-size: 10px;">Administrators</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="py-3">
                <label class="font-display text-xs text-slate-200 uppercase font-bold" style="font-size: 10px">Settings</label>
                <ul class="mt-2">
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="#" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 0 0 5.304 0l6.401-6.402M6.75 21A3.75 3.75 0 0 1 3 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 0 0 3.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Themes</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{ route('profile.show') }}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">My Profile</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="#" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Reports</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="#" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
                            <div class="p-2 rounded-lg bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 7.5 3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0 0 21 18V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v12a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <span class="text-xs uppercase font-bold" style="font-size: 10px;">Terminal</span>
                        </a>
                    </li>
                    <li class="ps-3 border-l-2 border-sky-950 hover:border-sky-400 transition ease-in-out duration-400">
                        <a wire:navigate href="{{ route('admin.logout') }}" class="p-2 w-100 flex items-center gap-3 text-slate-400 hover:text-sky-400">
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
