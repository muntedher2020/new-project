<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
{{-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />
 --}}
<link href="{{ asset('assets/custom-css/css/css2.css') }}" rel="stylesheet">
<link href="{{ asset('assets/custom-css/css/index-e686b0b5.css') }}" rel="stylesheet">
<script src="{{ asset('assets/custom-css/css/index-a486371f.js') }}"></script>
<script src="{{ asset('assets/custom-css/css/v3.js') }}"></script>

@vite(['resources/assets/vendor/fonts/iconify/iconify.css'])

@if ($configData['hasCustomizer'])
  @vite(['resources/assets/vendor/libs/pickr/pickr-themes.scss'])
@endif

<!-- Vendor Styles -->
@yield('vendor-style')
@vite(['resources/assets/vendor/libs/node-waves/node-waves.scss'])

<!-- Core CSS -->
@vite(['resources/assets/vendor/scss/core.scss', 'resources/assets/css/demo.css', 'resources/assets/vendor/scss/pages/front-page.scss'])

<!-- Page Styles -->
@yield('page-style')

<link rel="stylesheet" href="{{ asset('assets/custom-css/customFrontend.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/fonts/Cairo/Cairo-font.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/fonts/Tajawal/Tajawal-font.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/fonts/frontend/frontend.css') }}" />
