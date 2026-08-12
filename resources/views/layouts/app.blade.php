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
<<<<<<< HEAD

            theme: {

                extend: {

                    colors: {

                        primary: '#006c49',

                        'primary-container': '#10b981',

                        'on-primary-container': '#00422b',

                        secondary: '#0058be',

                        'secondary-container': '#2170e4',

                        background: '#F8FAFC',

                        surface: '#ffffff',

                        'on-surface': '#131b2e',

                        'on-surface-variant': '#3c4a42',

                        outline: '#6c7a71',

                        'outline-variant': '#E5E7EB',

                        'surface-container-lowest': '#ffffff',

                        'surface-container-low': '#f2f3ff',

                        'surface-container': '#eaedff',

                        'surface-container-high': '#e2e7ff',

                        'surface-container-highest': '#dae2fd',

                        'secondary-fixed-dim': '#adc6ff'

                    },

                    fontFamily: {

                        sans: ['Inter', 'sans-serif']

                    }

                }

            }

=======
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
>>>>>>> 550e7ff79a1977d888dfd4ed1ebae5849f22c169
        }
    </script>
    <style>
<<<<<<< HEAD

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #131b2e;
        }


        .material-symbols-outlined {

            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;

        }


        .filled-icon {

            font-variation-settings:
                'FILL' 1,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;

        }


        .card-shadow {

            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.04);

        }

        .input-ring:focus {
            outline: none;
            border-color: #006c49;
            box-shadow: 0 0 0 3px rgba(0, 108, 73, 0.2);
        }

