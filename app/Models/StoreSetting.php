<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'delivery_time_slots' => 'array',
        'delivery_active_days' => 'array',
        'promo_banners' => 'array',
        'grid_banners' => 'array',
        'info_accordion' => 'array',
        'payment_transfer_details' => 'array',
    ];
}
