<div>
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Manajemen Produk</h1>
            <p class="text-gray-500 mt-1">Kelola katalog produk, harga, dan varian.</p>
        </div>
        @if(!$isCreating)
        <button wire:click="createProduct" class="px-5 py-2.5 bg-growseri-green text-white font-bold rounded-xl shadow-lg hover:bg-emerald-600 transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk
        </button>
        @endif
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variasi</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok / Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @if($isCreating)
                <tr>
                    <td colspan="7" class="p-0 border-b-4 border-growseri-green">
                        <div class="p-6 bg-green-50/30">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Tambah Produk Baru</h3>
                            @include('livewire.admin.products-form')
                        </div>
                    </td>
                </tr>
                @endif
                
                @forelse($this->products as $product)
                <tr class="hover:bg-gray-50 transition-colors bg-white {{ $editingProductId === $product->id ? 'bg-blue-50/20' : '' }}" wire:key="product-{{ $product->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                                @if($product->image_url)
                                    <img class="h-12 w-12 object-cover" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                @else
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                                <div class="text-sm text-gray-500 truncate w-48">{{ $product->description }}</div>
                                <button type="button" wire:click="editProduct({{ $product->id }})" class="text-xs text-blue-600 font-bold mt-1 hover:underline">Edit Produk</button>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $product->category ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->has_variants)
                            <div class="text-xs text-gray-500">Mulai Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }}</div>
                        @else
                            @if($product->is_discount_active && $product->discount_price)
                                <div class="text-sm font-medium text-red-600 line-through">Rp {{ number_format($product->base_price, 0, ',', '.') }}</div>
                                <div class="text-sm font-bold text-green-600">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                            @else
                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($product->base_price, 0, ',', '.') }}</div>
                            @endif
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->has_variants)
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        @else
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->has_variants)
                            <div class="text-sm font-medium text-gray-900">{{ $product->variants->sum('stock') }} Total Stok</div>
                        @else
                            @if($product->is_out_of_stock || $product->stock <= 0)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Habis</span>
                            @else
                                <div class="text-sm font-medium text-gray-900">Stok: {{ $product->stock }}</div>
                            @endif
                        @endif
                        <div class="mt-1">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $product->created_at ? $product->created_at->format('d M Y') : '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button wire:click="deleteProduct({{ $product->id }})" wire:confirm="Yakin ingin menghapus produk ini?" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                    </td>
                </tr>
                @if($editingProductId === $product->id)
                <tr wire:key="product-edit-{{ $product->id }}">
                    <td colspan="7" class="p-0 border-b-4 border-blue-400">
                        <div class="p-6 bg-blue-50/20">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Produk: {{ $product->name }}</h3>
                            @include('livewire.admin.products-form')
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        Belum ada produk. Silakan tambah produk baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
