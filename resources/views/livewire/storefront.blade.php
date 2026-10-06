<div class="transition-all duration-300 mx-auto min-h-screen {{ $viewMode === 'mobile' ? 'max-w-md bg-white border-x border-gray-100 shadow-xl px-4 py-6' : 'max-w-7xl px-4 sm:px-6 lg:px-8 py-6' }}">
    
    <!-- Header -->
    <header class="flex items-center justify-between gap-3 mb-5 pt-2">
        <!-- Logo -->
        <div class="flex-shrink-0">
            @if($this->settings && $this->settings->logo_path)
                <img src="{{ asset($this->settings->logo_path) }}" alt="{{ $this->settings->store_name ?? 'Growseri' }}" class="h-8 object-contain">
            @else
                <h1 class="text-xl md:text-2xl font-extrabold text-growseri-green tracking-tight">{{ $this->settings->store_name ?? 'Growseri' }}</h1>
            @endif
        </div>
        
        <!-- Search -->
        <div class="flex-1 max-w-lg relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari sembako..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-growseri-green/50 focus:border-growseri-green focus:bg-white text-sm shadow-sm transition-all">
        </div>
        
        <!-- Store Status & User -->
        <div class="flex items-center space-x-2 md:space-x-3">
            <div class="flex items-center space-x-1.5 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-full shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-growseri-green"></span>
                </span>
                <span class="text-xs font-bold text-growseri-green-dark uppercase tracking-wide">Buka</span>
            </div>
            <button wire:click="openHistoryModal" class="p-2 text-gray-500 hover:text-growseri-green transition-colors bg-white border border-gray-100 rounded-full shadow-sm hover:shadow" title="Riwayat Pesanan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </button>
        </div>
    </header>

    <!-- Hero Banner Slider -->
    @if(count($promo_banners) > 0)
    <div class="mb-5 relative rounded-2xl overflow-hidden shadow-sm group" x-data="{ currentSlide: 1, maxSlide: {{ count($promo_banners) }}, autoplay: {{ $is_slider_autoplay ? 'true' : 'false' }}, interval: null }"
         x-init="if(autoplay && maxSlide > 1) { interval = setInterval(() => { currentSlide = currentSlide < maxSlide ? currentSlide + 1 : 1 }, 4000) }"
         @mouseenter="if(interval) clearInterval(interval)"
         @mouseleave="if(autoplay && maxSlide > 1) { interval = setInterval(() => { currentSlide = currentSlide < maxSlide ? currentSlide + 1 : 1 }, 4000) }">
        <div class="flex transition-transform duration-500 ease-in-out items-start" :style="'transform: translateX(-' + ((currentSlide - 1) * 100) + '%)'">
            @foreach($promo_banners as $banner)
            <div class="w-full flex-shrink-0">
                @if(!empty($banner['image']))
                    @if(!empty($banner['url']))
                        <a href="{{ $banner['url'] }}" target="_blank" class="block w-full">
                            <img src="{{ Storage::url($banner['image']) }}" class="w-full h-auto object-contain" alt="Promo Banner">
                        </a>
                    @else
                        <img src="{{ Storage::url($banner['image']) }}" class="w-full h-auto object-contain" alt="Promo Banner">
                    @endif
                @else
                    <div class="w-full aspect-[21/9] md:aspect-[21/6] bg-gray-200 flex items-center justify-center text-gray-400">
                        Belum ada gambar
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        @if(count($promo_banners) > 1)
        <!-- Slider Controls -->
        <button @click="currentSlide = currentSlide > 1 ? currentSlide - 1 : maxSlide" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/20 hover:bg-black/40 backdrop-blur-md text-white p-1.5 md:p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button @click="currentSlide = currentSlide < maxSlide ? currentSlide + 1 : 1" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/20 hover:bg-black/40 backdrop-blur-md text-white p-1.5 md:p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
        <!-- Indicators -->
        <div class="absolute bottom-2 md:bottom-4 left-1/2 -translate-x-1/2 flex space-x-1.5">
            <template x-for="i in maxSlide">
                <button @click="currentSlide = i" :class="currentSlide === i ? 'w-5 md:w-6 bg-white' : 'w-1.5 md:w-2 bg-white/50'" class="h-1.5 md:h-2 rounded-full transition-all duration-300 shadow-sm"></button>
            </template>
        </div>
        @endif
    </div>
    @endif

    <!-- Clickable Info Accordion -->
    @if(count($info_accordion) > 0)
    <div class="mb-6 space-y-2">
        <h2 class="text-sm font-bold text-gray-800 mb-3 px-1">Informasi Penting & Artikel</h2>
        @foreach($info_accordion as $info)
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden" x-data="{ open: false }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-left focus:outline-none hover:bg-gray-50 transition-colors">
                <div class="flex items-center text-gray-700 font-bold text-sm">
                    <div class="bg-emerald-100 text-emerald-600 p-1.5 rounded-lg mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    {{ $info['title'] ?? '' }}
                </div>
                <div class="bg-white border border-gray-200 rounded-full p-1 shadow-sm">
                    <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </button>
            <div x-show="open" 
                 x-collapse 
                 x-cloak 
                 class="px-5 py-4 text-sm text-gray-600 bg-gray-50 border-t border-gray-100">
                <p class="leading-relaxed">{!! nl2br(e($info['description'] ?? '')) !!}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Grid Banners (2x2) -->
    @if(count($grid_banners) > 0)
    <div class="mb-6 grid grid-cols-2 gap-3 md:gap-4">
        @foreach($grid_banners as $banner)
            <div class="rounded-xl overflow-hidden hover:shadow-md transition-shadow relative bg-transparent flex items-center justify-center">
                @if(!empty($banner['link']))
                    <a href="{{ $banner['link'] }}" class="w-full flex items-center justify-center">
                        <img src="{{ Storage::url($banner['image']) }}" alt="Grid Banner" class="w-full h-auto object-contain hover:scale-105 transition-transform duration-300 rounded-xl">
                    </a>
                @else
                    <img src="{{ Storage::url($banner['image']) }}" alt="Grid Banner" class="w-full h-auto object-contain hover:scale-105 transition-transform duration-300 rounded-xl">
                @endif
            </div>
        @endforeach
    </div>
    @endif

    <!-- Categories (Horizontal Scroll) -->
    <div class="flex overflow-x-auto pb-4 space-x-2 md:space-x-3 mb-2 no-scrollbar">
        <button wire:click="selectCategory('Semua')" class="px-4 py-1.5 rounded-full font-bold whitespace-nowrap text-sm shadow-sm transition-all border {{ $selectedCategory === 'Semua' ? 'bg-growseri-green text-white border-growseri-green scale-105' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50 hover:text-gray-900' }}">Semua</button>
        @foreach($categories as $category)
            <button wire:click="selectCategory('{{ $category }}')" class="px-4 py-1.5 rounded-full font-bold whitespace-nowrap text-sm shadow-sm transition-all border {{ $selectedCategory === $category ? 'bg-growseri-green text-white border-growseri-green scale-105' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50 hover:text-gray-900' }}">{{ $category }}</button>
        @endforeach
    </div>

    <!-- Product Grid -->
    <div class="grid {{ $viewMode === 'mobile' ? 'grid-cols-2 gap-3' : 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4' }}">
        @forelse($this->products as $product)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200 flex flex-col relative">
                <div wire:click="openPopup({{ $product->id }})" class="w-full relative bg-gray-50 flex items-center justify-center cursor-pointer">
                    @if($product->image_url)
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="w-full h-auto max-h-64 object-contain">
                    @else
                        <!-- Placeholder for image -->
                        <div class="py-12 flex items-center justify-center text-gray-400 w-full">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>
                <div class="p-3 flex-1 flex flex-col justify-between">
                    <div wire:click="openPopup({{ $product->id }})" class="cursor-pointer">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 leading-tight">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $product->description }}</p>
                    </div>
                    <div class="mt-3 flex justify-between items-center relative">
                        <div class="font-bold text-growseri-green">
                            @if($product->has_variants && $product->variants->isNotEmpty())
                                <span class="text-[10px] text-gray-500 font-normal leading-tight block">Mulai</span>
                                <span class="text-[13px] sm:text-sm">Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }}</span>
                            @else
                                @if($product->is_discount_active)
                                    <div class="flex flex-col leading-tight">
                                        <span class="text-[10px] text-gray-400 line-through">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                                        <span class="text-[13px] sm:text-sm">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                    </div>
                                @else
                                    <span class="text-[13px] sm:text-sm">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                                @endif
                            @endif
                        </div>
                        
                        @php
                            $cartKey = $product->id . '_0';
                            $inCart = isset($cart[$cartKey]) && !$product->has_variants;
                            
                            $isOutOfStock = $product->is_out_of_stock || 
                                (!$product->has_variants && $product->stock <= 0) || 
                                ($product->has_variants && $product->variants->every(fn($v) => $v->is_out_of_stock || $v->stock <= 0));
                        @endphp
                        
                        @if($isOutOfStock)
                            <span class="text-red-500 font-bold text-[11px] bg-red-50 px-2 py-1 rounded border border-red-100 ml-2">Habis</span>
                        @elseif($inCart)
                            <div class="flex items-center space-x-1.5 bg-growseri-green/10 rounded-full px-1.5 py-1 ml-2 border border-growseri-green/30">
                                <button wire:click.stop="decrementCartItem('{{ $cartKey }}')" class="w-6 h-6 flex items-center justify-center rounded-full bg-white text-growseri-green font-bold shadow-sm active:scale-95">-</button>
                                <span class="text-xs font-bold text-growseri-green w-4 text-center">{{ $cart[$cartKey]['quantity'] }}</span>
                                <button wire:click.stop="incrementCartItem('{{ $cartKey }}')" class="w-6 h-6 flex items-center justify-center rounded-full bg-growseri-green text-white font-bold shadow-sm active:scale-95">+</button>
                            </div>
                        @else
                            <button wire:click.stop="quickAddToCart({{ $product->id }})" class="w-7 h-7 flex-shrink-0 bg-growseri-green hover:bg-growseri-green-dark text-white rounded-full flex items-center justify-center shadow-sm transition-transform active:scale-95 ml-2" title="Tambah ke Keranjang">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        @endif

                        @if(session()->has('cart_error_' . $product->id))
                            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 7000)" x-show="show" x-transition.opacity.duration.500ms class="absolute -top-7 right-0 text-[10px] text-red-500 font-bold bg-red-50 px-2 py-1 rounded shadow-sm border border-red-100 whitespace-nowrap pointer-events-none">
                                {{ session('cart_error_' . $product->id) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500">
                Belum ada produk yang tersedia saat ini.
            </div>
        @endforelse
    </div>

    <!-- Popup Modal (GoFood style) -->
    @if($showPopup && $selectedProduct)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
            <!-- Backdrop -->
            <div wire:click="closePopup" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
            
            <!-- Modal Content -->
            <div class="bg-white w-full sm:w-[480px] max-h-[90vh] rounded-t-3xl sm:rounded-3xl shadow-2xl relative z-10 flex flex-col transform transition-transform animate-slide-up sm:animate-fade-in overflow-hidden">
                <!-- Share Button -->
                <button onclick="if(navigator.share){navigator.share({title: '{{ $selectedProduct->name }}', text: 'Cek {{ $selectedProduct->name }} di Growseri!', url: window.location.href}).catch(console.error);}else{alert('Fitur share tidak didukung di browser ini.');}" class="absolute top-4 left-4 bg-white/80 p-2 rounded-full shadow-sm text-gray-600 hover:text-gray-900 z-10 transition-transform hover:scale-105 active:scale-95" title="Bagikan Produk">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                </button>
                
                <!-- Close Button -->
                <button wire:click="closePopup" class="absolute top-4 right-4 bg-white/80 p-2 rounded-full shadow-sm text-gray-600 hover:text-gray-900 z-10 transition-transform hover:scale-105 active:scale-95" title="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="overflow-y-auto flex-1 w-full">
                    <!-- Product Image -->
                    <div class="w-full relative flex-shrink-0 bg-gray-50 flex items-center justify-center">
                        @if($selectedProduct->image_url)
                            <img src="{{ asset($selectedProduct->image_url) }}" alt="{{ $selectedProduct->name }}" class="w-full h-auto max-h-[50vh] object-contain">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $selectedProduct->name }}</h2>
                    <p class="text-gray-500 mt-2 text-sm leading-relaxed">{{ $selectedProduct->description ?? 'Tidak ada deskripsi.' }}</p>

                    <!-- Variants Selection -->
                    @if($selectedProduct->has_variants && $selectedProduct->variants->isNotEmpty())
                        <div class="mt-6">
                            <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">Pilih Varian</h3>
                            <div class="space-y-2">
                                @foreach($selectedProduct->variants as $variant)
                                    @php
                                        $isVariantOutOfStock = $variant->is_out_of_stock || $variant->stock <= 0;
                                    @endphp
                                    <label class="flex items-center justify-between p-3 border rounded-xl transition-colors {{ $isVariantOutOfStock ? 'opacity-50 cursor-not-allowed bg-gray-50' : ($selectedVariant == $variant->id ? 'border-growseri-green bg-emerald-50 cursor-pointer' : 'border-gray-200 hover:bg-gray-50 cursor-pointer') }}">
                                        <div class="flex items-center">
                                            <input type="radio" wire:model.live="selectedVariant" value="{{ $variant->id }}" class="w-5 h-5 text-growseri-green focus:ring-growseri-green border-gray-300" {{ $isVariantOutOfStock ? 'disabled' : '' }}>
                                            <span class="ml-3 font-medium text-gray-800">{{ $variant->variant_name }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="font-bold {{ $isVariantOutOfStock ? 'text-gray-500' : 'text-gray-900' }}">Rp {{ number_format($variant->price, 0, ',', '.') }}</span>
                                            @if($isVariantOutOfStock)
                                                <span class="block text-xs font-bold text-red-500">Habis</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-4">
                            <h3 class="text-sm font-bold text-gray-900 mb-1 uppercase tracking-wide">Harga</h3>
                            <div class="text-xl font-bold text-gray-900">Rp {{ number_format($selectedProduct->base_price, 0, ',', '.') }}</div>
                        </div>
                    @endif

                    <!-- Buyer Note -->
                    <div class="mt-6 border-t pt-6">
                        <label class="block text-sm font-bold text-gray-900 mb-2">Catatan Pembeli <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" wire:model="itemNote" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-growseri-green focus:border-growseri-green outline-none text-sm placeholder-gray-400" placeholder="Contoh: Tolong pilihkan yang masih segar / bungkus terpisah">
                    </div>

                    <!-- Quantity -->
                    <div class="mt-6 flex items-center justify-center border-t pt-6 pb-2">
                        <div class="flex items-center space-x-6 bg-gray-50 p-1.5 rounded-full border border-gray-200 shadow-inner">
                            <button wire:click="decrementQuantity" class="w-10 h-10 rounded-full flex items-center justify-center bg-white text-growseri-green shadow hover:shadow-md hover:bg-gray-50 transition-all disabled:opacity-50" {{ $quantity <= 1 ? 'disabled' : '' }}>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                            <span class="font-bold text-xl w-8 text-center text-gray-900">{{ $quantity }}</span>
                            <button wire:click="incrementQuantity" class="w-10 h-10 rounded-full flex items-center justify-center bg-white text-growseri-green shadow hover:shadow-md hover:bg-gray-50 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                    </div>
                    </div>
                </div>
                
                <!-- Action Button -->
                <div class="p-4 border-t bg-white">
                    <button wire:click="addToCart" class="w-full py-4 bg-growseri-green hover:bg-growseri-green-dark text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-200 transition-all active:scale-[0.98] flex justify-between items-center px-6">
                        <span>Tambah ke Keranjang</span>
                        <span>
                            @php
                                $displayPrice = $selectedProduct->has_variants 
                                    ? ($selectedProduct->variants->find($selectedVariant)?->price ?? 0) 
                                    : $selectedProduct->base_price;
                            @endphp
                            Rp {{ number_format($displayPrice * $quantity, 0, ',', '.') }}
                        </span>
                    </button>
                    @error('quantity') <span x-data="{ show: true }" x-init="setTimeout(() => show = false, 7000)" x-show="show" x-transition.opacity.duration.500ms class="text-red-500 text-sm mt-2 block text-center font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="mt-8 pt-8 pb-8 border-t border-gray-100 bg-gray-50 text-center px-4">
        <div class="flex justify-center mb-4">
            @if($this->settings && $this->settings->logo_path)
                <img src="{{ asset($this->settings->logo_path) }}" alt="{{ $this->settings->store_name ?? 'Growseri' }}" class="h-10 object-contain">
            @else
                <h4 class="font-black text-gray-900 text-base tracking-tight"><span class="text-growseri-green"><span class="text-growseri-yellow">Grow</span>seri</span></h4>
            @endif
        </div>
        <p class="text-xs text-gray-500 mb-6 px-4">Belanja kebutuhan harian lebih hemat, mudah, dan cepat langsung dari genggaman Anda.</p>
        
        <div class="flex flex-wrap justify-center gap-x-5 gap-y-3 text-[11px] font-bold text-gray-600 mb-6 uppercase tracking-wide">
            <a href="#" class="hover:text-growseri-green transition-colors">Syarat & Ketentuan</a>
            <a href="#" class="hover:text-growseri-green transition-colors">Kebijakan Privasi</a>
            <a href="#" class="hover:text-growseri-green transition-colors">Pusat Bantuan</a>
            <a href="#" class="hover:text-growseri-green transition-colors">Hubungi Kami</a>
        </div>
        
        <p class="text-[10px] text-gray-400 font-medium">&copy; {{ date('Y') }} Growseri. Hak Cipta Dilindungi.</p>
    </div>

    <!-- Floating Cart (Bottom Bar) -->
    @if(count($cart) > 0)
        <!-- Spacer to prevent cart from hiding bottom content -->
        <div class="h-24 w-full"></div>
        <div class="fixed bottom-6 left-0 right-0 z-40 flex justify-center pointer-events-none px-4">
            <div wire:click="openCartModal" class="bg-growseri-green text-white rounded-full py-2.5 px-5 flex items-center justify-between shadow-2xl pointer-events-auto cursor-pointer hover:bg-growseri-green-dark transition-transform hover:scale-105 active:scale-95 border border-emerald-400 w-auto min-w-[280px] max-w-sm">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <svg class="w-6 h-6 text-growseri-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="absolute -top-1.5 -right-1.5 bg-growseri-yellow text-growseri-green text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full">{{ $this->cartItemCount }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-base leading-none">Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="flex items-center font-bold text-sm bg-white/20 px-4 py-1.5 rounded-full ml-4">
                    Pesanan
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </div>
            </div>
        </div>
    @endif

    <!-- Cart Modal -->
    @if($showCartModal)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
            <div wire:click="closeCartModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
            
            <div class="bg-white w-full sm:w-[480px] max-h-[90vh] rounded-t-3xl sm:rounded-3xl shadow-2xl relative z-10 flex flex-col transform transition-transform animate-slide-up sm:animate-fade-in overflow-hidden">
                <!-- Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="font-bold text-lg text-gray-900">Pesanan Sementara</h3>
                    <button wire:click="closeCartModal" class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body (Scrollable) -->
                <div class="overflow-y-auto p-4 flex-1 space-y-4">
                    @foreach($cart as $key => $item)
                        <div class="flex items-start bg-gray-50 p-3 rounded-2xl border border-gray-100">
                            <div class="ml-1 flex-1 flex flex-col justify-between">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $item['name'] }}</h4>
                                    @if($item['variant_name'])
                                        <p class="text-xs text-growseri-green font-medium">{{ $item['variant_name'] }}</p>
                                    @endif
                                    <p class="font-bold text-gray-900 text-sm mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    @if(session()->has('cart_error_' . $item['product_id']))
                                        <p x-data="{ show: true }" x-init="setTimeout(() => show = false, 7000)" x-show="show" x-transition.opacity.duration.500ms class="text-xs text-red-500 font-bold mt-1">{{ session('cart_error_' . $item['product_id']) }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <button wire:click="removeCartItem('{{ $key }}')" class="text-xs text-red-500 font-medium hover:text-red-700">Hapus</button>
                                    
                                    <div class="flex items-center space-x-2 bg-growseri-green/10 rounded-full px-2 py-1 border border-growseri-green/30">
                                        <button wire:click="decrementCartItem('{{ $key }}')" class="w-6 h-6 flex items-center justify-center rounded-full bg-white text-growseri-green font-bold shadow-sm active:scale-95">-</button>
                                        <span class="text-xs font-bold text-growseri-green w-4 text-center">{{ $item['quantity'] }}</span>
                                        <button wire:click="incrementCartItem('{{ $key }}')" class="w-6 h-6 flex items-center justify-center rounded-full bg-growseri-green text-white font-bold shadow-sm active:scale-95">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Footer -->
                <div class="p-4 border-t bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-600 font-medium text-sm">Total Belanja</span>
                        <span class="font-bold text-xl text-gray-900">Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</span>
                    </div>
                    <button wire:click="goToCheckout" class="w-full py-3.5 bg-growseri-green hover:bg-growseri-green-dark text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-200 transition-all active:scale-[0.98]">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Order History Modal -->
    @if($showHistoryModal)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
        <!-- Backdrop -->
        <div wire:click="closeHistoryModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
        
        <!-- Modal Content -->
        <div class="bg-white w-full sm:w-[500px] max-h-[90vh] rounded-t-3xl sm:rounded-3xl shadow-2xl relative z-10 flex flex-col transform transition-transform animate-slide-up sm:animate-fade-in overflow-hidden">
            <!-- Header -->
            <div class="bg-white px-6 py-5 border-b border-gray-100 flex justify-between items-center z-10 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-growseri-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Riwayat Pesanan
                </h3>
                <button wire:click="closeHistoryModal" class="text-gray-400 hover:text-gray-900 p-2 bg-gray-50 rounded-full hover:bg-gray-100 transition-colors shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 bg-gray-50/50">
                <!-- Search Form -->
                <div class="mb-6 bg-emerald-50/80 rounded-2xl p-5 border border-emerald-100 shadow-sm">
                    <label class="block text-sm font-bold text-growseri-green-dark mb-3">Masukkan Nomor WhatsApp Anda</label>
                    <div class="flex space-x-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <input type="text" wire:model="historyPhone" placeholder="Contoh: 08123456789" class="w-full pl-10 pr-4 py-3 rounded-xl border border-emerald-200 focus:ring-2 focus:ring-growseri-green focus:border-growseri-green outline-none bg-white shadow-sm font-medium">
                        </div>
                        <button wire:click="checkHistory" class="px-5 py-3 bg-growseri-green hover:bg-growseri-green-dark text-white rounded-xl font-bold shadow-md transition-transform active:scale-[0.98] whitespace-nowrap flex items-center">
                            <span wire:loading.remove wire:target="checkHistory">Cari</span>
                            <span wire:loading wire:target="checkHistory">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </span>
                        </button>
                    </div>
                    @error('historyPhone') <span class="text-red-500 text-xs mt-2 font-medium flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</span> @enderror
                </div>

                <!-- Results -->
                @if($hasSearchedHistory)
                    @if(count($historyOrders) > 0)
                        <div class="space-y-4 pb-2">
                            @foreach($historyOrders as $order)
                                <a href="{{ $order->order_status !== 'pending' ? route('order.receipt', $order->id) : route('success', ['invoice' => $order->invoice_number]) }}" 
                                   {{ $order->order_status !== 'pending' ? 'target="_blank"' : '' }}
                                   class="block bg-white border border-gray-200 rounded-2xl p-5 shadow-sm relative overflow-hidden group hover:border-emerald-300 cursor-pointer transition-colors">
                                    <!-- Colored Top Border based on status -->
                                    <div class="absolute top-0 left-0 w-full h-1.5 
                                        {{ $order->order_status === 'pending' ? 'bg-yellow-400' : '' }}
                                        {{ $order->order_status === 'processing' ? 'bg-blue-400' : '' }}
                                        {{ $order->order_status === 'shipped' ? 'bg-purple-400' : '' }}
                                        {{ $order->order_status === 'completed' ? 'bg-emerald-500' : '' }}
                                        {{ $order->order_status === 'cancelled' ? 'bg-red-400' : '' }}">
                                    </div>
                                    
                                    <div class="flex justify-between items-start mb-4 mt-1">
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1 flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $order->created_at->format('d M Y, H:i') }}
                                            </div>
                                            <div class="font-black text-gray-900 tracking-tight">{{ $order->invoice_number }}</div>
                                        </div>
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm uppercase tracking-wider
                                            {{ $order->order_status === 'pending' ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : '' }}
                                            {{ $order->order_status === 'processing' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                            {{ $order->order_status === 'shipped' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                                            {{ $order->order_status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                            {{ $order->order_status === 'cancelled' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}">
                                            {{ $order->order_status === 'pending' ? 'Baru' : 
                                               ($order->order_status === 'processing' ? 'Diproses' : 
                                               ($order->order_status === 'shipped' ? 'Dikirim' : 
                                               ($order->order_status === 'completed' ? 'Selesai' : 'Batal'))) }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2.5 mb-4 border-y border-dashed border-gray-200 py-3">
                                        @foreach($order->items as $item)
                                            <div class="flex justify-between text-sm">
                                                <div class="text-gray-700 text-sm line-clamp-1 flex-1 pr-4">
                                                    <span class="font-bold text-gray-900 bg-gray-100 px-1.5 py-0.5 rounded mr-1">{{ $item->quantity }}x</span> 
                                                    {{ $item->product_name }}
                                                    @if($item->variant_name)
                                                        <span class="text-xs text-gray-400">({{ $item->variant_name }})</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($order->order_status === 'completed' && $order->proof_of_delivery_path)
                                        <div class="mt-4 mb-2 bg-emerald-50 rounded-xl p-3 border border-emerald-100 flex items-start">
                                            <div class="mr-3 mt-0.5 text-emerald-500">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-emerald-800">Telah Diantar dan Diterima Pembeli</div>
                                                <div class="text-xs text-emerald-600 mt-1 mb-2">Terima kasih telah berbelanja di Growseri!</div>
                                                <a href="{{ asset($order->proof_of_delivery_path) }}" target="_blank" class="block w-24 h-24 rounded-lg overflow-hidden border-2 border-emerald-200 shadow-sm">
                                                    <img src="{{ asset($order->proof_of_delivery_path) }}" alt="Bukti Penerimaan" class="w-full h-full object-cover">
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="flex justify-between items-center bg-gray-50 -mx-5 -mb-5 px-5 py-3 rounded-b-xl border-t border-gray-100">
                                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Belanja</div>
                                        <div class="font-black text-growseri-green text-lg">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <h4 class="text-gray-900 font-bold mb-1">Tidak Ada Pesanan</h4>
                            <p class="text-gray-500 text-sm px-4">Kami tidak menemukan riwayat belanja untuk nomor WhatsApp tersebut.</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12 flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-gray-400 text-sm font-medium">Cari riwayat untuk melihat detail pesanan Anda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif



    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        @keyframes slide-up {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-slide-up {
            animation: slide-up 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .sm\:animate-fade-in {
            @media (min-width: 640px) {
                animation: fade-in 0.2s ease-out;
            }
        }
    </style>
</div>
