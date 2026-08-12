<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'Mandalacare - Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "secondary-fixed-dim": "#adc6ff",
                      "surface-container-low": "#f2f3ff",
                      "on-primary": "#ffffff",
                      "on-background": "#131b2e",
                      "inverse-surface": "#283044",
                      "on-primary-container": "#00422b",
                      "surface-container-lowest": "#ffffff",
                      "on-error": "#ffffff",
                      "primary-container": "#10b981",
                      "secondary": "#0058be",
                      "on-tertiary-fixed-variant": "#2f2ebe",
                      "on-error-container": "#93000a",
                      "primary-fixed-dim": "#4edea3",
                      "secondary-container": "#2170e4",
                      "tertiary-fixed-dim": "#c0c1ff",
                      "surface-container-highest": "#dae2fd",
                      "tertiary-fixed": "#e1e0ff",
                      "outline": "#6c7a71",
                      "primary": "#006c49",
                      "on-primary-fixed": "#002113",
                      "surface-variant": "#dae2fd",
                      "error-container": "#ffdad6",
                      "tertiary-container": "#9699ff",
                      "on-surface-variant": "#3c4a42",
                      "surface-container-high": "#e2e7ff",
                      "secondary-fixed": "#d8e2ff",
                      "on-secondary-fixed": "#001a42",
                      "primary-fixed": "#6ffbbe",
                      "tertiary": "#494bd6",
                      "on-primary-fixed-variant": "#005236",
                      "inverse-primary": "#4edea3",
                      "on-secondary-fixed-variant": "#004395",
                      "error": "#ba1a1a",
                      "on-surface": "#131b2e",
                      "on-tertiary-container": "#1d17b2",
                      "surface": "#faf8ff",
                      "on-secondary-container": "#fefcff",
                      "surface-bright": "#faf8ff",
                      "background": "#faf8ff",
                      "inverse-on-surface": "#eef0ff",
                      "on-tertiary": "#ffffff",
                      "on-secondary": "#ffffff",
                      "surface-tint": "#006c49",
                      "surface-container": "#eaedff",
                      "surface-dim": "#d2d9f4",
                      "on-tertiary-fixed": "#07006c",
                      "outline-variant": "#bbcabf"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "margin-desktop": "40px",
                      "stack-lg": "32px",
                      "unit": "4px",
                      "stack-md": "16px",
                      "gutter": "24px",
                      "container-max": "1440px",
                      "stack-sm": "8px",
                      "margin-mobile": "16px"
              },
              "fontFamily": {
                      "body-md": ["Inter"],
                      "display-lg": ["Inter"],
                      "label-md": ["Inter"],
                      "label-sm": ["Inter"],
                      "body-lg": ["Inter"],
                      "headline-lg-mobile": ["Inter"],
                      "body-sm": ["Inter"],
                      "headline-lg": ["Inter"],
                      "headline-md": ["Inter"]
              },
              "fontSize": {
                      "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                      "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                      "label-md": ["14px", { "lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600" }],
                      "label-sm": ["12px", { "lineHeight": "16px", "letterSpacing": "0.02em", "fontWeight": "500" }],
                      "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                      "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                      "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                      "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                      "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }]
              }
      },
          },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; color: #131b2e; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .filled-icon { font-variation-settings: 'FILL' 1; }
        .glass-panel { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid #E2E8F0; }
        .card-shadow { box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.04); }
        .input-ring:focus { outline: none; border-color: #006c49; box-shadow: 0 0 0 3px rgba(0, 108, 73, 0.2); }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md antialiased h-screen overflow-hidden flex">
<!-- SideNavBar -->
<nav class="bg-surface dark:bg-on-background text-primary dark:text-primary-fixed-dim font-body-md text-body-md h-screen w-64 fixed left-0 top-0 border-r border-outline-variant dark:border-outline shadow-sm flex flex-col p-stack-md z-50">
<!-- Logo Area -->
<div class="mb-stack-lg flex items-center gap-3 px-2">
<div>
<h1 class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed-dim">Mandalacare</h1>
<p class="font-label-sm text-label-sm text-on-surface-variant">Clinical Management</p>
</div>
</div>
<!-- Navigation Links -->
<div class="flex-1 flex flex-col gap-1 overflow-y-auto">
<a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-primary-container dark:bg-on-primary-fixed-variant text-on-primary-container dark:text-primary-fixed font-bold' : 'text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant' }} rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined" style="{{ request()->routeIs('dashboard') ? 'font-variation-settings: &quot;FILL&quot; 1;' : '' }}">dashboard</span>
<span class="">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('pendaftaran') ? 'bg-primary-container dark:bg-on-primary-fixed-variant text-on-primary-container dark:text-primary-fixed font-bold' : 'text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant' }} rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="{{ Route::has('pendaftaran') ? route('pendaftaran') : '#' }}">
<span class="material-symbols-outlined {{ request()->routeIs('pendaftaran') ? 'filled-icon' : '' }}" style="{{ request()->routeIs('pendaftaran') ? 'font-variation-settings: &quot;FILL&quot; 1;' : '' }}">person_add</span>
<span class="">Pendaftaran</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="#">
<span class="material-symbols-outlined">group</span>
<span class="">Pasien</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="#">
<span class="material-symbols-outlined">medical_information</span>
<span class="">Rekam Medis</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="#">
<span class="material-symbols-outlined">assignment</span>
<span class="">Asuhan Keperawatan</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="#">
<span class="material-symbols-outlined">healing</span>
<span class="">Intervensi</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="#">
<span class="material-symbols-outlined">fact_check</span>
<span class="">Implementasi</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="#">
<span class="material-symbols-outlined">assignment_turned_in</span>
<span class="">Evaluasi</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="#">
<span class="material-symbols-outlined">smart_toy</span>
<span class="">AI Clinical Assistant</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="#">
<span class="material-symbols-outlined">payments</span>
<span class="">Pembayaran</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant rounded-lg cursor-pointer active:scale-95 transition-colors duration-200" href="#">
<span class="material-symbols-outlined">settings</span>
<span class="">Pengaturan</span>
</a></div>
<!-- User Profile / Logout -->
<div class="mt-auto pt-4 border-t border-outline-variant">
<div class="flex items-center gap-3 px-2 py-2">
<img alt="User Profile Avatar" class="w-10 h-10 rounded-full object-cover" data-alt="A professional headshot of a male medical practitioner, wearing a white lab coat over a light blue shirt, smiling subtly, set against a clean, softly lit clinical background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBZTgI53eWHHnIcFNKJljd7VDXxIqHK5KTzswIBGm-P0ffOuX92aE1VcRbJJ1B7OBlQuiJlGEO0mKVpOIhTgXBs1Q4jrfCsX1rfM5Su-otes-laDavwOVFKKRXgYUJG7cDTbpD0ZmqGAvwc9QfZmwIQTYmhgY9tZQj7GS4JxTJY5QPIQJoMvng3w5r4ilgRuHu4NVKsHYrKAe87ZDpIwvcII8FyWHKWMhWp7cgEd4aU56YNVZS2-byG4A">
<div class="flex-1 overflow-hidden">
<p class="font-label-md text-label-md font-semibold truncate">{{ auth()->user()->name ?? 'Yudha Tama' }}</p>
<p class="font-label-sm text-label-sm text-on-surface-variant truncate">Praktisi Medis</p>
</div>
@auth
<form method="POST" action="{{ route('logout') }}" class="inline">
@csrf
<button type="submit" class="p-2 text-error hover:bg-error-container rounded-lg transition-colors flex items-center justify-center">
<span class="material-symbols-outlined">logout</span>
</button>
</form>
@else
<button class="p-2 text-error hover:bg-error-container rounded-lg transition-colors">
<span class="material-symbols-outlined">logout</span>
</button>
@endauth
</div>
</div>
</nav>
<!-- Main Content Wrapper -->
<div class="ml-64 flex-1 flex flex-col h-screen overflow-hidden">
<!-- TopNavBar -->
<header class="bg-surface/80 dark:bg-on-background/80 text-primary dark:text-primary-fixed-dim font-label-md text-label-md fixed top-0 right-0 w-[calc(100%-16rem)] h-16 backdrop-blur-xl border-b border-outline-variant dark:border-outline flex justify-between items-center px-margin-desktop z-40">
<!-- Greeting -->
<div class="flex flex-col">
<h2 class="font-label-md text-label-md font-bold text-on-surface">Selamat datang, {{ auth()->check() ? 'Bp. ' . explode(' ', auth()->user()->name)[0] : 'Bp. Yudha' }}</h2>
<p class="font-body-sm text-body-sm text-on-surface-variant">Berikut ringkasan aktivitas klinik hari ini.</p>
</div>
<!-- Actions -->
<div class="flex items-center gap-4">

