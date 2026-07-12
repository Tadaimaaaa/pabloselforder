<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Halaman Utama / Landing Page Kopi Pablo.
     * Menampilkan Hero Banner, Promo, Kategori Menu, Produk Favorit, Info & Jam Operasional.
     */
    public function landing()
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $favorites = Product::where('is_available', true)->where('is_featured', true)->with('category')->get();
        $storeSettings = [
            'name' => Setting::get('store_name', 'Kopi Pablo'),
            'tagline' => Setting::get('store_tagline', 'Crafted Coffee, Self-Service Ordering Experience'),
            'address' => Setting::get('store_address', '2 Outlet Resmi di Kota Padang'),
            'hours' => Setting::get('store_hours', 'Padang Baru: 06:30 - 21:00 WIB | Pondok: Buka 24 Jam'),
            'phone' => Setting::get('store_phone', '0811-8899-7766'),
            'promo' => Setting::get('promo_banner', 'Nongkrong hemat & pesan mandiri di Kopi Pablo Padang'),
        ];

        return view('customer.landing', compact('categories', 'favorites', 'storeSettings'));
    }

    /**
     * Halaman Menu (Adaptasi konsep ESB Order dengan identitas Kopi Pablo).
     * Bagian Atas: Search Bar, Filter Kategori Horizontal, Banner Promo.
     * Bagian Bawah: Daftar Menu dalam bentuk Card berdesain mobile app modern.
     */
    public function menu(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        
        $query = Product::where('is_available', true)->with('category');

        // Filter berdasarkan kategori yang dipilih
        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter berdasarkan pencarian nama atau deskripsi produk
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->get();
        $activeCategory = $request->get('category', 'all');
        $promoBanner = Setting::get('promo_banner', 'DISKON 20% untuk semua varian Signature Kopi Pablo setiap hari Senin - Jumat pukul 08:00 - 11:00 WIB!');

        // Ambil isi keranjang saat ini dari Session
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        $cartTotal = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }

        return view('customer.menu', compact(
            'categories',
            'products',
            'activeCategory',
            'promoBanner',
            'cart',
            'cartCount',
            'cartTotal'
        ));
    }

    /**
     * Halaman Keranjang Belanja (/cart).
     */
    public function cart()
    {
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        $cartTotal = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }

        return view('customer.cart', compact('cart', 'cartCount', 'cartTotal'));
    }

    /**
     * Tambah Produk ke Keranjang (Sticky Bottom Cart session-based).
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $cartKey = $product->id . '_' . md5((string) $request->notes);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            $cartCount = array_sum(array_column($cart, 'quantity'));
            $cartTotal = 0;
            foreach ($cart as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil ditambahkan ke keranjang Kopi Pablo!',
                'cartCount' => $cartCount,
                'cartTotal' => 'Rp ' . number_format($cartTotal, 0, ',', '.'),
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Perbarui jumlah item di keranjang atau hapus item.
     */
    public function updateCart(Request $request)
    {
        $cartKey = $request->cart_key ?? $request->product_id;
        $quantity = (int) $request->quantity;
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            if ($quantity <= 0) {
                unset($cart[$cartKey]);
            } else {
                $cart[$cartKey]['quantity'] = $quantity;
            }
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    /**
     * Hapus seluruh item di keranjang.
     */
    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang dikosongkan.');
    }

    /**
     * Halaman Checkout Sederhana.
     * Field: Nama Customer, Nomor HP, Nomor Meja, Catatan, Ringkasan Pesanan, Total Pembayaran.
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Keranjang Anda masih kosong. Silakan pilih menu Kopi Pablo terlebih dahulu.');
        }

        $cartTotal = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }

        return view('customer.checkout', compact('cart', 'cartTotal'));
    }

    /**
     * Proses pesanan dari halaman Checkout dan buat Order baru.
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'table_number' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Keranjang Anda kosong!');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $orderNumber = 'PABLO-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // Simpan Transaksi Pesanan ke Database
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'table_number' => $request->table_number,
            'notes' => $request->notes,
            'status' => 'menunggu', // Status awal: Menunggu Konfirmasi Kasir
            'total_amount' => $subtotal,
            'payment_method' => 'qris',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        // Kosongkan keranjang di session
        session()->forget('cart');

        // Simpan ke riwayat pesanan sesi customer
        $myOrders = session()->get('my_orders', []);
        array_unshift($myOrders, $orderNumber);
        session()->put('my_orders', $myOrders);

        return redirect()->route('checkout.payment', ['orderNumber' => $orderNumber])
            ->with('success', 'Pesanan dibuat. Silakan lakukan pembayaran QRIS di bawah ini.');
    }

    /**
     * Halaman Pembayaran QRIS / QR Code Pembayaran (Langkah 6).
     */
    public function paymentQr($orderNumber)
    {
        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('customer.payment', compact('order'));
    }

    /**
     * Konfirmasi Sukses Pembayaran QRIS dan tampilkan Tiket Pengambilan Pesanan (Langkah 7).
     */
    public function confirmPayment($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        // Tandai pesanan diproses
        $order->update(['status' => 'diproses']);

        return redirect()->route('order.status', ['orderNumber' => $orderNumber])
            ->with('success', 'Pembayaran QRIS Berhasil! Tiket QR Pengambilan Pesanan telah diterbitkan.');
    }

    /**
     * Halaman Status Pesanan dengan tampilan Progress Step Bar (Menunggu -> Diproses -> Siap Diambil -> Selesai).
     */
    public function orderStatus($orderNumber)
    {
        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('customer.order-status', compact('order'));
    }

    /**
     * Endpoint API ringan untuk mengecek status pesanan secara real-time (polling).
     */
    public function checkOrderStatus($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();
        if (!$order) {
            return response()->json(['status' => 'unknown']);
        }

        return response()->json([
            'status' => $order->status,
            'status_label' => $order->status_label,
        ]);
    }

    /**
     * Halaman Riwayat Pesanan Customer.
     */
    public function orders()
    {
        // Strictly hanya ambil pesanan milik akun customer yang sedang login
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.orders', compact('orders'));
    }

    /**
     * Halaman Verifikasi Tiket Barista / Hasil Scan QR Code Pesanan.
     * Dapat diakses langsung saat QR di-scan kamera untuk melihat atas nama siapa dan produk apa saja yang dipesan.
     */
    public function verifyTicket($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        return view('customer.ticket-verify', compact('order'));
    }
}
