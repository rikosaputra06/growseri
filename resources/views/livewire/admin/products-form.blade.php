<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk *</label>
            <input type="text" wire:model="name" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <input type="text" wire:model="category" placeholder="Cth: Sembako" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
        </div>
    </div>
    

    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea wire:model="description" rows="3" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none"></textarea>
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar</label>
        <input type="file" wire:model="image" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
        @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        <div wire:loading wire:target="image" class="text-sm text-gray-500 mt-1">Mengunggah...</div>
        @if ($image)
            <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-32 h-32 object-cover rounded-xl border border-gray-200">
        @elseif ($image_url)
            <img src="{{ $image_url }}" class="mt-2 w-32 h-32 object-cover rounded-xl border border-gray-200">
        @endif
    </div>

    <div class="flex items-center mt-2">
        <input type="checkbox" wire:model="is_active" id="is_active" class="h-4 w-4 text-growseri-green border-gray-300 rounded focus:ring-growseri-green">
        <label for="is_active" class="ml-2 block text-sm text-gray-900 font-medium">Produk Aktif (Ditampilkan)</label>
    </div>
    
    <div class="border-t border-gray-100 pt-4 mt-4">
        <div class="flex items-center mb-4">
            <input type="checkbox" wire:model.live="has_variants" id="has_variants" class="h-4 w-4 text-growseri-green border-gray-300 rounded focus:ring-growseri-green">
            <label for="has_variants" class="ml-2 block text-sm font-bold text-gray-900">Produk Memiliki Varian (Ukuran/Berat)?</label>
        </div>
        
        @if(!$has_variants)
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Rp) *</label>
                    <input type="number" wire:model="base_price" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
                    @error('base_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    
                    <div class="mt-3">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" wire:model="is_discount_active" id="is_discount_active" class="h-4 w-4 text-growseri-green border-gray-300 rounded focus:ring-growseri-green">
                            <label for="is_discount_active" class="ml-2 block text-sm font-medium text-gray-700">Aktifkan Diskon</label>
                        </div>
                        @if($is_discount_active)
                            <input type="number" wire:model="discount_price" placeholder="Harga Diskon (Rp)" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok *</label>
                    <input type="number" wire:model="stock" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
                    @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    
                    <div class="mt-3">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" wire:model="is_out_of_stock" id="is_out_of_stock" class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="is_out_of_stock" class="ml-2 block text-sm font-medium text-gray-700">Tandai Habis</label>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-3">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">Daftar Varian</label>
                    <button type="button" wire:click="addVariant" class="text-xs bg-emerald-100 text-growseri-green font-bold px-3 py-1 rounded-lg hover:bg-emerald-200">+ Tambah Varian</button>
                </div>
                
                @foreach($variants as $index => $variant)
                <div class="p-3 border border-gray-200 rounded-lg bg-white relative mt-2" wire:key="variant-{{ $index }}">
                    <button type="button" wire:click="removeVariant({{ $index }})" class="absolute top-2 right-2 p-1 text-red-500 hover:bg-red-50 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    <div class="grid grid-cols-2 gap-3 mb-3 pr-8">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Varian</label>
                            <input type="text" wire:model="variants.{{ $index }}.variant_name" placeholder="Misal: 1 Kg" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-growseri-green outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Stok</label>
                            <div class="flex items-center gap-2">
                                <input type="number" wire:model="variants.{{ $index }}.stock" placeholder="Stok" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-growseri-green outline-none text-sm">
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="variants.{{ $index }}.is_out_of_stock" title="Tandai Habis" class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Harga (Rp)</label>
                            <input type="number" wire:model="variants.{{ $index }}.price" placeholder="Harga (Rp)" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-growseri-green outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-2">
                                Harga Diskon
                                <input type="checkbox" wire:model.live="variants.{{ $index }}.is_discount_active" class="h-3 w-3 text-growseri-green border-gray-300 rounded focus:ring-growseri-green" title="Aktifkan Diskon">
                            </label>
                            <input type="number" wire:model="variants.{{ $index }}.discount_price" placeholder="Opsional" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-growseri-green outline-none text-sm" {{ !empty($variants[$index]['is_discount_active']) ? '' : 'disabled' }}>
                        </div>
                    </div>
                </div>
                @endforeach
                @if(count($variants) == 0)
                    <p class="text-xs text-gray-500 italic">Belum ada varian. Tambahkan varian baru.</p>
                @endif
            </div>
        @endif
    </div>

    <div class="mt-6 flex flex-row-reverse gap-3">
        <button type="button" wire:click="saveProduct" class="inline-flex justify-center rounded-xl shadow-sm px-5 py-2 bg-growseri-green text-sm font-bold text-white hover:bg-emerald-600 focus:outline-none transition-colors">
            Simpan Produk
        </button>
        <button type="button" wire:click="cancelEdit" class="inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors">
            Batal
        </button>
    </div>
</div>
