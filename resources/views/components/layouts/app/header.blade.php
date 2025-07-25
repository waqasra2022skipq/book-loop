<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <!-- Mobile sidebar toggle -->
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            
            <!-- Logo and brand -->
            <a href="{{ route('welcome') }}" class="flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>
            
            <!-- Desktop navigation -->
            <flux:navbar class="-mb-px max-lg:hidden p-10">
                <flux:navbar.item :href="route('books.all')" :current="request()->routeIs('books.all')" wire:navigate>
                    {{ __('Explore') }}
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
        <flux:sidebar stashable sticky class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
            
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            
            {{-- <!-- Mobile logo -->
            <a href="{{ route('welcome') }}" class="flex items-center space-x-2 rtl:space-x-reverse px-2" wire:navigate>
                <x-app-logo />
            </a> --}}
            
            <!-- Mobile navigation -->
            <flux:navlist variant="outline">
                <flux:navlist.item :href="route('books.all')" :current="request()->routeIs('books.all')" wire:navigate>
                    {{ __('Explore') }}
                </flux:navlist.item>
                @if(auth()->check())
                    <flux:navlist.item :href="route('books.mybooks')" :current="request()->routeIs('books.mybooks')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:navlist.item>
                @endif
            </flux:navlist>
            
            <flux:spacer />
            
            <!-- Mobile bottom actions -->
            <flux:navlist variant="outline">
                @if(auth()->check())
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:navlist.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                            {{ __('Sign Out') }}
                        </flux:navlist.item>
                    </form>
                @else
                    <flux:navlist.item :href="route('login')" icon="user" wire:navigate>
                        {{ __('Login') }}
                    </flux:navlist.item>
                @endif
            </flux:navlist>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>