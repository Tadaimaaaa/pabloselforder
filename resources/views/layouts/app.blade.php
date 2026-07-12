<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Kopi Pablo | Self-Service Ordering System')</title>
    <meta name="description" content="Pesan kopi dan hidangan istimewa Kopi Pablo secara mandiri, cepat, tanpa antre.">
    <meta name="theme-color" content="#34543D">

    <!-- PWA Manifest & Favicon -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS & AlpineJS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pablo: {
                            primary: '#34543D',
                            secondary: '#58725A',
                            bg: '#F6F0E1',
                            card: '#FFFFFF',
                            accent: '#7A9478',
                            border: '#D8D6CF',
                            text: '#24352A',
                            muted: '#6E756D',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Poppins', 'sans-serif'],
                    },
                    borderRadius: {
                        'pablo': '16px',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
            background-color: #F6F0E1;
            color: #24352A;
            -webkit-tap-highlight-color: transparent;
        }
        /* Custom scrollbar untuk Horizontal Category Filter */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .card-shadow {
            box-shadow: 0 4px 20px -2px rgba(36, 53, 42, 0.08);
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .glass-dark {
            background: rgba(52, 84, 61, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-[#34543D] selection:text-white">

    <!-- TOP STICKY NAVBAR -->
    <header class="sticky top-0 z-50 glass-nav border-b border-[#D8D6CF]/70 transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('landing') }}" class="flex items-center space-x-2.5 group">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Kopi Pablo" class="w-10 h-10 rounded-full object-contain shadow-sm group-hover:scale-105 transition-transform bg-[#F6F0E1]">
                <div>
                    <span class="font-bold text-lg tracking-tight text-[#24352A] block leading-none">KOPI PABLO</span>
                    <span class="text-[10px] font-medium tracking-widest uppercase text-[#6E756D] block mt-1">Self-Service Order</span>
                </div>
            </a>

            <!-- Right Nav Links -->
            <div class="flex items-center space-x-2 sm:space-x-2.5">
                @if(!request()->routeIs('landing', 'register', 'login'))
                    <a href="{{ route('menu') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('menu') ? 'bg-[#34543D] text-white shadow-sm' : 'text-[#24352A] hover:bg-[#D8D6CF]/40' }}">
                        <i class="fa-solid fa-utensils text-[11px]"></i>
                        <span>Menu</span>
                    </a>

                    @php
                        $headerCartCount = count(session('cart', []));
                    @endphp
                    <a href="{{ route('cart') }}" 
                       class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $headerCartCount > 0 ? 'bg-amber-100 text-amber-900 hover:bg-amber-200 border border-amber-300/80 shadow-2xs' : 'text-[#24352A] hover:bg-[#D8D6CF]/40 border border-transparent' }}" 
                       title="Lihat Keranjang Belanja">
                        <i class="fa-solid fa-bag-shopping {{ $headerCartCount > 0 ? 'text-amber-800' : 'text-[#6E756D]' }}"></i>
                        <span>Keranjang</span>
                        <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded-full {{ $headerCartCount > 0 ? 'bg-[#34543D] text-white' : 'bg-[#D8D6CF]/80 text-[#24352A]' }}">
                            {{ $headerCartCount }}
                        </span>
                    </a>

                    <a href="{{ route('orders') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('orders*') ? 'bg-[#34543D] text-white shadow-sm' : 'text-[#24352A] hover:bg-[#D8D6CF]/40' }}" title="Riwayat Pesanan Saya">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span class="hidden sm:inline">Riwayat</span>
                    </a>
                @endif

                @auth
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/60 transition flex items-center gap-1.5" title="Keluar">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="hidden md:inline">Keluar</span>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <!-- ALERT MESSAGES -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-3">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in text-sm font-medium mb-2">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold ml-2">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in text-sm font-medium mb-2">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold ml-2">&times;</button>
            </div>
        @endif
    </div>

    <!-- MAIN APP CONTENT (Responsive & Adaptive Mobile + Windows Desktop PC) -->
    <main class="max-w-7xl mx-auto w-full flex-1 px-4 sm:px-6 lg:px-8 py-6 pb-28">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#24352A] text-[#F6F0E1] border-t border-[#34543D]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-center md:text-left">
                    <h3 class="font-bold text-base flex items-center justify-center md:justify-start gap-2">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo Kopi Pablo" class="w-6 h-6 rounded-full object-contain"> Kopi Pablo
                    </h3>
                    <p class="text-xs text-[#D8D6CF] mt-1">Crafted Coffee & Self-Service Ordering PWA</p>
                </div>
                <div class="text-center md:text-right text-xs text-[#6E756D]">
                    <p>&copy; {{ date('Y') }} Kopi Pablo Padang. All rights reserved.</p>
                    <p class="text-[11px] text-[#7A9478] mt-0.5">Artisan Coffee & Self-Service PWA</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Service Worker PWA Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset("sw.js") }}')
                    .then(reg => console.log('ServiceWorker registered:', reg.scope))
                    .catch(err => console.log('ServiceWorker registration failed:', err));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
