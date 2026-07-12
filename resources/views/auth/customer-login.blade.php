@extends('layouts.app')

@section('title', 'Masuk Akun | Kopi Pablo Self-Service Order')

@section('content')
<div class="relative overflow-hidden rounded-[32px] min-h-[640px] flex items-center justify-center p-4 sm:p-8 shadow-2xl bg-cover bg-center border border-[#D8D6CF]"
     style="background-image: url('{{ asset('img/hero/hero-2.jpg') }}');">
    <!-- Dark Forest Green Glass Backdrop Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#1b2b20]/85 via-[#24352A]/75 to-[#24352A]/85 backdrop-blur-[2px]"></div>

    <div class="relative z-10 max-w-md w-full bg-white/95 backdrop-blur-md rounded-[28px] p-7 sm:p-8 shadow-2xl border border-white/40 space-y-6 my-4">
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-[#34543D] text-white flex items-center justify-center mx-auto shadow-md">
                <i class="fa-solid fa-right-to-bracket text-2xl"></i>
            </div>
            <h1 class="text-xl font-extrabold text-[#24352A]">Masuk Akun Pelanggan</h1>
            <p class="text-xs text-[#6E756D]">Masuk untuk melanjutkan pemesanan & checkout QR</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold p-3.5 rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1.5">Alamat Email</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="email@contoh.com"
                       class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-4 py-2.5 text-xs text-[#24352A] font-medium focus:outline-none focus:border-[#34543D]">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1.5">Kata Sandi</label>
                <input type="password" name="password" required placeholder="Masukkan kata sandi Anda"
                       class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-4 py-2.5 text-xs text-[#24352A] font-medium focus:outline-none focus:border-[#34543D]">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-xs text-[#6E756D]">
                    <input type="checkbox" name="remember" class="rounded border-[#D8D6CF]">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-[#34543D] hover:bg-[#24352A] text-white font-extrabold text-xs py-3.5 rounded-xl shadow-lg transition">
                Masuk & Mulai Pesan
            </button>
        </form>

        <div class="text-center pt-3 border-t border-[#D8D6CF]/60 space-y-2">
            <p class="text-xs text-[#6E756D]">
                Belum terdaftar? 
                <a href="{{ route('register') }}" class="font-bold text-[#34543D] hover:underline">Daftar Akun Pelanggan Baru &rarr;</a>
            </p>
        </div>

        <!-- Catatan Demo Skripsi -->
        <div class="bg-[#F6F0E1] p-3 rounded-xl text-center text-[11px] text-[#58725A]">
            <strong>Akun Contoh Penguji:</strong><br>
            Email: <code>customer@kopipablo.id</code> | Sandi: <code>password</code>
        </div>
    </div>
</div>
@endsection
