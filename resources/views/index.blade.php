@php
$config = [
    'appName' => config('app.name'),
    'locale' => $locale = app()->getLocale(),
    'locales' => config('app.locales'),
    'githubAuth' => config('services.github.client_id'),
    'googleAuth' => config('services.google.client_id'),
    'facebookAuth' => config('services.facebook.client_id'),
];

$polyfills = [
    'Promise',
    'Object.assign',
    'Object.values',
    'Array.prototype.find',
    'Array.prototype.findIndex',
    'Array.prototype.includes',
    'String.prototype.includes',
    'String.prototype.startsWith',
    'String.prototype.endsWith',
];
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- PWA manifest -->
  <link rel="manifest" href="/manifest.json">

  {{-- https://developers.google.com/web/tools/lighthouse/audits/address-bar --}}
  <meta name="theme-color" content="#317EFB"/>

  <title>{{ config('app.name') }}</title>

  <link rel="stylesheet" href="{{ mix('css/app.css') }}">


  <!-- Add to home screen for Safari on iOS -->
  <!--meta name="apple-mobile-web-app-capable" content="yes" -->
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="Shnatr">
  <link rel="apple-touch-icon" href="static/icons/icon-152x152.png">

  <!-- tile icon for Windows -->
  <meta name="msapplication-TileImage" content="images/icons/icon-144x144.png">
  <meta name="msapplication-TileColor" content="#2F3BA2">

</head>

<body>
  <div id="app">
    <p>
      first glimpse - some dummy content that will be delivered while the app is loading!
    </p>
    <h2>This will soon be replaced by some NUXT content</h2>
  </div>

  {{-- Global configuration object --}}
  <script>window.config = @json($config);</script>

  {{-- Polyfill JS features via polyfill.io --}}
  <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>

  {{-- Load the application scripts --}}
  @if (app()->isLocal())
    <script src="{{ mix('js/app.js') }}"></script>
  @else
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
  @endif
</body>
</html>
