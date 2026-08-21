<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes" name="viewport">
    <title>@yield('title', 'Mandalacare')</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    screens: {
                        'xs': '480px',
                    },
                    colors: {
                        primary: '#006c49',
                        'primary-container': '#10b981',
                        'on-primary-container': '#00422b',
                        'primary-fixed': '#6ffbbe',
                        'primary-fixed-dim': '#4edea3',
                        'on-primary-fixed': '#002113',
                        'on-primary-fixed-variant': '#005236',
                        'inverse-primary': '#4edea3',
                        secondary: '#0058be',
                        'secondary-container': '#2170e4',
                        'on-secondary-container': '#fefcff',
                        'secondary-fixed': '#d8e2ff',
                        'secondary-fixed-dim': '#adc6ff',
                        'on-secondary-fixed': '#001a42',
                        'on-secondary-fixed-variant': '#004395',
                        tertiary: '#494bd6',
                        'tertiary-container': '#9699ff',
                        'on-tertiary-container': '#1d17b2',
                        'tertiary-fixed': '#e1e0ff',
                        'tertiary-fixed-dim': '#c0c1ff',
                        'on-tertiary-fixed': '#07006c',
                        'on-tertiary-fixed-variant': '#2f2ebe',
                        background: '#FAF8FF',
                        surface: '#ffffff',
                        'surface-bright': '#faf8ff',
                        'surface-dim': '#d2d9f4',
                        'surface-variant': '#dae2fd',
                        'inverse-surface': '#283044',
                        'inverse-on-surface': '#eef0ff',
                        'on-surface': '#131b2e',
                        'on-surface-variant': '#3c4a42',
                        'on-background': '#131b2e',
                        outline: '#6c7a71',
                        'outline-variant': '#E5E7EB',
                        'surface-container-lowest': '#ffffff',
                        'surface-container-low': '#f2f3ff',
                        'surface-container': '#eaedff',
                        'surface-container-high': '#e2e7ff',
                        'surface-container-highest': '#dae2fd',
                        'error-container': '#ffdad6',
                        'on-error-container': '#93000a',
                        error: '#ba1a1a'
                    },
                    borderRadius: {
                        'DEFAULT': '0.25rem',
                        'lg': '0.5rem',
                        'xl': '0.75rem',
                        '2xl': '1rem',
                        'full': '9999px'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #131b2e;
            -webkit-tap-highlight-color: transparent;
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        .input-ring:focus {
            outline: none;
            border-color: #006c49;
            box-shadow: 0 0 0 3px rgba(0, 108, 73, 0.2);
        }

        /* Custom smooth scrollbar for tables */
        ::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-background min-h-screen flex flex-col md:flex-row text-on-surface antialiased overflow-x-hidden">

    <!-- MOBILE SIDEBAR OVERLAY -->
    <div
        id="sidebar-overlay"
        onclick="toggleSidebar()"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0 md:hidden"
    ></div>

    <!-- SIDEBAR -->
    <aside
        id="sidebar"
        class="fixed top-0 left-0 bottom-0 z-50 w-72 md:w-64 bg-[#f9f9ff] border-r-2 border-outline-variant flex flex-col h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out p-4 shadow-2xl md:shadow-none"
    >
        <!-- LOGO & CLOSE BUTTON -->
        <div class="px-2 mb-4 py-2 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <img
                    src="{{ asset('images/Logo Mandala Care.png') }}"
                    alt="Logo Mandala Care"
                    class="h-10 w-auto object-contain"
                >
            </a>
            <!-- Mobile Close Button -->
            <button
                type="button"
                onclick="toggleSidebar()"
                class="md:hidden text-on-surface-variant hover:text-on-surface p-1.5 rounded-lg hover:bg-surface-container transition-colors"
                title="Tutup Menu"
            >
                <span class="material-symbols-outlined text-2xl">close</span>
            </button>
        </div>

        <!-- MENU -->
        <nav class="flex-1 overflow-y-auto space-y-1 pr-1">
            <!-- DASHBOARD -->
            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-3.5 px-4 py-2.5 sm:py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('dashboard*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('dashboard*') ? 'filled-icon' : '' }}">
                    grid_view
                </span>
                <span class="text-sm font-semibold">Dashboard</span>
            </a>

            <!-- PENDAFTARAN -->
            <a
                href="{{ route('pendaftaran') }}"
                class="flex items-center gap-3.5 px-4 py-2.5 sm:py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('pendaftaran*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('pendaftaran*') ? 'filled-icon' : '' }}">
                    person_add
                </span>
                <span class="text-sm font-semibold">Pendaftaran</span>
            </a>

            <!-- PASIEN -->
            <a
                href="{{ route('pasien') }}"
                class="flex items-center gap-3.5 px-4 py-2.5 sm:py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('pasien*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('pasien*') ? 'filled-icon' : '' }}">
                    group
                </span>
                <span class="text-sm font-semibold">Pasien</span>
            </a>

            <!-- REKAM MEDIS -->
            <a
                href="{{ route('rekam-medis') }}"
                class="flex items-center gap-3.5 px-4 py-2.5 sm:py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('rekam-medis*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('rekam-medis*') ? 'filled-icon' : '' }}">
                    medical_services
                </span>
                <span class="text-sm font-semibold">Rekam Medis</span>
            </a>

            <!-- ASUHAN KEPERAWATAN -->
            <a
                href="{{ route('asuhan-keperawatan') }}"
                class="flex items-center gap-3.5 px-4 py-2.5 sm:py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('asuhan-keperawatan*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('asuhan-keperawatan*') ? 'filled-icon' : '' }}">
                    assignment
                </span>
                <span class="text-sm font-semibold">Asuhan Keperawatan</span>
            </a>

            <!-- EVALUASI -->
            <a
                href="{{ route('evaluasi') }}"
                class="flex items-center gap-3.5 px-4 py-2.5 sm:py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('evaluasi*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('evaluasi*') ? 'filled-icon' : '' }}">
                    assignment_turned_in
                </span>
                <span class="text-sm font-semibold">Evaluasi</span>
            </a>

            <!-- AI CLINICAL ASSISTANT -->
            <a
                href="#"
                class="flex items-center gap-3.5 px-4 py-2.5 sm:py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('ai*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('ai*') ? 'filled-icon' : '' }}">
                    smart_toy
                </span>
                <span class="text-sm font-semibold">AI Clinical Assistant</span>
            </a>

            <!-- PEMBAYARAN -->
            <a
                href="{{ route('pembayaran') }}"
                class="flex items-center gap-3.5 px-4 py-2.5 sm:py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('pembayaran*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('pembayaran*') ? 'filled-icon' : '' }}">
                    payments
                </span>
                <span class="text-sm font-semibold">Pembayaran</span>
            </a>

        </nav>

        <!-- ACCOUNT + LOGOUT -->
        <div class="pt-3 mt-2 border-t border-outline-variant">
            <div class="flex items-center gap-3 px-2 mb-2">
                <img
                    src="{{ asset('images/profil.png') }}"
                    alt="Foto Profil"
                    class="w-9 h-9 rounded-full object-cover shrink-0 border border-outline-variant shadow-sm"
                >
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">
                        {{ auth()->user()?->name ?? 'Yudha Tama' }}
                    </p>
                    <p class="text-xs text-on-surface-variant truncate">
                        Pemilik Klinik
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-red-600 hover:bg-red-50 transition-all text-sm font-semibold"
                >
                    <span class="material-symbols-outlined text-xl">logout</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="md:ml-64 flex-1 min-h-screen flex flex-col w-full min-w-0">
        <!-- TOP NAVBAR HEADER -->
        <header
            class="sticky top-0 right-0 w-full bg-[#f9f9ff]/95 backdrop-blur-md border-b-2 border-outline-variant z-30 flex justify-between items-center px-4 sm:px-6 py-2.5 sm:py-3 transition-all"
        >
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <button
                    type="button"
                    onclick="toggleSidebar()"
                    class="md:hidden text-on-surface p-2 rounded-lg hover:bg-surface-container-low transition-colors shrink-0 flex items-center justify-center"
                    aria-label="Buka Menu"
                >
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>
                <div class="flex flex-col min-w-0">
                    <h2 class="font-bold text-on-surface text-sm sm:text-base leading-tight truncate">
                        @yield('header_title', 'Selamat datang, ' . (auth()->user()?->name ? 'Bp. ' . auth()->user()->name : 'Bp. Yudha'))
                    </h2>
                    <p class="text-xs text-on-surface-variant truncate hidden xs:block">
                        @yield('header_subtitle', 'Berikut ringkasan aktivitas klinik hari ini.')
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                <div class="flex items-center gap-2 sm:gap-3 p-1 rounded-lg">
                    <span class="text-xs sm:text-sm font-semibold text-on-surface hidden lg:inline-block max-w-[120px] truncate">
                        {{ auth()->user()?->name ?? 'Yudha Tama' }}
                    </span>
                    <img
                        src="{{ asset('images/profil.png') }}"
                        alt="Foto Profil"
                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover border border-outline-variant shrink-0 shadow-sm"
                    >
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <div class="flex-1 w-full min-w-0">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;

            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                // Open Drawer
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                document.body.style.overflow = 'hidden';
            } else {
                // Close Drawer
                overlay.classList.add('opacity-0');
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                setTimeout(() => overlay.classList.add('hidden'), 300);
                document.body.style.overflow = '';
            }
        }
    </script>
</body>
</html>