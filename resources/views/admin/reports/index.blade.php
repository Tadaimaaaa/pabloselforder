@extends('layouts.admin')

@section('title', 'Laporan Penjualan | Admin Kopi Pablo')
@section('header_title', 'Rekapitulasi Laporan Penjualan')

@section('content')
<div class="space-y-6">

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#6E756D] uppercase block">TOTAL TRANSAKSI SELESAI</span>
                <span class="text-2xl font-extrabold text-[#24352A] mt-1 block">{{ number_format($totalOrdersCount) }} Transaksi</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F6F0E1] text-[#34543D] flex items-center justify-center text-xl">
                <i class="fa-solid fa-check-double"></i>
            </div>
        </div>

        <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#6E756D] uppercase block">TOTAL PENDAPATAN BERSIH</span>
                <span class="text-2xl font-extrabold text-[#34543D] mt-1 block">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-coins"></i>
            </div>
        </div>
    </div>

    <!-- ACTION PRINT -->
    <div class="flex items-center justify-between">
        <h2 class="font-extrabold text-sm text-[#24352A]">Rincian Transaksi Selesai</h2>
        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-[#34543D] text-white text-xs font-extrabold shadow hover:bg-[#24352A] flex items-center gap-2">
            <i class="fa-solid fa-print"></i>
            <span>Cetak Laporan</span>
        </button>
    </div>

    <!-- TABLE LAPORAN -->
    <div class="bg-white rounded-[20px] border border-[#D8D6CF] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F6F0E1] text-[#24352A] font-extrabold uppercase border-b border-[#D8D6CF]">
                    <tr>
                        <th class="p-4">No. Pesanan</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Outlet</th>
                        <th class="p-4">Total Item</th>
                        <th class="p-4 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8D6CF]/60">
                    @forelse($completedOrders as $order)
                        <tr class="hover:bg-[#F6F0E1]/30">
                            <td class="p-4 font-extrabold text-[#24352A]">{{ $order->order_number }}</td>
                            <td class="p-4 text-[#6E756D]">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="p-4 font-bold text-[#24352A]">{{ $order->customer_name }}</td>
                            <td class="p-4">{{ $order->table_number }}</td>
                            <td class="p-4">{{ $order->items->count() }} Jenis</td>
                            <td class="p-4 text-right font-extrabold text-[#34543D]">{{ $order->formatted_total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-[#6E756D]">Belum ada pesanan dengan status selesai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
