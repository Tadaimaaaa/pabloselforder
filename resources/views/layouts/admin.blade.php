<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel | Kopi Pablo')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F6F0E1;
            color: #24352A;
        }
    </style>
</head>
<body class="min-h-screen flex bg-[#F6F0E1]">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#24352A] text-white flex flex-col justify-between shrink-0 hidden md:flex">
        <div>
            <!-- Brand -->
            <div class="p-6 border-b border-[#34543D]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Kopi Pablo" class="w-10 h-10 rounded-full object-contain bg-[#F6F0E1] border border-[#7A9478]/30">
                    <div>
                        <span class="font-extrabold text-base tracking-wide block">KOPI PABLO</span>
                        <span class="text-[10px] text-[#7A9478] uppercase font-bold tracking-widest block">Management System</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold transition
                   {{ request()->routeIs('admin.dashboard') ? 'bg-[#34543D] text-white shadow' : 'text-[#D8D6CF] hover:bg-[#34543D]/50 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line w-5"></i>
                    <span>Dashboard KPI</span>
                </a>

                <a href="{{ route('admin.orders') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold transition
                   {{ request()->routeIs('admin.orders*') ? 'bg-[#34543D] text-white shadow' : 'text-[#D8D6CF] hover:bg-[#34543D]/50 hover:text-white' }}">
                    <i class="fa-solid fa-bell-concierge w-5"></i>
                    <span>Kelola Pesanan</span>
                </a>

                <a href="{{ route('admin.products') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold transition
                   {{ request()->routeIs('admin.products*') ? 'bg-[#34543D] text-white shadow' : 'text-[#D8D6CF] hover:bg-[#34543D]/50 hover:text-white' }}">
                    <i class="fa-solid fa-mug-saucer w-5"></i>
                    <span>Menu & Produk</span>
                </a>

                <a href="{{ route('admin.reports') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold transition
                   {{ request()->routeIs('admin.reports*') ? 'bg-[#34543D] text-white shadow' : 'text-[#D8D6CF] hover:bg-[#34543D]/50 hover:text-white' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5"></i>
                    <span>Laporan Penjualan</span>
                </a>

                <a href="{{ route('landing') }}" target="_blank"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold text-[#7A9478] hover:bg-[#34543D]/30 hover:text-white transition">
                    <i class="fa-solid fa-external-link w-5"></i>
                    <span>Lihat PWA Customer</span>
                </a>
            </nav>
        </div>

        <!-- Footer Admin -->
        <div class="p-4 border-t border-[#34543D]">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-[#34543D] hover:bg-rose-700 text-white text-xs font-bold py-2.5 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- TOPBAR -->
        <header class="bg-white border-b border-[#D8D6CF] px-6 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-3">
                <h1 class="text-base font-extrabold text-[#24352A]">@yield('header_title', 'Back-Office Admin')</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-xs font-bold text-[#58725A] bg-[#F6F0E1] px-3 py-1.5 rounded-xl">
                    <i class="fa-solid fa-user-shield mr-1"></i> Admin Kopi Pablo
                </span>
            </div>
        </header>

        <!-- FLASH NOTIFICATIONS -->
        <div class="px-6 pt-4 space-y-2">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-xl flex items-center justify-between text-xs font-semibold shadow-xs animate-fade-in">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-xl flex items-center justify-between text-xs font-semibold shadow-xs animate-fade-in">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 font-bold">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-xl text-xs font-semibold shadow-xs animate-fade-in">
                    <div class="flex items-center gap-2 mb-1 font-bold">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                        <span>Gagal memperbarui data:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- CONTENT -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
