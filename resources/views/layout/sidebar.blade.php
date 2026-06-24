<div class="flex">
    <aside id="sidebar"
        class="font-poppins fixed inset-y-0 my-6 ml-4 w-full max-w-72 md:max-w-60 xl:max-w-64 2xl:max-w-64 z-50 rounded-lg bg-white overflow-y-auto transform transition-transform duration-300 -translate-x-full md:translate-x-0 ease-in-out shadow-xl">
        <div class="p-2">
            <div class="p-6">
                <a href="{{ route('dashboard') }}">
                    <div class="w-32 md:w-28 xl:w-32 2xl:w-32 h-auto flex items-center mx-auto">
                        <img src="{{ asset('logo.png') }}" alt="Logo" class="w-full h-auto object-contain">
                    </div>
                </a>
            </div>

            <hr class="mx-5 shadow-2xl text-gray-100 rounded-xl" />

            <ul>
                <!-- Dashboard -->
                <li class="p-4 mx-2">
                    <a href="{{ route('dashboard') }}">
                        <div class="flex space-x-4">
                            <div class="bg-orange-500 p-2 rounded-xl">
                                <i class="material-icons text-white">home</i>
                            </div>
                            <div class="my-auto">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">Dashboard</h1>
                            </div>
                        </div>
                    </a>
                </li>

                <!-- Transactions -->
                <li class="p-4 mx-2">
                    <a href="{{ route('transactions') }}">
                        <div class="flex space-x-4">
                            <div class="bg-orange-500 p-2 rounded-xl">
                                <i class="material-icons text-white">receipt_long</i>
                            </div>
                            <div class="my-auto">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">Transactions</h1>
                            </div>
                        </div>
                    </a>
                </li>

                <!-- Events -->
                <li class="p-4 mx-2">
                    <a href="{{ route('events') }}">
                        <div class="flex space-x-4">
                            <div class="bg-orange-500 p-2 rounded-xl">
                                <i class="material-icons text-white">event</i>
                            </div>
                            <div class="my-auto">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">Events</h1>
                            </div>
                        </div>
                    </a>
                </li>

                @if (auth()->user()->level === 'Organizer')
                    <li class="p-4 mx-2">
                        <a href="{{ route('scanner') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">qr_code_scanner</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Scanner</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a href="{{ route('attendances') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">fence</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Attendance</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a href="{{ route('tickets') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">confirmation_number</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Tickets</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a href="{{ route('submissions') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">task</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Submissions</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                @endif

                <!-- Withdraw -->
                <li class="p-4 mx-2">
                    <a href="{{ route('withdraws') }}">
                        <div class="flex space-x-4">
                            <div class="bg-orange-500 p-2 rounded-xl">
                                <i class="material-icons text-white">payment</i>
                            </div>
                            <div class="my-auto">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">Withdraw</h1>
                            </div>
                        </div>
                    </a>
                </li>

                @if (auth()->user()->level === 'Admin')
                    <hr class="mx-5 shadow-2xl text-gray-100 rounded-xl" />

                    <li class="p-4 mx-2">
                        <a href="{{ route('pricings') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">attach_money</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Pricing</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a href="{{ route('category') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">category</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Category</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a href="{{ route('hots') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">local_fire_department</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Hots</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a href="{{ route('approvals') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">inventory</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Approvals</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a href="{{ route('users') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">person</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Users</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a href="{{ route('organizers') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">group</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Organizers</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a href="{{ route('activities') }}">
                            <div class="flex space-x-4">
                                <div class="bg-orange-500 p-2 rounded-xl">
                                    <i class="material-icons text-white">history</i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Activities</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                @endif

                <hr class="mx-5 shadow-2xl text-gray-100 rounded-xl" />

                <!-- Logout -->
                <li class="p-4 mx-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <div class="flex space-x-4">
                            <div class="bg-orange-500 p-2 rounded-xl">
                                <i class="material-icons rotate-180 text-white">logout</i>
                            </div>
                            <button class="text-gray-500 hover:text-black text-base font-normal" type="submit">
                                Logout
                            </button>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </aside>
</div>
