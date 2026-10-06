<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Growseri - Toko Sembako Online' }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-gray-50 text-gray-800 font-sans antialiased pb-20 md:pb-0">
        @if(request()->is('admin/*'))
        <!-- Admin Header -->
        <header class="bg-white shadow-sm sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <a href="/admin/products" class="text-xl font-bold text-growseri-green tracking-tight flex items-center">
                        @php $settings = \App\Models\StoreSetting::first(); @endphp
                        @if($settings && $settings->logo_path)
                            <img src="{{ asset($settings->logo_path) }}" alt="{{ $settings->store_name ?? 'Growseri' }}" class="h-8 object-contain mr-2">
                        @else
                            <span class="text-growseri-yellow">Grow</span>seri Admin
                        @endif
                    </a>
                    
                    <nav class="hidden md:flex space-x-4">
                        <a href="/admin/orders" class="text-sm font-bold {{ request()->is('admin/orders') ? 'text-growseri-green' : 'text-gray-500 hover:text-gray-900' }}">Pesanan</a>
                        <a href="/admin/products" class="text-sm font-bold {{ request()->is('admin/products') ? 'text-growseri-green' : 'text-gray-500 hover:text-gray-900' }}">Produk</a>
                        <a href="/admin/coupons" class="text-sm font-bold {{ request()->is('admin/coupons') ? 'text-growseri-green' : 'text-gray-500 hover:text-gray-900' }}">Kupon</a>
                        <a href="/admin/settings" class="text-sm font-bold {{ request()->is('admin/settings') ? 'text-growseri-green' : 'text-gray-500 hover:text-gray-900' }}">Pengaturan</a>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Toggle View Mode for Storefront -->
                    <form method="POST" action="/admin/set-view-mode" class="hidden md:flex bg-gray-100 p-1 rounded-xl border border-gray-200" x-data>
                        @csrf
                        <input type="hidden" name="mode" x-ref="modeInput" value="{{ session('view_mode', 'mobile') }}">
                        <button type="button" @click="$refs.modeInput.value = 'desktop'; $el.closest('form').submit()" class="px-3 py-1.5 rounded-lg text-sm font-bold flex items-center transition-all {{ session('view_mode', 'mobile') === 'desktop' ? 'bg-white text-growseri-green shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Desktop
                        </button>
                        <button type="button" @click="$refs.modeInput.value = 'mobile'; $el.closest('form').submit()" class="px-3 py-1.5 rounded-lg text-sm font-bold flex items-center transition-all {{ session('view_mode', 'mobile') === 'mobile' ? 'bg-white text-growseri-green shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Mobile
                        </button>
                    </form>
                    
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-bold flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </header>
        @endif

        <!-- Main Content -->
        <main class="w-full">
            {{ $slot }}
        </main>

        @livewireScripts
    </body>
</html>
