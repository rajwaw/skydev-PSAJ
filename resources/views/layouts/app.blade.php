<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
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
                        'secondary-fixed-dim': '#adc6ff',
                        'error-container': '#ffdad6',
                        'on-error-container': '#93000a',
                        error: '#ba1a1a'
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
    </style>
</head>

<body class="bg-background min-h-screen flex text-on-surface">

    <!-- MOBILE SIDEBAR OVERLAY -->
    <div
        id="sidebar-overlay"
        onclick="toggleSidebar()"
        class="fixed inset-0 bg-black/40 z-40 hidden md:hidden"
    ></div>

    <!-- SIDEBAR -->
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
            <h1 class="text-[22px] font-bold text-primary">
                Mandalacare
            </h1>
            <p class="text-[12px] text-outline font-medium mt-1">
                Clinical Management
            </p>
        </div>

        <!-- MENU -->
        <nav class="flex-1 overflow-y-auto space-y-1">
            <!-- DASHBOARD -->
            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
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
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
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
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
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
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
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
                href="#"
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('asuhan-keperawatan*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('asuhan-keperawatan*') ? 'filled-icon' : '' }}">
                    assignment
                </span>
                <span class="text-sm font-semibold">Asuhan Keperawatan</span>
            </a>

            <!-- INTERVENSI -->
            <a
                href="#"
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('intervensi*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('intervensi*') ? 'filled-icon' : '' }}">
                    healing
                </span>
                <span class="text-sm font-semibold">Intervensi</span>
            </a>

            <!-- IMPLEMENTASI -->
            <a
                href="#"
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('implementasi*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('implementasi*') ? 'filled-icon' : '' }}">
                    fact_check
                </span>
                <span class="text-sm font-semibold">Implementasi</span>
            </a>

            <!-- EVALUASI -->
            <a
                href="#"
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
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
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
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
                href="#"
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('pembayaran*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('pembayaran*') ? 'filled-icon' : '' }}">
                    payments
                </span>
                <span class="text-sm font-semibold">Pembayaran</span>
            </a>

            <!-- PENGATURAN -->
            <a
                href="#"
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('pengaturan*')
                           ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                           : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >
                <span class="material-symbols-outlined {{ request()->routeIs('pengaturan*') ? 'filled-icon' : '' }}">
                    settings
                </span>
                <span class="text-sm font-semibold">Pengaturan</span>
            </a>
        </nav>

        <!-- ACCOUNT + LOGOUT -->
        <div class="pt-4 mt-4 border-t border-outline-variant">
            <div class="flex items-center gap-3 px-2 mb-3">
                <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'Yudha', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">
                        {{ auth()->user()->name ?? 'Yudha Tama' }}
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
                    class="w-full flex items-center gap-4 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all"
                >
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text-sm font-semibold">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="md:ml-64 flex-1 min-h-screen flex flex-col">
        <!-- TOP NAVBAR HEADER -->
        <header
            class="sticky top-0 right-0 w-full bg-[#f9f9ff] border-b-2 border-outline-variant z-30 flex justify-between items-center px-6 py-3"
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
                        Selamat datang, {{ auth()->user()->name ? 'Bp. ' . auth()->user()->name : 'Bp. Yudha' }}
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
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-600 rounded-full border border-white"></span>
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
</body>
</html>