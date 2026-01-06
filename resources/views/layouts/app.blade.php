<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'BBKB Yogyakarta - Magang')</title>

    {{-- Resource Hints untuk performa lebih cepat --}}
    <link rel="dns-prefetch" href="{{ url('/') }}">
    @auth
    <link rel="prefetch" href="{{ route('dashboard') }}" as="document">
    <link rel="prefetch" href="{{ route('lowongan') }}" as="document">
    <link rel="prefetch" href="{{ route('lamaran') }}" as="document">
    @else
    <link rel="prefetch" href="{{ route('login') }}" as="document">
    <link rel="prefetch" href="{{ route('register') }}" as="document">
    @endauth

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Font dengan optimasi loading --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet"></noscript>

    {{-- Style tambahan --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            position: relative;
            background: #ffffff;
        }

        /* Pattern batik subtle untuk background putih - tema batik */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 40px, rgba(139, 69, 19, 0.015) 40px, rgba(139, 69, 19, 0.015) 80px),
                repeating-linear-gradient(-45deg, transparent, transparent 40px, rgba(184, 134, 11, 0.015) 40px, rgba(184, 134, 11, 0.015) 80px);
            z-index: 0;
            pointer-events: none;
            opacity: 0.6;
        }

        /* Pastikan konten di atas overlay */
        main {
            position: relative;
            z-index: 1;
            margin: 0;
            padding: 0;
        }

        nav {
            position: relative;
            z-index: 10;
        }

        footer {
            position: relative;
            z-index: 1;
        }

        .fade-in {
            animation: fadeIn 1.2s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 1.5s ease-in-out forwards;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .animate-bounce {
            animation: bounce 2s infinite;
        }

        /* Responsive background */
        @media (max-width: 768px) {
            body {
                background-attachment: scroll;
            }
        }
    </style>
</head>

<body class="text-gray-800">

    {{-- NAVBAR --}}
    @include('components.navbar')

    {{-- CONTENT --}}
    <main class="fade-in">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('components.footer')
</main>

    {{-- Global UI (toasts) and PJAX/nav enhancements --}}
    @include('partials.global_ui')
    @include('partials.nav_enhance')

</body>
</html>

