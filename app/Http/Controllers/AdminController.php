<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Tampilkan halaman login untuk Admin.
     */
    public function login()
    {
        return view('admin.login');
    }

    /**
     * Proses otentikasi Admin.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di Panel Admin Kopi Pablo!');
        }

        // Fallback demo untuk sidang skripsi bila login manual email/password
        if (($request->email === 'admin@kopipablo.id' || $request->email === 'admin@kopipablo.com') && $request->password === 'password') {
            $user = User::where('email', $request->email)->first() ?? User::where('role', 'admin')->first();
            if ($user) {
                Auth::login($user);
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Admin Kopi Pablo!');
            }
        }

        return back()->withErrors([
            'email' => 'Kredensial email atau password tidak sesuai.',
        ]);
    }

    /**
     * Logout Admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah keluar dari panel Admin.');
    }

    /**
     * Dashboard Admin Modern.
     * Menampilkan: Jumlah Customer, Jumlah Produk, Jumlah Pesanan, Pendapatan, Produk Terlaris, Statistik.
     */
    public function dashboard()
    {
        $totalCustomers = Order::distinct('customer_phone')->count('customer_phone');
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'dibatalkan')->sum('total_amount');

        // Produk terlaris berdasarkan total kuantitas terjual
        $bestSellingProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Pesanan terbaru masuk
        $recentOrders = Order::with('items')->orderBy('created_at', 'desc')->take(8)->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'bestSellingProducts',
            'recentOrders'
        ));
    }

    /**
     * Halaman Kelola Produk & Kategori.
     */
    public function products()
    {
        $products = Product::with('category')->orderBy('category_id')->orderBy('name')->get();
        $categories = Category::orderBy('order')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Tambah Produk Baru Kopi Pablo.
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:20480',
        ], [
            'image.max' => 'Ukuran file foto tidak boleh lebih dari 20 MB.',
            'image.image' => 'File yang diupload harus berupa gambar.',
        ]);

        $imageUrl = 'https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=800&q=80';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/products'), $filename);
            $imageUrl = asset('img/products/' . $filename);
        }

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . rand(100, 999),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imageUrl,
            'is_featured' => $request->has('is_favorite'),
            'is_available' => true,
        ]);

        return redirect()->back()->with('success', 'Produk baru Kopi Pablo berhasil ditambahkan!');
    }

    /**
     * Ubah status ketersediaan atau detail produk.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:20480',
        ], [
            'image.max' => 'Ukuran file foto tidak boleh lebih dari 20 MB.',
            'image.image' => 'File yang diupload harus berupa gambar.',
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'is_featured' => $request->has('is_favorite'),
            'is_available' => $request->has('is_available'),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/products'), $filename);
            $data['image'] = asset('img/products/' . $filename);
        }

        $product->update($data);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus Produk.
     */
    public function destroyProduct(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari menu.');
    }

    /**
     * Halaman Kelola Pesanan & Ubah Status Pesanan secara Real-time.
     */
    public function orders(Request $request)
    {
        $query = Order::with('items')->orderBy('created_at', 'desc');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Ubah Status Pesanan (Menunggu -> Diproses -> Siap Diambil -> Selesai / Dibatalkan).
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,siap_diambil,selesai,dibatalkan',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diperbarui ke: ' . strtoupper($request->status));
    }

    /**
     * Halaman Laporan Penjualan Kopi Pablo.
     */
    public function reports()
    {
        $completedOrders = Order::with('items')->where('status', 'selesai')->orderBy('created_at', 'desc')->get();
        $totalRevenue = $completedOrders->sum('total_amount');
        $totalOrdersCount = $completedOrders->count();

        return view('admin.reports.index', compact('completedOrders', 'totalRevenue', 'totalOrdersCount'));
    }

    /**
     * Tampilkan halaman pendaftaran (Register) akun baru.
     */
    public function registerForm()
    {
        return view('auth.register');
    }

    /**
     * Proses pendaftaran akun baru ke tabel users.
     */
    public function processRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:customer,admin',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau langsung masuk (Login).',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Akun Admin baru berhasil didaftarkan & Anda langsung masuk!');
        }

        return redirect()->route('landing')->with('success', 'Akun berhasil dibuat! Selamat datang di Kopi Pablo.');
    }
}
