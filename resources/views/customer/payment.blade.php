@extends('layouts.app')

@section('title', 'Pembayaran QRIS - Kopi Pablo')

@section('content')
<div class="max-w-md mx-auto space-y-6 pb-12">

    <!-- HEADER / BACK -->
    <div class="flex items-center justify-between">
        <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#34543D] hover:underline">
            &larr; Kembali ke Menu
        </a>
        <span class="text-xs font-black text-[#58725A] uppercase tracking-wider">Langkah 6: Pembayaran QRIS</span>
    </div>

    <!-- ALERT PESAN -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 text-xs font-bold flex items-center gap-2 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            {{ session('success') }}
        </div>
    @endif

    <!-- KARTU UTAMA PEMBAYARAN QRIS -->
    <div class="bg-white rounded-[32px] p-6 md:p-8 border border-[#D8D6CF] card-shadow space-y-6 text-center relative overflow-hidden">
        
        <!-- Badge QRIS Resmi -->
        <div class="flex items-center justify-center gap-2">
            <span class="bg-[#34543D] text-white text-[10px] font-black tracking-widest uppercase px-3 py-1 rounded-full">
                QRIS NASIONAL
            </span>
            <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded-full">
                INSTANT PAY
            </span>
        </div>

        <div>
            <p class="text-xs font-bold text-[#6E756D] uppercase tracking-wider">TOTAL TAGIHAN PEMBAYARAN</p>
            <h2 class="text-3xl md:text-4xl font-black text-[#24352A] mt-1">
                {{ $order->formatted_total }}
            </h2>
            <p class="text-[11px] text-[#6E756D] mt-1">
                Nomor Pesanan: <span class="font-mono font-bold text-[#24352A]">{{ $order->order_number }}</span>
            </p>
        </div>

        <!-- TIMER HITUNG MUNDUR -->
        <div class="bg-[#F6F0E1] border border-[#D8D6CF] rounded-2xl p-3 flex items-center justify-between px-4">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                <span class="text-xs font-bold text-[#24352A]">Selesaikan pembayaran dalam</span>
            </div>
            <span id="countdownTimer" class="font-mono text-sm font-black text-rose-600">05:00</span>
        </div>

        <!-- BINGKAI QR CODE PEMBAYARAN -->
        <div class="bg-gradient-to-b from-[#34543D] to-[#24352A] p-5 rounded-[28px] shadow-xl text-white space-y-4">
            <div class="flex items-center justify-between border-b border-white/20 pb-2">
                <span class="font-extrabold tracking-wider text-xs">QRIS TOKO KOPI PABLO</span>
                <span class="text-[10px] text-white/80 font-mono">NMID: ID1023948812</span>
            </div>

            <!-- QR Code Box -->
            <div class="bg-white p-5 rounded-2xl inline-block shadow-inner mx-auto">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=QRIS-PAYMENT-KOPI-PABLO-{{ $order->order_number }}-Rp{{ $order->total_amount }}&color=24352A" 
                     alt="QRIS Pembayaran Kopi Pablo" 
                     class="w-52 h-52 mx-auto object-contain">
            </div>

           
        </div>

        <!-- RINCIAN OUTLET -->
        <div class="bg-[#F6F0E1]/50 rounded-2xl p-3.5 text-left border border-[#D8D6CF] space-y-1">
            <span class="text-[10px] font-bold text-[#6E756D] uppercase block">Lokasi Pengambilan Pesanan:</span>
            <p class="text-xs font-extrabold text-[#24352A] leading-relaxed">
                {{ $order->table_number }}
            </p>
        </div>

        <!-- INSTRUKSI CARA BAYAR -->
        <div class="text-left space-y-2 border-t border-[#D8D6CF] pt-4">
            <p class="text-xs font-extrabold text-[#24352A]">Cara Pembayaran:</p>
            <ol class="text-xs text-[#6E756D] space-y-1.5 list-decimal list-inside">
                <li>Buka aplikasi e-Wallet atau Mobile Banking Anda.</li>
                <li>Pilih menu <strong>Scan QRIS</strong> dan arahkan kamera ke QR Code di atas.</li>
                <li>Periksa nama penerima <strong>TOKO KOPI PABLO</strong> dan pastikan nominal tepat.</li>
                <li>Tekan tombol <strong>"Konfirmasi Pembayaran"</strong> di bawah setelah selesai bayar.</li>
            </ol>
        </div>

        <!-- TOMBOL KONFIRMASI PEMBAYARAN -->
        <form action="{{ route('checkout.payment.confirm', ['orderNumber' => $order->order_number]) }}" method="POST">
            @csrf
            <button type="submit"
                    class="w-full bg-[#34543D] hover:bg-[#24352A] text-white font-extrabold text-sm py-4 rounded-2xl shadow-lg transition transform active:scale-98 flex items-center justify-center gap-2">
                <span>Konfirmasi Pembayaran & Terbitkan Tiket QR</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>
        </form>

    </div>

</div>

<!-- SCRIPT COUNTDOWN TIMER -->
<script>
    let duration = 5 * 60; // 5 menit
    const display = document.getElementById('countdownTimer');
    const timer = setInterval(() => {
        let minutes = parseInt(duration / 60, 10);
        let seconds = parseInt(duration % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--duration < 0) {
            clearInterval(timer);
            display.textContent = "KEDALUWARSA";
        }
    }, 1000);
</script>
@endsection
