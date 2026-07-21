@extends('layouts.admin')

@section('title', 'Kelola Pesanan Real-Time | Admin Kopi Pablo')
@section('header_title', 'Manajemen Antrean & Status Pemesanan')

@section('content')
<div class="space-y-6">

    <!-- FILTER STATUS TABS -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-1">
        @php
            $statuses = [
                'all' => 'Semua Pesanan',
                'menunggu' => 'Menunggu Konfirmasi',
                'diproses' => 'Sedang Diproses',
                'siap_diambil' => 'Siap Diambil',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
            ];
            $currentStatus = request('status', 'all');
        @endphp

        @foreach($statuses as $key => $label)
            <a href="{{ route('admin.orders', ['status' => $key]) }}"
               class="px-4 py-2 rounded-xl text-xs font-extrabold transition shrink-0
               {{ $currentStatus === $key ? 'bg-[#34543D] text-white shadow-sm' : 'bg-white text-[#24352A] border border-[#D8D6CF]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- DAFTAR PESANAN -->
    @if($orders->isEmpty())
        <div class="bg-white rounded-[20px] p-8 text-center border border-[#D8D6CF]">
            <p class="text-xs text-[#6E756D]">Belum ada pesanan dengan status ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] shadow-sm space-y-4">
                    <!-- Top header -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 border-b border-[#D8D6CF]/60 pb-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-extrabold text-sm text-[#24352A]">{{ $order->order_number }}</span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold
                                {{ $order->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($order->status === 'dibatalkan' ? 'bg-rose-100 text-rose-800' : 'bg-[#F6F0E1] text-[#34543D]') }}">
                                {{ strtoupper($order->status) }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-[#34543D] text-white flex items-center gap-1 shadow-sm">
                                <i class="fa-solid fa-clock text-[9px]"></i>
                                <span>Ambil: {{ $order->pickup_time ?: 'Secepatnya' }}</span>
                            </span>
                        </div>
                        <div class="text-xs text-[#6E756D]">
                            <i class="fa-solid fa-clock mr-1"></i> {{ $order->created_at->format('d M Y, H:i') }} WIB
                        </div>
                    </div>

                    @if($order->reschedule_status === 'requested')
                        <div class="bg-amber-100 border border-amber-300 text-amber-900 p-3 rounded-xl text-xs font-bold flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
                                <span>⚠️ Permintaan Reschedule Customer: <strong>{{ $order->pickup_time }}</strong> ({{ $order->reschedule_notes }})</span>
                            </div>
                            <span class="bg-amber-600 text-white text-[10px] px-2.5 py-1 rounded-full uppercase shrink-0">Harap Sesuaikan Racikan</span>
                        </div>
                    @elseif($order->reschedule_status === 'late_notice')
                        <div class="bg-blue-100 border border-blue-300 text-blue-900 p-3 rounded-xl text-xs font-bold flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-bell text-blue-600 text-sm"></i>
                                <span>ℹ️ Info Keterlambatan Customer: <strong>{{ $order->pickup_time }}</strong> ({{ $order->reschedule_notes }})</span>
                            </div>
                            <span class="bg-blue-600 text-white text-[10px] px-2.5 py-1 rounded-full uppercase shrink-0">Simpan di Counter</span>
                        </div>
                    @elseif($order->reschedule_status === 'acknowledged' && $order->reschedule_notes)
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-2.5 rounded-xl text-xs flex items-center space-x-2">
                            <i class="fa-solid fa-check-circle text-emerald-600"></i>
                            <span>🕒 Jadwal Diperbarui: <strong>{{ $order->pickup_time }}</strong> ({{ $order->reschedule_notes }})</span>
                        </div>
                    @endif

                    <!-- Customer & Table -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                        <div>
                            <span class="text-[10px] font-bold text-[#6E756D] block">NAMA PEMESAN</span>
                            <span class="font-extrabold text-[#24352A]">{{ $order->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-[#6E756D] block">OUTLET TUJUAN</span>
                            <span class="font-extrabold text-[#24352A]">{{ $order->table_number }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-[#6E756D] block">TELEPON / WA</span>
                            <span class="font-semibold text-[#24352A]">{{ $order->customer_phone ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-[#6E756D] block">TOTAL HARGA</span>
                            <span class="font-extrabold text-[#34543D]">{{ $order->formatted_total }}</span>
                        </div>
                    </div>

                    <!-- Item Rincian -->
                    <div class="bg-[#F6F0E1]/40 rounded-xl p-3 text-xs space-y-1.5">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-[#24352A]">
                                    {{ $item->quantity }}x {{ $item->product_name }}
                                    @if($item->notes)
                                        <em class="text-[11px] text-[#58725A]">({{ $item->notes }})</em>
                                    @endif
                                </span>
                                <span class="font-bold text-[#34543D]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- STATUS UPDATE CONTROL (DROPDOWN + ACTION BUTTONS) -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-3 border-t border-[#D8D6CF]/60">
                        <!-- 1. Dropdown Ubah Status Manual ke Semua Tahap -->
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="bg-[#F6F0E1] border border-[#D8D6CF] text-xs font-bold text-[#24352A] rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#34543D]">
                                <option value="menunggu" {{ $order->status === 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="diproses" {{ $order->status === 'diproses' ? 'selected' : '' }}>Sedang Diproses Barista</option>
                                <option value="siap_diambil" {{ $order->status === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil di Counter</option>
                                <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $order->status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-[#24352A] hover:bg-[#34543D] text-white text-xs font-extrabold shadow-sm transition flex items-center gap-1.5 shrink-0">
                                <i class="fa-solid fa-arrows-rotate text-[11px]"></i>
                                <span>Update Status</span>
                            </button>
                        </form>

                        <!-- 2. Satu Klik Cepat (Next Step Action) -->
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            @if($order->status === 'menunggu')
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="diproses">
                                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#34543D] text-white text-xs font-extrabold shadow hover:bg-[#24352A] flex items-center gap-1.5">
                                        <span>Proses Barista</span>
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </form>
                            @elseif($order->status === 'diproses')
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="siap_diambil">
                                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#34543D] text-white text-xs font-extrabold shadow hover:bg-[#24352A] flex items-center gap-1.5">
                                        <span>Siap Diambil di Counter</span>
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </form>
                            @elseif($order->status === 'siap_diambil')
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-extrabold shadow hover:bg-emerald-700 flex items-center gap-1.5">
                                        <span>Selesai &amp; Serahkan</span>
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            @if($order->status !== 'selesai' && $order->status !== 'dibatalkan')
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST"
                                      onsubmit="return confirm('Batalkan pesanan ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="dibatalkan">
                                    <button type="submit" class="px-3 py-2.5 rounded-xl bg-rose-50 text-rose-700 text-xs font-bold hover:bg-rose-100">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
