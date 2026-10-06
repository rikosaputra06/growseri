<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pesanan</h1>
            <p class="text-gray-500">Kelola dan pantau pesanan pelanggan yang masuk.</p>
        </div>
        <a href="/" class="text-sm font-bold text-growseri-green hover:underline">Ke Halaman Depan &rarr;</a>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-growseri-green-dark px-4 py-3 rounded-xl mb-6 font-medium flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Filter -->
    <div class="mb-6 flex space-x-2 overflow-x-auto pb-2">
        @php
            $statuses = [
                'all' => 'Semua Pesanan',
                'pending' => 'Baru Masuk',
                'processing' => 'Diproses',
                'shipped' => 'Dikirim',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan'
            ];
        @endphp
        
        @foreach($statuses as $value => $label)
            <button wire:click="$set('statusFilter', '{{ $value }}')" 
                class="px-4 py-2 rounded-xl text-sm font-bold whitespace-nowrap transition-colors {{ $statusFilter === $value ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Orders List -->
    <div class="space-y-6">
        @forelse($orders as $order)
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <!-- Header Card -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <div class="flex items-center space-x-3 mb-1">
                            <span class="font-bold text-gray-900">{{ $order->invoice_number }}</span>
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold
                                {{ $order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->order_status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->order_status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $order->order_status === 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $order->order_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ strtoupper($order->order_status) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }} • Pengiriman: {{ str_replace('_', ' ', strtoupper($order->shipping_method)) }}</p>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        @if($order->order_status === 'pending')
                        <button wire:click="editOrder({{ $order->id }})" class="text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg font-bold inline-flex items-center focus:outline-none transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Edit
                        </button>
                        @endif
                        <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="text-sm bg-gray-100 hover:bg-gray-200 border border-gray-200 text-gray-700 px-3 py-1.5 rounded-lg font-medium inline-flex items-center focus:outline-none transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Struk
                        </a>
                        <select wire:change="updateStatus({{ $order->id }}, $event.target.value)" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-growseri-green outline-none font-medium">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                            <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                </div>
                
                <!-- Body Card -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Informasi Penerima</h3>
                        <div class="text-sm text-gray-700 space-y-1">
                            <p><span class="font-medium">Nama:</span> {{ $order->recipient_name }}</p>
                            <p><span class="font-medium">WhatsApp:</span> 
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->recipient_whatsapp) }}" target="_blank" class="text-growseri-green hover:underline">
                                    {{ $order->recipient_whatsapp }}
                                </a>
                            </p>
                            <p><span class="font-medium">Alamat:</span> {{ $order->shipping_address }}</p>
                            <p><span class="font-medium">Jadwal Pengiriman:</span> 
                                @if($order->delivery_date)
                                    {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
                                    @if($order->delivery_time)
                                        (Pukul: {{ $order->delivery_time }})
                                    @endif
                                @else
                                    Sesuai antrean
                                @endif
                            </p>
                            @if($order->latitude && $order->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="inline-flex items-center text-blue-600 hover:underline mt-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Buka di Google Maps
                                </a>
                            @endif
                            @if($order->notes)
                                <div class="mt-2 p-2 bg-yellow-50 text-yellow-800 rounded-lg text-xs">
                                    <span class="font-bold">Catatan:</span> {{ $order->notes }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Ringkasan Pesanan</h3>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <div class="text-gray-700 flex-1">
                                        {{ $item->quantity }}x {{ $item->product_name }}
                                        @if($item->variant_name)
                                            <span class="text-gray-400 text-xs block">{{ $item->variant_name }}</span>
                                        @endif
                                        @if($item->note)
                                            <span class="text-orange-500 text-xs block italic mt-0.5">Catatan: {{ $item->note }}</span>
                                        @endif
                                    </div>
                                    <div class="text-gray-900 font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="border-t border-gray-100 mt-4 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Ongkos Kirim</span>
                                <span>Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-900 font-bold text-base mt-2">
                                <span>Total Belanja</span>
                                <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                Metode Pembayaran: <span class="font-bold uppercase">{{ $order->payment_method }}</span>
                            </div>

                            @if($order->order_status === 'shipped' || $order->order_status === 'completed')
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <h4 class="text-sm font-bold text-gray-900 mb-2">Bukti Pengiriman</h4>
                                    
                                    @if($order->proof_of_delivery_path)
                                        <div class="mt-2">
                                            <a href="{{ asset($order->proof_of_delivery_path) }}" target="_blank">
                                                <img src="{{ asset($order->proof_of_delivery_path) }}" alt="Bukti Pengiriman" class="w-full max-w-xs rounded-xl border border-gray-200 shadow-sm object-cover">
                                            </a>
                                            <p class="text-xs text-green-600 mt-1 font-medium">✓ Bukti berhasil diunggah</p>
                                        </div>
                                    @else
                                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                                            <input type="file" wire:model="proofs.{{ $order->id }}" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-growseri-green hover:file:bg-emerald-100">
                                            @error("proofs.{$order->id}") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                            
                                            <button wire:click="uploadProof({{ $order->id }})" wire:loading.attr="disabled" class="mt-2 px-3 py-1.5 bg-growseri-green text-white text-xs font-bold rounded-lg hover:bg-growseri-green-dark transition-colors flex items-center">
                                                <span wire:loading.remove wire:target="uploadProof({{ $order->id }})">Upload & Selesaikan Pesanan</span>
                                                <span wire:loading wire:target="uploadProof({{ $order->id }})">Mengunggah...</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-gray-100 rounded-2xl py-12 text-center shadow-sm">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Pesanan</h3>
                <p class="text-gray-500">Saat ini tidak ada pesanan dengan status yang dipilih.</p>
            </div>
        @endforelse
    </div>

    <!-- Edit Order Modal -->
    <div>
        @if($isEditingOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cancelEdit"></div>
                
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">Edit Pesanan</h3>
                    
                    <div class="space-y-6">
                        <!-- Recipient Info -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h4 class="font-bold text-sm text-gray-900 mb-3">Informasi Penerima</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penerima</label>
                                    <input type="text" wire:model="editRecipientName" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-growseri-green outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">WhatsApp</label>
                                    <input type="text" wire:model="editRecipientWhatsapp" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-growseri-green outline-none">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                                    <textarea wire:model="editShippingAddress" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-growseri-green outline-none"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Kirim</label>
                                    <input type="date" wire:model="editDeliveryDate" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-growseri-green outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Jam Kirim</label>
                                    <input type="text" wire:model="editDeliveryTime" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-growseri-green outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h4 class="font-bold text-sm text-gray-900 mb-3">Ringkasan Pesanan</h4>
                            
                            <div class="space-y-3 mb-4">
                                @foreach($editItems as $index => $item)
                                    <div wire:key="edit-item-{{ $index }}" class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-100">
                                        <div class="flex-1">
                                            <div class="font-medium text-sm text-gray-900">{{ $item['product_name'] }}</div>
                                            @if($item['variant_name'])
                                                <div class="text-xs text-gray-500">{{ $item['variant_name'] }}</div>
                                            @endif
                                            <div class="text-xs text-gray-500">Rp {{ number_format((float) $item['price'], 0, ',', '.') }}</div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <input type="number" wire:model.live.debounce.500ms="editItems.{{ $index }}.quantity" min="1" class="w-16 px-2 py-1 text-center border border-gray-200 rounded text-sm focus:ring-growseri-green outline-none">
                                            <div class="font-bold text-sm text-gray-900 w-24 text-right">Rp {{ number_format((float) $item['subtotal'], 0, ',', '.') }}</div>
                                            <button wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Add New Item -->
                            <div class="flex items-end space-x-3 bg-white p-3 rounded-lg border border-gray-100">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tambah Produk</label>
                                    <select wire:model="selectedProductId" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-growseri-green outline-none">
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($this->getOrderableProducts() as $product)
                                            @if(count($product['variants']) > 0)
                                                @foreach($product['variants'] as $variant)
                                                    <option value="{{ $product['id'] }}-{{ $variant['id'] }}" {{ $variant['is_out_of_stock'] ? 'disabled' : '' }}>
                                                        {{ $product['name'] }} - {{ $variant['name'] }} (Rp {{ number_format((float) $variant['price'], 0, ',', '.') }}) {{ $variant['is_out_of_stock'] ? ' - (HABIS)' : '' }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="{{ $product['id'] }}" {{ $product['is_out_of_stock'] ? 'disabled' : '' }}>
                                                    {{ $product['name'] }} (Rp {{ number_format((float) $product['price'], 0, ',', '.') }}) {{ $product['is_out_of_stock'] ? ' - (HABIS)' : '' }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('selectedProduct') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Qty</label>
                                    <input type="number" wire:model="selectedProductQty" min="1" class="w-16 px-3 py-2 rounded-lg border border-gray-200 text-sm text-center focus:ring-growseri-green outline-none">
                                </div>
                                <button wire:click="addItem" class="px-4 py-2 bg-gray-900 hover:bg-black text-white text-sm font-bold rounded-lg transition-colors">Tambah</button>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200" x-data="adminOrderMap()">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">Ongkos Kirim</span>
                                    <input type="number" wire:model="editShippingFee" readonly class="w-32 px-3 py-1 text-right border border-gray-200 bg-gray-50 rounded-lg text-sm focus:ring-growseri-green outline-none cursor-not-allowed">
                                </div>
                                <div class="mt-2 text-xs text-gray-500 mb-2">Geser pin pada peta untuk menghitung ulang ongkos kirim secara otomatis sesuai jarak.</div>
                                <div id="admin-map" wire:ignore class="h-48 rounded-xl border border-gray-200 w-full relative z-0 mb-2"></div>
                                <div class="text-xs font-medium text-growseri-green flex items-center justify-between">
                                    <span x-text="calculating ? 'Menghitung jarak...' : (distance ? 'Jarak: ' + distance.toFixed(1) + ' km' : '')"></span>
                                    <span x-show="error" class="text-red-500" x-text="error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button wire:click="saveOrder" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-growseri-green text-base font-bold text-white hover:bg-growseri-green-dark focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan Perubahan
                    </button>
                    <button wire:click="cancelEdit" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

<!-- Leaflet JS & CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Ensure we register Alpine data whether Alpine is already initialized or not
    document.addEventListener('alpine:init', () => {
        registerAdminOrderMap();
    });
    
    if (typeof Alpine !== 'undefined') {
        registerAdminOrderMap();
    }

    function registerAdminOrderMap() {
        if (Alpine.data('adminOrderMap')) return; // prevent double registration
        
        Alpine.data('adminOrderMap', () => ({
            calculating: false,
            distance: 0,
            error: '',
            storeLat: {{ \App\Models\StoreSetting::first()->store_latitude ?? -6.200000 }},
            storeLng: {{ \App\Models\StoreSetting::first()->store_longitude ?? 106.816666 }},
            map: null,
            marker: null,

            init() {
                // Initialize immediately since the component is rendered inside the modal if block
                setTimeout(() => this.initMap(), 300);
            },

            initMap() {
                if (this.map) return;
                
                let lat = this.$wire.editLatitude || this.storeLat;
                let lng = this.$wire.editLongitude || this.storeLng;

                this.map = L.map('admin-map').setView([lat, lng], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(this.map);
                
                this.marker = L.marker([lat, lng], {
                    draggable: true,
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(this.map).bindPopup('Lokasi Pengiriman');
                
                this.marker.on('dragend', (event) => {
                    var position = this.marker.getLatLng();
                    this.calculateRoutingDistance(position.lat, position.lng);
                });
            },

            async calculateRoutingDistance(userLat, userLng) {
                this.calculating = true;
                this.error = '';
                
                try {
                    const response = await fetch(`https://router.project-osrm.org/route/v1/driving/${this.storeLng},${this.storeLat};${userLng},${userLat}?overview=false`);
                    const data = await response.json();
                    
                    if (data.code === 'Ok' && data.routes.length > 0) {
                        this.distance = data.routes[0].distance / 1000;
                        this.$wire.updateShippingFee(userLat, userLng, this.distance);
                    } else {
                        throw new Error('Rute tidak ditemukan');
                    }
                } catch (err) {
                    this.error = 'Gagal menghitung jarak otomatis. Ongkir akan memakai tarif tetap.';
                    this.$wire.updateShippingFee(userLat, userLng, 0);
                } finally {
                    this.calculating = false;
                }
            }
        }));
    }
</script>
</div>
