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

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

<body class="bg-background min-h-screen flex flex-col md:flex-row text-on-surface antialiased overflow-x-clip">

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
                href="{{ route('ai-clinical-assistant') }}"
                class="flex items-center gap-3.5 px-4 py-2.5 sm:py-3 rounded-xl transition-all duration-200
                       {{ request()->routeIs('ai-clinical-assistant*')
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
        <div class="pt-3 mt-2 border-t border-outline-variant relative" id="accountMenuContainer">
            <!-- PROFILE POPUP MENU -->
            <div
                id="profilePopup"
                class="hidden absolute bottom-full left-0 right-0 mb-2.5 bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-2 z-50 transition-all duration-200"
            >
                <div class="p-2.5 bg-slate-50 rounded-xl mb-1.5 flex items-center gap-2.5 border border-slate-100">
                    <img
                        id="popupUserAvatar"
                        src="{{ auth()->user()?->avatar_url ?? asset('images/profil.png') }}"
                        alt="Foto Profil"
                        class="w-9 h-9 rounded-full object-cover border border-outline-variant shrink-0 shadow-sm"
                    >
                    <div class="min-w-0 flex-1">
                        <p id="popupUserName" class="text-xs font-bold text-on-surface truncate">
                            {{ auth()->user()?->name ?? 'Yudha Tama' }}
                        </p>
                        <p id="popupUserEmail" class="text-[11px] text-on-surface-variant truncate">
                            {{ auth()->user()?->email ?? 'admin@mandalacare.com' }}
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    onclick="openProfileModal('profile')"
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-all text-xs font-semibold text-left group cursor-pointer"
                >
                    <span class="material-symbols-outlined text-xl text-emerald-600 group-hover:scale-110 transition-transform">
                        manage_accounts
                    </span>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold">Edit Profil</div>
                        <div class="text-[10px] text-slate-400 font-normal">Ganti foto & username</div>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 group-hover:text-emerald-600 text-sm">chevron_right</span>
                </button>

                <button
                    type="button"
                    onclick="openProfileModal('account')"
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-all text-xs font-semibold text-left group mt-0.5 cursor-pointer"
                >
                    <span class="material-symbols-outlined text-xl text-emerald-600 group-hover:scale-110 transition-transform">
                        lock_reset
                    </span>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold">Ganti Email & Password</div>
                        <div class="text-[10px] text-slate-400 font-normal">Kredensial login akun</div>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 group-hover:text-emerald-600 text-sm">chevron_right</span>
                </button>
            </div>

            <!-- Profile Button Trigger -->
            <button
                type="button"
                id="profileButtonTrigger"
                onclick="toggleProfilePopup(event)"
                class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-all duration-200 text-left cursor-pointer group border border-transparent hover:border-outline-variant mb-2"
                title="Klik untuk membuka menu profil & akun"
            >
                <div class="relative shrink-0">
                    <img
                        id="sidebarUserAvatar"
                        src="{{ auth()->user()?->avatar_url ?? asset('images/profil.png') }}"
                        alt="Foto Profil"
                        class="w-10 h-10 rounded-full object-cover border border-outline-variant shadow-sm group-hover:scale-105 transition-transform"
                    >
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p id="sidebarUserName" class="text-sm font-semibold truncate group-hover:text-primary transition-colors">
                        {{ auth()->user()?->name ?? 'Yudha Tama' }}
                    </p>
                    <p class="text-xs text-on-surface-variant truncate">
                        Pemilik Klinik
                    </p>
                </div>
                <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors text-lg" id="profilePopupChevron">
                    expand_less
                </span>
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-red-600 hover:bg-red-50 transition-all text-sm font-semibold cursor-pointer"
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
                <button
                    type="button"
                    onclick="openProfileModal('profile')"
                    class="flex items-center gap-2 sm:gap-3 p-1.5 rounded-xl hover:bg-surface-container transition-all cursor-pointer border border-transparent hover:border-outline-variant group"
                    title="Pengaturan Profil & Akun"
                >
                    <span id="headerUserName" class="text-xs sm:text-sm font-semibold text-on-surface hidden lg:inline-block max-w-[120px] truncate group-hover:text-primary transition-colors">
                        {{ auth()->user()?->name ?? 'Yudha Tama' }}
                    </span>
                    <img
                        id="headerUserAvatar"
                        src="{{ auth()->user()?->avatar_url ?? asset('images/profil.png') }}"
                        alt="Foto Profil"
                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover border border-outline-variant shrink-0 shadow-sm group-hover:scale-105 transition-transform"
                    >
                </button>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <div class="flex-1 w-full min-w-0">
            @yield('content')
        </div>
    </main>

    <!-- GLOBAL TOAST NOTIFICATION -->
    <div
        id="globalToast"
        class="fixed top-5 right-5 z-[9999] hidden flex items-center gap-3 px-4 py-3 bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-200 transition-all duration-300 transform -translate-y-4 opacity-0 max-w-sm"
    >
        <div id="toastIconContainer" class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
            <span id="toastIcon" class="material-symbols-outlined text-xl">check_circle</span>
        </div>
        <div class="flex-1 min-w-0 text-xs">
            <p id="toastTitle" class="font-bold text-slate-900 truncate">Berhasil</p>
            <p id="toastMessage" class="text-slate-600 mt-0.5 leading-snug line-clamp-2">Pembaruan berhasil disimpan.</p>
        </div>
        <button type="button" onclick="hideGlobalToast()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
            <span class="material-symbols-outlined text-base">close</span>
        </button>
    </div>

    <!-- PROFILE & ACCOUNT MODAL -->
    <div
        id="profileModal"
        class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300"
        role="dialog"
        aria-modal="true"
        aria-labelledby="profileModalTitle"
    >
        <!-- Modal Card -->
        <div
            id="profileModalCard"
            class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 flex flex-col max-h-[90vh]"
        >
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-2xl">manage_accounts</span>
                    </div>
                    <div>
                        <h3 id="profileModalTitle" class="text-base font-bold text-slate-800 leading-tight">
                            Pengaturan Akun
                        </h3>
                        <p class="text-xs text-slate-500">
                            Kelola profil dan kredensial login Anda
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    onclick="closeProfileModal()"
                    class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors"
                    aria-label="Tutup"
                >
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <!-- Navigation Tabs -->
            <div class="flex border-b border-slate-200 bg-slate-50/30 px-6 pt-3 gap-2">
                <button
                    type="button"
                    id="tabBtnProfile"
                    onclick="switchProfileTab('profile')"
                    class="flex items-center gap-2 pb-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-emerald-600 text-emerald-700 transition-all cursor-pointer"
                >
                    <span class="material-symbols-outlined text-lg">badge</span>
                    <span>Edit Profil</span>
                </button>
                <button
                    type="button"
                    id="tabBtnAccount"
                    onclick="switchProfileTab('account')"
                    class="flex items-center gap-2 pb-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all cursor-pointer"
                >
                    <span class="material-symbols-outlined text-lg">lock_reset</span>
                    <span>Ganti Email & Password</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1">
                <!-- TAB 1: EDIT PROFIL -->
                <div id="tabContentProfile" class="space-y-4">
                    <form id="formUpdateProfile" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" onsubmit="handleProfileSubmit(event)">
                        @csrf
                        <!-- Alert Box inside modal -->
                        <div id="profileAlert" class="hidden mb-4 p-3 rounded-xl text-xs font-medium border"></div>

                        <!-- Foto Profil Section -->
                        <div class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border border-slate-100 mb-4">
                            <div class="relative group cursor-pointer" onclick="document.getElementById('avatarFileInput').click()" title="Klik untuk mengganti foto profil">
                                <img
                                    id="avatarModalPreview"
                                    src="{{ auth()->user()?->avatar_url ?? asset('images/profil.png') }}"
                                    alt="Foto Profil"
                                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md ring-2 ring-emerald-500/30 group-hover:opacity-90 transition-all"
                                >
                                <div class="absolute inset-0 rounded-full bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity text-white text-xs font-semibold">
                                    <span class="material-symbols-outlined text-2xl">photo_camera</span>
                                </div>
                                <div
                                    class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-md hover:bg-emerald-700 ring-2 ring-white transition-transform active:scale-95"
                                >
                                    <span class="material-symbols-outlined text-base">camera_alt</span>
                                </div>
                            </div>

                            <input
                                type="file"
                                id="avatarFileInput"
                                name="avatar"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="hidden"
                                onchange="onAvatarFileSelected(this)"
                            >

                            <div class="mt-3 text-center">
                                <button
                                    type="button"
                                    onclick="document.getElementById('avatarFileInput').click()"
                                    class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1.5 cursor-pointer"
                                >
                                    <span class="material-symbols-outlined text-sm">upload</span>
                                    <span>Pilih Foto Baru</span>
                                </button>
                                <p class="text-[11px] text-slate-400 mt-1">
                                    Format: JPG, PNG, WEBP. Maksimal 2MB.
                                </p>
                            </div>
                        </div>

                        <!-- Username / Nama Input -->
                        <div class="space-y-1.5 mb-5">
                            <label for="inputProfileName" class="block text-xs font-bold text-slate-700">
                                Username / Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">
                                    person
                                </span>
                                <input
                                    type="text"
                                    id="inputProfileName"
                                    name="name"
                                    value="{{ auth()->user()?->name ?? '' }}"
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    placeholder="Masukkan nama atau username"
                                >
                            </div>
                            <p class="text-[11px] text-slate-400">
                                Nama ini tampil di sidebar, navbar atas, serta riwayat tindakan medis.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                            <button
                                type="button"
                                onclick="closeProfileModal()"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                id="btnSaveProfile"
                                class="px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2 active:scale-95 cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-base">check</span>
                                <span>Simpan Profil</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: GANTI EMAIL & PASSWORD -->
                <div id="tabContentAccount" class="hidden space-y-4">
                    <form id="formUpdateAccount" action="{{ route('profile.account') }}" method="POST" onsubmit="handleAccountSubmit(event)">
                        @csrf
                        <!-- Alert Box inside modal -->
                        <div id="accountAlert" class="hidden mb-4 p-3 rounded-xl text-xs font-medium border"></div>

                        <!-- Important Information Banner -->
                        <div class="p-3.5 bg-blue-50/80 border border-blue-200 rounded-xl flex items-start gap-3 text-xs text-blue-900">
                            <span class="material-symbols-outlined text-blue-600 text-xl shrink-0 mt-0.5">info</span>
                            <div class="leading-relaxed">
                                <p class="font-bold">Akses Login Mandalacare</p>
                                <p class="text-blue-700 text-[11px] mt-0.5">
                                    Email dan password yang Anda simpan di sini akan digunakan saat Anda masuk (login) ke aplikasi Mandalacare.
                                </p>
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div class="space-y-1.5">
                            <label for="inputAccountEmail" class="block text-xs font-bold text-slate-700">
                                Alamat Email (Login) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">
                                    mail
                                </span>
                                <input
                                    type="email"
                                    id="inputAccountEmail"
                                    name="email"
                                    value="{{ auth()->user()?->email ?? '' }}"
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    placeholder="nama@email.com"
                                >
                            </div>
                        </div>

                        <!-- Password Baru -->
                        <div class="space-y-1.5">
                            <label for="inputAccountPassword" class="block text-xs font-bold text-slate-700">
                                Password Baru
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">
                                    key
                                </span>
                                <input
                                    type="password"
                                    id="inputAccountPassword"
                                    name="password"
                                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    placeholder="Kosongkan jika tidak ingin ganti password"
                                >
                                <button
                                    type="button"
                                    onclick="togglePasswordVisibility('inputAccountPassword', 'eyeIcon1')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer"
                                    tabindex="-1"
                                >
                                    <span id="eyeIcon1" class="material-symbols-outlined text-lg">visibility</span>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-400">Minimal 6 karakter (kombinasi huruf & angka disarankan).</p>
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div class="space-y-1.5">
                            <label for="inputAccountPasswordConfirmation" class="block text-xs font-bold text-slate-700">
                                Konfirmasi Password Baru
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg">
                                    lock_clock
                                </span>
                                <input
                                    type="password"
                                    id="inputAccountPasswordConfirmation"
                                    name="password_confirmation"
                                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    placeholder="Ketik ulang password baru"
                                >
                                <button
                                    type="button"
                                    onclick="togglePasswordVisibility('inputAccountPasswordConfirmation', 'eyeIcon2')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer"
                                    tabindex="-1"
                                >
                                    <span id="eyeIcon2" class="material-symbols-outlined text-lg">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                            <button
                                type="button"
                                onclick="closeProfileModal()"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                id="btnSaveAccount"
                                class="px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2 active:scale-95 cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-base">save</span>
                                <span>Simpan Email & Password</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle mobile sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;

            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                document.body.style.overflow = 'hidden';
            } else {
                overlay.classList.add('opacity-0');
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                setTimeout(() => overlay.classList.add('hidden'), 300);
                document.body.style.overflow = '';
            }
        }

        // Toggle Profile Popup Menu above sidebar profile button
        function toggleProfilePopup(event) {
            if (event) {
                event.stopPropagation();
            }
            const popup = document.getElementById('profilePopup');
            const chevron = document.getElementById('profilePopupChevron');
            if (!popup) return;

            const isHidden = popup.classList.contains('hidden');
            if (isHidden) {
                popup.classList.remove('hidden');
                if (chevron) chevron.textContent = 'expand_more';
            } else {
                popup.classList.add('hidden');
                if (chevron) chevron.textContent = 'expand_less';
            }
        }

        // Close popup when clicking outside
        document.addEventListener('click', function(event) {
            const container = document.getElementById('accountMenuContainer');
            const popup = document.getElementById('profilePopup');
            if (popup && !popup.classList.contains('hidden')) {
                if (container && !container.contains(event.target)) {
                    popup.classList.add('hidden');
                    const chevron = document.getElementById('profilePopupChevron');
                    if (chevron) chevron.textContent = 'expand_less';
                }
            }
        });

        // Open Profile Settings Modal with specific tab
        function openProfileModal(tab = 'profile') {
            // Close popup
            const popup = document.getElementById('profilePopup');
            if (popup) {
                popup.classList.add('hidden');
                const chevron = document.getElementById('profilePopupChevron');
                if (chevron) chevron.textContent = 'expand_less';
            }

            // Close mobile sidebar if open
            if (window.innerWidth < 768) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
            }

            // Reset alerts
            hideAlert('profileAlert');
            hideAlert('accountAlert');

            switchProfileTab(tab);

            const modal = document.getElementById('profileModal');
            const card = document.getElementById('profileModalCard');
            if (!modal || !card) return;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        // Close Profile Settings Modal
        function closeProfileModal() {
            const modal = document.getElementById('profileModal');
            const card = document.getElementById('profileModalCard');
            if (!modal || !card) return;

            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 250);
        }

        // Close modal on backdrop click or ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProfileModal();
                const popup = document.getElementById('profilePopup');
                if (popup) popup.classList.add('hidden');
            }
        });

        document.getElementById('profileModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProfileModal();
            }
        });

        // Switch between 'profile' and 'account' tabs
        function switchProfileTab(tab) {
            const tabBtnProfile = document.getElementById('tabBtnProfile');
            const tabBtnAccount = document.getElementById('tabBtnAccount');
            const tabContentProfile = document.getElementById('tabContentProfile');
            const tabContentAccount = document.getElementById('tabContentAccount');

            if (!tabBtnProfile || !tabBtnAccount || !tabContentProfile || !tabContentAccount) return;

            if (tab === 'profile') {
                // Active profile tab
                tabBtnProfile.className = 'flex items-center gap-2 pb-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-emerald-600 text-emerald-700 transition-all cursor-pointer';
                tabBtnAccount.className = 'flex items-center gap-2 pb-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all cursor-pointer';
                tabContentProfile.classList.remove('hidden');
                tabContentAccount.classList.add('hidden');
            } else {
                // Active account tab
                tabBtnAccount.className = 'flex items-center gap-2 pb-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-emerald-600 text-emerald-700 transition-all cursor-pointer';
                tabBtnProfile.className = 'flex items-center gap-2 pb-3 px-3 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all cursor-pointer';
                tabContentAccount.classList.remove('hidden');
                tabContentProfile.classList.add('hidden');
            }
        }

        // Avatar file preview
        function onAvatarFileSelected(input) {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];

            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                showAlert('profileAlert', 'Ukuran foto maksimal adalah 2 MB. Silakan pilih foto lain.', true);
                input.value = '';
                return;
            }

            hideAlert('profileAlert');
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarModalPreview');
                if (preview) preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        // Toggle password visibility
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        // Global Toast Notification
        window.globalToastTimeout = null;
        function showGlobalToast(title, message, isError = false) {
            const toast = document.getElementById('globalToast');
            const toastTitle = document.getElementById('toastTitle');
            const toastMsg = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            const toastContainer = document.getElementById('toastIconContainer');
            if (!toast) return;

            toastTitle.textContent = title;
            toastMsg.textContent = message;

            if (isError) {
                toastIcon.textContent = 'error';
                toastContainer.className = 'w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0';
            } else {
                toastIcon.textContent = 'check_circle';
                toastContainer.className = 'w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0';
            }

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.remove('-translate-y-4', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 10);

            clearTimeout(window.globalToastTimeout);
            window.globalToastTimeout = setTimeout(hideGlobalToast, 4500);
        }

        function hideGlobalToast() {
            const toast = document.getElementById('globalToast');
            if (!toast) return;
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('-translate-y-4', 'opacity-0');
            setTimeout(() => toast.classList.add('hidden'), 300);
        }

        // Alert helper inside modal
        function showAlert(containerId, message, isError = true) {
            const box = document.getElementById(containerId);
            if (!box) return;
            box.className = isError
                ? 'mb-4 p-3 rounded-xl text-xs font-medium border bg-red-50 text-red-700 border-red-200'
                : 'mb-4 p-3 rounded-xl text-xs font-medium border bg-emerald-50 text-emerald-700 border-emerald-200';
            box.innerHTML = message;
            box.classList.remove('hidden');
        }

        function hideAlert(containerId) {
            const box = document.getElementById(containerId);
            if (box) {
                box.classList.add('hidden');
                box.innerHTML = '';
            }
        }

        // Handle Profile Form Submit via Fetch (AJAX)
        async function handleProfileSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const btn = document.getElementById('btnSaveProfile');
            const originalBtnHtml = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-outlined text-base animate-spin">progress_activity</span><span>Menyimpan...</span>`;
            hideAlert('profileAlert');

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Update user name in all places
                    const sidebarName = document.getElementById('sidebarUserName');
                    const headerName = document.getElementById('headerUserName');
                    const popupName = document.getElementById('popupUserName');
                    if (sidebarName) sidebarName.textContent = data.name;
                    if (headerName) headerName.textContent = data.name;
                    if (popupName) popupName.textContent = data.name;

                    // Update avatar in all places
                    if (data.avatar_url) {
                        const sidebarAvatar = document.getElementById('sidebarUserAvatar');
                        const headerAvatar = document.getElementById('headerUserAvatar');
                        const popupAvatar = document.getElementById('popupUserAvatar');
                        const modalAvatar = document.getElementById('avatarModalPreview');
                        if (sidebarAvatar) sidebarAvatar.src = data.avatar_url;
                        if (headerAvatar) headerAvatar.src = data.avatar_url;
                        if (popupAvatar) popupAvatar.src = data.avatar_url;
                        if (modalAvatar) modalAvatar.src = data.avatar_url;
                    }

                    closeProfileModal();
                    showGlobalToast('Berhasil!', data.message || 'Profil berhasil diperbarui!');
                } else {
                    let errMsg = data.message || 'Gagal menyimpan profil.';
                    if (data.errors) {
                        errMsg = Object.values(data.errors).flat().join('<br>');
                    }
                    showAlert('profileAlert', errMsg, true);
                }
            } catch (err) {
                console.error(err);
                // Fallback to normal form submit if fetch fails
                form.submit();
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalBtnHtml;
            }
        }

        // Handle Account (Email & Password) Form Submit via Fetch (AJAX)
        async function handleAccountSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const btn = document.getElementById('btnSaveAccount');
            const originalBtnHtml = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-outlined text-base animate-spin">progress_activity</span><span>Menyimpan...</span>`;
            hideAlert('accountAlert');

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Update email on popup header
                    const popupEmail = document.getElementById('popupUserEmail');
                    if (popupEmail && data.email) {
                        popupEmail.textContent = data.email;
                    }

                    // Clear password fields
                    document.getElementById('inputAccountPassword').value = '';
                    document.getElementById('inputAccountPasswordConfirmation').value = '';

                    closeProfileModal();
                    showGlobalToast('Berhasil!', data.message || 'Email & password login berhasil diperbarui!');
                } else {
                    let errMsg = data.message || 'Gagal menyimpan email dan password.';
                    if (data.errors) {
                        errMsg = Object.values(data.errors).flat().join('<br>');
                    }
                    showAlert('accountAlert', errMsg, true);
                }
            } catch (err) {
                console.error(err);
                // Fallback to normal form submit
                form.submit();
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalBtnHtml;
            }
        }

        // Auto display session notifications or open modal on server-side validation error
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('profile_success'))
                showGlobalToast('Berhasil!', '{{ session("profile_success") }}');
            @endif
            @if(session('account_success'))
                showGlobalToast('Berhasil!', '{{ session("account_success") }}');
            @endif
            @if($errors->has('name') || $errors->has('avatar'))
                openProfileModal('profile');
                showAlert('profileAlert', '{!! addslashes(implode("<br>", $errors->all())) !!}', true);
            @elseif($errors->has('email') || $errors->has('password'))
                openProfileModal('account');
                showAlert('accountAlert', '{!! addslashes(implode("<br>", $errors->all())) !!}', true);
            @endif
        });
    </script>
</body>
</html>