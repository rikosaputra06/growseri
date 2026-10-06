<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\StoreSetting;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Settings extends Component
{
    use WithFileUploads;
    public $store_latitude;
    public $store_longitude;
    public $store_name;
    public $slogan;
    public $address;
    public $store_whatsapp;
    public $logo;
    public $logo_path;
    public $currency;
    public $is_open;
    public $shipping_rate_type;
    public $shipping_flat_rate;
    public $shipping_per_km_rate;
    public $max_delivery_distance_km;
    
    // Delivery Schedule Properties
    public $delivery_label;
    public $delivery_time_mode;
    public $delivery_time_slots = [];
    public $delivery_active_days = [];
    
    // UI Properties
    public $promo_banners = [];
    public $grid_banners = [];
    public $info_accordion = [];
    public $banner_uploads = [];
    public $grid_banner_uploads = [];
    public $is_slider_autoplay = true;

    // Payment Settings Properties
    public $payment_transfer_active = true;
    public $payment_transfer_details = [];
    public $payment_qris_active = true;
    public $payment_qris_image;
    public $payment_qris_image_path;

    public function mount()
    {
        $settings = StoreSetting::first();
        if ($settings) {
            $this->store_latitude = $settings->store_latitude;
            $this->store_longitude = $settings->store_longitude;
            $this->store_name = $settings->store_name;
            $this->slogan = $settings->slogan;
            $this->address = $settings->address;
            $this->store_whatsapp = $settings->store_whatsapp;
            $this->logo_path = $settings->logo_path;
            $this->currency = $settings->currency;
            $this->is_open = $settings->is_open;
            $this->shipping_rate_type = $settings->shipping_rate_type;
            $this->shipping_flat_rate = $settings->shipping_flat_rate;
            $this->shipping_per_km_rate = $settings->shipping_per_km_rate;
            $this->max_delivery_distance_km = $settings->max_delivery_distance_km;
            
            $this->delivery_label = $settings->delivery_label;
            $this->delivery_time_mode = $settings->delivery_time_mode;
            $this->delivery_time_slots = $settings->delivery_time_slots ?? [];
            $this->delivery_active_days = $settings->delivery_active_days ?? [];
            $this->promo_banners = $settings->promo_banners ?? [];
            $this->grid_banners = $settings->grid_banners ?? [];
            $this->info_accordion = $settings->info_accordion ?? [];
            $this->is_slider_autoplay = $settings->is_slider_autoplay ?? true;
            
            $this->payment_transfer_active = $settings->payment_transfer_active ?? true;
            $this->payment_transfer_details = is_array($settings->payment_transfer_details) ? $settings->payment_transfer_details : [];
            $this->payment_qris_active = $settings->payment_qris_active ?? true;
            $this->payment_qris_image_path = $settings->payment_qris_image_path;
        }
    }
    
    public function addTimeSlot()
    {
        $this->delivery_time_slots[] = ['start' => '08:00', 'end' => '10:00'];
    }
    
    public function removeTimeSlot($index)
    {
        unset($this->delivery_time_slots[$index]);
        $this->delivery_time_slots = array_values($this->delivery_time_slots); // re-index
    }
    
    public function addBanner()
    {
        $this->promo_banners[] = [
            'image' => null,
            'url' => '',
        ];
    }

    public function removeBanner($index)
    {
        unset($this->promo_banners[$index]);
        $this->promo_banners = array_values($this->promo_banners);
    }
    
    public function removeBannerImage($index)
    {
        if (isset($this->promo_banners[$index]['image'])) {
            unset($this->promo_banners[$index]['image']);
        }
        if (isset($this->banner_uploads[$index])) {
            unset($this->banner_uploads[$index]);
        }
    }
    
    public function addGridBanner()
    {
        if (count($this->grid_banners) < 4) {
            $this->grid_banners[] = [
                'image' => null,
                'link' => '',
            ];
        } else {
            $this->addError('grid_banners', 'Maksimal 4 banner grid (2 kolom x 2 baris).');
        }
    }

    public function removeGridBanner($index)
    {
        unset($this->grid_banners[$index]);
        $this->grid_banners = array_values($this->grid_banners);
    }
    
    public function removeGridBannerImage($index)
    {
        if (isset($this->grid_banners[$index]['image'])) {
            unset($this->grid_banners[$index]['image']);
        }
        if (isset($this->grid_banner_uploads[$index])) {
            unset($this->grid_banner_uploads[$index]);
        }
    }

    public function addAccordion()
    {
        $this->info_accordion[] = [
            'title' => '',
            'description' => ''
        ];
    }

    public function removeAccordion($index)
    {
        unset($this->info_accordion[$index]);
        $this->info_accordion = array_values($this->info_accordion);
    }
    
    public function addBankAccount()
    {
        $this->payment_transfer_details[] = [
            'bank_name' => '',
            'account_number' => '',
            'account_name' => ''
        ];
    }

    public function removeBankAccount($index)
    {
        unset($this->payment_transfer_details[$index]);
        $this->payment_transfer_details = array_values($this->payment_transfer_details);
    }
    
    public function toggleActiveDay($dayNumber)
    {
        if (in_array($dayNumber, $this->delivery_active_days)) {
            $this->delivery_active_days = array_diff($this->delivery_active_days, [$dayNumber]);
        } else {
            $this->delivery_active_days[] = $dayNumber;
        }
    }

    public function updateLocation($lat, $lng)
    {
        $this->store_latitude = $lat;
        $this->store_longitude = $lng;
    }

    public function saveSettings()
    {
        // Handle banner images upload
        foreach ($this->banner_uploads as $index => $file) {
            if ($file) {
                $path = $file->store('banners', 'public');
                $this->promo_banners[$index]['image'] = $path;
            }
        }
        
        // Handle grid banner images upload
        foreach ($this->grid_banner_uploads as $index => $file) {
            if ($file) {
                $path = $file->store('banners', 'public');
                $this->grid_banners[$index]['image'] = $path;
            }
        }
        
        $data = [
            'store_latitude' => $this->store_latitude,
            'store_longitude' => $this->store_longitude,
            'store_name' => $this->store_name,
            'slogan' => $this->slogan,
            'address' => $this->address,
            'store_whatsapp' => $this->store_whatsapp,
            'currency' => $this->currency,
            'is_open' => $this->is_open,
            'shipping_rate_type' => $this->shipping_rate_type,
            'shipping_flat_rate' => $this->shipping_flat_rate,
            'shipping_per_km_rate' => $this->shipping_per_km_rate,
            'max_delivery_distance_km' => $this->max_delivery_distance_km,
            'delivery_label' => $this->delivery_label,
            'delivery_time_mode' => $this->delivery_time_mode,
            'delivery_time_slots' => $this->delivery_time_slots,
            'delivery_active_days' => $this->delivery_active_days,
            'promo_banners' => $this->promo_banners,
            'grid_banners' => $this->grid_banners,
            'info_accordion' => $this->info_accordion,
            'is_slider_autoplay' => $this->is_slider_autoplay,
            'payment_transfer_active' => $this->payment_transfer_active,
            'payment_transfer_details' => $this->payment_transfer_details,
            'payment_qris_active' => $this->payment_qris_active,
        ];
        
        if ($this->logo) {
            $path = $this->logo->store('store', 'public');
            $data['logo_path'] = '/storage/' . $path;
            $this->logo_path = $data['logo_path'];
        }

        if ($this->payment_qris_image) {
            $path = $this->payment_qris_image->store('payments', 'public');
            $data['payment_qris_image_path'] = '/storage/' . $path;
            $this->payment_qris_image_path = $data['payment_qris_image_path'];
        }

        $settings = StoreSetting::first();
        if ($settings) {
            $settings->update($data);
        }
        
        session()->flash('message', 'Pengaturan berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
