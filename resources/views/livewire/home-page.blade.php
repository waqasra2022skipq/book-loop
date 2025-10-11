<section class="w-full min-h-screen bg-gradient-to-b from-blue-50 to-white flex flex-col">
    <!-- Hero Section -->
    <x-home.hero />

    <!-- Features Section -->
    <x-home.features />

    <!-- Call to Action Section -->
    <x-home.cta />


    <!-- Popular Genres Section -->
    <x-home.genres :popularGenres="$popularGenres" />
</section>
