<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center space-x-4">
        <a href="/" class="text-gray-500 hover:text-growseri-green">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
        <!-- Form Section -->
        <div class="md:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Pengiriman</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none transition-colors" placeholder="Cth: Budi Santoso">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                        <input type="text" wire:model.blur="whatsapp_number" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none transition-colors" placeholder="Cth: 08123456789">
                        @error('whatsapp_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea wire:model="shipping_address" rows="3" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none transition-colors" placeholder="Jalan, RT/RW, Patokan Rumah..."></textarea>
                        @error('shipping_address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Location Picker -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Lokasi (Untuk Ongkos Kirim)</h2>
                
                @error('location') 
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4 text-sm font-medium">
                        {{ $message }}
                    </div> 
                @enderror
                
                <div x-data="locationPicker()" x-init="initMap()" class="space-y-4">
                    <!-- Map Container -->
                    <div wire:ignore class="rounded-xl overflow-hidden border border-gray-200 relative z-0">
                        <div id="osm-map" class="w-full h-64"></div>
                        <div class="absolute top-2 right-2 z-[400]">
                            <button @click.prevent="getLocation()" type="button" class="bg-white p-2 rounded-lg shadow-md border border-gray-100 hover:bg-gray-50 text-growseri-green flex items-center space-x-2" title="Gunakan GPS Saya">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                                <span class="text-sm font-bold pr-1">Lacak GPS</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start justify-between bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                        <div>
                            <h3 class="font-bold text-gray-900">Jarak Tempuh (Rute Jalan)</h3>
                            <p class="text-sm text-gray-600">
                                <span x-show="!calculated">Silakan geser Pin Merah di peta ke lokasi Anda</span>
                                <span x-show="calculated" x-cloak class="font-bold text-growseri-green"><span x-text="distance.toFixed(1)"></span> Km</span>
                            </p>
                            <p x-show="calculating" x-cloak class="text-xs text-growseri-yellow mt-1 font-medium flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-growseri-yellow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Menghitung rute...
                            </p>
                            <p x-show="error" x-cloak class="text-xs text-red-500 mt-1" x-text="error"></p>
                        </div>
                        <div class="bg-white px-3 py-1 rounded-lg border border-emerald-200 text-xs font-bold text-emerald-800 flex items-center shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-growseri-green mr-2 animate-pulse"></span>
                            OpenStreetMap
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Metode Pengiriman</h2>
                <div class="space-y-3">
                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer transition-colors border-growseri-green bg-emerald-50">
                        <div class="flex items-center">
                            <input type="radio" wire:model.live="shipping_method" value="kurir_toko" class="w-5 h-5 text-growseri-green focus:ring-growseri-green border-gray-300">
                            <div class="ml-3">
                                <div class="font-medium text-gray-800">Kurir Toko</div>
                                <div class="text-xs text-gray-500">Dikirim menggunakan armada khusus toko</div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">{{ $settings->delivery_label ?? 'Jadwal Pengiriman' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanggal</label>
                        <input type="date" wire:model.live="delivery_date" min="{{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d') }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none transition-colors">
                        @error('delivery_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Waktu</label>
                        @if($settings && $settings->delivery_time_mode === 'time_slot' && $settings->delivery_time_slots)
                            <select wire:model="delivery_time" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none transition-colors">
                                <option value="">-- Pilih Jam --</option>
                                @php
                                    $currentDate = \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d');
                                    $currentTime = \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('H:i');
                                    $isToday = empty($delivery_date) || $delivery_date === $currentDate;
                                @endphp
                                @foreach($settings->delivery_time_slots as $slot)
                                    @php
                                        $isDisabled = $isToday && $currentTime >= $slot['start'];
                                    @endphp
                                    <option value="{{ $slot['start'] }} - {{ $slot['end'] }}" {{ $isDisabled ? 'disabled' : '' }} class="{{ $isDisabled ? 'text-gray-400 bg-gray-50' : '' }}">
                                        {{ $slot['start'] }} - {{ $slot['end'] }} {{ $isDisabled ? '(Waktu telah lewat)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif($settings && $settings->delivery_time_mode === 'date_time')
                            <input type="time" wire:model="delivery_time" min="{{ (empty($delivery_date) || $delivery_date === \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d')) ? \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('H:i') : '' }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none transition-colors">
                        @else
                            <input type="text" wire:model="delivery_time" placeholder="Jam pengiriman fleksibel" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-growseri-green outline-none transition-colors" value="Sesuai antrean" readonly>
                        @endif
                        @error('delivery_time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Metode Pembayaran</h2>
                <div class="space-y-3">
                    @if(!$settings || $settings->payment_transfer_active)
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer transition-colors {{ $payment_method == 'transfer' ? 'border-growseri-green bg-emerald-50' : 'border-gray-200 hover:bg-gray-50' }}">
                        <input type="radio" wire:model.live="payment_method" value="transfer" class="w-5 h-5 text-growseri-green focus:ring-growseri-green border-gray-300">
                        <span class="ml-3 font-medium text-gray-800">Transfer Bank Manual</span>
                    </label>
                    @endif
                    @if(!$settings || $settings->payment_qris_active)
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer transition-colors {{ $payment_method == 'qris' ? 'border-growseri-green bg-emerald-50' : 'border-gray-200 hover:bg-gray-50' }}">
                        <input type="radio" wire:model.live="payment_method" value="qris" class="w-5 h-5 text-growseri-green focus:ring-growseri-green border-gray-300">
                        <span class="ml-3 font-medium text-gray-800">QRIS</span>
                    </label>
                    @endif
                    <label class="flex items-center p-3 border rounded-xl cursor-pointer transition-colors {{ $payment_method == 'cod' ? 'border-growseri-green bg-emerald-50' : 'border-gray-200 hover:bg-gray-50' }}">
                        <input type="radio" wire:model.live="payment_method" value="cod" class="w-5 h-5 text-growseri-green focus:ring-growseri-green border-gray-300">
                        <span class="ml-3 font-medium text-gray-800">Bayar di Tempat (COD)</span>
                    </label>
                </div>
                @error('payment_method')
                    <div class="mt-3 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Summary Section -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-20">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                
                <div class="space-y-4 mb-6 max-h-[40vh] overflow-y-auto pr-2">
                    @foreach($cart as $item)
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium text-gray-800 line-clamp-1 leading-tight">{{ $item['name'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $item['variant_name'] ?? 'Reguler' }} x {{ $item['quantity'] }}</div>
                            @if(!empty($item['note']))
                                <div class="text-xs text-orange-500 mt-1 italic">Catatan: {{ $item['note'] }}</div>
                            @endif
                        </div>
                        <div class="font-bold text-gray-900 whitespace-nowrap ml-4">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="border-t pt-4 space-y-2">
                    <!-- Coupon Section -->
                    <div class="mb-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Punya Kode Kupon?</h3>
                        @if($applied_coupon)
                            <div class="flex items-center justify-between bg-emerald-100 border border-emerald-200 px-3 py-2 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-growseri-green mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-sm font-bold text-growseri-green-dark">{{ $applied_coupon }}</span>
                                </div>
                                <button type="button" wire:click="removeCoupon" class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>
                            </div>
                            @if (session()->has('coupon_message'))
                                <p class="text-xs text-growseri-green mt-1 font-medium">{{ session('coupon_message') }}</p>
                            @endif
                        @else
                            <div class="flex space-x-2">
                                <input type="text" wire:model="coupon_code" placeholder="Masukkan kode promo" class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:ring-growseri-green outline-none text-sm uppercase">
                                <button type="button" wire:click="applyCoupon" class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm">Terapkan</button>
                            </div>
                            @error('coupon') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        @endif
                    </div>
                    
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkos Kirim</span>
                        <span class="text-right">
                            @if($distance_km == 0 && $settings && $settings->shipping_rate_type == 'per_km')
                                <span class="text-xs text-gray-400 block mb-1">Cek jarak dulu</span>
                            @endif
                            @if($is_shipping_discount && $discount_amount > 0)
                                <div class="flex flex-col text-right leading-tight mt-1">
                                    <span class="line-through text-[11px] text-gray-400 mb-0.5">Rp {{ number_format($this->shippingFee, 0, ',', '.') }}</span>
                                    <span class="text-gray-900 font-bold">Rp {{ number_format($this->shippingFee - $discount_amount, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-growseri-green bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 self-end mt-1 font-bold">Hemat Rp {{ number_format($discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @else
                                Rp {{ number_format($this->shippingFee, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    @if($discount_amount > 0 && !$is_shipping_discount)
                    <div class="flex justify-between text-growseri-green font-medium">
                        <span>Diskon ({{ $applied_coupon }})</span>
                        <span>- Rp {{ number_format($discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between font-bold text-lg text-gray-900 pt-3 border-t mt-3">
                        <span>Total Pembayaran</span>
                        <span class="text-growseri-green">Rp {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button wire:click="submitOrder" wire:loading.attr="disabled" class="w-full mt-8 py-4 bg-growseri-green hover:bg-growseri-green-dark text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-200 transition-all active:scale-[0.98] disabled:opacity-70 flex justify-center items-center">
                    <span wire:loading.remove wire:target="submitOrder">Pesan Sekarang</span>
                    <span wire:loading wire:target="submitOrder">Memproses...</span>
                </button>
                <p class="text-xs text-gray-400 text-center mt-4">Dengan melakukan pemesanan, Anda menyetujui syarat & ketentuan Growseri.</p>
            </div>
        </div>
    </div>

    <!-- Leaflet JS & CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('locationPicker', () => ({
                calculating: false,
                calculated: false,
                distance: 0,
                error: '',
                storeLat: {{ $settings->store_latitude ?? -6.200000 }},
                storeLng: {{ $settings->store_longitude ?? 106.816666 }},
                map: null,
                marker: null,
                userLat: null,
                userLng: null,
                
                initMap() {
                    // Initialize map after component mounts
                    setTimeout(() => {
                        this.map = L.map('osm-map').setView([this.storeLat, this.storeLng], 13);
                        
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(this.map);
                        
                        // Initial position for Buyer Marker (will be updated via GPS)
                        this.userLat = this.storeLat;
                        this.userLng = this.storeLng;
                        
                        this.marker = L.marker([this.userLat, this.userLng], {
                            draggable: true,
                            icon: L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                shadowSize: [41, 41]
                            })
                        }).addTo(this.map).bindPopup('<b>Lokasi Anda</b><br>Geser pin ini ke lokasi pengiriman!');
                        
                        this.marker.on('dragend', (event) => {
                            var position = this.marker.getLatLng();
                            this.userLat = position.lat;
                            this.userLng = position.lng;
                            this.calculateRoutingDistance();
                        });
                        
                        // Automatically try to get user's current GPS location
                        this.getLocation();
                    }, 100);
                },
                
                getLocation() {
                    this.calculating = true;
                    this.error = '';
                    
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.userLat = position.coords.latitude;
                                this.userLng = position.coords.longitude;
                                
                                this.marker.setLatLng([this.userLat, this.userLng]);
                                this.map.flyTo([this.userLat, this.userLng], 15);
                                this.marker.openPopup();
                                
                                this.calculateRoutingDistance();
                            },
                            (error) => {
                                this.calculating = false;
                                this.error = 'Gagal mengakses lokasi GPS. Silakan geser pin secara manual.';
                            },
                            { enableHighAccuracy: true, timeout: 10000 }
                        );
                    } else {
                        this.calculating = false;
                        this.error = 'Browser Anda tidak mendukung fitur lokasi.';
                    }
                },
                
                async calculateRoutingDistance() {
                    this.calculating = true;
                    this.error = '';
                    
                    try {
                        // OSRM Public API (Format: lon,lat)
                        const response = await fetch(`https://router.project-osrm.org/route/v1/driving/${this.storeLng},${this.storeLat};${this.userLng},${this.userLat}?overview=false`);
                        const data = await response.json();
                        
                        if(data.code === 'Ok' && data.routes.length > 0) {
                            this.distance = data.routes[0].distance / 1000; // convert meters to km
                            this.calculated = true;
                            
                            @this.updateLocation(this.userLat, this.userLng, this.distance);
                        } else {
                            this.fallbackToHaversine();
                        }
                    } catch(e) {
                        this.fallbackToHaversine();
                    }
                    
                    this.calculating = false;
                },
                
                fallbackToHaversine() {
                    const R = 6371; 
                    const dLat = this.deg2rad(this.userLat - this.storeLat);
                    const dLon = this.deg2rad(this.userLng - this.storeLng); 
                    const a = 
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(this.deg2rad(this.storeLat)) * Math.cos(this.deg2rad(this.userLat)) * 
                    Math.sin(dLon/2) * Math.sin(dLon/2); 
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                    
                    // Add 30% padding to simulate actual road distance instead of straight line
                    this.distance = (R * c) * 1.3; 
                    this.calculated = true;
                    
                    @this.updateLocation(this.userLat, this.userLng, this.distance);
                },
                
                deg2rad(deg) {
                    return deg * (Math.PI/180)
                }
            }))
        })
    </script>
</div>
