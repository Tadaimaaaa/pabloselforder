@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya | Kopi Pablo')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-12">

    <!-- HEADER -->
    <div class="flex items-center justify-between border-b border-[#D8D6CF] pb-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('menu') }}" class="w-10 h-10 rounded-2xl bg-white border border-[#D8D6CF] flex items-center justify-center text-[#24352A] hover:bg-[#F6F0E1] transition shadow-sm">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-lg md:text-xl font-black text-[#24352A]">Riwayat Pesanan Saya</h1>
                <p class="text-xs text-[#6E756D]">Daftar tiket & transaksi pemesanan Kopi Pablo Anda</p>
            </div>
        </div>
        <a href="{{ route('menu') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-extrabold text-[#34543D] bg-white border border-[#34543D] px-4 py-2 rounded-xl hover:bg-[#34543D] hover:text-white transition">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Pesan Baru</span>
        </a>
    </div>

    @if($orders->isEmpty())
        <!-- STATE KOSONG -->
        <div class="bg-white rounded-[28px] p-10 text-center border border-[#D8D6CF] card-shadow space-y-4 my-8">
            <div class="w-16 h-16 rounded-full bg-[#F6F0E1] text-[#34543D] flex items-center justify-center mx-auto shadow-inner">
                <i class="fa-solid fa-receipt text-2xl"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-black text-base text-[#24352A]">Belum Ada Riwayat Pesanan</h3>
                <p class="text-xs text-[#6E756D]">Semua transaksi pesanan Anda akan dicatat dan ditampilkan di sini.</p>
            </div>
            <a href="{{ route('menu') }}" class="inline-block bg-[#34543D] hover:bg-[#24352A] text-white text-xs font-extrabold px-6 py-3 rounded-xl shadow-md transition">
                Lihat Menu & Pesan Sekarang
            </a>
        </div>
    @else
        <!-- DAFTAR PESANAN -->
        <div class="space-y-4">
            @foreach($orders as $order)
                @php
                    // Penentuan warna badge status
                    $statusBadge = match($order->status) {
                        'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'siap_diambil' => 'bg-amber-100 text-amber-800 border-amber-300 animate-pulse',
                        'diproses' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'dibatalkan' => 'bg-rose-100 text-rose-800 border-rose-300',
                        default => 'bg-[#F6F0E1] text-[#34543D] border-[#D8D6CF]'
                    };
                @endphp

                <div class="bg-white rounded-[24px] p-5 md:p-6 border border-[#D8D6CF] card-shadow hover:shadow-xl transition space-y-4">
                    
                    <!-- BARIS ATAS: NOMOR PESANAN & STATUS -->
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-[#D8D6CF]/60 pb-3.5">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-[#F6F0E1] text-[#34543D] flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-ticket"></i>
                            </span>
                            <div>
                                <span class="font-mono font-black text-sm text-[#24352A] block">{{ $order->order_number }}</span>
                                <span class="text-[11px] text-[#6E756D] block">{{ $order->created_at->format('d M Y • H:i') }} WIB</span>
                            </div>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-extrabold border {{ $statusBadge }} flex items-center gap-1.5 shadow-2xs">
                            <i class="fa-solid fa-circle text-[7px]"></i>
                            <span>{{ $order->status_label }}</span>
                        </span>
                    </div>

                    <!-- BARIS TENGAH: RINCIAN PELANGGAN, OUTLET & HARGA -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                        <div class="sm:col-span-2 space-y-2">
                            <!-- Pemesan -->
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-[#6E756D] font-medium w-24">Atas Nama:</span>
                                <span class="font-extrabold text-[#24352A]">{{ $order->customer_name }}</span>
                            </div>

                            <!-- Lokasi Outlet -->
                            <div class="flex items-start gap-2 text-xs">
                                <span class="text-[#6E756D] font-medium w-24 shrink-0">Outlet Tujuan:</span>
                                <span class="font-semibold text-[#24352A] leading-snug">
                                    <i class="fa-solid fa-location-dot text-[#34543D] mr-1"></i>
                                    {{ $order->table_number }}
                                </span>
                            </div>

                            <!-- Jumlah Item -->
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-[#6E756D] font-medium w-24">Item Pesanan:</span>
                                <span class="font-bold text-[#58725A] bg-[#F6F0E1] px-2.5 py-0.5 rounded-lg text-[11px]">
                                    {{ $order->items->count() }} Jenis Menu
                                </span>
                            </div>
                        </div>

                        <!-- Total Tagihan -->
                        <div class="sm:text-right border-t sm:border-t-0 pt-3 sm:pt-0 border-[#D8D6CF]/60">
                            <span class="text-[10px] text-[#6E756D] font-bold uppercase block">Total Bayar</span>
                            <span class="text-xl md:text-2xl font-black text-[#34543D]">{{ $order->formatted_total }}</span>
                        </div>
                    </div>

                    <!-- BARIS BAWAH: AKSI TIKET QR -->
                    <div class="pt-2 flex flex-wrap items-center justify-between gap-3 border-t border-[#D8D6CF]/60">
                        <span class="text-[11px] text-[#6E756D] flex items-center gap-1.5">
                            <i class="fa-solid fa-qrcode text-[#34543D]"></i>
                            <span>Tunjukkan Tiket QR saat pengambilan</span>
                        </span>

                        <a href="{{ route('order.status', ['orderNumber' => $order->order_number]) }}" 
                           class="inline-flex items-center justify-center gap-2 bg-[#34543D] hover:bg-[#24352A] text-white text-xs font-extrabold px-4 py-2.5 rounded-xl shadow transition active:scale-98">
                            <span>Lihat Tiket QR & Status</span>
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
