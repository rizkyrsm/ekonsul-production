<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

{{-- <title>{{ $title ?? config('app.name') }}</title> --}}
<title>{{ $title ?? 'Ekonsul PKBI' }}</title>

<link rel="icon" type="image/png" href="{{ asset('https://pkbi-jatim.or.id/wp-content/uploads/2021/12/cropped-Logo-PKBI-Jatim.png') }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
