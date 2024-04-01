<div>
    <div class="m-auto relative max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 mt-[50px]">
            <div class="p-5">
                <h1 class="uppercase font-bold">Faculty Information</h1>
                <hr class="mt-2">
            </div>
            <div class="block md:flex items-center px-5 border-b rounded-t dark:border-gray-600 gap-5 pb-4">
                <div>
                    <img src="{{$user->image ? asset('storage/images/student/' . $user->image) : 'https://ui-avatars.com/api/?name='.$user->firstname.'&length=2&bold=true&color=ff0000&background=random'}}" class="rounded-lg w-[150px] h-[150px]">
                </div>
                <div class="mb-3">
                    <ul class="list-none mt-3">
                        <li>Employee #: <span class="underline">{{$user->employee_number}}</span></li>
                        <li>Full Name: <span class="underline">{{$user->firstname . ' ' .$user->lastname}}</span></li>
                        <li>Gender: <span class="underline">{{to_gender($user->gender)}}</span></li>
                        <li>Username: <span class="underline">{{$user->username}}</span></li>
                        <li>Department: <span class="underline">{{$user->departments->name}}</span></li>
                        <li>Branch: <span class="underline">{{$user->departments->branches->name}}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
