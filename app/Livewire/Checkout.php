<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreSetting;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Checkout extends Component
{
    public $cart = [];
    public $settings;
    
    // Form fields
    public $name;
    public $whatsapp_number;
    public $shipping_address;
    public $shipping_method = 'kurir_toko';
    public $payment_method = 'transfer';
    public $notes;
    
    // Coupon state
    public $coupon_code = '';
    public $applied_coupon = null;
    public $discount_amount = 0;
    public $is_shipping_discount = false;
    
    // Delivery Schedule
    public $delivery_date;
    public $delivery_time;

    // Maps & Distance
    public $latitude;
    public $longitude;
    public $distance_km = 0;

    protected $rules = [
        'name' => 'required|min:3',
        'whatsapp_number' => 'required|numeric|min:10',
        'shipping_address' => 'required|min:10',
        'shipping_method' => 'required|in:kurir_toko',
        'payment_method' => 'required|in:transfer,qris,cod',
        'delivery_date' => 'required|date',
        'delivery_time' => 'required',
    ];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
        
        if (empty($this->cart)) {
            return redirect('/');
        }
        
        $this->settings = StoreSetting::first();
        
        $now = \Carbon\Carbon::now()->timezone('Asia/Jakarta');
        $this->delivery_date = $now->format('Y-m-d');
        
        if ($this->settings && $this->settings->delivery_time_mode === 'time_slot' && $this->settings->delivery_time_slots) {
            $currentTime = $now->format('H:i');
            $allSlotsPassed = collect($this->settings->delivery_time_slots)->every(function ($slot) use ($currentTime) {
                return $currentTime >= $slot['start'];
            });
            
            if ($allSlotsPassed) {
                $this->delivery_date = $now->addDay()->format('Y-m-d');
            }
        }
        
        if ($this->settings && $this->settings->delivery_time_mode === 'date_only') {
            $this->delivery_time = 'Sesuai antrean';
        }
    }

    public function updateLocation($lat, $lng, $distance)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->distance_km = round($distance, 1);
    }

    public function updatedPaymentMethod($value)
    {
        if ($value === 'cod') {
            if (empty($this->whatsapp_number)) {
                $this->addError('payment_method', 'Silakan isi Nomor WhatsApp terlebih dahulu untuk mengecek kelayakan metode COD.');
                $this->payment_method = 'transfer';
                return;
            }

            // Clean whatsapp number for query
            $phone = preg_replace('/[^0-9]/', '', $this->whatsapp_number);
            
            // Allow if admin or specific condition (optional, sticking to > 0 orders for now)
            $completedOrders = Order::where('recipient_whatsapp', $phone)
                ->whereNotIn('status', ['cancelled', 'pending'])
                ->count();

            $minRequired = 1; // Requires at least 1 previous successful/processed order

            if ($completedOrders < $minRequired) {
                $this->addError('payment_method', "Metode COD hanya bisa dipilih jika Anda telah melakukan minimal {$minRequired} kali pembelian sebelumnya yang berhasil.");
                $this->payment_method = 'transfer';
            } else {
                $this->resetErrorBag('payment_method');
            }
        }
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function applyCoupon()
    {
        $this->resetErrorBag('coupon');
        
        if (empty($this->coupon_code)) {
            return;
        }

        $coupon = \App\Models\Coupon::where('code', strtoupper($this->coupon_code))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            $this->addError('coupon', 'Kupon tidak valid atau tidak aktif.');
            return;
        }

        if ($coupon->valid_from && now()->startOfDay()->lt(\Carbon\Carbon::parse($coupon->valid_from))) {
            $this->addError('coupon', 'Kupon ini belum bisa digunakan.');
            return;
        }

        if ($coupon->valid_until && now()->startOfDay()->gt(\Carbon\Carbon::parse($coupon->valid_until))) {
            $this->addError('coupon', 'Kupon ini sudah kedaluwarsa.');
            return;
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            $this->addError('coupon', 'Kuota penggunaan kupon ini sudah habis.');
            return;
        }

        if ($coupon->min_purchase && $this->subtotal < $coupon->min_purchase) {
            $this->addError('coupon', 'Minimal belanja untuk menggunakan kupon ini adalah Rp ' . number_format($coupon->min_purchase, 0, ',', '.'));
            return;
        }

        // Calculate discount
        $this->is_shipping_discount = $coupon->is_shipping;

        if ($coupon->is_shipping) {
            $shipping_fee = $this->shippingFee;
            if ($coupon->type === 'percent') {
                $this->discount_amount = ($shipping_fee * $coupon->value) / 100;
            } else {
                $this->discount_amount = $coupon->value;
            }
            // Prevent discount > shipping fee
            if ($this->discount_amount > $shipping_fee) {
                $this->discount_amount = $shipping_fee;
            }
        } else {
            if ($coupon->type === 'percent') {
                $this->discount_amount = ($this->subtotal * $coupon->value) / 100;
            } else {
                $this->discount_amount = $coupon->value;
            }
            // Prevent discount > subtotal
            if ($this->discount_amount > $this->subtotal) {
                $this->discount_amount = $this->subtotal;
            }
        }

        $this->applied_coupon = $coupon->code;
        session()->flash('coupon_message', 'Kupon berhasil digunakan!');
    }

    public function removeCoupon()
    {
        $this->coupon_code = '';
        $this->applied_coupon = null;
        $this->discount_amount = 0;
        $this->is_shipping_discount = false;
        $this->resetErrorBag('coupon');
    }

    public function getShippingFeeProperty()
    {
        if (!$this->settings) return 15000;

        if ($this->settings->shipping_rate_type === 'per_km') {
            // Only calculate per km if distance is determined
            if ($this->distance_km > 0) {
                $fee = $this->distance_km * $this->settings->shipping_per_km_rate;
                return max($fee, $this->settings->shipping_flat_rate); // Minimum fee is the flat rate
            }
        }
        
        return $this->settings->shipping_flat_rate ?? 15000;
    }

    public function getGrandTotalProperty()
    {
        $total = $this->subtotal + $this->shippingFee - $this->discount_amount;
        return max(0, $total);
    }

    public function submitOrder()
    {
        $this->validate();

        if (empty($this->cart)) {
            return;
        }
        
        $now = \Carbon\Carbon::now()->timezone('Asia/Jakarta');
        $currentDate = $now->format('Y-m-d');
        $currentTime = $now->format('H:i');

        if ($this->delivery_date < $currentDate) {
            $this->addError('delivery_date', 'Tanggal pengiriman tidak boleh di masa lalu.');
            return;
        }

        if ($this->delivery_date === $currentDate) {
            if ($this->settings && $this->settings->delivery_time_mode === 'time_slot') {
                $parts = explode(' - ', $this->delivery_time);
                if (count($parts) === 2) {
                    $startTime = trim($parts[0]);
                    if ($currentTime >= $startTime) {
                        $this->addError('delivery_time', 'Waktu pengiriman yang dipilih sudah terlewat. Silakan pilih jam lain atau ubah tanggal ke hari berikutnya.');
                        return;
                    }
                }
            } elseif ($this->settings && $this->settings->delivery_time_mode === 'date_time') {
                if ($currentTime >= $this->delivery_time) {
                    $this->addError('delivery_time', 'Waktu pengiriman tidak boleh di masa lalu. Silakan ubah jam atau tanggal.');
                    return;
                }
            }
        }
        if ($this->settings && $this->settings->shipping_rate_type === 'per_km' && empty($this->latitude)) {
            $this->addError('location', 'Silakan tentukan lokasi (Cek Jarak) terlebih dahulu.');
            return;
        }

        if ($this->distance_km > ($this->settings->max_delivery_distance_km ?? 20)) {
            $this->addError('location', 'Jarak pengiriman terlalu jauh (Maksimal ' . ($this->settings->max_delivery_distance_km ?? 20) . ' Km).');
            return;
        }

        $customer = Customer::firstOrCreate(
            ['whatsapp_number' => $this->whatsapp_number],
            ['name' => $this->name]
        );

        $order = Order::create([
            'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5)),
            'customer_id' => $customer->id,
            'recipient_name' => $this->name,
            'recipient_whatsapp' => $this->whatsapp_number,
            'shipping_address' => $this->shipping_address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'subtotal' => $this->subtotal,
            'shipping_fee' => $this->shippingFee,
            'grand_total' => $this->grandTotal,
            'payment_method' => $this->payment_method,
            'shipping_method' => $this->shipping_method,
            'delivery_date' => $this->delivery_date,
            'delivery_time' => $this->delivery_time,
            'notes' => $this->notes,
            'coupon_code' => $this->applied_coupon,
            'discount_amount' => $this->discount_amount,
        ]);
        
        if ($this->applied_coupon) {
            \App\Models\Coupon::where('code', $this->applied_coupon)->increment('used_count');
        }

        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['variant_id'],
                'product_name' => $item['name'],
                'variant_name' => $item['variant_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
                'note' => $item['note'] ?? null,
            ]);
        }

        $customer->increment('total_orders');
        session()->forget('cart');
        session()->put('customer_phone', $this->whatsapp_number);

        return redirect()->route('success')->with('invoice', $order->invoice_number);
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
