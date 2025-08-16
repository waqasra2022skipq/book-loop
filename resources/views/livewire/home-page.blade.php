<section class="w-full min-h-screen bg-gradient-to-b from-blue-50 to-white flex flex-col">
    <!-- Hero Section -->
    <x-home.hero />

    <!-- Features Section -->
    <x-home.features />

    <!-- Call to Action Section -->
    <x-home.cta />

    <!-- Community Posts Feed -->
    <div class="max-w-2xl w-full mx-auto px-4 py-8">
        @include('livewire.partials.posts-panel')
    </div>
</section>
