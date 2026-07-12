@extends('layouts.app')

@section('title', 'Tiket QR Pesanan #' . $order->order_number . ' | Kopi Pablo')

@section('content')
<div class="space-y-6">

    <!-- TOP BAR -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('menu') }}" class="w-9 h-9 rounded-xl bg-white border border-[#D8D6CF] flex items-center justify-center text-[#24352A] hover:bg-[#F6F0E1]">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <span class="text-[10px] font-bold text-[#58725A] uppercase">TIKET PENGAMBILAN</span>
                <h1 class="text-base font-extrabold text-[#24352A]">{{ $order->order_number }}</h1>
            </div>
        </div>

        <button onclick="window.location.reload()" class="px-3.5 py-2 rounded-xl bg-white border border-[#D8D6CF] text-xs font-bold text-[#34543D] hover:bg-[#F6F0E1] flex items-center gap-1.5 transition">
            <i class="fa-solid fa-rotate text-xs"></i>
            <span>Segarkan</span>
        </button>
    </div>

    <!-- QR CODE & BARCODE PENGAMBILAN PESANAN (LANGKAH 7 ALUR CUSTOMER) -->
    <div class="bg-gradient-to-b from-[#34543D] to-[#24352A] text-white rounded-[24px] p-6 text-center shadow-xl space-y-4 border border-[#7A9478]/30">
        <div class="inline-flex items-center gap-2 bg-[#58725A]/80 text-[#F6F0E1] text-[10px] font-extrabold px-3.5 py-1 rounded-full uppercase tracking-wider">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Kopi Pablo" class="w-4 h-4 rounded-full object-contain bg-[#F6F0E1]">
            <span>Tiket Pengambilan Pesanan</span>
        </div>
        
        <h2 class="text-base font-extrabold">Tunjukkan QR Code ke Barista / Counter</h2>
        <p class="text-xs text-[#D8D6CF] max-w-xs mx-auto">
            Pindai kode di bawah ini pada counter Kopi Pablo untuk konfirmasi & pengambilan hidangan Anda.
        </p>

        <!-- Container QR Code -->
        <div class="bg-white p-5 rounded-2xl w-56 h-56 mx-auto flex flex-col items-center justify-center shadow-lg text-[#24352A]">
            <div id="qrcodeBox" class="mx-auto flex items-center justify-center"></div>
        </div>

        <!-- Visual Barcode & Order Number -->
        <div class="pt-2">
            <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl inline-block border border-white/20">
                <span class="font-mono text-sm md:text-base font-black tracking-widest block text-[#F6F0E1]">{{ $order->order_number }}</span>
                <span class="text-[9px] text-[#D8D6CF] uppercase tracking-wider block">OUTLET: {{ Str::limit($order->table_number, 40) }} | ATAS NAMA: {{ strtoupper($order->customer_name) }}</span>
            </div>
        </div>
    </div>

    <!-- PROGRESS STEP BAR (4 TAHAP STATUS PESANAN) -->
    <div class="bg-white rounded-[24px] p-6 border border-[#D8D6CF] card-shadow space-y-6">
        <div class="text-center">
            <span class="inline-block px-3.5 py-1 rounded-full text-xs font-extrabold 
                  {{ $order->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($order->status === 'dibatalkan' ? 'bg-rose-100 text-rose-800' : 'bg-[#F6F0E1] text-[#34543D]') }}">
                {{ $order->status_label }}
            </span>
            <p class="text-xs text-[#6E756D] mt-1.5">
                Pantau progres racikan pesanan Anda secara *real-time*
            </p>
        </div>

        @php
            $steps = [
                'menunggu' => ['label' => 'Menunggu', 'icon' => 'fa-clock'],
                'diproses' => ['label' => 'Diproses', 'icon' => 'fa-mug-hot'],
                'siap_diambil' => ['label' => 'Siap Diambil', 'icon' => 'fa-bell'],
                'selesai' => ['label' => 'Selesai', 'icon' => 'fa-circle-check'],
            ];
            $currentStepIndex = match($order->status) {
                'menunggu', 'pending' => 1,
                'diproses', 'processing' => 2,
                'siap_diambil', 'ready' => 3,
                'selesai', 'completed' => 4,
                default => 0,
            };
        @endphp

        <!-- Visual Step Indicators -->
        <div class="relative flex items-center justify-between px-2 md:px-6">
            <div class="absolute left-6 right-6 top-5 h-1 bg-[#D8D6CF] -z-0"></div>
            <div class="absolute left-6 top-5 h-1 bg-[#34543D] transition-all duration-500 -z-0" 
                 style="width: {{ max(0, ($currentStepIndex - 1) * 33.33) }}%;"></div>

            @php $index = 1; @endphp
            @foreach($steps as $key => $step)
                <div class="flex flex-col items-center relative z-10">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300
                                {{ $index <= $currentStepIndex ? 'bg-[#34543D] text-white shadow-md' : 'bg-[#F6F0E1] text-[#6E756D] border border-[#D8D6CF]' }}">
                        <i class="fa-solid {{ $step['icon'] }} text-xs"></i>
                    </div>
                    <span class="text-[11px] font-bold mt-2 {{ $index <= $currentStepIndex ? 'text-[#24352A]' : 'text-[#6E756D]' }}">
                        {{ $step['label'] }}
                    </span>
                </div>
                @php $index++; @endphp
            @endforeach
        </div>
    </div>

    <!-- DETAIL CUSTOMER & OUTLET -->
    <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] card-shadow grid grid-cols-2 gap-4">
        <div>
            <span class="text-[10px] font-bold text-[#6E756D] uppercase block">NAMA PEMESAN</span>
            <p class="text-xs font-extrabold text-[#24352A] mt-0.5">{{ $order->customer_name }}</p>
        </div>
        <div>
            <span class="text-[10px] font-bold text-[#6E756D] uppercase block">LOKASI OUTLET PENGAMBILAN</span>
            <p class="text-xs font-extrabold text-[#24352A] mt-0.5">{{ $order->table_number }}</p>
        </div>
        <div>
            <span class="text-[10px] font-bold text-[#6E756D] uppercase block">METODE PEMBAYARAN</span>
            <p class="text-xs font-bold text-[#34543D] mt-0.5 uppercase">QRIS / QR CODE</p>
        </div>
        <div>
            <span class="text-[10px] font-bold text-[#6E756D] uppercase block">WAKTU PESAN</span>
            <p class="text-xs font-semibold text-[#24352A] mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
    </div>

    <!-- DAFTAR ITEM PESANAN -->
    <div class="bg-white rounded-[20px] p-5 border border-[#D8D6CF] card-shadow space-y-4">
        <h3 class="text-xs font-extrabold text-[#58725A] uppercase tracking-wider">
            Rincian Menu Pesanan
        </h3>

        <div class="divide-y divide-[#D8D6CF]/60">
            @foreach($order->items as $item)
                <div class="py-2.5 flex items-start justify-between gap-3">
                    <div>
                        <h4 class="text-xs font-bold text-[#24352A]">{{ $item->product_name }}</h4>
                        <p class="text-[11px] text-[#6E756D]">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        @if($item->notes)
                            <span class="inline-block bg-[#F6F0E1] text-[#58725A] text-[10px] px-2 py-0.5 rounded mt-1">
                                Catatan: {{ $item->notes }}
                            </span>
                        @endif
                    </div>
                    <span class="text-xs font-extrabold text-[#34543D]">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>

        <div class="border-t border-[#D8D6CF] pt-3 flex items-center justify-between font-extrabold text-sm text-[#24352A]">
            <span>Total Transaksi</span>
            <span class="text-[#34543D]">{{ $order->formatted_total }}</span>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#34543D] hover:underline">
            <i class="fa-solid fa-plus"></i> Pesan Menu Lainnya
        </a>
    </div>

</div>

<!-- CEK OTOMATIS STATUS PESANAN (REAL-TIME POLLING) -->
<div id="autoCompleteModal" class="fixed inset-0 bg-black/70 backdrop-blur-md z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-[32px] p-8 max-w-sm w-full text-center space-y-4 shadow-2xl border-4 border-emerald-500 animate-bounce-once">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-3xl">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h3 class="text-xl font-black text-[#24352A]">Pesanan Telah Selesai!</h3>
        <p class="text-xs text-[#6E756D] leading-relaxed">
            Terima kasih! Pesanan Anda sudah siap & selesai dilayani. Anda akan otomatis dialihkan ke halaman utama Kopi Pablo...
        </p>
        <div class="pt-2">
            <a href="{{ route('landing') }}" class="inline-block w-full py-3.5 bg-[#34543D] text-white font-bold text-xs rounded-2xl shadow hover:bg-[#24352A] transition">
                Kembali ke Beranda Sekarang
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Pembuatan QR Code Tiket
        const qrContent = "{{ route('ticket.verify', ['orderNumber' => $order->order_number]) }}";
        const qrContainer = document.getElementById("qrcodeBox");
        if (qrContainer && typeof QRCode !== 'undefined') {
            new QRCode(qrContainer, {
                text: qrContent,
                width: 180,
                height: 180,
                colorDark: "#24352A",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        }

        // 2. Jika status saat ini sudah selesai, langsung alihkan ke beranda setelah 3 detik
        let currentStatus = "{{ $order->status }}";
        const landingUrl = "{{ route('landing') }}?completed=1";

        if (currentStatus === 'selesai' || currentStatus === 'completed') {
            document.getElementById('autoCompleteModal').classList.remove('hidden');
            document.getElementById('autoCompleteModal').classList.add('flex');
            setTimeout(() => {
                window.location.href = landingUrl;
            }, 3000);
            return;
        }

        // 3. Polling AJAX otomatis setiap 3 detik mengecek perubahan status pesanan
        setInterval(function() {
            fetch("{{ route('order.check_status', ['orderNumber' => $order->order_number]) }}")
                .then(response => response.json())
                .then(data => {
                    if (data && data.status) {
                        // Jika status berubah menjadi selesai
                        if (data.status === 'selesai' || data.status === 'completed') {
                            document.getElementById('autoCompleteModal').classList.remove('hidden');
                            document.getElementById('autoCompleteModal').classList.add('flex');
                            setTimeout(() => {
                                window.location.href = landingUrl;
                            }, 2500);
                        } 
                        // Jika status maju ke tahap lain (misal dari menunggu -> diproses / siap diambil)
                        else if (data.status !== currentStatus) {
                            window.location.reload();
                        }
                    }
                })
                .catch(err => console.error("Error checking order status:", err));
        }, 3000);
    });
</script>
@endpush
