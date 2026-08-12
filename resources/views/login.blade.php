<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login - Mandalacare</title>


    <!-- =====================================================
         GOOGLE FONT
    ====================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- =====================================================
         MATERIAL ICON
    ====================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap"
        rel="stylesheet"
    >


    <!-- =====================================================
         TAILWIND
    ====================================================== -->

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

                        background: '#faf8ff',

                        'surface-container-low': '#f2f3ff',

                        'on-surface': '#131b2e',

                        'on-surface-variant': '#3c4a42',

                        outline: '#6c7a71',

                        'outline-variant': '#bbcabf',

                        'error-container': '#ffdad6',

                        'on-error-container': '#93000a'

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
        }


        .material-symbols-outlined {

            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;

        }


        .input-glow:focus-within {

            box-shadow:
                0 0 0 3px rgba(16, 185, 129, 0.15);

            border-color: #10b981;

        }


        .card-shadow {

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.05);

        }

    </style>

</head>


<body class="bg-background text-on-surface min-h-screen">


<div class="flex min-h-screen w-full">


    <!-- =====================================================
         LEFT SIDE - BRANDING
    ====================================================== -->

    <section
        class="hidden lg:flex
               lg:w-1/2
               relative
               overflow-hidden
               bg-surface-container-low
               items-center
               justify-center
               px-10"
    >


        <!-- Background Decoration -->

        <div class="absolute inset-0 opacity-30">

            <div
                class="absolute
                       w-[500px]
                       h-[500px]
                       rounded-full
                       bg-primary-container
                       blur-3xl
                       -top-40
                       -left-40"
            ></div>


            <div
                class="absolute
                       w-[500px]
                       h-[500px]
                       rounded-full
                       bg-secondary-container
                       blur-3xl
                       -bottom-40
                       -right-40"
            ></div>

        </div>


        <!-- Branding Content -->

        <div
            class="relative
                   z-10
                   text-center
                   max-w-md"
        >


            <!-- Logo -->

            <div
                class="mx-auto
                       w-24
                       h-24
                       rounded-3xl
                       bg-white
                       border
                       border-outline-variant
                       shadow-sm
                       flex
                       items-center
                       justify-center
                       mb-8"
            >

                <span
                    class="material-symbols-outlined
                           text-primary
                           text-5xl"
                    style="font-variation-settings:'FILL' 1;"
                >
                    health_and_safety
                </span>

            </div>


            <!-- Brand -->

            <h1
                class="text-5xl
                       font-bold
                       text-primary
                       tracking-tight"
            >
                Mandalacare
            </h1>


            <h2
                class="text-2xl
                       font-semibold
                       text-secondary
                       mt-2"
            >
                Clinical Management
            </h2>


            <p
                class="text-lg
                       text-on-surface-variant
                       mt-5"
            >
                Sistem manajemen klinik yang membantu
                mengelola pelayanan pasien secara lebih
                mudah dan terintegrasi.
            </p>


        </div>


        <!-- Footer -->

        <div
            class="absolute
                   bottom-8
                   left-10
                   right-10
                   flex
                   justify-between
                   text-sm
                   text-on-surface-variant"
        >

            <span>
                © 2026 Mandalacare
            </span>

            <span>
                Versi 1.0.0
            </span>

        </div>


    </section>



    <!-- =====================================================
         RIGHT SIDE - LOGIN
    ====================================================== -->

    <section
        class="w-full
               lg:w-1/2
               min-h-screen
               flex
               items-center
               justify-center
               bg-white
               p-6
               lg:p-10"
    >


        <div
            class="w-full
                   max-w-[440px]
                   bg-white
                   border
                   border-slate-200
                   rounded-2xl
                   p-7
                   md:p-9
                   card-shadow"
        >


            <!-- Mobile Logo -->

            <div
                class="lg:hidden
                       flex
                       flex-col
                       items-center
                       mb-8"
            >

                <span
                    class="material-symbols-outlined
                           text-primary
                           text-5xl
                           mb-3"
                    style="font-variation-settings:'FILL' 1;"
                >
                    health_and_safety
                </span>


                <h1
                    class="text-2xl
                           font-bold
                           text-primary"
                >
                    Mandalacare
                </h1>


                <p
                    class="text-sm
                           text-on-surface-variant"
                >
                    Clinical Management
                </p>

            </div>



            <!-- Heading -->

            <div class="mb-8">

                <p
                    class="text-sm
                           font-semibold
                           text-primary
                           mb-2"
                >
                    PEMILIK KLINIK
                </p>


                <h2
                    class="text-3xl
                           font-semibold"
                >
                    Selamat Datang
                </h2>


                <p
                    class="text-base
                           text-on-surface-variant
                           mt-2"
                >
                    Masuk untuk mengakses sistem
                    Mandalacare.
                </p>

            </div>



            <!-- =================================================
                 ERROR MESSAGE
            ================================================== -->

            @if ($errors->any())

                <div
                    class="flex
                           items-start
                           gap-3
                           p-4
                           mb-6
                           rounded-xl
                           bg-error-container
                           text-on-error-container
                           border
                           border-red-200"
                >

                    <span class="material-symbols-outlined">
                        error
                    </span>


                    <div>

                        <p class="font-semibold text-sm">
                            Login gagal
                        </p>

                        <p class="text-sm mt-1">
                            {{ $errors->first() }}
                        </p>

                    </div>

                </div>

            @endif



            <!-- =================================================
                 LOGIN FORM
            ================================================== -->

            <form
                method="POST"
                action="{{ route('login') }}"
                class="space-y-5"
            >

                @csrf



                <!-- EMAIL -->

                <div>

                    <label
                        for="email"
                        class="block
                               text-sm
                               font-semibold
                               mb-2"
                    >
                        Email
                    </label>


                    <div
                        class="relative
                               flex
                               items-center
                               border
                               border-slate-200
                               rounded-xl
                               input-glow"
                    >

                        <span
                            class="material-symbols-outlined
                                   absolute
                                   left-4
                                   text-outline
                                   pointer-events-none"
                        >
                            mail
                        </span>


                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan email pemilik"
                            autocomplete="email"
                            required
                            autofocus
                            class="w-full
                                   h-12
                                   pl-12
                                   pr-4
                                   rounded-xl
                                   border-0
                                   outline-none
                                   focus:ring-0"
                        >

                    </div>

                </div>



                <!-- PASSWORD -->

                <div>

                    <label
                        for="password"
                        class="block
                               text-sm
                               font-semibold
                               mb-2"
                    >
                        Password
                    </label>


                    <div
                        class="relative
                               flex
                               items-center
                               border
                               border-slate-200
                               rounded-xl
                               input-glow"
                    >

                        <span
                            class="material-symbols-outlined
                                   absolute
                                   left-4
                                   text-outline
                                   pointer-events-none"
                        >
                            lock
                        </span>


                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                            class="w-full
                                   h-12
                                   pl-12
                                   pr-12
                                   rounded-xl
                                   border-0
                                   outline-none
                                   focus:ring-0"
                        >


                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute
                                   right-4
                                   text-outline
                                   hover:text-primary"
                        >

                            <span
                                id="password-toggle"
                                class="material-symbols-outlined"
                            >
                                visibility
                            </span>

                        </button>

                    </div>

                </div>



                <!-- REMEMBER -->

                <div>

                    <label
                        class="flex
                               items-center
                               gap-2
                               cursor-pointer"
                    >

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="rounded
                                   border-outline
                                   text-primary
                                   focus:ring-primary"
                        >


                        <span
                            class="text-sm
                                   text-on-surface-variant"
                        >
                            Ingat saya
                        </span>

                    </label>

                </div>



                <!-- LOGIN BUTTON -->

                <button
                    type="submit"
                    class="w-full
                           h-12
                           rounded-xl
                           bg-primary
                           text-white
                           font-semibold
                           flex
                           items-center
                           justify-center
                           gap-2
                           hover:bg-[#005c3e]
                           transition
                           shadow-sm"
                >

                    <span class="material-symbols-outlined">
                        login
                    </span>

                    Masuk ke Mandalacare

                </button>


            </form>



            <!-- INFO -->

            <div
                class="mt-7
                       pt-5
                       border-t
                       border-slate-200
                       text-center"
            >

                <p
                    class="text-xs
                           text-on-surface-variant"
                >
                    Akses sistem hanya diperuntukkan
                    bagi pemilik klinik.
                </p>

            </div>


        </div>

    </section>

</div>



<!-- =====================================================
     PASSWORD TOGGLE
====================================================== -->

<script>

function togglePassword() {

    const passwordInput =
        document.getElementById('password');

    const toggleIcon =
        document.getElementById('password-toggle');


    if (passwordInput.type === 'password') {

        passwordInput.type = 'text';

        toggleIcon.textContent =
            'visibility_off';

    } else {

        passwordInput.type = 'password';

        toggleIcon.textContent =
            'visibility';

    }

}

</script>


</body>
</html>