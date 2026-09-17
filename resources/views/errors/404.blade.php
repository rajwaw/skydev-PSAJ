<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - Mandalacare</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#006c49',
                        'primary-container': '#10b981',
                        'on-primary-container': '#00422b',
                        'surface': '#ffffff',
                        'on-surface': '#131b2e',
                        'on-surface-variant': '#3c4a42',
                        'outline-variant': '#E5E7EB',
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
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="w-20 h-20 rounded-full bg-[#E5F5F0] flex items-center justify-center text-primary mx-auto mb-6">
            <span class="material-symbols-outlined text-4xl">search_off</span>
        </div>

        <h1 class="text-7xl font-bold text-primary mb-2">404</h1>
        <h2 class="text-xl font-semibold text-on-surface mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-sm text-on-surface-variant mb-8 leading-relaxed">
            Sepertinya halaman yang Anda cari sudah dipindahkan atau tidak tersedia. Silakan kembali ke dashboard untuk melanjutkan.
        </p>

        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-primary text-white font-semibold text-sm px-6 py-3 rounded-lg hover:bg-[#005a3c] transition-colors shadow-sm">
            <span class="material-symbols-outlined text-lg">home</span>
            Kembali ke Dashboard
        </a>
    </div>

    <p class="mt-12 text-xs text-on-surface-variant/60">Mandalacare - Clinical Management</p>
</body>
</html>
