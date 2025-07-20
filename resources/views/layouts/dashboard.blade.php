<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />


        {{-- <flux:input as="button" variant="filled" placeholder="Search..." icon="magnifying-glass" /> --}}

        <flux:navlist variant="outline">
            <flux:navlist.group expandable heading="My Books" class="lg:grid">
                <flux:navlist.item :href="route('books.mybooks')">My Books</flux:navlist.item>
                <flux:navlist.item :href="route('books.create')">Add a Book</flux:navlist.item>
                <flux:navlist.item :href="route('mybooks.requests')">Requests</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="cog-6-tooth" :href="route('settings.profile')">Settings</flux:navlist.item>
        </flux:navlist>
    </flux:sidebar>

    <flux:main>
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>
        <flux:separator variant="subtle" />

        {{ $slot }}
    </flux:main>
    </div>
</x-layouts.app>
