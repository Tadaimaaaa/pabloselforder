@extends('layouts.app')

@section('title', 'Menu | Kopi Pablo Self-Service Order')

@section('content')
<div class="space-y-5">

    <!-- SEARCH BAR & HORIZONTAL CATEGORY FILTER (Konsep ESB Order) -->
    <div class="sticky top-16 z-40 bg-[#F6F0E1]/95 backdrop-blur-md pt-2 pb-3 -mx-4 px-4 border-b border-[#D8D6CF]/70 space-y-3">
        <!-- Search Form -->
        <form action="{{ route('menu') }}" method="GET" class="relative">
            @if($activeCategory && $activeCategory !== 'all')
                <input type="hidden" name="category" value="{{ $activeCategory }}">
            @endif
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#6E756D] text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari kopi, espresso, atau pastry favoritmu..."
                   class="w-full bg-white border border-[#D8D6CF] rounded-2xl pl-11 pr-10 py-2.5 text-xs md:text-sm text-[#24352A] focus:outline-none focus:border-[#34543D] transition shadow-sm">
            @if(request('search'))
                <a href="{{ route('menu', ['category' => $activeCategory]) }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-[#6E756D] hover:text-[#24352A]">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>

        <!-- Horizontal Scrollable Category Filter Chips -->
        <div class="flex items-center space-x-2 overflow-x-auto no-scrollbar py-0.5">
            <a href="{{ route('menu', ['search' => request('search')]) }}" 
               class="shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition {{ $activeCategory === 'all' ? 'bg-[#34543D] text-white shadow-sm' : 'bg-white text-[#24352A] border border-[#D8D6CF] hover:bg-[#D8D6CF]/30' }}">
                Semua Menu
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('menu', ['category' => $cat->slug, 'search' => request('search')]) }}" 
                   class="shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $activeCategory === $cat->slug ? 'bg-[#34543D] text-white shadow-sm' : 'bg-white text-[#24352A] border border-[#D8D6CF] hover:bg-[#D8D6CF]/30' }}">
                    <i class="fa-solid {{ $cat->icon ?: 'fa-coffee' }} text-[11px]"></i>
                    <span>{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- DAFTAR MENU (MOBILE CARD LAYOUT) -->
    <div>
        @if($products->isEmpty())
            <div class="bg-white rounded-[20px] p-8 text-center border border-[#D8D6CF] my-6">
                <div class="w-14 h-14 rounded-full bg-[#F6F0E1] text-[#34543D] flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-mug-hot text-xl"></i>
                </div>
                <h3 class="font-bold text-base text-[#24352A]">Menu Tidak Ditemukan</h3>
                <p class="text-xs text-[#6E756D] mt-1">Coba cari dengan kata kunci lain atau pilih kategori Semua Menu.</p>
                <a href="{{ route('menu') }}" class="mt-4 inline-block bg-[#34543D] text-white text-xs font-bold px-4 py-2 rounded-xl">
                    Tampilkan Semua Menu
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($products as $product)
                    <div class="bg-white rounded-[24px] p-4 border border-[#D8D6CF]/80 shadow-xs hover:-translate-y-1 hover:shadow-xl hover:border-[#34543D] transition-all duration-300 flex sm:flex-col gap-4 items-center sm:items-stretch group overflow-hidden">
                        <!-- Foto Produk -->
                        <div class="relative shrink-0 overflow-hidden rounded-[18px]">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" 
                                 class="w-24 h-24 sm:w-full sm:h-48 object-cover bg-[#F6F0E1] group-hover:scale-105 transition-transform duration-500 ease-out">
                            @if($product->is_favorite)
                                <span class="absolute top-2.5 left-2.5 bg-[#34543D]/95 backdrop-blur-md text-white text-[9px] font-black tracking-wider uppercase px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
                                    <i class="fa-solid fa-star text-amber-300"></i> Favorit
                                </span>
                            @endif
                        </div>

                        <!-- Info Produk -->
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10px] font-extrabold text-[#58725A] uppercase tracking-wider truncate">
                                        {{ $product->category->name }}
                                    </span>
                                    <!-- Status -->
                                    @if($product->is_available)
                                        <span class="text-[9px] font-black text-emerald-800 bg-emerald-100/80 px-2 py-0.5 rounded-full uppercase tracking-wider">
                                            Ready
                                        </span>
                                    @else
                                        <span class="text-[9px] font-black text-rose-800 bg-rose-100/80 px-2 py-0.5 rounded-full uppercase tracking-wider">
                                            Habis
                                        </span>
                                    @endif
                                </div>

                                <h3 class="font-extrabold text-base text-[#24352A] truncate mt-1 group-hover:text-[#34543D] transition-colors">{{ $product->name }}</h3>
                                <p class="text-xs text-[#6E756D] line-clamp-2 mt-1 leading-relaxed">{{ $product->description }}</p>
                            </div>

                            <div class="flex items-center justify-between mt-4 pt-3 border-t border-[#D8D6CF]/60">
                                <span class="font-black text-base text-[#34543D]">{{ $product->formatted_price }}</span>

                                <!-- Tombol Tambah (Buka Modal Detail Produk) -->
                                @if($product->is_available)
                                    <button type="button" 
                                            onclick="openProductModal({{ json_encode($product) }}, '{{ $product->category->name }}', '{{ $product->formatted_price }}')"
                                            class="bg-[#34543D] hover:bg-[#24352A] active:scale-95 text-white text-xs font-extrabold px-4 py-2.5 rounded-xl flex items-center gap-1.5 shadow-md hover:shadow-lg transition">
                                        <i class="fa-solid fa-plus text-[10px]"></i>
                                        <span>Pesan</span>
                                    </button>
                                @else
                                    <button disabled class="bg-gray-100 text-gray-400 text-xs font-bold px-3.5 py-2 rounded-xl cursor-not-allowed">
                                        Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<!-- MODAL DETAIL PRODUK (POP-UP SELF-SERVICE) -->
<div id="productModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-end md:items-center justify-center p-0 md:p-4 transition-opacity">
    <div class="bg-white rounded-t-[28px] md:rounded-[24px] max-w-md w-full p-6 space-y-5 animate-slide-up shadow-2xl border border-[#D8D6CF]">
        <div class="flex items-center justify-between">
            <span id="modalCategory" class="text-xs font-bold text-[#58725A] uppercase tracking-wider">KATEGORI</span>
            <button type="button" onclick="closeProductModal()" class="w-8 h-8 rounded-full bg-[#F6F0E1] text-[#24352A] hover:bg-gray-200 flex items-center justify-center font-bold">
                &times;
            </button>
        </div>

        <!-- Foto & Deskripsi -->
        <div class="text-center space-y-3">
            <img id="modalImage" src="" alt="" class="w-44 h-44 mx-auto rounded-2xl object-cover shadow-md bg-[#F6F0E1]">
            <h3 id="modalName" class="text-lg font-extrabold text-[#24352A]"></h3>
            <p id="modalPrice" class="text-base font-extrabold text-[#34543D]"></p>
            <p id="modalDescription" class="text-xs text-[#6E756D] leading-relaxed"></p>
        </div>

        <!-- Form Tambah ke Keranjang -->
        <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="product_id" id="modalProductId" value="">

            <!-- Pengatur Jumlah -->
            <div class="flex items-center justify-between bg-[#F6F0E1] px-4 py-3 rounded-2xl">
                <span class="text-xs font-bold text-[#24352A]">Jumlah Item</span>
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="decrementQty()" class="w-8 h-8 rounded-xl bg-white text-[#24352A] font-bold shadow-sm hover:bg-gray-100 flex items-center justify-center">
                        <i class="fa-solid fa-minus text-xs"></i>
                    </button>
                    <input type="number" name="quantity" id="modalQty" value="1" min="1" readonly class="w-8 text-center bg-transparent font-bold text-sm text-[#24352A] focus:outline-none">
                    <button type="button" onclick="incrementQty()" class="w-8 h-8 rounded-xl bg-[#34543D] text-white font-bold shadow-sm hover:bg-[#24352A] flex items-center justify-center">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Catatan Pesanan -->
            <div>
                <label for="modalNotes" class="block text-xs font-bold text-[#24352A] mb-1.5">
                    Catatan Khusus (Opsional)
                </label>
                <input type="text" name="notes" id="modalNotes" 
                       placeholder="Contoh: Less sugar, extra ice, kopi panas..."
                       class="w-full bg-white border border-[#D8D6CF] rounded-xl px-3.5 py-2.5 text-xs text-[#24352A] focus:outline-none focus:border-[#34543D]">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-[#34543D] hover:bg-[#24352A] text-white font-extrabold text-sm py-3.5 rounded-2xl shadow-lg transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-cart-plus"></i>
                <span>Tambah ke Keranjang</span>
            </button>
        </form>
    </div>
</div>

<!-- STICKY BOTTOM CART (Keranjang Melayang di Bagian Bawah Layar) -->
@if(!empty($cart) && $cartCount > 0)
<div class="fixed bottom-4 left-0 right-0 z-50 px-4 max-w-2xl mx-auto">
    <div class="bg-[#34543D] text-white rounded-[24px] p-3.5 shadow-2xl border border-white/20 flex items-center justify-between">
        <!-- Klik untuk Lihat Isi Keranjang -->
        <a href="{{ route('cart') }}" class="flex items-center space-x-3 text-left hover:opacity-90 transition">
            <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center relative">
                <i class="fa-solid fa-bag-shopping text-lg"></i>
                <span class="absolute -top-1.5 -right-1.5 bg-[#7A9478] text-white text-[10px] font-extrabold w-5 h-5 rounded-full flex items-center justify-center border-2 border-[#34543D]">
                    {{ $cartCount }}
                </span>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold text-[#D8D6CF] flex items-center gap-1">
                    <span>Lihat Keranjang</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </span>
                <span class="text-sm font-black block leading-tight">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
            </div>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('cart') }}" class="hidden sm:inline-flex bg-white/10 hover:bg-white/20 text-white text-xs font-bold px-3.5 py-3 rounded-xl transition">
                Rincian
            </a>
            <a href="{{ route('checkout') }}" class="bg-[#F6F0E1] text-[#24352A] hover:bg-white active:scale-95 text-xs font-extrabold px-5 py-3 rounded-xl shadow-md transition flex items-center gap-2">
                <span>Checkout</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- MODAL LIHAT KERANJANG -->
<div id="cartModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4 transition-opacity">
    <div class="bg-white rounded-t-[32px] sm:rounded-[32px] max-w-lg w-full p-6 space-y-5 shadow-2xl max-h-[85vh] flex flex-col">
        <!-- Header Modal -->
        <div class="flex items-center justify-between border-b border-[#D8D6CF] pb-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-[#F6F0E1] text-[#34543D] flex items-center justify-center">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div>
                    <h3 class="font-black text-base text-[#24352A]">Keranjang Pesanan Anda</h3>
                    <p class="text-[10px] text-[#6E756D]">{{ $cartCount }} item terpilih</p>
                </div>
            </div>
            <button type="button" onclick="closeCartModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-[#24352A] flex items-center justify-center font-bold">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Daftar Item di Keranjang (Scrollable) -->
        <div class="overflow-y-auto space-y-3.5 pr-1 flex-1">
            @foreach($cart as $id => $item)
                <div class="bg-[#F6F0E1]/40 border border-[#D8D6CF]/70 rounded-2xl p-3.5 flex items-center justify-between gap-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-[#34543D] font-bold shrink-0">
                            <i class="fa-solid fa-mug-hot"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-extrabold text-[#24352A]">{{ $item['name'] }}</h4>
                            <p class="text-xs font-bold text-[#34543D]">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            @if(!empty($item['notes']))
                                <p class="text-[10px] text-[#6E756D] italic">Catatan: {{ $item['notes'] }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Jumlah & Aksi Ubah -->
                    <div class="flex items-center space-x-2">
                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center space-x-1.5">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                            <button type="submit" class="w-7 h-7 rounded-lg bg-white border border-[#D8D6CF] text-[#24352A] hover:bg-rose-50 hover:text-rose-600 font-bold flex items-center justify-center">
                                <i class="fa-solid fa-minus text-[10px]"></i>
                            </button>
                        </form>

                        <span class="font-extrabold text-xs text-[#24352A] w-5 text-center">{{ $item['quantity'] }}</span>

                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center space-x-1.5">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                            <button type="submit" class="w-7 h-7 rounded-lg bg-[#34543D] text-white hover:bg-[#24352A] font-bold flex items-center justify-center">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer Modal Keranjang -->
        <div class="border-t border-[#D8D6CF] pt-4 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#6E756D]">Total Belanja</span>
                <span class="text-lg font-black text-[#34543D]">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
            </div>

            <div class="grid grid-cols-3 gap-2.5">
                <form action="{{ route('cart.clear') }}" method="POST" class="col-span-1">
                    @csrf
                    <button type="submit" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs py-3 rounded-xl border border-rose-200 transition">
                        Kosongkan
                    </button>
                </form>
                <a href="{{ route('checkout') }}" class="col-span-2 bg-[#34543D] hover:bg-[#24352A] text-white text-center font-extrabold text-xs py-3 rounded-xl shadow-md transition flex items-center justify-center gap-1.5">
                    <span>Lanjut ke Checkout</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    function openProductModal(product, categoryName, formattedPrice) {
        document.getElementById('modalProductId').value = product.id;
        document.getElementById('modalCategory').innerText = categoryName;
        document.getElementById('modalImage').src = product.image;
        document.getElementById('modalName').innerText = product.name;
        document.getElementById('modalPrice').innerText = formattedPrice;
        document.getElementById('modalDescription').innerText = product.description || '';
        document.getElementById('modalQty').value = 1;
        document.getElementById('modalNotes').value = '';

        const modal = document.getElementById('productModal');
        modal.classList.remove('hidden');
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
    }

    function incrementQty() {
        const input = document.getElementById('modalQty');
        input.value = parseInt(input.value) + 1;
    }

    function decrementQty() {
        const input = document.getElementById('modalQty');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    function openCartModal() {
        const cartModal = document.getElementById('cartModal');
        if (cartModal) {
            cartModal.classList.remove('hidden');
        }
    }

    function closeCartModal() {
        const cartModal = document.getElementById('cartModal');
        if (cartModal) {
            cartModal.classList.add('hidden');
        }
    }
</script>
@endpush