<button class="p-2 text-on-surface-variant dark:text-surface-variant hover:text-primary dark:hover:text-primary-fixed-dim transition-all focus-within:ring-2 focus-within:ring-primary rounded-full relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full"></span>
</button>
<div class="flex items-center gap-2 pl-4 border-l border-outline-variant">
<span class="font-label-md text-label-md text-on-surface">{{ auth()->user()->name ?? 'Yudha Tama' }}</span>
<img alt="User Profile Avatar" class="w-8 h-8 rounded-full object-cover" data-alt="A professional headshot of a male medical practitioner, wearing a white lab coat over a light blue shirt, smiling subtly, set against a clean, softly lit clinical background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhLFfabB6LwPDDypcfRBH8kvsFSF4W3qW1E5X17KolnwjARQeotGpF6_KFDiOX0nGpOFHWJwCc6yfRxfJX-6zAVyp-2JwkDkMep4W20-MKxfdT0vyisNYweLY0G-kahTcigE1TFH89UDeZe-lJ7pEGMioUdlt9JRJHQ1EoACCxVDzz8imTz8uKHELqvdqsTgFQbO9eCu9lNRpFNdJSHGdGrmh1eLRMoReZN2iao-nlTK9FohBwaXCOqg">
</div>
</div>
</header>
<!-- Scrollable Canvas -->
<main class="flex-1 overflow-y-auto mt-16 p-margin-desktop bg-background">
@yield('content')
</main>
</div>
</body>
</html>