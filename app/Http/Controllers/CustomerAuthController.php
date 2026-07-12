<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    /**
     * Tampilkan halaman Login Customer.
     */
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('menu');
        }
        return view('auth.customer-login');
    }

    /**
     * Proses Login Customer.
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->forget(['cart', 'my_orders']);
            $request->session()->regenerate();

            // Bila yang masuk adalah admin, arahkan ke dashboard admin
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di Panel Admin Kopi Pablo.');
            }

            // Customer diarahkan ke halaman produk (menu)
            return redirect()->intended(route('menu'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '! Silakan pilih hidangan favoritmu.');
        }

        return back()->withErrors([
            'email' => 'Alamat email atau kata sandi tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan halaman Pendaftaran (Register) Customer.
     */
    public function registerForm()
    {
        if (Auth::check()) {
            return redirect()->route('menu');
        }
        return view('auth.customer-register');
    }

    /**
     * Proses Pendaftaran Customer ke tabel users.
     */
    public function processRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan langsung masuk (Login).',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        // Setelah daftar & login, customer langsung diarahkan ke halaman produk / menu
        return redirect()->route('menu')->with('success', 'Akun berhasil didaftarkan! Selamat datang di Kopi Pablo, silakan pilih menu Anda.');
    }

    /**
     * Logout Customer.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Anda telah keluar dari akun Kopi Pablo.');
    }
}
