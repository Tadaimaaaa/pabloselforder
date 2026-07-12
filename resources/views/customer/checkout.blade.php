@extends('layouts.app')

@section('title', 'Checkout | Kopi Pablo Self-Service Order')

@section('content')
<div class="space-y-6">

    <!-- HEADER TITLE -->
    <div class="flex items-center space-x-3">
        <a href="{{ route('menu') }}" class="w-9 h-9 rounded-xl bg-white border border-[#D8D6CF] flex items-center justify-center text-[#24352A] hover:bg-[#F6F0E1]">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-lg font-extrabold text-[#24352A]">Konfirmasi & Pembayaran QR</h1>
            <p class="text-[11px] text-[#6E756D]">Lengkapi identitas & pilih metode pembayaran QR</p>
        </div>
    </div>

    <!-- MAIN CHECKOUT FORM (Responsive 1 column on Mobile, 2 column split on Windows PC Desktop) -->
    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf

        <!-- LEFT COLUMN: IDENTITAS & METODE BAYAR (lg:col-span-7) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- INFORMASI CUSTOMER & PILIHAN OUTLET -->
            <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] card-shadow space-y-4">
                <h2 class="text-xs font-extrabold text-[#58725A] uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-user"></i> Data Pemesan (Terdaftar)
            </h2>

            <!-- Nama Customer -->
            <div>
                <label for="customer_name" class="block text-xs font-bold text-[#24352A] mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="customer_name" id="customer_name" required
                       value="{{ old('customer_name', Auth::user()->name ?? '') }}"
                       placeholder="Contoh: Budi Santoso"
                       class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-3.5 py-2.5 text-xs text-[#24352A] font-bold focus:outline-none focus:border-[#34543D]">
            </div>

            <!-- Nomor HP / WhatsApp -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="customer_phone" class="block text-xs font-bold text-[#24352A] mb-1.5">
                        Nomor HP / WhatsApp <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="customer_phone" id="customer_phone" required
                           value="{{ old('customer_phone', Auth::user()->email ?? '08123456789') }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-3.5 py-2.5 text-xs text-[#24352A] focus:outline-none focus:border-[#34543D]">
                </div>

                <!-- Lokasi Outlet Pengambilan Pesanan -->
                <div>
                    <label for="table_number" class="block text-xs font-bold text-[#24352A] mb-1.5">
                        Lokasi Outlet Pengambilan Pesanan <span class="text-rose-500">*</span>
                    </label>
                    <select name="table_number" id="table_number" required
                            class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-3.5 py-2.5 text-xs text-[#24352A] font-bold focus:outline-none focus:border-[#34543D]">
                        <option value="Kopi Pablo Padang Baru - Jl. Batang Kasang, Alai Parak Kopi, Kec. Padang Utara, Kota Padang 25173" {{ old('table_number') == 'Kopi Pablo Padang Baru - Jl. Batang Kasang, Alai Parak Kopi, Kec. Padang Utara, Kota Padang 25173' ? 'selected' : '' }}>
                            Kopi Pablo Padang Baru — Jl. Batang Kasang, Alai Parak Kopi, Padang Utara
                        </option>
                        <option value="Kopi Pablo Pondok - 29Q6+5XR, Jl. Kelenteng, Kp. Pd., Kec. Padang Barat, Kota Padang" {{ old('table_number') == 'Kopi Pablo Pondok - 29Q6+5XR, Jl. Kelenteng, Kp. Pd., Kec. Padang Barat, Kota Padang' ? 'selected' : '' }}>
                            Kopi Pablo Pondok — Jl. Kelenteng, Kp. Pd., Padang Barat
                        </option>
                    </select>
                </div>
            </div>

            <!-- Catatan Tambahan -->
            <div>
                <label for="notes" class="block text-xs font-bold text-[#24352A] mb-1.5">
                    Catatan Pesanan (Opsional)
                </label>
                <textarea name="notes" id="notes" rows="2"
                          placeholder="Contoh: Less sugar, extra ice..."
                          class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl p-3 text-xs text-[#24352A] focus:outline-none focus:border-[#34543D]">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- METODE PEMBAYARAN QR (QRIS KOPI PABLO) -->
        <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] card-shadow space-y-4">
            <h2 class="text-xs font-extrabold text-[#58725A] uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-qrcode"></i> Metode Pembayaran
            </h2>

            <label class="block border-2 border-[#34543D] bg-[#F6F0E1]/60 rounded-2xl p-4 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <input type="radio" name="payment_method" value="qris" checked class="text-[#34543D] focus:ring-[#34543D] w-4 h-4">
                        <div>
                            <span class="font-extrabold text-sm text-[#24352A] block">QRIS / QR Code Pembayaran</span>
                            <span class="text-[11px] text-[#6E756D]">GoPay, OVO, Dana, ShopeePay, Mobile Banking</span>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white text-[#34543D] flex items-center justify-center font-bold shadow-sm">
                        <i class="fa-solid fa-qrcode text-lg"></i>
                    </div>
                </div>
        </div>

        <!-- RIGHT COLUMN: RINGKASAN & TOMBOL BAYAR (lg:col-span-5 sticky) -->
        <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24">
            <!-- RINGKASAN PESANAN -->
            <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] card-shadow space-y-4">
                <h2 class="text-xs font-extrabold text-[#58725A] uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-receipt"></i> Ringkasan Pesanan
            </h2>

            <div class="divide-y divide-[#D8D6CF]/60">
                @foreach($cart as $key => $item)
                    <div class="py-3 flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <h3 class="font-bold text-xs text-[#24352A]">{{ $item['name'] }}</h3>
                            <p class="text-[11px] text-[#6E756D] mt-0.5">
                                {{ $item['quantity'] }}x @ Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </p>
                            @if(!empty($item['notes']))
                                <span class="inline-block bg-[#F6F0E1] text-[#58725A] text-[10px] font-medium px-2 py-0.5 rounded-md mt-1">
                                    Catatan: {{ $item['notes'] }}
                                </span>
                            @endif
                        </div>
                        <span class="font-extrabold text-xs text-[#34543D]">
                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- TOTAL PEMBAYARAN -->
            <div class="border-t border-[#D8D6CF] pt-4 space-y-2">
                <div class="flex items-center justify-between text-xs text-[#6E756D]">
                    <span>Subtotal Menu</span>
                    <span>Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between text-xs text-[#6E756D]">
                    <span>Biaya Layanan PWA</span>
                    <span class="text-emerald-600 font-bold">Gratis</span>
                </div>
                <div class="flex items-center justify-between text-base font-extrabold text-[#24352A] pt-2 border-t border-[#D8D6CF]/40">
                    <span>Total Pembayaran QR</span>
                    <span class="text-[#34543D]">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- TOMBOL BAYAR SEKARANG -->
        <div class="space-y-3">
            <button type="submit" class="w-full bg-[#34543D] hover:bg-[#24352A] active:scale-98 text-white font-extrabold text-sm py-4 rounded-[18px] shadow-lg transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-qrcode text-base"></i>
                <span>Bayar Rp {{ number_format($cartTotal, 0, ',', '.') }} & Dapatkan Tiket QR</span>
            </button>
            <p class="text-center text-[10px] text-[#6E756D]">
                Setelah berhasil, Anda akan menerima QR Code pengambilan pesanan di counter Kopi Pablo.
            </p>
        </div>
    </div>
    </form>
</div>
@endsection
