<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 px-4 sm:px-6">
            <!-- Mobile sidebar toggle -->
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            
            <!-- Logo and brand -->
            <a href="{{ route('welcome') }}" class="flex items-center space-x-2 rtl:space-x-reverse text-lg sm:text-xl font-bold" wire:navigate>
                <x-app-logo />
            </a>
            
            <!-- Desktop navigation -->
            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item :href="route('books.all')" :current="request()->routeIs('books.all')" wire:navigate>
                    {{ __('Explore') }}
                </flux:navbar.item>
                <flux:navbar.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate>
                    {{ __('Contact Us') }}
                </flux:navbar.item>
                @if(auth()->check())
                    <flux:navbar.item :href="route('books.mybooks')" :current="request()->routeIs('books.mybooks')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:navbar.item>
                @endif
            </flux:navbar>
            
            <flux:spacer />
            
            @if(auth()->check())
                <!-- User profile dropdown -->
                <flux:dropdown position="top" align="start">
                    @if(auth()->user()->avatar ?? false)
                        <flux:profile avatar="{{ auth()->user()->avatar }}" />
                    @else
                        <flux:button variant="ghost" size="sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-full bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white font-semibold">
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
            @else
                <!-- Login button for guests -->
                <flux:navbar>
                    <flux:navbar.item :href="route('login')" icon="user" wire:navigate>
                        {{ __('Login') }}
                    </flux:navbar.item>
                </flux:navbar>
            @endif
        </flux:header>

        <!-- Mobile sidebar -->
        <flux:sidebar stashable sticky class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700 w-64">
            
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            
            <!-- Mobile logo -->
            <div class="flex items-center justify-center py-4 border-b border-zinc-200 dark:border-zinc-700">
                <a href="{{ route('welcome') }}" class="flex items-center space-x-2 rtl:space-x-reverse text-lg font-bold" wire:navigate>
                    <x-app-logo />
                </a>
            </div>
            
            <!-- Mobile navigation -->
            <flux:navlist variant="outline" class="px-2 py-4">
                <flux:navlist.item :href="route('books.all')" :current="request()->routeIs('books.all')" wire:navigate icon="book-open">
                    {{ __('Explore Books') }}
                </flux:navlist.item>
                @if(auth()->check())
                    <flux:navlist.item :href="route('books.create')" :current="request()->routeIs('books.create')" wire:navigate icon="plus-circle">
                        {{ __('Add Book') }}
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('books.mybooks')" :current="request()->routeIs('books.mybooks')" wire:navigate icon="rectangle-stack">
                        {{ __('My Books') }}
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('mybooks.requests')" :current="request()->routeIs('mybooks.requests')" wire:navigate icon="inbox">
                        {{ __('My Requests') }}
                    </flux:navlist.item>
                @endif
                <flux:navlist.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate icon="phone">
                    {{ __('Contact Us') }}
                </flux:navlist.item>
            </flux:navlist>
            
            <flux:spacer />
            
            <!-- Mobile bottom actions -->
            <flux:navlist variant="outline" class="px-2 pb-4">
                @if(auth()->check())
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:navlist.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-left">
                            {{ __('Sign Out') }}
                        </flux:navlist.item>
                    </form>
                @else
                    <flux:navlist.item :href="route('login')" icon="user" wire:navigate>
                        {{ __('Login') }}
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('register')" icon="user-plus" wire:navigate>
                        {{ __('Register') }}
                    </flux:navlist.item>
                @endif
            </flux:navlist>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>