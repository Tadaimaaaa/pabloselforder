@extends('layouts.app')

@section('title', 'Verifikasi Tiket Pesanan - ' . $order->order_number)

@section('content')
<div class="max-w-md mx-auto py-6 space-y-6">

    <!-- HEADER / LOGO -->
    <div class="text-center space-y-1">
        <div class="w-16 h-16 mx-auto">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Kopi Pablo" class="w-16 h-16 rounded-full object-contain mx-auto shadow-md bg-[#F6F0E1] border border-[#34543D]/20">
        </div>
        <h1 class="text-lg font-black text-[#24352A] mt-2">VERIFIKASI TIKET PESANAN</h1>
        <p class="text-xs text-[#6E756D]">Hasil Pindai QR Code Resmi Kopi Pablo</p>
    </div>

    <!-- KARTU RINCIAN TIKET RESMI -->
    <div class="bg-white rounded-[32px] border-2 border-[#34543D] shadow-2xl overflow-hidden">
        
        <!-- BAGIAN ATAS: ATAS NAMA CUSTOMER -->
        <div class="bg-gradient-to-r from-[#34543D] to-[#24352A] text-white p-6 text-center space-y-2">
            <span class="inline-block bg-white/20 backdrop-blur-md text-[#F6F0E1] text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                ATAS NAMA PEMESAN
            </span>
            <h2 class="text-3xl font-black tracking-wide text-amber-300 drop-shadow">
                {{ strtoupper($order->customer_name) }}
            </h2>
            <div class="pt-1 flex items-center justify-center gap-3 text-xs text-[#D8D6CF]">
                <span><i class="fa-solid fa-phone mr-1"></i> {{ $order->customer_phone ?: 'Tanpa No. HP' }}</span>
                <span>•</span>
                <span><i class="fa-solid fa-ticket mr-1"></i> {{ $order->order_number }}</span>
            </div>
        </div>

        <!-- OUTLET & STATUS -->
        <div class="p-5 bg-[#F6F0E1]/60 border-b border-[#D8D6CF] flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-[#6E756D] uppercase block">LOKASI OUTLET PENGAMBILAN</span>
                <span class="text-xs font-extrabold text-[#24352A] block mt-0.5">{{ $order->table_number }}</span>
            </div>
            <span class="px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider
                  {{ $order->status === 'selesai' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-900 border border-amber-300' }}">
                {{ $order->status_label }}
            </span>
        </div>

        <!-- DAFTAR PRODUK YANG DIPESAN -->
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-[#D8D6CF] pb-2">
                <h3 class="text-xs font-black text-[#34543D] uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-mug-hot"></i> Daftar Produk Pesanan
                </h3>
                <span class="text-xs font-bold text-[#6E756D]">{{ $order->items->count() }} Varian</span>
            </div>

            <div class="divide-y divide-[#D8D6CF]/70">
                @foreach($order->items as $index => $item)
                    <div class="py-3 flex items-start justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-[#34543D] text-white font-black text-xs flex items-center justify-center shrink-0 mt-0.5">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <h4 class="text-sm font-extrabold text-[#24352A] leading-tight">
                                    {{ $item->product_name }}
                                </h4>
                                <p class="text-xs text-[#6E756D] font-medium mt-0.5">
                                    Sebanyak: <strong class="text-[#24352A]">{{ $item->quantity }} Porsi</strong> @ Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                                @if($item->notes)
                                    <div class="mt-1.5 inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-900 text-xs px-2.5 py-1 rounded-lg font-semibold">
                                        <i class="fa-solid fa-note-sticky text-amber-600"></i>
                                        <span>Catatan: {{ $item->notes }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <span class="text-xs font-black text-[#34543D] shrink-0">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- TOTAL BAYAR -->
            <div class="pt-4 border-t-2 border-dashed border-[#D8D6CF] flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-[#6E756D] uppercase block">TOTAL PEMBAYARAN</span>
                    <span class="text-[10px] text-emerald-600 font-extrabold block">✓ STATUS: LUNAS (QRIS)</span>
                </div>
                <span class="text-xl font-black text-[#34543D]">
                    {{ $order->formatted_total }}
                </span>
            </div>
        </div>

        <!-- FOOTER TIKET -->
        <div class="bg-[#24352A] text-white text-center p-3.5 text-[11px] font-medium">
            Tiket Digital Resmi • Sistem Pemesanan Kopi Pablo
        </div>
    </div>

    <!-- TOMBOL KEMBALI -->
    <div class="text-center">
        <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-white border border-[#D8D6CF] text-[#24352A] font-bold text-xs shadow hover:bg-[#F6F0E1] transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Menu Kopi Pablo
        </a>
    </div>

</div>
@endsection
