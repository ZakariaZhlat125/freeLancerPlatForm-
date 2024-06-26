<!-- header -->
<header class="w-full bg-success   h-fit flex justify-center items-center shadow-lg border-md p-2 px-3 text-white ">

    <!-- navigation -->
    <nav x-data="{ isOpen: false }" @keydown.escape="isOpen = false" id='nav'
        class="flex  items-center justify-between md:flex-rowflex-col md:flex-row  w-full h-fit p-3 px-8"
        :class="{ 'shadow-lg flex-col border-md': isOpen, '': !isOpen }">
        <div class=" flex justify-between md:w-fit w-full">
            <a href="{{ route('home') }}" class="logo-font">
                <p>
                    {{ __('static.title') }}
                </p>
            </a>

            <!-- toggle menu  -->

            <!-- <div class="inline md:hidden cursor-pointer" @click="isNavOpend = !isNavOpend">close</div> -->
            <button @click="isOpen = !isOpen" type="button"
                class="block md:hidden px-2 text-gray-500 hover:text-white focus:outline-none focus:text-white"
                :class="{ 'transition transform-180': isOpen }">
                <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path x-show="isOpen" style='stroke: white; fill: white;' fill-rule="evenodd" clip-rule="evenodd"
                        d="M18.278 16.864a1 1 0 0 1-1.414 1.414l-4.829-4.828-4.828 4.828a1 1 0 0 1-1.414-1.414l4.828-4.829-4.828-4.828a1 1 0 0 1 1.414-1.414l4.829 4.828 4.828-4.828a1 1 0 1 1 1.414 1.414l-4.828 4.829 4.828 4.828z" />
                    <path x-show="!isOpen" style='stroke: white; fill: white;' fill-rule="evenodd"
                        d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z" />
                </svg>
            </button>
        </div>
        <!-- the nav content -->
        <div :class="{ 'block shadow-3xl': isOpen, 'hidden': !isOpen }" @click.away="isOpen = false"
            x-show.transition="true"
            class="hidden md:flex flex-col md:flex-row mt-10 md:mt-0 w-full h-full md:items-center justify-between">
            <div class="mx-6 flex-1 mb-10 md:mb-0 ">
                <ul class="flex flex-col md:flex-row gap-x-4 ">
                    <li class="nav_item font-sm cursor-pointer {{ request()->segment(2) == '' ? 'active_link' : '' }}">
                        <a href="{{ route('home') }}">
                            {{ __('static.Home') }}
                        </a>
                    </li>

                    <li
                        class="nav_item font-sm cursor-pointer {{ request()->segment(2) == 'posts' ? 'active_link' : '' }}">
                        <a href="{{ route('projectlancer') }}">
                            {{ __('static.Available_projects') }}

                        </a>
                    </li>

                    <li
                        class="nav_item font-sm cursor-pointer {{ request()->segment(2) == 'freelancers' ? 'active_link' : '' }}">
                        <a href="{{ route('freelancers') }}">
                            {{ __('static.Service_providers') }}


                        </a>
                    </li>
                </ul>
            </div>



            @if (Auth::guest())
                <div class="flex gap-x-3 ">
                    <a href="{{ route('login') }}"
                        class="mo-btn btn-light-pink-bg p-2 px-6 rounded-full bg-primary-light-pink text-black">
                        {{ __('static.Signup') }}

                    </a>
                    <a href="{{ route('create_user') }}" class="mo-btn btn-light-pink-rounderd">
                        {{ __('static.new_account') }}

                    </a>

                </div>
            @endif
            @if (Auth::check() && !Auth::user()->hasRole('admin'))

                <div @click.away="open = false" class="relative" x-data="{ open: false }">
                    <button @click="open = !open; if (open) markAsRead()"
                        class="flex flex-row items-center w-full px-3 py-1 mt-2 text-sm font-semibold text-left bg-transparent rounded-lg md:w-auto md:mt-0 md:ml-2 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                        <span class="text-lg text-primary relative">
                            <i class="fas fa-bell text-white"></i>
                            <!-- Only show the counter if there are unread notifications -->
                            @if (auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                                <span id='notify-mark'
                                    class="w-4 h-4 notififcationCount text-white text-xs rounded-full absolute top-0 right-0 flex items-center justify-center">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute {{ session()->get('lang') == 'en' ? 'right-0' : 'left-0' }} w-full mt-2 origin-top-right rounded-md shadow-lg z-50 md:w-96"
                        style='z-index: 19999;'>
                        <div class="px-2 py-2 bg-white rounded-md shadow" id='notify'>
                            @foreach (auth()->user()->notifications as $notification)
                                <a class="rounded text-black bg-gray-200 my-2 hover:bg-primary-light-pink border border-primary-light-gray py-2 px-4 block whitespace-no-wrap hover:text-black"
                                    href="{{ $notification->data['url'] }}">
                                    {{ $notification->data['message'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a class="flex relative items-center px-3 py-1 mt-2 text-lg font-semibold text-primary rounded-lg md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                    href="{{ route('inbox.index') }}">
                    <i class="fas fa-envelope text-white "></i>
                    @php
                        $unreadMessageCount = countMessages();
                    @endphp
                    @if ($unreadMessageCount > 0)
                        <span
                            class=" w-4 h-4 notififcationCount text-white text-xs rounded-full absolute top-0 flex items-center justify-center">{{ $unreadMessageCount }}</span>
                    @endif
                </a>
            @endif

            <div class="d-flex align-items-center justify-content-end">
                @if (session()->get('lang') == 'en')
                    <a class="language-link d-flex" href="javascript:void(0);" onclick="changeLanguage('ar')">
                        <span class="align-self-center">{{ __('Arabic') }}</span>
                    </a>
                @else
                    <a class="language-link d-flex" href="javascript:void(0);" onclick="changeLanguage('en')">
                        <span class="align-self-center">{{ __('English') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </nav>
    <script>
        function markAsRead() {
            @if (auth()->check())
                fetch('/mark-notifications-as-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            user_id: {{ auth()->user()->id }}
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('notify-mark').classList.add('hidden');
                        }
                    }).catch(error => console.error('Error:', error));
            @else
                console.warn('User not authenticated.');
            @endif
        }

        function changeLanguage(lang) {
            // Send an AJAX request to update the language
            fetch('{{ route('LanguageSwitcher') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        lang: lang
                    })
                })
                .then(response => {
                    if (response.ok) {
                        // Reload the page to reflect the language change
                        window.location.reload();
                    } else {
                        // Handle errors
                        console.error('Failed to change language');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>

</header>
