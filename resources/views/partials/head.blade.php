<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>
<meta name="description" content="{{ $description ?? 'Explore, Borrow, Lend Books for free' }}">

{{-- Open Graph Meta Tags --}}
<meta property="og:title" content="{{ $title ?? config('app.name') }}">
<meta property="og:description" content="{{ $description ?? 'Explore, Borrow, Lend Books for free' }}">
<meta property="og:image" content="{{ $ogImage ?? asset('images/logo.svg') }}" />
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:type" content="website">

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? config('app.name') }}">
<meta name="twitter:description" content="{{ $description ?? 'Explore, Borrow, Lend Books for free' }}">
<meta name="twitter:image" content="{{ $ogImage ?? asset('images/logo.svg') }}">

<link rel="icon" href="{{ asset('images/book_icon.png') }}" sizes="any">
<link rel="icon" href="{{ asset('images/book_icon.png') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
{{-- @fluxAppearance --}}
