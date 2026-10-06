<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-center text-3xl font-extrabold text-growseri-green">
                <span class="text-growseri-yellow">Grow</span>seri
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Login ke Panel Admin
            </p>
        </div>
        <form class="mt-8 space-y-6" wire:submit="login">
            <div class="space-y-4">
                <div>
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" type="email" wire:model="email" required class="appearance-none relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-growseri-green focus:border-growseri-green sm:text-sm" placeholder="Alamat Email">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" type="password" wire:model="password" required class="appearance-none relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-growseri-green focus:border-growseri-green sm:text-sm" placeholder="Password">
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-growseri-green hover:bg-growseri-green-dark focus:outline-none shadow-md transition-colors">
                    <span wire:loading.remove wire:target="login">Masuk</span>
                    <span wire:loading wire:target="login">Memeriksa...</span>
                </button>
            </div>
            <div class="text-center mt-4">
                <a href="/" class="text-xs text-gray-400 hover:text-growseri-green transition-colors">&larr; Kembali ke halaman utama</a>
            </div>
        </form>
    </div>
</div>
