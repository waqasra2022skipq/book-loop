<x-layouts.app.header :title="$title ?? null" :description="$description ?? null" :ogImage="$ogImage ?? null" :schemaMarkup="$schemaMarkup ?? null">
    {{ $slot }}
</x-layouts.app.header>
