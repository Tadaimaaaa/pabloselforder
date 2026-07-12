@extends('layouts.admin')

@section('title', 'Kelola Produk & Menu | Admin Kopi Pablo')
@section('header_title', 'Manajemen Daftar Menu Kopi Pablo')

@section('content')
<div class="space-y-6">

    <!-- ACTION HEADER -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-extrabold text-[#24352A]">Katalog Produk & Menu</h2>
            <p class="text-xs text-[#6E756D]">Total {{ $products->count() }} hidangan tersedia</p>
        </div>
        <button onclick="document.getElementById('modalAddProduct').classList.remove('hidden')"
                class="bg-[#34543D] hover:bg-[#24352A] text-white text-xs font-extrabold px-4 py-2.5 rounded-xl shadow flex items-center gap-2 transition">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Produk Baru</span>
        </button>
    </div>

    <!-- TABEL / DAFTAR PRODUK -->
    <div class="bg-white rounded-[20px] border border-[#D8D6CF] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F6F0E1] text-[#24352A] font-extrabold uppercase border-b border-[#D8D6CF]">
                    <tr>
                        <th class="p-4">Foto & Menu</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Favorit</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8D6CF]/60">
                    @foreach($products as $product)
                        <tr class="hover:bg-[#F6F0E1]/30 transition">
                            <td class="p-4 flex items-center gap-3">
                                <img src="{{ $product->image }}" alt="" class="w-12 h-12 rounded-xl object-cover bg-[#F6F0E1]">
                                <div>
                                    <span class="font-bold text-sm text-[#24352A] block">{{ $product->name }}</span>
                                    <span class="text-[11px] text-[#6E756D] line-clamp-1 max-w-xs">{{ $product->description }}</span>
                                </div>
                            </td>
                            <td class="p-4 font-bold text-[#58725A]">{{ $product->category->name }}</td>
                            <td class="p-4 font-extrabold text-[#34543D]">{{ $product->formatted_price }}</td>
                            <td class="p-4">
                                @if($product->is_favorite)
                                    <span class="bg-[#34543D] text-white text-[10px] font-bold px-2 py-0.5 rounded">Ya</span>
                                @else
                                    <span class="text-[#6E756D]">-</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($product->is_available)
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Tersedia</span>
                                @else
                                    <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Habis</span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <button onclick="openEditModal({{ json_encode($product) }})"
                                        class="px-3 py-1.5 rounded-xl bg-[#F6F0E1] text-[#34543D] font-bold hover:bg-[#D8D6CF]">
                                    Edit
                                </button>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 font-bold hover:bg-rose-100">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL TAMBAH PRODUK BARU -->
<div id="modalAddProduct" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[24px] max-w-lg w-full p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-base text-[#24352A]">Tambah Produk Menu Baru</h3>
            <button onclick="document.getElementById('modalAddProduct').classList.add('hidden')" class="font-bold text-lg">&times;</button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1">Nama Produk</label>
                <input type="text" name="name" required placeholder="Contoh: Kopi Pablo Latte"
                       class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl px-3 py-2 text-xs">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-[#24352A] mb-1">Kategori</label>
                    <select name="category_id" required class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl px-3 py-2 text-xs">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#24352A] mb-1">Harga (Rupiah)</label>
                    <input type="number" name="price" required placeholder="28000"
                           class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl px-3 py-2 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1">Upload Foto Produk (Lokal, Maks. 20 MB)</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl px-3 py-2 text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1">Deskripsi Produk</label>
                <textarea name="description" rows="2" placeholder="Komposisi rasa..."
                          class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl p-3 text-xs"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_favorite" value="1" id="addFav">
                <label for="addFav" class="text-xs font-bold text-[#24352A]">Tandai sebagai Produk Favorit</label>
            </div>

            <button type="submit" class="w-full bg-[#34543D] text-white font-extrabold text-xs py-3 rounded-xl shadow">
                Simpan Produk Baru
            </button>
        </form>
    </div>
</div>

<!-- MODAL EDIT PRODUK -->
<div id="modalEditProduct" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[24px] max-w-lg w-full p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-base text-[#24352A]">Edit Produk Kopi Pablo</h3>
            <button onclick="document.getElementById('modalEditProduct').classList.add('hidden')" class="font-bold text-lg">&times;</button>
        </div>

        <form id="formEditProduct" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1">Nama Produk</label>
                <input type="text" name="name" id="editName" required class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl px-3 py-2 text-xs">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-[#24352A] mb-1">Kategori</label>
                    <select name="category_id" id="editCategoryId" required class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl px-3 py-2 text-xs">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#24352A] mb-1">Harga (Rupiah)</label>
                    <input type="number" name="price" id="editPrice" required class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl px-3 py-2 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1">Ganti Foto Produk (Upload Lokal Opsional, Maks. 20 MB)</label>
                <input type="file" name="image" id="editImage" accept="image/*" class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl px-3 py-2 text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#24352A] mb-1">Deskripsi Produk</label>
                <textarea name="description" id="editDescription" rows="2" class="w-full bg-[#F6F0E1]/60 border border-[#D8D6CF] rounded-xl p-3 text-xs"></textarea>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_favorite" value="1" id="editFav">
                    <label for="editFav" class="text-xs font-bold text-[#24352A]">Favorit</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_available" value="1" id="editAvail">
                    <label for="editAvail" class="text-xs font-bold text-[#24352A]">Tersedia</label>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#34543D] text-white font-extrabold text-xs py-3 rounded-xl shadow">
                Perbarui Produk
            </button>
        </form>
    </div>
</div>

<script>
function openEditModal(product) {
    document.getElementById('formEditProduct').action = '/admin/products/' + product.id;
    document.getElementById('editName').value = product.name;
    document.getElementById('editCategoryId').value = product.category_id;
    document.getElementById('editPrice').value = product.price;
    document.getElementById('editDescription').value = product.description || '';
    document.getElementById('editFav').checked = product.is_favorite;
    document.getElementById('editAvail').checked = product.is_available;
    document.getElementById('modalEditProduct').classList.remove('hidden');
}
</script>
@endsection
