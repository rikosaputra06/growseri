<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Layout;

use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class Orders extends Component
{
    use WithFileUploads;
    public $statusFilter = 'all';

    public $proofs = [];

    // Edit Order State
    public $isEditingOrder = false;
    public $editOrderId = null;
    public $editRecipientName;
    public $editRecipientWhatsapp;
    public $editShippingAddress;
    public $editDeliveryDate;
    public $editDeliveryTime;
    public $editItems = [];
    public $editShippingFee;
    public $editLatitude;
    public $editLongitude;
    public $editDistance;
    
    // For adding new items
    public $availableProducts = []; // Kept to prevent Livewire Payload crashes from old frontend state
    public $selectedProductId = '';
    public $selectedProductQty = 1;

    public function getOrderableProducts()
    {
        return \App\Models\Product::with('variants')->get()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->is_discount_active ? $product->discount_price : $product->base_price,
                'is_out_of_stock' => $product->is_out_of_stock || $product->stock <= 0,
                'variants' => $product->variants->map(function($variant) {
                    return [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'price' => $variant->is_discount_active ? $variant->discount_price : $variant->price,
                        'is_out_of_stock' => $variant->is_out_of_stock || $variant->stock <= 0,
                    ];
                })->toArray(),
            ];
        })->toArray();
    }

    public function updateShippingFee($lat, $lng, $distance)
    {
        $this->editLatitude = $lat;
        $this->editLongitude = $lng;
        $this->editDistance = round($distance, 1);

        $settings = \App\Models\StoreSetting::first();
        if ($settings && $settings->shipping_rate_type === 'per_km') {
            if ($this->editDistance > 0) {
                $fee = $this->editDistance * $settings->shipping_per_km_rate;
                $this->editShippingFee = max($fee, $settings->shipping_flat_rate);
            }
        } else {
            $this->editShippingFee = $settings->shipping_flat_rate ?? 15000;
        }
    }

    public function render()
    {
        $query = Order::with('customer', 'items')->orderBy('created_at', 'desc');

        if ($this->statusFilter !== 'all') {
            $query->where('order_status', $this->statusFilter);
        }

        $orders = $query->get();

        return view('livewire.admin.orders', [
            'orders' => $orders
        ]);
    }

    public function updateStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        if ($order) {
            $oldStatus = $order->order_status;
            
            if ($status === 'completed' && $oldStatus !== 'completed') {
                // Kurangi stok
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = \App\Models\ProductVariant::find($item->product_variant_id);
                        if ($variant) {
                            $variant->decrement('stock', $item->quantity);
                        }
                    } else {
                        $product = \App\Models\Product::find($item->product_id);
                        if ($product) {
                            $product->decrement('stock', $item->quantity);
                        }
                    }
                }
            } elseif ($oldStatus === 'completed' && $status !== 'completed') {
                // Kembalikan stok jika status dikembalikan dari completed
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = \App\Models\ProductVariant::find($item->product_variant_id);
                        if ($variant) {
                            $variant->increment('stock', $item->quantity);
                        }
                    } else {
                        $product = \App\Models\Product::find($item->product_id);
                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    }
                }
            }

            $order->update(['order_status' => $status]);
            session()->flash('message', "Status pesanan {$order->invoice_number} diperbarui menjadi {$status}.");
        }
    }

    public function uploadProof($orderId)
    {
        if (!isset($this->proofs[$orderId])) return;
        
        $this->validate([
            "proofs.{$orderId}" => 'image|max:5120',
        ]);

        $order = Order::find($orderId);
        if ($order) {
            $path = $this->proofs[$orderId]->store('proofs', 'public');
            $order->update([
                'proof_of_delivery_path' => 'storage/' . $path,
                'order_status' => 'completed'
            ]);
            unset($this->proofs[$orderId]);
            session()->flash('message', "Bukti foto untuk pesanan {$order->invoice_number} berhasil diunggah.");
        }
    }
    public function editOrder($id)
    {
        try {
            $order = Order::with('items')->find($id);
            if (!$order) return;

            if ($order->order_status !== 'pending') {
                session()->flash('message', 'Hanya pesanan berstatus Pending yang dapat diedit.');
                return;
            }

            $this->isEditingOrder = true;
            $this->editOrderId = $order->id;
            $this->editRecipientName = $order->recipient_name;
            $this->editRecipientWhatsapp = $order->recipient_whatsapp;
            $this->editShippingAddress = $order->shipping_address;
            $this->editDeliveryDate = $order->delivery_date;
            $this->editDeliveryTime = $order->delivery_time;
            $this->editShippingFee = $order->shipping_fee;
            $this->editLatitude = $order->latitude;
            $this->editLongitude = $order->longitude;

            $this->editItems = $order->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product_name,
                    'variant_name' => $item->variant_name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                ];
            })->toArray();
        } catch (\Throwable $e) {
            session()->flash('message', 'Error: ' . $e->getMessage() . ' on line ' . $e->getLine());
        }
    }

    public function removeItem($index)
    {
        unset($this->editItems[$index]);
        $this->editItems = array_values($this->editItems); // Re-index
    }

    public function addItem()
    {
        if (empty($this->selectedProductId)) {
            $this->addError('selectedProduct', 'Silakan pilih produk.');
            return;
        }

        $parts = explode('-', $this->selectedProductId);
        $productId = $parts[0];
        $variantId = $parts[1] ?? null;

        $product = \App\Models\Product::find($productId);
        if (!$product) return;

        $price = $product->is_discount_active ? $product->discount_price : $product->base_price;
        $variantName = null;

        if ($variantId) {
            $variant = $product->variants()->find($variantId);
            if ($variant) {
                $price = $variant->is_discount_active ? $variant->discount_price : $variant->price;
                $variantName = $variant->name;
            }
        }

        $this->editItems[] = [
            'id' => null, // null means new item
            'product_id' => $product->id,
            'product_name' => $product->name,
            'variant_name' => $variantName,
            'quantity' => $this->selectedProductQty,
            'price' => $price,
            'subtotal' => $price * $this->selectedProductQty,
        ];

        $this->selectedProductId = '';
        $this->selectedProductQty = 1;
        $this->resetErrorBag('selectedProduct');
    }

    public function updatedEditItems($value, $name)
    {
        // $name will be something like "0.quantity"
        $parts = explode('.', $name);
        if (count($parts) == 2 && $parts[1] === 'quantity') {
            $index = $parts[0];
            $qty = (int)$value;
            if (isset($this->editItems[$index]) && $qty > 0) {
                $this->editItems[$index]['quantity'] = $qty;
                $this->editItems[$index]['subtotal'] = $this->editItems[$index]['price'] * $qty;
            }
        }
    }

    public function saveOrder()
    {
        $order = Order::find($this->editOrderId);
        if (!$order) return;

        $subtotal = collect($this->editItems)->sum('subtotal');
        $grandTotal = $subtotal + $this->editShippingFee;

        // Note: discount calculation might be lost if we don't handle it, 
        // but let's assume we preserve grandTotal properly or recalculate.
        // If there was a discount, we subtract it from grandTotal.
        $discountAmount = ($order->subtotal + $order->shipping_fee) - $order->grand_total;
        if ($discountAmount > 0) {
            $grandTotal -= $discountAmount;
            if ($grandTotal < 0) $grandTotal = 0;
        }

        $order->update([
            'recipient_name' => $this->editRecipientName,
            'recipient_whatsapp' => $this->editRecipientWhatsapp,
            'shipping_address' => $this->editShippingAddress,
            'delivery_date' => $this->editDeliveryDate,
            'delivery_time' => $this->editDeliveryTime,
            'shipping_fee' => $this->editShippingFee,
            'latitude' => $this->editLatitude,
            'longitude' => $this->editLongitude,
            'subtotal' => $subtotal,
            'grand_total' => $grandTotal,
        ]);

        // Sync items
        $order->items()->delete();
        foreach ($this->editItems as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'variant_name' => $item['variant_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        $this->cancelEdit();
        session()->flash('message', "Pesanan {$order->invoice_number} berhasil diperbarui.");
    }

    public function cancelEdit()
    {
        $this->isEditingOrder = false;
        $this->editOrderId = null;
        $this->editItems = [];
    }
}
