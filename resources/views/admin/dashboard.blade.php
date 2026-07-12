@extends('layouts.admin')

@section('title', 'Dashboard KPI | Admin Kopi Pablo')
@section('header_title', 'Dashboard Ringkasan & Statistik Penjualan')

@section('content')
<div class="space-y-6">

    <!-- KPI SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Jumlah Customer -->
        <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#6E756D] uppercase block">JUMLAH CUSTOMER</span>
                <span class="text-2xl font-extrabold text-[#24352A] mt-1 block">{{ number_format($totalCustomers) }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F6F0E1] text-[#34543D] flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Jumlah Produk -->
        <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#6E756D] uppercase block">MENU TERSEDIA</span>
                <span class="text-2xl font-extrabold text-[#24352A] mt-1 block">{{ number_format($totalProducts) }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F6F0E1] text-[#34543D] flex items-center justify-center text-xl">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
        </div>

        <!-- Jumlah Pesanan -->
        <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#6E756D] uppercase block">TOTAL TRANSAKSI</span>
                <span class="text-2xl font-extrabold text-[#24352A] mt-1 block">{{ number_format($totalOrders) }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F6F0E1] text-[#34543D] flex items-center justify-center text-xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <!-- Pendapatan -->
        <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#6E756D] uppercase block">ESTIMASI PENDAPATAN</span>
                <span class="text-xl font-extrabold text-[#34543D] mt-1 block">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- PESANAN TERBARU -->
        <div class="lg:col-span-2 bg-white rounded-[20px] p-5 border border-[#D8D6CF] shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-extrabold text-sm text-[#24352A]">Pesanan Masuk Terbaru</h2>
                <a href="{{ route('admin.orders') }}" class="text-xs font-bold text-[#34543D] hover:underline">Kelola Semua &rarr;</a>
            </div>

            <div class="divide-y divide-[#D8D6CF]/60">
                @forelse($recentOrders as $order)
                    <div class="py-3 flex items-center justify-between gap-4">
                        <div>
                            <span class="font-extrabold text-xs text-[#24352A] block">{{ $order->order_number }}</span>
                            <span class="text-[11px] text-[#6E756D]">{{ $order->customer_name }} ({{ $order->table_number }})</span>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold
                                {{ $order->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-[#F6F0E1] text-[#34543D]' }}">
                                {{ strtoupper($order->status) }}
                            </span>
                            <span class="block text-xs font-bold text-[#24352A] mt-1">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#6E756D] py-4 text-center">Belum ada pesanan terbaru.</p>
                @endforelse
            </div>
        </div>

        <!-- PRODUK TERLARIS -->
        <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] shadow-sm space-y-4">
            <h2 class="font-extrabold text-sm text-[#24352A]">Produk Terlaris</h2>

            <div class="space-y-3">
                @forelse($bestSellingProducts as $item)
                    <div class="flex items-center justify-between bg-[#F6F0E1]/60 p-3 rounded-xl">
                        <div>
                            <h4 class="font-bold text-xs text-[#24352A]">{{ $item->product_name }}</h4>
                            <span class="text-[10px] text-[#58725A]">{{ $item->total_qty }}x terjual</span>
                        </div>
                        <span class="font-extrabold text-xs text-[#34543D]">
                            Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-[#6E756D] py-4 text-center">Belum ada data penjualan produk.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
