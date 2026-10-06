<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kupon & Diskon</h1>
            <p class="text-gray-500">Kelola kode promo dan diskon untuk pelanggan.</p>
        </div>
        <div class="flex space-x-3">
            <a href="/" class="text-sm font-bold text-gray-600 hover:text-growseri-green flex items-center">
                Ke Halaman Depan &rarr;
            </a>
            <button wire:click="create" class="bg-growseri-green hover:bg-growseri-green-dark text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kupon
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-growseri-green-dark px-4 py-3 rounded-xl mb-6 font-medium flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    @if($isCreating)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6">{{ $editingId ? 'Edit Kupon' : 'Buat Kupon Baru' }}</h2>
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Kupon *</label>
                    <input type="text" wire:model="code" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green uppercase outline-none" placeholder="Cth: DISKON20">
                    @error('code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Diskon *</label>
                        <select wire:model="type" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
                            <option value="fixed">Nominal (Rp)</option>
                            <option value="percent">Persentase (%)</option>
                        </select>
                        @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Diskon *</label>
                        <input type="number" wire:model="value" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none" placeholder="Cth: 10000 atau 10">
                        @error('value') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimal Belanja (Rp)</label>
                    <input type="number" wire:model="min_purchase" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none" placeholder="Kosongkan jika tidak ada">
                    @error('min_purchase') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Batas Kuota Penggunaan</label>
                    <input type="number" wire:model="usage_limit" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none" placeholder="Kosongkan untuk tanpa batas">
                    @error('usage_limit') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Dari</label>
                    <input type="date" wire:model="valid_from" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
                    @error('valid_from') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Sampai</label>
                    <input type="date" wire:model="valid_until" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
                    @error('valid_until') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex items-center space-x-6 mt-6">
                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" wire:model.live="is_active" class="sr-only">
                            <div class="block w-10 h-6 rounded-full transition-colors {{ $is_active ? 'bg-growseri-green' : 'bg-gray-300' }}"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform {{ $is_active ? 'translate-x-4' : '' }}"></div>
                        </div>
                        <div class="ml-3 font-medium text-sm text-gray-700">Aktifkan Kupon</div>
                    </label>

                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" wire:model.live="is_shipping" class="sr-only">
                            <div class="block w-10 h-6 rounded-full transition-colors {{ $is_shipping ? 'bg-growseri-green' : 'bg-gray-300' }}"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform {{ $is_shipping ? 'translate-x-4' : '' }}"></div>
                        </div>
                        <div class="ml-3 font-medium text-sm text-gray-700">Kupon Diskon Ongkir</div>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <button type="button" wire:click="cancel" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-bold transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2 bg-growseri-green hover:bg-growseri-green-dark text-white rounded-xl text-sm font-bold shadow-sm transition-colors">Simpan Kupon</button>
            </div>
        </form>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Kupon</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Diskon</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Masa Berlaku</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kuota / Terpakai</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ $coupon->code }}</span>
                                @if($coupon->min_purchase)
                                <div class="text-xs text-gray-500 mt-1">Min: Rp {{ number_format($coupon->min_purchase, 0, ',', '.') }}</div>
                                @endif
                                @if($coupon->is_shipping)
                                <div class="text-xs text-blue-500 mt-1 font-bold">Diskon Ongkir</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-growseri-green">
                                    {{ $coupon->type === 'percent' ? $coupon->value . '%' : 'Rp ' . number_format($coupon->value, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($coupon->valid_from || $coupon->valid_until)
                                    {{ $coupon->valid_from ? \Carbon\Carbon::parse($coupon->valid_from)->format('d/m/Y') : 'Selamanya' }} 
                                    - 
                                    {{ $coupon->valid_until ? \Carbon\Carbon::parse($coupon->valid_until)->format('d/m/Y') : 'Selamanya' }}
                                @else
                                    <span class="text-gray-400">Selamanya</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="font-medium text-gray-900">{{ $coupon->used_count }}</span>
                                <span class="text-gray-400">/ {{ $coupon->usage_limit ?: '∞' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleActive({{ $coupon->id }})" class="relative inline-flex items-center cursor-pointer">
                                    <div class="w-10 h-6 rounded-full transition-colors {{ $coupon->is_active ? 'bg-growseri-green' : 'bg-gray-300' }}"></div>
                                    <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform {{ $coupon->is_active ? 'translate-x-4' : '' }}"></div>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button wire:click="edit({{ $coupon->id }})" class="text-blue-600 hover:bg-blue-50 px-2 py-1 rounded text-sm font-medium transition-colors">Edit</button>
                                <button wire:click="delete({{ $coupon->id }})" class="text-red-600 hover:bg-red-50 px-2 py-1 rounded text-sm font-medium transition-colors" onclick="confirm('Yakin ingin menghapus kupon ini?') || event.stopImmediatePropagation()">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                <p class="text-gray-500 font-medium">Belum ada kupon diskon.</p>
                                <button wire:click="create" class="mt-4 text-growseri-green font-bold hover:underline">Buat Kupon Pertama</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
