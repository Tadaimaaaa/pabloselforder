@extends('layouts.app')

@section('title', 'Kopi Pablo | Official Self-Service Order')

@section('content')
<div class="space-y-7">

    @if(request('completed'))
        <!-- BANNER CELEBRATION PESANAN SELESAI -->
        <div class="bg-emerald-600 text-white rounded-[24px] p-5 shadow-xl flex items-center justify-between gap-4 border border-emerald-400 animate-fade-in">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-white text-emerald-700 flex items-center justify-center shrink-0 text-2xl shadow">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
                <div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-emerald-200 block">PESANAN ANDA SELESAI</span>
                    <h3 class="font-extrabold text-sm sm:text-base leading-tight">Terima kasih telah menikmati sajian Kopi Pablo!</h3>
                    <p class="text-xs text-emerald-100 mt-0.5">Silakan menikmati hidangan Anda. Ingin memesan lagi?</p>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-white/80 hover:text-white text-xl p-2">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- HERO BANNER DENGAN BACKGROUND FOTO GESER OTOMATIS -->
    <section class="relative overflow-hidden rounded-[28px] text-white shadow-2xl min-h-[340px] md:min-h-[420px] flex items-center bg-[#24352A]">
        <!-- Slideshow Background Images -->
        <div id="heroSlide0" class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-100">
            <img src="{{ asset('img/hero/hero-1.jpg') }}" alt="Toko Kopi Pablo Container Booth" class="w-full h-full object-cover">
        </div>
        <div id="heroSlide1" class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0">
            <img src="{{ asset('img/hero/hero-2.jpg') }}" alt="Toko Kopi Pablo Classic Shopfront" class="w-full h-full object-cover">
        </div>
        <div id="heroSlide2" class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0">
            <img src="{{ asset('img/hero/hero-3.jpg') }}" alt="Toko Kopi Pablo Bajaj Coffee" class="w-full h-full object-cover">
        </div>

        <!-- Dark Gradient Overlay (bagian kanan transparan agar foto terlihat jelas) -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#1b2b20]/95 via-[#24352A]/75 to-[#24352A]/20 z-10"></div>

        <!-- Hero Content -->
        <div class="relative z-20 p-6 md:p-10 max-w-xl space-y-5">
            <div class="inline-flex items-center gap-2.5 bg-white/15 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20 shadow-sm">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Kopi Pablo" class="w-6 h-6 rounded-full object-contain bg-[#F6F0E1]">
                <span class="text-xs font-bold tracking-wide text-white">Kopi Pablo &bull; Self-Service Ordering</span>
            </div>
            
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black leading-tight drop-shadow-md">
                Nikmati Kopi Pablo,<br>
                <span class="text-amber-200">Pesan Mandiri Tanpa Antri.</span>
            </h1>
            <p class="text-xs md:text-sm text-[#D8D6CF] leading-relaxed max-w-md font-medium">
                Pilih menu favoritmu langsung dari layar ini, tentukan catatan rasa, dan ambil di outlet saat pesanan siap.
            </p>
            <div class="pt-2 flex flex-wrap items-center gap-3">
                <a href="{{ route('menu') }}" class="inline-flex items-center justify-center gap-2.5 bg-[#F6F0E1] text-[#24352A] font-extrabold text-xs md:text-sm px-6 py-3.5 rounded-2xl shadow-xl hover:bg-white active:scale-95 transition-all">
                    <span>Mulai Pesan Sekarang</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="#favorit" class="inline-flex items-center justify-center gap-1.5 border border-white/40 bg-black/20 backdrop-blur-md text-white font-bold text-xs md:text-sm px-5 py-3.5 rounded-2xl hover:bg-white/20 transition">
                    Lihat Favorit
                </a>
            </div>
        </div>

        <!-- Slideshow Indicators -->
        <div class="absolute bottom-5 right-6 z-20 flex items-center space-x-2 bg-black/30 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/15">
            <button type="button" onclick="showHeroSlide(0)" id="heroDot0" class="h-2 w-6 rounded-full bg-amber-300 transition-all duration-300"></button>
            <button type="button" onclick="showHeroSlide(1)" id="heroDot1" class="h-2 w-2 rounded-full bg-white/50 hover:bg-white transition-all duration-300"></button>
            <button type="button" onclick="showHeroSlide(2)" id="heroDot2" class="h-2 w-2 rounded-full bg-white/50 hover:bg-white transition-all duration-300"></button>
        </div>
    </section>

    @push('scripts')
    <script>
        let currentHeroSlide = 0;
        const totalHeroSlides = 3;

        function showHeroSlide(index) {
            currentHeroSlide = index;
            for (let i = 0; i < totalHeroSlides; i++) {
                const slideEl = document.getElementById('heroSlide' + i);
                const dotEl = document.getElementById('heroDot' + i);
                if (slideEl && dotEl) {
                    if (i === index) {
                        slideEl.classList.remove('opacity-0');
                        slideEl.classList.add('opacity-100');
                        dotEl.className = 'h-2 w-6 rounded-full bg-amber-300 transition-all duration-300';
                    } else {
                        slideEl.classList.remove('opacity-100');
                        slideEl.classList.add('opacity-0');
                        dotEl.className = 'h-2 w-2 rounded-full bg-white/50 hover:bg-white transition-all duration-300';
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setInterval(function() {
                let nextSlide = (currentHeroSlide + 1) % totalHeroSlides;
                showHeroSlide(nextSlide);
            }, 4500);
        });
    </script>
    @endpush

    <!-- KATEGORI MENU QUICK ACCESS -->
    <section>
        <div class="flex items-center justify-between mb-3.5">
            <h2 class="text-base font-bold text-[#24352A]">Kategori Menu</h2>
            <a href="{{ route('menu') }}" class="text-xs font-semibold text-[#34543D] hover:underline">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($categories as $category)
                <a href="{{ route('menu', ['category' => $category->slug]) }}" 
                   class="bg-white rounded-[18px] p-3.5 border border-[#D8D6CF] card-shadow hover:border-[#34543D] transition group flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#F6F0E1] text-[#34543D] flex items-center justify-center group-hover:bg-[#34543D] group-hover:text-white transition">
                        <i class="fa-solid {{ $category->icon ?: 'fa-coffee' }}"></i>
                    </div>
                    <span class="text-xs font-bold text-[#24352A] group-hover:text-[#34543D] transition">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- PRODUK FAVORIT KOPI PABLO -->
    <section id="favorit">
        <div class="flex items-center justify-between mb-3.5">
            <div>
                <h2 class="text-base font-bold text-[#24352A]">Rekomendasi Favorit</h2>
                <p class="text-[11px] text-[#6E756D]">Pilihan menu terpopuler Kopi Pablo</p>
            </div>
            <a href="{{ route('menu') }}" class="text-xs font-semibold text-[#34543D] hover:underline">Semua Menu</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($favorites as $item)
                <div class="bg-white rounded-[20px] p-3.5 border border-[#D8D6CF] card-shadow flex gap-3.5 items-center">
                    <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-20 h-20 rounded-[14px] object-cover shrink-0 bg-[#F6F0E1]">
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] font-semibold text-[#58725A] uppercase tracking-wide block">{{ $item->category->name }}</span>
                        <h3 class="font-bold text-sm text-[#24352A] truncate mt-0.5">{{ $item->name }}</h3>
                        <p class="text-xs font-extrabold text-[#34543D] mt-1.5">{{ $item->formatted_price }}</p>
                    </div>
                    <!-- Quick Add to Cart Form -->
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-9 h-9 rounded-xl bg-[#34543D] text-white flex items-center justify-center shadow hover:bg-[#24352A] active:scale-90 transition" title="Tambah ke Keranjang">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </section>

    <!-- TENTANG KOPI PABLO & 2 OUTLET PADANG -->
    <section class="bg-white rounded-[24px] p-6 border border-[#D8D6CF] card-shadow space-y-5">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-[#34543D] text-white flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-store text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-[#24352A]">Tentang Kopi Pablo Padang</h3>
                    <p class="text-xs text-[#6E756D] font-medium">{{ $storeSettings['tagline'] }}</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-extrabold">
                <i class="fa-solid fa-check-circle text-emerald-600"></i> 2 Outlet Resmi
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-[#D8D6CF]/70">
            <!-- Outlet 1: Alai Parak Kopi / Padang Baru -->
            <div class="bg-[#F6F0E1]/50 rounded-2xl p-4 border border-[#D8D6CF]/80 hover:border-[#34543D] transition space-y-2">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 text-xs font-extrabold text-[#34543D] uppercase tracking-wide">
                        <i class="fa-solid fa-location-dot"></i> Outlet 1 • Padang Baru (Alai Parak Kopi)
                    </span>
                    <span class="text-[10px] font-bold bg-[#34543D] text-white px-2 py-0.5 rounded-md">Buka</span>
                </div>
                <p class="text-xs text-[#24352A] leading-relaxed font-medium">
                    Jl. Batang Kasang, Alai Parak Kopi, Kec. Padang Utara, Kota Padang, Sumatera Barat 25173
                </p>
                <div class="flex items-center gap-2 text-xs font-bold text-[#34543D] pt-1">
                    <i class="fa-solid fa-clock"></i>
                    <span>06:30 - 21:00 WIB</span>
                </div>
            </div>

            <!-- Outlet 2: Pondok -->
            <div class="bg-[#F6F0E1]/50 rounded-2xl p-4 border border-[#D8D6CF]/80 hover:border-[#34543D] transition space-y-2">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 text-xs font-extrabold text-[#34543D] uppercase tracking-wide">
                        <i class="fa-solid fa-location-dot"></i> Outlet 2 • Pondok
                    </span>
                    <span class="text-[10px] font-bold bg-amber-500 text-white px-2 py-0.5 rounded-md flex items-center gap-1">
                        <i class="fa-solid fa-bolt text-[9px]"></i> 24 JAM
                    </span>
                </div>
                <p class="text-xs text-[#24352A] leading-relaxed font-medium">
                    29Q6+5XR, Jl. Kelenteng, Kp. Pd., Kec. Padang Bar., Kota Padang, Sumatera Barat
                </p>
                <div class="flex items-center gap-2 text-xs font-bold text-amber-700 pt-1">
                    <i class="fa-solid fa-clock"></i>
                    <span>Buka 24 Jam Non-Stop</span>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
