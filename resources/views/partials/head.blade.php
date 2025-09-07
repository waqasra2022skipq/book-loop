<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>
<meta name="description" content="{{ $description ?? 'Explore, Borrow, Lend Books for free' }}">

{{-- Open Graph Meta Tags --}}
<meta property="og:title" content="{{ $title ?? config('app.name') }}">
<meta property="og:description" content="{{ $description ?? 'Explore, Borrow, Lend Books for free' }}">
<meta property="og:image"
    content="{{ isset($ogImage) && $ogImage ? asset('storage/' . $ogImage) : asset('images/logo.svg') }}" />
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:type" content="website">

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? config('app.name') }}">
<meta name="twitter:description" content="{{ $description ?? 'Explore, Borrow, Lend Books for free' }}">
<meta name="twitter:image"
    content="{{ isset($ogImage) && $ogImage ? asset('storage/' . $ogImage) : asset('images/logo.svg') }}">

<link rel="icon" href="{{ asset('images/book_icon.png') }}" sizes="any">
<link rel="icon" href="{{ asset('images/book_icon.png') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
{{-- @fluxAppearance --}}

<!-- Google Tag Manager -->
<script>
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-NPSWPVH3');
</script>
<!-- End Google Tag Manager -->

{{-- Schema Markup --}}
<script type="application/ld+json">
    {!! $schemaMarkup ?? "{}" !!}
</script>
