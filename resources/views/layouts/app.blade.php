<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'BBKB Yogyakarta - Magang')</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

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

</body>
</html>

