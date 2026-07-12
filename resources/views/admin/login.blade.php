<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Kopi Pablo</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
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
            <div class="w-20 h-20 mx-auto">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Kopi Pablo" class="w-20 h-20 rounded-full object-contain shadow-md mx-auto bg-[#F6F0E1] border-2 border-[#34543D]/20">
            </div>
            <h1 class="text-xl font-extrabold text-[#24352A]">Login Back-Office Admin</h1>
            <p class="text-xs text-[#6E756D]">Sistem Manajemen Kopi Pablo (UCD & PWA)</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold p-3 rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.authenticate') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1.5">Email Admin</label>
                <input type="email" name="email" required value="admin@kopipablo.id"
                       class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-4 py-2.5 text-xs text-[#24352A] font-medium focus:outline-none focus:border-[#34543D]">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1.5">Kata Sandi</label>
                <input type="password" name="password" required value="password"
                       class="w-full bg-[#F6F0E1]/50 border border-[#D8D6CF] rounded-xl px-4 py-2.5 text-xs text-[#24352A] font-medium focus:outline-none focus:border-[#34543D]">
            </div>

            <button type="submit" class="w-full bg-[#34543D] hover:bg-[#24352A] text-white font-extrabold text-xs py-3.5 rounded-xl shadow transition">
                Masuk ke Dashboard Admin
            </button>
        </form>

        <div class="text-center pt-1">
            <a href="{{ route('register') }}" class="text-xs font-bold text-[#34543D] hover:underline">
                Belum punya akun? Daftar Akun Baru di sini &rarr;
            </a>
        </div>

        <div class="bg-[#F6F0E1] p-3.5 rounded-xl text-center text-[11px] text-[#58725A]">
            <strong>Catatan Penguji / Mahasiswa Skripsi:</strong><br>
            Email: <code>admin@kopipablo.id</code> | Password: <code>password</code>
        </div>
    </div>

</body>
</html>
