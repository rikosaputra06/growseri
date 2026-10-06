<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;

#[Layout('components.layouts.app')]
class Success extends Component
{
    public $invoice;
    public $order;
    public $settings;
    public $selectedBank = 0;

    public function mount($invoice = null)
    {
        $this->invoice = $invoice ?? session()->get('invoice');
        if (!$this->invoice) {
            return redirect('/');
        }

        $this->order = Order::where('invoice_number', $this->invoice)->with('items')->first();
        $this->settings = \App\Models\StoreSetting::first();
    }

    public function getWaUrlProperty()
    {
        if (!$this->order || !$this->settings || empty($this->settings->store_whatsapp)) {
            return null;
        }

        $phone = preg_replace('/^0/', '62', $this->settings->store_whatsapp);
        
        $text = "Halo Growseri!\n\n";
        $text .= "Saya baru saja membuat pesanan dengan detail berikut:\n";
        $text .= "*No. Invoice:* {$this->order->invoice_number}\n";
        $text .= "*Nama:* {$this->order->recipient_name}\n";
        $text .= "*Alamat:* {$this->order->shipping_address}\n";
        
        if ($this->order->latitude && $this->order->longitude) {
            $text .= "*Titik Maps:* https://www.google.com/maps/search/?api=1&query={$this->order->latitude},{$this->order->longitude}\n";
        }
        $text .= "\n";
        
        $text .= "*Daftar Pesanan:*\n";
        foreach ($this->order->items as $item) {
            $text .= "- {$item->quantity}x {$item->product_name} " . ($item->variant_name ? "({$item->variant_name})" : "") . "\n";
        }
        
        $text .= "\n*Total Pembayaran:* Rp " . number_format($this->order->grand_total, 0, ',', '.') . "\n";
        
        $payment = 'Bayar di Tempat (COD)';
        if ($this->order->payment_method == 'qris') {
            $payment = 'QRIS';
        } elseif ($this->order->payment_method == 'transfer') {
            $bankName = '';
            if ($this->settings && is_array($this->settings->payment_transfer_details)) {
                $bank = $this->settings->payment_transfer_details[$this->selectedBank] ?? $this->settings->payment_transfer_details[0] ?? null;
                if ($bank && isset($bank['bank_name'])) {
                    $bankName = ' (' . $bank['bank_name'] . ')';
                }
            }
            $payment = 'Transfer Bank' . $bankName;
        }
        
        $text .= "*Metode Bayar:* {$payment}\n\n";
        $text .= "Mohon segera diproses ya. Terima kasih!";

        return "https://wa.me/{$phone}?text=" . urlencode($text);
    }

    public function render()
    {
        return view('livewire.success');
    }
}
