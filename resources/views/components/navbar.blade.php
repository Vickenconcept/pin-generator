    <nav class="fixed top-0 z-50 w-full  border-b  bg-gray-900 border-gray-700">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto py-4 px-10">

            <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                type="button"
                class="inline-flex items-center p-2 text-sm  rounded-lg sm:hidden bg-gray-900 focus:outline-none focus:ring-2  text-gray-400 hover:bg-gray-700 focus:ring-gray-600">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                    </path>
                </svg>
            </button>
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />

                <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">Flowbite</span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm  rounded-lg md:hidden  focus:outline-none focus:ring-2  text-gray-400 hover:bg-gray-700 focus:ring-gray-600"
                aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>


            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border  rounded-lg  md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0  bg-gray-900  border-gray-700">
                    <li>
                        <a href="#"
                            class="block py-2 px-3  bg-blue-700 rounded md:bg-transparent  md:p-0 text-white md:text-blue-500"
                            aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('pins.generate') }}"
                            class="block py-2 px-3 rounded md:border-0 md:hover:text-blue-700 md:p-0 text-white md:dark:hover:text-blue-500 hover:bg-gray-700 hover:text-white md:hover:bg-transparent">Generate</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block py-2 px-3 rounded md:border-0 md:hover:text-blue-700 md:p-0 text-white md:dark:hover:text-blue-500 hover:bg-gray-700 hover:text-white md:hover:bg-transparent">Schedule</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block py-2 px-3 rounded md:border-0 md:hover:text-blue-700 md:p-0 text-white md:dark:hover:text-blue-500 hover:bg-gray-700 hover:text-white md:hover:bg-transparent">Template</a>
                    </li>


                    {{-- <li>
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <a href="javascript:void(0)" onclick="logout(this)"
                            class="block py-2 px-3 rounded md:border-0 md:hover:text-blue-700 md:p-0 text-white md:dark:hover:text-blue-500 hover:bg-gray-700 hover:text-white md:hover:bg-transparent">Sign
                            out</a>
                    </form>
                </li> --}}

                    <li>
                        <div class="flex items-center ms-3 space-x-4">
                            <button type="button"
                                class="flex text-sm items-center hover:bg-gray-50 rounded-full focus:ring-4 focus:ring-gray-100 md:space-x-2"
                                aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>

                                <img class="w-8 h-8 rounded-full"
                                    src="https://flowbite.com/docs/images/people/profile-picture-5.jpg"
                                    alt="user photo">
                                @if (auth()->check())
                                    <p class="text-sm font-semibold hidden md:block">{{ auth()->user()->name }} </p>
                                @endif
                                <i class='bx bx-chevron-down text-xl hidden md:block'></i>
                            </button>

                        </div>
                    </li>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow"
                        id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            @if (auth()->check())
                                <p class="text-sm text-gray-500 " role="none">
                                    {{ auth()->user()->name }}
                                </p>
                            @endif
                            @if (auth()->check())
                                <p class="text-xs font-medium text-gray-500 truncate " role="none">
                                    {{ auth()->user()->email }}
                                </p>
                            @endif
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                @if (auth()->check())
                                    <form action="{{ route('auth.logout') }}" method="POST">
                                        @csrf
                                        <a href="javascript:void(0)" onclick="logout(this)"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign
                                            out</a>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign
                                        In</a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </ul>

            </div>
        </div>
    </nav>