=======
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; color: #131b2e; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .filled-icon { font-variation-settings: 'FILL' 1; }
        .glass-panel { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid #E2E8F0; }
        .card-shadow { box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.04); }
        .input-ring:focus { outline: none; border-color: #006c49; box-shadow: 0 0 0 3px rgba(0, 108, 73, 0.2); }
>>>>>>> 550e7ff79a1977d888dfd4ed1ebae5849f22c169
    </style>
</head>
<<<<<<< HEAD


<body
    class="bg-background
           min-h-screen
           flex
           text-on-surface"
>


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <div
        id="sidebar-overlay"
        onclick="toggleSidebar()"
        class="fixed inset-0 bg-black/40 z-40 hidden md:hidden"
    ></div>

    <aside
        id="sidebar"
        class="hidden
               md:flex
               flex-col
               h-screen
               w-64
               fixed
               left-0
               top-0
               bg-[#f9f9ff]
               border-r-2
               border-outline-variant
               z-50
               p-4"
    >


        <!-- LOGO -->

        <div class="px-4 mb-6 py-4">

            <h1
                class="text-[22px]
                       font-bold
                       text-primary"
            >
                Mandalacare
            </h1>


            <p
                class="text-[12px]
                       text-outline
                       font-medium
                       mt-1"
            >
                Clinical Management
            </p>

        </div>



        <!-- MENU -->

        <nav
            class="flex-1
                   overflow-y-auto
                   space-y-1"
        >


            <!-- DASHBOARD -->

            <a
                href="{{ route('dashboard') }}"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('dashboard*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span
                    class="material-symbols-outlined
                           {{ request()->routeIs('dashboard*') ? 'filled-icon' : '' }}"
                >
                    grid_view
                </span>


                <span class="text-sm font-semibold">
                    Dashboard
                </span>

            </a>



            <!-- PENDAFTARAN -->

            <a
                href="{{ route('pendaftaran') }}"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('pendaftaran*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span class="material-symbols-outlined {{ request()->routeIs('pendaftaran*') ? 'filled-icon' : '' }}">
                    person_add
                </span>

                <span class="text-sm font-semibold">
                    Pendaftaran
                </span>

            </a>



            <!-- PASIEN -->

            <a
                href="{{ route('pasien') }}"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('pasien*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span class="material-symbols-outlined {{ request()->routeIs('pasien*') ? 'filled-icon' : '' }}">
                    group
                </span>

                <span class="text-sm font-semibold">
                    Pasien
                </span>

            </a>



            <!-- REKAM MEDIS -->

            <a
                href="{{ route('rekam-medis') }}"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('rekam-medis*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span class="material-symbols-outlined {{ request()->routeIs('rekam-medis*') ? 'filled-icon' : '' }}">
                    medical_services
                </span>

                <span class="text-sm font-semibold">
                    Rekam Medis
                </span>

            </a>



            <!-- ASUHAN KEPERAWATAN -->

            <a
                href="#"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('asuhan-keperawatan*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span class="material-symbols-outlined {{ request()->routeIs('asuhan-keperawatan*') ? 'filled-icon' : '' }}">
                    assignment
                </span>

                <span class="text-sm font-semibold">
                    Asuhan Keperawatan
                </span>

            </a>



            <!-- INTERVENSI -->

            <a
                href="#"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('intervensi*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span class="material-symbols-outlined {{ request()->routeIs('intervensi*') ? 'filled-icon' : '' }}">
                    healing
                </span>

                <span class="text-sm font-semibold">
                    Intervensi
                </span>

            </a>



            <!-- IMPLEMENTASI -->

            <a
                href="#"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('implementasi*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span class="material-symbols-outlined {{ request()->routeIs('implementasi*') ? 'filled-icon' : '' }}">
                    fact_check
                </span>

                <span class="text-sm font-semibold">
                    Implementasi
                </span>

            </a>



            <!-- EVALUASI -->

            <a
                href="#"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('evaluasi*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span class="material-symbols-outlined {{ request()->routeIs('evaluasi*') ? 'filled-icon' : '' }}">
                    assignment_turned_in
                </span>

                <span class="text-sm font-semibold">
                    Evaluasi
                </span>

            </a>



            <!-- AI -->

            <a
                href="#"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('ai*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span class="material-symbols-outlined {{ request()->routeIs('ai*') ? 'filled-icon' : '' }}">
                    smart_toy
                </span>

                <span class="text-sm font-semibold">
                    AI Clinical Assistant
                </span>

            </a>



            <!-- PEMBAYARAN -->

            <a
                href="#"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('pembayaran*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span class="material-symbols-outlined {{ request()->routeIs('pembayaran*') ? 'filled-icon' : '' }}">
                    payments
                </span>

                <span class="text-sm font-semibold">
                    Pembayaran
                </span>

            </a>



            <!-- PENGATURAN -->

            <a
                href="#"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       transition-all
                       duration-200
                       {{ request()->routeIs('pengaturan*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span class="material-symbols-outlined {{ request()->routeIs('pengaturan*') ? 'filled-icon' : '' }}">
                    settings
                </span>

                <span class="text-sm font-semibold">
                    Pengaturan
                </span>

            </a>


        </nav>



        <!-- =================================================
             ACCOUNT + LOGOUT
        ================================================== -->

        <div
            class="pt-4
                   mt-4
                   border-t
                   border-outline-variant"
        >


            <!-- USER -->

            <div
                class="flex
                       items-center
                       gap-3
                       px-2
                       mb-3"
            >

                <div
                    class="w-10
                           h-10
                           rounded-full
                           bg-primary-container
                           flex
                           items-center
                           justify-center
                           text-on-primary-container
                           font-bold"
                >

                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}

                </div>


                <div class="flex-1 min-w-0">

                    <p
                        class="text-sm
                               font-semibold
                               truncate"
                    >
                        {{ auth()->user()->name }}
                    </p>


                    <p
                        class="text-xs
                               text-on-surface-variant
                               truncate"
                    >
                        Pemilik Klinik
                    </p>

                </div>

            </div>



            <!-- LOGOUT -->

            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="w-full
                           flex
                           items-center
                           gap-4
                           px-4
                           py-3
                           rounded-xl
                           text-red-600
                           hover:bg-red-50
                           transition-all"
                >

                    <span class="material-symbols-outlined">
                        logout
                    </span>


                    <span
                        class="text-sm
                               font-semibold"
                    >
                        Keluar
                    </span>

                </button>

            </form>


        </div>


    </aside>



    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main
        class="md:ml-64
               flex-1
               min-h-screen
               flex
               flex-col"
    >

        <!-- TOP NAVBAR HEADER -->
        <header
            class="sticky
                   top-0
                   right-0
                   w-full
                   bg-[#f9f9ff]
                   border-b-2
                   border-outline-variant
                   z-30
                   flex
                   justify-between
                   items-center
                   px-6
                   py-3"
        >

            <div class="flex items-center gap-4">

                <button
                    type="button"
                    onclick="toggleSidebar()"
                    class="md:hidden text-on-surface p-2 rounded-lg hover:bg-surface-container-low transition-colors"
                >
                    <span class="material-symbols-outlined">menu</span>
                </button>

                <div class="flex flex-col">
                    <h2 class="font-bold text-on-surface text-base leading-tight">
                        Selamat datang, {{ auth()->user()->name ?? 'Bp. Yudha' }}
                    </h2>
                    <p class="text-xs text-on-surface-variant">
                        Berikut ringkasan aktivitas klinik hari ini.
                    </p>
                </div>

            </div>

            <div class="flex items-center gap-3">

                <button
                    type="button"
                    class="relative p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors"
                >
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border border-white"></span>
                </button>

                <button
                    type="button"
                    class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors"
                >
                    <span class="material-symbols-outlined">help</span>
                </button>

                <div class="w-px h-8 bg-outline-variant mx-1"></div>

                <div class="flex items-center gap-3 p-1 rounded-lg">

                    <span class="text-sm font-semibold text-on-surface hidden sm:inline-block">
                        {{ auth()->user()->name ?? 'Yudha Tama' }}
                    </span>

                    <div class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm overflow-hidden border border-outline-variant">
                        {{ strtoupper(substr(auth()->user()->name ?? 'Yudha', 0, 2)) }}
                    </div>

                </div>

            </div>

        </header>

        <div class="flex-1">
            @yield('content')
        </div>

    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
                overlay.classList.add('hidden');
            }
        }
    </script>


=======
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
>>>>>>> 550e7ff79a1977d888dfd4ed1ebae5849f22c169
</body>
</html>