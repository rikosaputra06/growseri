<div class="max-w-4xl mx-auto py-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan Toko (Admin)</h1>
            <p class="text-gray-500">Atur lokasi pusat pengiriman dan metode ongkos kirim.</p>
        </div>
        <a href="/" class="text-sm font-bold text-growseri-green hover:underline">Ke Halaman Depan &rarr;</a>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-growseri-green-dark px-4 py-3 rounded-xl mb-6 font-medium flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Settings Form -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Informasi Dasar</h2>
                    <!-- Toggle Status Toko -->
                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" wire:model="is_open" class="sr-only">
                            <div class="block w-14 h-8 rounded-full transition-colors {{ $is_open ? 'bg-growseri-green' : 'bg-gray-300' }}"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition transform {{ $is_open ? 'translate-x-6' : '' }}"></div>
                        </div>
                        <div class="ml-3 font-medium text-sm {{ $is_open ? 'text-growseri-green' : 'text-gray-500' }}">
                            {{ $is_open ? 'Toko Buka' : 'Toko Tutup' }}
                        </div>
                    </label>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                        <input type="text" wire:model="store_name" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo Toko (Opsional)</label>
                        <input type="file" wire:model="logo" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none">
                        <div wire:loading wire:target="logo" class="text-sm text-gray-500 mt-1">Mengunggah...</div>
                        @if ($logo)
                            <img src="{{ $logo->temporaryUrl() }}" class="mt-2 h-16 object-contain rounded border border-gray-200">
                        @elseif ($logo_path)
                            <img src="{{ asset($logo_path) }}" class="mt-2 h-16 object-contain rounded border border-gray-200">
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp CS/Admin</label>
                        <input type="text" wire:model="store_whatsapp" placeholder="Contoh: 08123456789" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slogan Toko</label>
                        <input type="text" wire:model="slogan" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Toko</label>
                        <textarea wire:model="address" rows="3" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Uang</label>
                        <select wire:model="currency" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none">
                            <option value="IDR">Rupiah (IDR)</option>
                            <option value="USD">US Dollar (USD)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Ongkos Kirim</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Perhitungan</label>
                        <select wire:model="shipping_rate_type" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
                            <option value="flat">Tarif Flat (Tetap ke semua lokasi)</option>
                            <option value="per_km">Berdasarkan Jarak (Per Km)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tarif Flat Dasar (Rp)</label>
                        <input type="number" wire:model="shipping_flat_rate" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tarif Per Km (Rp)</label>
                        <input type="number" wire:model="shipping_per_km_rate" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maksimal Jangkauan Jarak (Km)</label>
                        <div class="flex items-center">
                            <input type="number" wire:model="max_delivery_distance_km" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none">
                            <span class="ml-3 text-sm text-gray-500 font-bold">Km</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Pesanan akan ditolak jika jarak alamat tujuan melebihi batas kilometer ini.</p>
                    </div>
                </div>
            </div>
            
            <!-- Pengaturan Pengiriman (Delivery) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Waktu Pengiriman (Delivery)</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label Tanggal & Waktu</label>
                        <input type="text" wire:model="delivery_label" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none" placeholder="Cth: Pilih Waktu Pengiriman">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mode Pemilihan Waktu</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border rounded-xl cursor-pointer transition-colors {{ $delivery_time_mode == 'time_slot' ? 'border-growseri-green bg-emerald-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" wire:model.live="delivery_time_mode" value="time_slot" class="text-growseri-green focus:ring-growseri-green border-gray-300">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-800 text-sm">Time Slot (Rentang Jam)</div>
                                    <div class="text-xs text-gray-500">Pilih slot waktu (misal: 10:00 - 12:00)</div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border rounded-xl cursor-pointer transition-colors {{ $delivery_time_mode == 'date_only' ? 'border-growseri-green bg-emerald-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" wire:model.live="delivery_time_mode" value="date_only" class="text-growseri-green focus:ring-growseri-green border-gray-300">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-800 text-sm">Kalender Tanggal</div>
                                    <div class="text-xs text-gray-500">Hanya memilih tanggal spesifik di kalender</div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border rounded-xl cursor-pointer transition-colors {{ $delivery_time_mode == 'date_time' ? 'border-growseri-green bg-emerald-50' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" wire:model.live="delivery_time_mode" value="date_time" class="text-growseri-green focus:ring-growseri-green border-gray-300">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-800 text-sm">Kalender Tanggal & Jam</div>
                                    <div class="text-xs text-gray-500">Kalender beserta pilihan jam spesifik</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    @if($delivery_time_mode == 'time_slot')
                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Slot Waktu Tersedia</label>
                            <button type="button" wire:click="addTimeSlot" class="text-xs bg-emerald-100 text-growseri-green font-bold px-3 py-1 rounded-lg hover:bg-emerald-200 transition-colors">+ Tambah Slot</button>
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($delivery_time_slots as $index => $slot)
                            <div class="flex items-center space-x-2">
                                <input type="time" wire:model="delivery_time_slots.{{ $index }}.start" class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none text-sm">
                                <span class="text-gray-400">-</span>
                                <input type="time" wire:model="delivery_time_slots.{{ $index }}.end" class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none text-sm">
                                <button type="button" wire:click="removeTimeSlot({{ $index }})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            @endforeach
                            @if(count($delivery_time_slots) == 0)
                                <p class="text-xs text-gray-500 italic">Belum ada slot waktu. Tambahkan slot baru.</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hari Operasional (Slot Hari)</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                            @endphp
                            @foreach($days as $num => $name)
                                <button type="button" 
                                    wire:click="toggleActiveDay({{ $num }})" 
                                    class="px-3 py-1.5 text-xs font-bold rounded-lg border transition-colors {{ in_array($num, $delivery_active_days) ? 'bg-growseri-green text-white border-growseri-green shadow-sm' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50' }}">
                                    {{ $name }}
                                </button>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Hanya hari yang dipilih ini yang bisa dipilih customer. Customer yang memesan diluar jam operasional hari ini otomatis diarahkan ke slot di hari berikutnya yang tersedia.</p>
                    </div>
                </div>
            </div>

            <!-- Pengaturan Pembayaran -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Pengaturan Pembayaran</h2>
                
                <!-- Transfer Bank -->
                <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="font-bold text-gray-900">Transfer Bank Manual</div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="payment_transfer_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-growseri-green"></div>
                        </label>
                    </div>
                    @if($payment_transfer_active)
                    <div class="space-y-4">
                        @foreach($payment_transfer_details as $index => $bank)
                        <div class="bg-white p-4 rounded-xl border border-gray-200 relative">
                            <button type="button" wire:click="removeBankAccount({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:bg-red-50 p-1 rounded-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs font-bold text-gray-700 block mb-1">Nama Bank</label>
                                    <input type="text" wire:model="payment_transfer_details.{{ $index }}.bank_name" placeholder="Cth: BCA" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-growseri-green outline-none text-sm">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-700 block mb-1">Nomor Rekening</label>
                                    <input type="text" wire:model="payment_transfer_details.{{ $index }}.account_number" placeholder="Cth: 1234567890" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-growseri-green outline-none text-sm">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-700 block mb-1">Atas Nama</label>
                                    <input type="text" wire:model="payment_transfer_details.{{ $index }}.account_name" placeholder="Cth: Toko Growseri" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-growseri-green outline-none text-sm">
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <button type="button" wire:click="addBankAccount" class="w-full py-2 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 hover:text-growseri-green hover:border-growseri-green transition-colors font-medium text-sm flex justify-center items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Rekening Bank
                        </button>
                    </div>
                    @endif
                </div>

                <!-- QRIS -->
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="font-bold text-gray-900">QRIS</div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="payment_qris_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-growseri-green"></div>
                        </label>
                    </div>
                    @if($payment_qris_active)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload QR Code (QRIS)</label>
                        
                        @if($payment_qris_image_path)
                            <div class="mb-3 relative inline-block">
                                <img src="{{ asset($payment_qris_image_path) }}" class="h-32 rounded border border-gray-200">
                            </div>
                        @endif
                        
                        <input type="file" wire:model="payment_qris_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-growseri-green hover:file:bg-emerald-100">
                        <div wire:loading wire:target="payment_qris_image" class="text-sm text-gray-500 mt-2">Mengunggah...</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pengaturan Banners -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Slider Banners (Promo)</h2>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_slider_autoplay" class="w-4 h-4 text-growseri-green rounded border-gray-300 focus:ring-growseri-green">
                            <span class="text-sm font-bold text-gray-700">Auto Slide (Otomatis Geser)</span>
                        </label>
                        <button type="button" wire:click="addBanner" class="text-xs bg-emerald-100 text-growseri-green font-bold px-3 py-1 rounded-lg hover:bg-emerald-200">+ Tambah Banner</button>
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($promo_banners as $index => $banner)
                    <div class="border border-gray-200 p-4 rounded-xl relative bg-gray-50">
                        <button type="button" wire:click="removeBanner({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:bg-red-50 p-1 rounded-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <div class="col-span-2">
                                <label class="text-xs font-bold text-gray-700">Tautan Tujuan / URL (Opsional)</label>
                                <input type="text" wire:model="promo_banners.{{ $index }}.url" class="w-full mt-1 px-3 py-1.5 rounded-lg border border-gray-200 text-sm" placeholder="https://...">
                                <p class="text-[10px] text-gray-500 mt-1">Jika diisi, pengunjung akan diarahkan ke halaman ini saat mengklik banner.</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs font-bold text-gray-700">Foto Banner (Unggah dari Device)</label>
                                @if(!empty($banner['image']))
                                    <div class="mb-2 relative inline-block">
                                        <img src="{{ Storage::url($banner['image']) }}" class="h-20 rounded object-cover">
                                        <button type="button" wire:click="removeBannerImage({{ $index }})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                @elseif(isset($banner_uploads[$index]))
                                    <div class="mb-2 relative inline-block">
                                        <img src="{{ $banner_uploads[$index]->temporaryUrl() }}" class="h-20 rounded object-cover">
                                        <button type="button" wire:click="removeBannerImage({{ $index }})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                @else
                                    <input type="file" wire:model="banner_uploads.{{ $index }}" accept="image/*" class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-growseri-green hover:file:bg-emerald-100">
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if(count($promo_banners) === 0)
                        <p class="text-xs text-gray-500 italic">Belum ada banner promo.</p>
                    @endif
                </div>
            </div>

            <!-- Pengaturan Grid Banners -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Grid Banners (Promo 2x2)</h2>
                        <p class="text-xs text-gray-500 mt-1">Banner kotak-kotak yang tampil di atas kategori produk. Maksimal 4 banner (2 baris x 2 kolom).</p>
                    </div>
                    <button type="button" wire:click="addGridBanner" class="text-xs bg-emerald-100 text-growseri-green font-bold px-3 py-1 rounded-lg hover:bg-emerald-200">+ Tambah Grid</button>
                </div>
                
                @error('grid_banners')
                    <div class="text-xs text-red-500 mb-3 bg-red-50 p-2 rounded-lg">{{ $message }}</div>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($grid_banners as $index => $banner)
                    <div class="border border-gray-200 p-4 rounded-xl relative bg-gray-50">
                        <button type="button" wire:click="removeGridBanner({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:bg-red-50 p-1 rounded-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        <div class="space-y-3 mt-2">
                            <div>
                                <label class="text-xs font-bold text-gray-700">Tautan Tujuan / URL (Opsional)</label>
                                <input type="text" wire:model="grid_banners.{{ $index }}.link" class="w-full mt-1 px-3 py-1.5 rounded-lg border border-gray-200 text-sm" placeholder="https://...">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-700">Foto Banner Grid</label>
                                @if(!empty($banner['image']))
                                    <div class="mt-1 relative inline-block">
                                        <img src="{{ Storage::url($banner['image']) }}" class="h-20 rounded object-cover aspect-square">
                                        <button type="button" wire:click="removeGridBannerImage({{ $index }})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                @elseif(isset($grid_banner_uploads[$index]))
                                    <div class="mt-1 relative inline-block">
                                        <img src="{{ $grid_banner_uploads[$index]->temporaryUrl() }}" class="h-20 rounded object-cover aspect-square">
                                        <button type="button" wire:click="removeGridBannerImage({{ $index }})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                @else
                                    <input type="file" wire:model="grid_banner_uploads.{{ $index }}" accept="image/*" class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-growseri-green hover:file:bg-emerald-100">
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if(count($grid_banners) === 0)
                    <p class="text-xs text-gray-500 italic mt-2">Belum ada banner grid.</p>
                @endif
            </div>

            <!-- Pengaturan Accordion -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Informasi Penting & Cara Belanja</h2>
                    <button type="button" wire:click="addAccordion" class="text-xs bg-emerald-100 text-growseri-green font-bold px-3 py-1 rounded-lg hover:bg-emerald-200">+ Tambah Info</button>
                </div>
                <div class="space-y-3">
                    @foreach($info_accordion as $index => $info)
                    <div class="flex items-start space-x-2 border border-gray-100 p-3 rounded-xl bg-gray-50">
                        <div class="flex-grow space-y-2">
                            <input type="text" wire:model="info_accordion.{{ $index }}.title" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 focus:ring-growseri-green outline-none text-sm font-bold" placeholder="Judul Info (Contoh: Cara Pemesanan)">
                            <textarea wire:model="info_accordion.{{ $index }}.description" rows="3" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-growseri-green outline-none text-sm" placeholder="Isi penjelasan..."></textarea>
                        </div>
                        <button type="button" wire:click="removeAccordion({{ $index }})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                    @endforeach
                    @if(count($info_accordion) === 0)
                        <p class="text-xs text-gray-500 italic">Belum ada informasi cara belanja.</p>
                    @endif
                </div>
            </div>
            
            <button wire:click="saveSettings" class="w-full py-4 bg-gray-900 hover:bg-black text-white rounded-xl font-bold text-lg shadow-lg transition-all active:scale-[0.98]">
                Simpan Pengaturan
            </button>
        </div>

        <!-- Maps Form -->
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h2 class="text-lg font-bold text-gray-900 mb-1">Titik Lokasi Toko</h2>
                <p class="text-sm text-gray-500 mb-4">Geser pin merah untuk menetapkan dari mana barang akan dikirim.</p>
                
                <div x-data="adminMap()" x-init="initMap()" class="space-y-4">
                    <div wire:ignore class="rounded-xl overflow-hidden border border-gray-200 relative z-0">
                        <div id="admin-map" class="w-full h-80"></div>
                        <div class="absolute top-2 right-2 z-[400]">
                            <button @click.prevent="getLocation()" type="button" class="bg-white p-2 rounded-lg shadow-md border border-gray-100 hover:bg-gray-50 text-growseri-green flex items-center space-x-2" title="Gunakan Lokasi Saat Ini">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-500 mb-1">Koordinat (Latitude, Longitude)</label>
                            <input type="text" x-model="coordString" @input="updateFromInput()" placeholder="Contoh: -6.1753871, 106.8249641" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-mono focus:ring-growseri-green focus:border-growseri-green outline-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS & CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminMap', () => ({
                lat: {{ $store_latitude ?? -6.200000 }},
                lng: {{ $store_longitude ?? 106.816666 }},
                coordString: '{{ $store_latitude ?? -6.200000 }}, {{ $store_longitude ?? 106.816666 }}',
                map: null,
                marker: null,
                
                initMap() {
                    setTimeout(() => {
                        this.map = L.map('admin-map').setView([this.lat, this.lng], 15);
                        
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(this.map);
                        
                        this.marker = L.marker([this.lat, this.lng], {
                            draggable: true,
                            icon: L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41]
                            })
                        }).addTo(this.map).bindPopup('Geser saya!').openPopup();
                        
                        this.marker.on('dragend', (event) => {
                            var position = this.marker.getLatLng();
                            this.updatePos(position.lat, position.lng);
                        });
                    }, 100);
                },
                
                updatePos(newLat, newLng) {
                    this.lat = newLat;
                    this.lng = newLng;
                    this.coordString = `${this.lat.toFixed(7)}, ${this.lng.toFixed(7)}`;
                    @this.updateLocation(this.lat, this.lng);
                },

                updateFromInput() {
                    let parts = this.coordString.split(',');
                    if(parts.length === 2) {
                        let newLat = parseFloat(parts[0].trim());
                        let newLng = parseFloat(parts[1].trim());
                        
                        if(!isNaN(newLat) && !isNaN(newLng)) {
                            this.lat = newLat;
                            this.lng = newLng;
                            this.marker.setLatLng([this.lat, this.lng]);
                            this.map.flyTo([this.lat, this.lng], 16);
                            @this.updateLocation(this.lat, this.lng);
                        }
                    }
                },
                
                getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition((position) => {
                            this.lat = position.coords.latitude;
                            this.lng = position.coords.longitude;
                            this.marker.setLatLng([this.lat, this.lng]);
                            this.map.flyTo([this.lat, this.lng], 16);
                            @this.updateLocation(this.lat, this.lng);
                        });
                    }
                }
            }))
        })
    </script>
</div>
