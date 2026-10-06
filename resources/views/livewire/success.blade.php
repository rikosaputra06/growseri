<div class="max-w-2xl mx-auto text-center py-12">
    <div class="w-24 h-24 bg-emerald-100 text-growseri-green rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    </div>
    
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Berhasil!</h1>
    <p class="text-gray-500 mb-8">Terima kasih telah berbelanja di Growseri. Pesanan Anda sedang kami proses.</p>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-left mb-8 inline-block w-full max-w-md">
        <div class="flex justify-between items-center mb-4 border-b pb-4">
            <span class="text-gray-500">Nomor Pesanan</span>
            <span class="font-bold text-gray-900">{{ $invoice }}</span>
        </div>
        
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-500">Total Pembayaran</span>
                <span class="font-bold text-growseri-green text-xl">Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-500">Metode Pembayaran</span>
                <span class="font-medium text-gray-900 uppercase">
                    @if(isset($order))
                        {{ $order->payment_method == 'transfer' ? 'Transfer Bank' : ($order->payment_method == 'cod' ? 'Bayar di Tempat' : 'QRIS') }}
                    @endif
                </span>
            </div>
        </div>

        @if(isset($order) && $order->payment_method == 'transfer')
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <p class="text-sm text-yellow-800 font-medium mb-3">Pilih rekening bank tujuan transfer:</p>
                @if($settings && is_array($settings->payment_transfer_details) && count($settings->payment_transfer_details) > 0)
                    <select wire:model.live="selectedBank" class="w-full px-4 py-3 rounded-xl border border-yellow-300 bg-white focus:ring-yellow-500 focus:border-yellow-500 outline-none mb-4 text-gray-700 font-medium shadow-sm cursor-pointer">
                        @foreach($settings->payment_transfer_details as $index => $b)
                            <option value="{{ $index }}">{{ $b['bank_name'] }}</option>
                        @endforeach
                    </select>

                    @php
                        $bank = $settings->payment_transfer_details[$selectedBank] ?? $settings->payment_transfer_details[0];
                    @endphp
                    
                    <div class="bg-white p-4 rounded-xl border border-yellow-300 shadow-sm text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-yellow-400"></div>
                        <div class="text-sm text-gray-500 font-medium mb-1">Nomor Rekening</div>
                        <div class="font-black text-gray-900 text-2xl tracking-wider mb-1">{{ $bank['account_number'] }}</div>
                        <div class="text-sm text-gray-700">a.n. <span class="font-bold">{{ $bank['account_name'] }}</span></div>
                    </div>
                @else
                    <div class="font-bold text-gray-900 text-lg">Rekening belum diatur</div>
                @endif
            </div>
        @elseif(isset($order) && $order->payment_method == 'qris')
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
                <p class="text-sm text-yellow-800 font-medium mb-2">Silakan scan QRIS berikut:</p>
                @if($settings && $settings->payment_qris_image_path)
                    <img src="{{ asset($settings->payment_qris_image_path) }}" alt="QRIS" class="w-full max-w-[240px] mx-auto rounded-xl shadow-sm border border-gray-200 mb-2">
                @else
                    <div class="w-48 h-48 bg-white border-2 border-gray-200 mx-auto rounded-xl flex items-center justify-center p-2 mb-2">
                        <svg class="w-32 h-32 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h6v6H4V4zm2 2v2h2V6H6zm10-2h6v6h-6V4zm2 2v2h2V6h-2zM4 14h6v6H4v-6zm2 2v2h2v-2H6zm10-2h6v6h-6v-6zm2 2v2h2v-2h-2zm-6-2h2v2h-2v-2zm-2 2h2v2h-2v-2zm-2 2h2v2h-2v-2zm2 2h2v2h-2v-2zm4-4h2v2h-2v-2zm2 2h2v2h-2v-2zm-4 4h2v2h-2v-2zm2 2h2v2h-2v-2z"></path></svg>
                    </div>
                @endif
                <p class="text-xs text-gray-600">Growseri QRIS Payment</p>
            </div>
        @endif
    </div>

    <div class="space-y-4 flex flex-col items-center">
        @if($this->wa_url)
            <a href="{{ $this->wa_url }}" target="_blank" id="wa-btn" class="inline-flex items-center justify-center w-full max-w-sm px-8 py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold shadow-lg shadow-green-200 transition-all active:scale-[0.98]">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                Kirim Pesanan ke WhatsApp
            </a>
        @endif
        
        <a href="/" class="inline-block text-sm font-bold text-gray-500 hover:text-growseri-green mt-2 transition-colors">
            &larr; Kembali Belanja
        </a>
    </div>
</div>
