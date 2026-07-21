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

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 p-4 rounded-2xl text-xs font-bold flex items-center gap-2.5 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('warning_reschedule'))
        <div class="bg-amber-50 border border-amber-300 text-amber-900 p-4 rounded-2xl text-xs font-bold flex items-start gap-2.5 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-base mt-0.5"></i>
            <span>{{ session('warning_reschedule') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-300 text-rose-800 p-4 rounded-2xl text-xs font-bold flex items-center gap-2.5 shadow-sm">
            <i class="fa-solid fa-circle-xmark text-rose-600 text-base"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

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

        <!-- TOMBOL DOWNLOAD TIKET QR -->
        <div class="pt-3">
            <button type="button" onclick="downloadTicketQR()" class="px-5 py-3 rounded-2xl bg-[#F6F0E1] hover:bg-white text-[#24352A] font-extrabold text-xs shadow-lg transition active:scale-95 inline-flex items-center gap-2 border border-[#D8D6CF]/60">
                <i class="fa-solid fa-download text-sm text-[#34543D]"></i>
                <span>Download Tiket QR (PNG)</span>
            </button>
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

    <!-- JADWAL PENGAMBILAN & RESCHEDULE CARD (UCD FEATURE) -->
    <div class="bg-white rounded-[24px] p-6 border-2 border-[#34543D] card-shadow flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start space-x-3.5">
            <div class="w-11 h-11 rounded-2xl bg-[#F6F0E1] text-[#34543D] flex items-center justify-center font-bold shrink-0 mt-0.5">
                <i class="fa-solid fa-clock text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] font-extrabold text-[#58725A] uppercase tracking-wider block">WAKTU PENGAMBILAN PESANAN</span>
                <h3 class="text-base font-extrabold text-[#24352A] mt-0.5">{{ $order->pickup_time ?: 'Secepatnya (Ambil Sekarang)' }}</h3>
                @if($order->reschedule_status === 'requested')
                    <span class="inline-block bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full mt-1.5">
                        <i class="fa-solid fa-hourglass-half mr-1"></i> Menunggu penyesuaian dari Barista
                    </span>
                @elseif($order->reschedule_status === 'acknowledged')
                    <span class="inline-block bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full mt-1.5">
                        <i class="fa-solid fa-check mr-1"></i> Jadwal diperbarui
                    </span>
                @elseif($order->reschedule_status === 'late_notice')
                    <span class="inline-block bg-blue-100 text-blue-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full mt-1.5">
                        <i class="fa-solid fa-bell mr-1"></i> Info keterlambatan disampaikan
                    </span>
                @endif
                @if($order->reschedule_notes)
                    <p class="text-xs text-[#6E756D] italic mt-1">Catatan: "{{ $order->reschedule_notes }}"</p>
                @endif
            </div>
        </div>

        @if(!in_array(strtolower($order->status), ['selesai', 'dibatalkan']))
            <button type="button" onclick="openRescheduleModal()" class="px-4 py-3 rounded-xl bg-[#34543D] hover:bg-[#24352A] text-white text-xs font-extrabold shadow-md transition flex items-center justify-center gap-2 shrink-0">
                <i class="fa-solid fa-calendar-alt"></i>
                <span>Ubah Waktu Pengambilan</span>
            </button>
        @endif
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

<!-- MODAL RESCHEDULE PENGAMBILAN (UCD FEATURE) -->
<div id="rescheduleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 shadow-2xl border border-[#D8D6CF] space-y-5">
        <div class="flex items-center justify-between border-b border-[#D8D6CF]/60 pb-3.5">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-xl bg-[#F6F0E1] text-[#34543D] flex items-center justify-center font-bold">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-[#24352A]">Ubah Waktu Pengambilan</h3>
                    <p class="text-[10px] text-[#6E756D]">Sesuaikan jadwal kehadiran Anda di outlet</p>
                </div>
            </div>
            <button type="button" onclick="closeRescheduleModal()" class="w-8 h-8 rounded-lg bg-[#F6F0E1] text-[#6E756D] hover:text-[#24352A] flex items-center justify-center text-xs font-bold transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        @if($order->status === 'diproses')
            <div class="bg-amber-50 border border-amber-200 text-amber-800 p-3.5 rounded-xl text-xs flex items-start space-x-2.5">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 shrink-0"></i>
                <div>
                    <span class="font-extrabold block">Barista Sedang Menyiapkan Pesanan!</span>
                    <span class="text-[11px] leading-relaxed">Mengubah waktu terlalu lama dapat memengaruhi suhu/kesegaran minuman. Permintaan ini akan dikirim ke counter untuk disesuaikan.</span>
                </div>
            </div>
        @elseif($order->status === 'siap_diambil')
            <div class="bg-blue-50 border border-blue-200 text-blue-800 p-3.5 rounded-xl text-xs flex items-start space-x-2.5">
                <i class="fa-solid fa-circle-info text-blue-600 mt-0.5 shrink-0"></i>
                <div>
                    <span class="font-extrabold block">Pesanan Sudah Siap di Counter!</span>
                    <span class="text-[11px] leading-relaxed">Anda dapat mengirimkan info perkiraan kedatangan (keterlambatan) agar pesanan disimpan dengan aman di kulkas/counter.</span>
                </div>
            </div>
        @endif

        <form action="{{ route('order.reschedule', ['orderNumber' => $order->order_number]) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="reschedule_time" class="block text-xs font-extrabold text-[#24352A] mb-1.5">
                    Pilih Waktu / Jam Pengambilan Baru <span class="text-rose-500">*</span>
                </label>
                <select name="reschedule_time" id="reschedule_time" required class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl px-3.5 py-2.5 text-xs text-[#24352A] font-bold focus:outline-none focus:border-[#34543D]">
                    <option value="15 Menit Lagi (+15m)">🕒 +15 Menit dari sekarang</option>
                    <option value="30 Menit Lagi (+30m)">🕒 +30 Menit dari sekarang</option>
                    <option value="1 Jam Lagi (+1 Jam)">🕒 +1 Jam dari sekarang</option>
                    <option value="Pukul 12:00 WIB">Pukul 12:00 WIB</option>
                    <option value="Pukul 14:00 WIB">Pukul 14:00 WIB</option>
                    <option value="Pukul 16:00 WIB">Pukul 16:00 WIB</option>
                    <option value="Pukul 17:30 WIB (Pulang Kerja/Kuliah)">Pukul 17:30 WIB (Pulang Kerja/Kuliah)</option>
                    <option value="Pukul 19:00 WIB (Malam)">Pukul 19:00 WIB (Malam)</option>
                </select>
            </div>

            <div>
                <label for="reschedule_notes" class="block text-xs font-extrabold text-[#24352A] mb-1.5">
                    Catatan / Alasan (Opsional)
                </label>
                <input type="text" name="reschedule_notes" id="reschedule_notes" placeholder="Contoh: Terjebak macet / hujan deras di jalan..." class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl px-3.5 py-2.5 text-xs text-[#24352A] focus:outline-none focus:border-[#34543D]">
            </div>

            <div class="flex items-center justify-end space-x-2.5 pt-2 border-t border-[#D8D6CF]/60">
                <button type="button" onclick="closeRescheduleModal()" class="px-4 py-2.5 rounded-xl bg-[#F6F0E1] text-[#6E756D] hover:text-[#24352A] text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#34543D] hover:bg-[#24352A] text-white text-xs font-extrabold shadow transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Konfirmasi Perubahan</span>
                </button>
            </div>
        </form>
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

    function openRescheduleModal() {
        const modal = document.getElementById('rescheduleModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRescheduleModal() {
        const modal = document.getElementById('rescheduleModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function downloadTicketQR() {
        const qrEl = document.querySelector('#qrcodeBox canvas') || document.querySelector('#qrcodeBox img');
        if (!qrEl) {
            alert("QR Code sedang dimuat, silakan coba beberapa detik lagi.");
            return;
        }

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const width = 450;
        const height = 620;
        canvas.width = width;
        canvas.height = height;

        // Background utama dark green
        ctx.fillStyle = '#24352A';
        ctx.fillRect(0, 0, width, height);

        // Header strip
        ctx.fillStyle = '#34543D';
        ctx.fillRect(0, 0, width, 110);

        // Judul Header
        ctx.fillStyle = '#F6F0E1';
        ctx.font = 'bold 22px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('KOPI PABLO', width / 2, 45);

        ctx.fillStyle = '#D8D6CF';
        ctx.font = 'bold 13px sans-serif';
        ctx.fillText('TIKET PENGAMBILAN PESANAN (PWA)', width / 2, 72);

        // Garis pemisah header
        ctx.strokeStyle = '#58725A';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(30, 110);
        ctx.lineTo(width - 30, 110);
        ctx.stroke();

        // Kotak putih QR Code
        const qrBoxSize = 250;
        const qrBoxX = (width - qrBoxSize) / 2;
        const qrBoxY = 140;
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(qrBoxX - 15, qrBoxY - 15, qrBoxSize + 30, qrBoxSize + 30);

        // Gambar QR Code dari qrcodeBox
        ctx.drawImage(qrEl, qrBoxX, qrBoxY, qrBoxSize, qrBoxSize);

        // Order Number Box di Bawah QR
        ctx.fillStyle = '#F6F0E1';
        ctx.font = 'bold 24px monospace';
        ctx.fillText('{{ $order->order_number }}', width / 2, 455);

        // Nama Pemesan & Waktu Pengambilan
        ctx.fillStyle = '#FFFFFF';
        ctx.font = 'bold 15px sans-serif';
        ctx.fillText('ATAS NAMA: {{ strtoupper($order->customer_name) }}', width / 2, 495);

        ctx.fillStyle = '#D8D6CF';
        ctx.font = '13px sans-serif';
        ctx.fillText('JADWAL: {{ strtoupper($order->pickup_time ?: "SECEPATNYA") }}', width / 2, 525);

        // Outlet
        ctx.fillStyle = '#A8BBAA';
        ctx.font = 'italic 11px sans-serif';
        ctx.fillText('{{ Str::limit($order->table_number, 55) }}', width / 2, 560);

        // Footer info
        ctx.fillStyle = '#7A9478';
        ctx.font = '11px sans-serif';
        ctx.fillText('Tunjukkan gambar tiket ini kepada Barista saat pengambilan', width / 2, 595);

        // Trigger download PNG
        const link = document.createElement('a');
        link.download = 'Tiket_KopiPablo_{{ $order->order_number }}.png';
        link.href = canvas.toDataURL('image/png');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endpush
