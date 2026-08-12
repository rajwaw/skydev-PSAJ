<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Mandalacare')
    </title>


    <!-- Google Font -->

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- Material Symbols -->

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap"
        rel="stylesheet"
    >


    <!-- Tailwind -->

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>


    <script>

        tailwind.config = {

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

                        'surface-container-low': '#f2f3ff',

                        'surface-container-high': '#e2e7ff'

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

            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.04);

        }

    </style>

</head>


<body
    class="bg-background
           min-h-screen
           flex
           text-on-surface"
>


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside
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
                       {{ request()->routeIs('dashboard')
                            ? 'bg-primary-container text-on-primary-container font-semibold shadow-sm'
                            : 'text-on-surface-variant hover:bg-surface-container-low' }}"
            >

                <span
                    class="material-symbols-outlined
                           {{ request()->routeIs('dashboard') ? 'filled-icon' : '' }}"
                >
                    grid_view
                </span>


                <span class="text-sm font-semibold">
                    Dashboard
                </span>

            </a>



            <!-- PENDAFTARAN -->

            <a
                href="#"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       text-on-surface-variant
                       hover:bg-surface-container-low
                       transition-all"
            >

                <span class="material-symbols-outlined">
                    person_add
                </span>

                <span class="text-sm font-semibold">
                    Pendaftaran
                </span>

            </a>



            <!-- PASIEN -->

            <a
                href="#"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       text-on-surface-variant
                       hover:bg-surface-container-low
                       transition-all"
            >

                <span class="material-symbols-outlined">
                    group
                </span>

                <span class="text-sm font-semibold">
                    Pasien
                </span>

            </a>



            <!-- REKAM MEDIS -->

            <a
                href="#"
                class="flex
                       items-center
                       gap-4
                       px-4
                       py-3
                       rounded-xl
                       text-on-surface-variant
                       hover:bg-surface-container-low
                       transition-all"
            >

                <span class="material-symbols-outlined">
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
                       text-on-surface-variant
                       hover:bg-surface-container-low
                       transition-all"
            >

                <span class="material-symbols-outlined">
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
                       text-on-surface-variant
                       hover:bg-surface-container-low
                       transition-all"
            >

                <span class="material-symbols-outlined">
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
                       text-on-surface-variant
                       hover:bg-surface-container-low
                       transition-all"
            >

                <span class="material-symbols-outlined">
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
                       text-on-surface-variant
                       hover:bg-surface-container-low
                       transition-all"
            >

                <span class="material-symbols-outlined">
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
                       text-on-surface-variant
                       hover:bg-surface-container-low
                       transition-all"
            >

                <span class="material-symbols-outlined">
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
                       text-on-surface-variant
                       hover:bg-surface-container-low
                       transition-all"
            >

                <span class="material-symbols-outlined">
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
                       text-on-surface-variant
                       hover:bg-surface-container-low
                       transition-all"
            >

                <span class="material-symbols-outlined">
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
               min-h-screen"
    >

        @yield('content')

    </main>


</body>

</html>