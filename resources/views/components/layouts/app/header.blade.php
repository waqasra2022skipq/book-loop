<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 border-b border-zinc-200 dark:border-zinc-700 px-3 sm:px-6 rounded-b-2xl shadow-sm">
            <!-- Mobile sidebar toggle -->
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <!-- Logo and brand -->
            <a href="{{ route('welcome') }}" class="flex items-center gap-2 rtl:space-x-reverse text-xl font-bold text-blue-700 tracking-tight" wire:navigate>
                <x-app-logo class="h-8 w-8" />
                <span class="hidden sm:inline">Book Loop</span>
            </a>
            <!-- Desktop navigation -->
            <flux:navbar class="-mb-px max-lg:hidden gap-2">
                <flux:navbar.item :href="route('books.all')" :current="request()->routeIs('books.all')" wire:navigate icon="book-open" class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                    {{ __('Explore') }}
                </flux:navbar.item>
                <flux:navbar.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate icon="phone" class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                    {{ __('Contact Us') }}
                </flux:navbar.item>
                @if(auth()->check())
                    <flux:navbar.item :href="route('books.mybooks')" :current="request()->routeIs('books.mybooks')" wire:navigate icon="rectangle-stack" class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                        {{ __('Dashboard') }}
                    </flux:navbar.item>
                @endif
            </flux:navbar>
            <flux:spacer />
            @if(auth()->check())
                <!-- Notification icon: mobile links to page, desktop shows dropdown -->
                <div class="flex items-center gap-1">
                    <!-- Mobile: link to notifications page -->
                    <a href="{{ route('notifications.index') }}" class="md:hidden relative flex items-center justify-center w-10 h-10 rounded-full bg-white shadow hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition mr-1" aria-label="Notifications">
                        <svg class="w-6 h-6 text-zinc-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if(auth()->user()->unreadNotifications->count())
                            <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full animate-pulse">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                    <!-- Desktop: show dropdown -->
                    <livewire:notifications-dropdown />
                    <!-- User profile dropdown -->
                    <flux:dropdown position="top" align="start">
                        @if(auth()->user()->avatar ?? false)
                            <flux:profile avatar="{{ auth()->user()->avatar }}" />
                        @else
                            <flux:button variant="ghost" size="sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-full bg-blue-200 text-blue-700 font-semibold">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </flux:button>
                        @endif
                        <flux:menu>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                                    {{ __('Sign Out') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            @else
                <!-- Login button for guests -->
                <flux:navbar>
                    <flux:navbar.item :href="route('login')" icon="user" wire:navigate class="rounded-lg px-4 py-2 text-base font-medium hover:bg-blue-100 transition">
                        {{ __('Login') }}
                    </flux:navbar.item>
                </flux:navbar>
            @endif
        </flux:header>

        <!-- Mobile sidebar -->
        <flux:sidebar stashable sticky class="lg:hidden bg-gradient-to-b from-blue-50 via-purple-50 to-pink-50 border rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700 w-72 rounded-r-2xl shadow-xl">
            
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            
            <!-- Mobile logo -->
            <div class="flex items-center justify-center py-4 border-b border-zinc-200 dark:border-zinc-700 bg-white/70">
                <a href="{{ route('welcome') }}" class="flex items-center gap-2 rtl:space-x-reverse text-lg font-bold text-blue-700 tracking-tight" wire:navigate>
                    <x-app-logo class="h-7 w-7" />
                    <span class="font-semibold">Book Loop</span>
                </a>
            </div>
            
            <!-- Mobile navigation -->
            <flux:navlist variant="outline" class="px-2 py-4 gap-1">
                <flux:navlist.item :href="route('books.all')" :current="request()->routeIs('books.all')" wire:navigate icon="book-open" class="rounded-lg px-4 py-2 text-base font-semibold flex items-center gap-2 hover:bg-blue-100 focus:bg-blue-200 transition">
                    {{ __('Explore Books') }}
                </flux:navlist.item>
                @if(auth()->check())
                    <flux:navlist.item :href="route('books.create')" :current="request()->routeIs('books.create')" wire:navigate icon="plus-circle" class="rounded-lg px-4 py-2 text-base font-semibold flex items-center gap-2 hover:bg-blue-100 focus:bg-blue-200 transition">
                        {{ __('Add Book') }}
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('books.mybooks')" :current="request()->routeIs('books.mybooks')" wire:navigate icon="rectangle-stack" class="rounded-lg px-4 py-2 text-base font-semibold flex items-center gap-2 hover:bg-blue-100 focus:bg-blue-200 transition">
                        {{ __('My Books') }}
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('mybooks.requests')" :current="request()->routeIs('mybooks.requests')" wire:navigate icon="inbox" class="rounded-lg px-4 py-2 text-base font-semibold flex items-center gap-2 hover:bg-blue-100 focus:bg-blue-200 transition">
                        {{ __('My Requests') }}
                    </flux:navlist.item>
                @endif
                <flux:navlist.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate icon="phone" class="rounded-lg px-4 py-2 text-base font-semibold flex items-center gap-2 hover:bg-blue-100 focus:bg-blue-200 transition">
                    {{ __('Contact Us') }}
                </flux:navlist.item>
            </flux:navlist>
            
            <flux:spacer />
            
            <!-- Mobile bottom actions -->
            <flux:navlist variant="outline" class="px-2 pb-4 gap-1">
                @if(auth()->check())
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:navlist.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-left rounded-lg px-4 py-2 font-semibold flex items-center gap-2 hover:bg-blue-100 focus:bg-blue-200 transition">
                            {{ __('Sign Out') }}
                        </flux:navlist.item>
                    </form>
                @else
                    <flux:navlist.item :href="route('login')" icon="user" wire:navigate class="rounded-lg px-4 py-2 font-semibold flex items-center gap-2 hover:bg-blue-100 focus:bg-blue-200 transition">
                        {{ __('Login') }}
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('register')" icon="user-plus" wire:navigate class="rounded-lg px-4 py-2 font-semibold flex items-center gap-2 hover:bg-blue-100 focus:bg-blue-200 transition">
                        {{ __('Register') }}
                    </flux:navlist.item>
                @endif
            </flux:navlist>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>