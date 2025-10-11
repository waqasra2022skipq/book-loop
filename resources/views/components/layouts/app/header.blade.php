<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NPSWPVH3" height="0" width="0"
                style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:brand href="{{ route('welcome') }}" logo="{{ asset('images/logo.svg') }}" name="Loop Your Book"
                class="max-lg:hidden dark:hidden" />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:separator vertical variant="subtle" class="my-2" />
                @if (auth()->user())
                    <flux:dropdown class="max-lg:hidden">
                        <flux:navbar.item icon:trailing="chevron-down">My Books</flux:navbar.item>
                        <flux:navmenu>
                            <flux:navmenu.item :href="route('books.my-books')" wire:navigate>My Books
                            </flux:navmenu.item>
                            <flux:navmenu.item :href="route('books.create')" wire:navigate>Add a Book
                            </flux:navmenu.item>
                            <flux:navmenu.item :href="route('my-books.requests')" wire:navigate badge="12">Borrow
                                Requests</flux:navmenu.item>
                            <flux:navmenu.item :href="route('books.loans')" wire:navigate>Book Loans</flux:navmenu.item>

                        </flux:navmenu>
                    </flux:dropdown>
                @endif

                <flux:navbar.item :href="route('books.all')" :current="request()->routeIs('books.all')" wire:navigate
                    icon="book-open" class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                    {{ __('Explore') }}
                </flux:navbar.item>
                <flux:navbar.item :href="route('genres.index')" :current="request()->routeIs('genres.*')" wire:navigate
                    icon="tag" class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                    {{ __('Genres') }}
                </flux:navbar.item>
                <flux:navbar.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate
                    icon="phone" class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                    {{ __('Contact Us') }}
                </flux:navbar.item>
                <flux:navbar.item :href="route('feed')" :current="request()->routeIs('feed')" wire:navigate
                    icon="book-open" class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                    {{ __('Book Posts') }}
                </flux:navbar.item>
                <flux:navbar.item :href="route('ai.books.recommendation')"
                    :current="request()->routeIs('ai.books.recommendation')" wire:navigate
                    class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                    <div class="flex items-center gap-2">
                        🤖 {{ __('AI Recommendations') }}
                    </div>
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-4">
                @if (auth()->user())
                    <flux:navbar.item :href="route('notifications.index')"
                        :current="request()->routeIs('notifications.index')" wire:navigate
                        class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                        <div class="flex items-center gap-1">
                            <svg class="w-6 h-6 text-zinc-700" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if (auth()->user()->unreadNotifications->count())
                                <span
                                    class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full animate-pulse">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </div>
                    </flux:navbar.item>
                @endif
            </flux:navbar>

            @if (auth()->check())
                <!-- Notification icon: mobile links to page, desktop shows dropdown -->

                <!-- User profile dropdown -->
                <flux:dropdown position="top" align="start">
                    @if (auth()->user()->avatar ?? false)
                        <flux:profile avatar="{{ auth()->user()->avatar }}" />
                    @else
                        <flux:button variant="ghost" size="sm">
                            <span
                                class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-full bg-blue-200 text-blue-700 font-semibold">
                                {{ auth()->user()->initials() }}
                            </span>
                        </flux:button>
                    @endif
                    <flux:menu>
                        <flux:menu.item icon="user" :href="route('settings.profile')">Profile</flux:menu.item>
                        <flux:menu.item icon="key" :href="route('settings.password')">Update Password
                        </flux:menu.item>
                        <flux:menu.separator />
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                                {{ __('Sign Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <!-- Login button for guests -->
                <flux:navbar>
                    <flux:navbar.item :href="route('login')" icon="user" wire:navigate
                        class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                        {{ __('Login') }}
                    </flux:navbar.item>
                </flux:navbar>
            @endif

        </flux:header>

        <flux:sidebar stashable sticky
            class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <flux:brand href="{{ route('welcome') }}" logo="{{ asset('images/logo.svg') }}" name="Loop Your Book"
                class="px-2 dark:hidden" />

            @if (auth()->user())
                <flux:navlist variant="outline">
                    <flux:navlist.group expandable heading="My Books" class="">
                        <flux:navlist.item :href="route('books.my-books')" wire:navigate>My Books</flux:navlist.item>
                        <flux:navlist.item :href="route('books.create')" wire:navigate>Add Book</flux:navlist.item>
                        <flux:navlist.item :href="route('my-books.requests')" wire:navigate>Borrow Requests
                        </flux:navlist.item>
                        <flux:navlist.item :href="route('books.loans')">Book Loans</flux:navlist.item>
                    </flux:navlist.group>
                </flux:navlist>
            @endif

            <flux:navlist variant="outline">
                <flux:navlist.group expandable heading="Book Posts" class="">
                    <flux:navlist.item :href="route('feed')">Book Posts</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>


            <flux:navlist.item :href="route('ai.books.recommendation')"
                :current="request()->routeIs('ai.books.recommendation')" wire:navigate icon="book-open"
                class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                <div class="flex items-center gap-2">
                    🤖 {{ __('AI Recommendations') }}
                </div>
            </flux:navlist.item>

            <flux:navlist.item :href="route('books.all')" :current="request()->routeIs('books.all')" wire:navigate
                icon="book-open" class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                {{ __('Explore') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate
                icon="phone" class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                {{ __('Contact Us') }}
            </flux:navlist.item>

        </flux:sidebar>

        <flux:main container>
            {{ $slot }}
        </flux:main>

        @fluxScripts
    </body>

</html>
