@extends('layouts.app')

@section('title', 'Keranjang Belanja | Kopi Pablo')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-16">

    <!-- HEADER -->
    <div class="flex items-center justify-between border-b border-[#D8D6CF] pb-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('menu') }}" class="w-10 h-10 rounded-2xl bg-white border border-[#D8D6CF] flex items-center justify-center text-[#24352A] hover:bg-[#F6F0E1] transition shadow-sm">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-lg md:text-xl font-black text-[#24352A]">Keranjang Belanja</h1>
                <p class="text-xs text-[#6E756D]">Periksa kembali pesanan Anda sebelum lanjut ke pembayaran</p>
            </div>
        </div>
        <a href="{{ route('menu') }}" class="inline-flex items-center gap-1.5 text-xs font-extrabold text-[#34543D] bg-white border border-[#34543D] px-4 py-2 rounded-xl hover:bg-[#34543D] hover:text-white transition">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Menu</span>
        </a>
    </div>

    <!-- ALERT PESAN -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 text-xs font-bold flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(empty($cart) || $cartCount == 0)
        <!-- STATE KERANJANG KOSONG -->
        <div class="bg-white rounded-[28px] p-10 text-center border border-[#D8D6CF] card-shadow space-y-4 my-8">
            <div class="w-16 h-16 rounded-full bg-[#F6F0E1] text-[#34543D] flex items-center justify-center mx-auto shadow-inner">
                <i class="fa-solid fa-bag-shopping text-2xl"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-black text-base text-[#24352A]">Keranjang Anda Masih Kosong</h3>
                <p class="text-xs text-[#6E756D]">Pilih minuman kopi, racikan espresso, atau camilan favoritmu sekarang.</p>
            </div>
            <a href="{{ route('menu') }}" class="inline-block bg-[#34543D] hover:bg-[#24352A] text-white text-xs font-extrabold px-6 py-3.5 rounded-xl shadow-md transition">
                Lihat Daftar Menu Kopi Pablo
            </a>
        </div>
    @else
        <!-- DAFTAR ITEM DI KERANJANG -->
        <div class="space-y-4">
            @foreach($cart as $key => $item)
                <div class="bg-white rounded-[24px] p-4 md:p-5 border border-[#D8D6CF] card-shadow flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    <!-- INFO PRODUK -->
                    <div class="flex items-start space-x-3.5">
                        @if(!empty($item['image']))
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 rounded-2xl object-cover border border-[#D8D6CF]/70 bg-[#F6F0E1] shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-[#F6F0E1] text-[#34543D] flex items-center justify-center font-extrabold text-xl shrink-0">
                                <i class="fa-solid fa-mug-hot"></i>
                            </div>
                        @endif
                        <div class="space-y-1">
                            <h3 class="font-black text-sm text-[#24352A]">{{ $item['name'] }}</h3>
                            <p class="text-xs font-extrabold text-[#34543D]">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            @if(!empty($item['notes']))
                                <p class="text-[11px] text-[#6E756D] bg-[#F6F0E1] px-2.5 py-1 rounded-lg inline-block font-medium">
                                    <i class="fa-solid fa-pen text-[9px] mr-1"></i> {{ $item['notes'] }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- KONTROL JUMLAH & SUBTOTAL -->
                    <div class="flex items-center justify-between sm:justify-end gap-6 border-t sm:border-t-0 pt-3 sm:pt-0 border-[#D8D6CF]/60">
                        <!-- Tombol + / - -->
                        <div class="flex items-center space-x-2 bg-[#F6F0E1] px-2 py-1.5 rounded-xl border border-[#D8D6CF]/60">
                            <form action="{{ route('cart.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cart_key" value="{{ $key }}">
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                <button type="submit" class="w-7 h-7 rounded-lg bg-white text-[#24352A] hover:bg-rose-100 hover:text-rose-700 font-bold flex items-center justify-center shadow-xs transition" title="Kurangi">
                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                </button>
                            </form>

                            <span class="font-extrabold text-xs text-[#24352A] w-6 text-center">{{ $item['quantity'] }}</span>

                            <form action="{{ route('cart.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cart_key" value="{{ $key }}">
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                <button type="submit" class="w-7 h-7 rounded-lg bg-[#34543D] text-white hover:bg-[#24352A] font-bold flex items-center justify-center shadow-xs transition" title="Tambah">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Subtotal Per Item -->
                        <div class="text-right min-w-[100px]">
                            <span class="text-[10px] text-[#6E756D] uppercase font-bold block">Subtotal</span>
                            <span class="text-sm font-black text-[#24352A]">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- RINGKASAN & AKSI LANJUTAN -->
        <div class="bg-white rounded-[28px] p-6 border border-[#D8D6CF] card-shadow space-y-5 mt-6">
            <div class="flex items-center justify-between border-b border-[#D8D6CF]/60 pb-4">
                <div>
                    <span class="text-xs font-bold text-[#6E756D] block">TOTAL PEMBELIAN</span>
                    <span class="text-xs font-semibold text-[#58725A]">{{ $cartCount }} jenis menu terpilih</span>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black text-[#34543D]">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <form action="{{ route('cart.clear') }}" method="POST" class="sm:col-span-1">
                    @csrf
                    <button type="submit" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold text-xs py-4 rounded-2xl border border-rose-200 transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Kosongkan</span>
                    </button>
                </form>

                <a href="{{ route('checkout') }}" class="sm:col-span-2 bg-[#34543D] hover:bg-[#24352A] text-white font-extrabold text-sm py-4 rounded-2xl shadow-lg transition active:scale-98 flex items-center justify-center gap-2">
                    <span>Lanjut ke Checkout Pembayaran</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
