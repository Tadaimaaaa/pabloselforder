<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Kopi Pablo</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F6F0E1; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-[24px] p-8 shadow-xl border border-[#D8D6CF] space-y-6">
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-[#34543D] text-white flex items-center justify-center mx-auto shadow-md">
                <i class="fa-solid fa-user-plus text-2xl"></i>
            </div>
            <h1 class="text-xl font-extrabold text-[#24352A]">Daftar Akun Baru</h1>
            <p class="text-xs text-[#6E756D]">Sistem PWA Kopi Pablo (Tabel `users`)</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold p-3.5 rounded-xl space-y-1">
                @foreach($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.process') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Budi Santoso"
                       class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-4 py-2.5 text-xs text-[#24352A] font-medium focus:outline-none focus:border-[#34543D]">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1.5">Alamat Email</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="email@contoh.com"
                       class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-4 py-2.5 text-xs text-[#24352A] font-medium focus:outline-none focus:border-[#34543D]">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-[#24352A] mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                           class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-4 py-2.5 text-xs text-[#24352A] font-medium focus:outline-none focus:border-[#34543D]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#24352A] mb-1.5">Konfirmasi Sandi</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi sandi"
                           class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-4 py-2.5 text-xs text-[#24352A] font-medium focus:outline-none focus:border-[#34543D]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1.5">Peran / Hak Akses (Role)</label>
                <select name="role" required
                        class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-4 py-2.5 text-xs text-[#24352A] font-bold focus:outline-none focus:border-[#34543D]">
                    <option value="customer">Customer / Pelanggan</option>
                    <option value="admin">Administrator (Back-Office)</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-[#34543D] hover:bg-[#24352A] text-white font-extrabold text-xs py-3.5 rounded-xl shadow transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>Daftarkan Akun Sekarang</span>
            </button>
        </form>

        <div class="text-center pt-2 border-t border-[#D8D6CF]/70 space-y-2">
            <p class="text-xs text-[#6E756D]">
                Sudah memiliki akun? 
                <a href="{{ route('admin.login') }}" class="font-bold text-[#34543D] hover:underline">Masuk (Login)</a>
            </p>
            <p class="text-xs text-[#6E756D]">
                Atau langsung pesan menu tanpa login? 
                <a href="{{ route('landing') }}" class="font-bold text-[#58725A] hover:underline">Ke Beranda PWA</a>
            </p>
        </div>
    </div>

</body>
</html>
