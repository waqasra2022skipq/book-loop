<div class="flex justify-center items-center py-6">
    <div class="flex items-start max-md:flex-col w-full max-w-3xl  dark:bg-zinc-900 p-6">
        <div class="flex-1 self-stretch max-md:pt-6">
            {{-- <flux:heading>{{ $heading ?? '' }}</flux:heading> --}}
            {{-- <flux:subheading>{{ $subheading ?? '' }}</flux:subheading> --}}

            <div class="mt-3 w-full max-w-lg">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
