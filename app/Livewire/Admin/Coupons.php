<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Coupon;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Coupons extends Component
{
    public $coupons;
    
    // Form state
    public $isCreating = false;
    public $editingId = null;
    
    public $code;
    public $type = 'fixed';
    public $value;
    public $min_purchase;
    public $valid_from;
    public $valid_until;
    public $usage_limit;
    public $is_active = true;
    public $is_shipping = false;

    protected $rules = [
        'code' => 'required|string|max:255',
        'type' => 'required|in:fixed,percent',
        'value' => 'required|numeric|min:0',
        'min_purchase' => 'nullable|numeric|min:0',
        'valid_from' => 'nullable|date',
        'valid_until' => 'nullable|date|after_or_equal:valid_from',
        'usage_limit' => 'nullable|integer|min:1',
        'is_active' => 'boolean',
        'is_shipping' => 'boolean',
    ];

    public function render()
    {
        $this->coupons = Coupon::orderBy('id', 'desc')->get();
        return view('livewire.admin.coupons');
    }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $coupon = Coupon::findOrFail($id);
        
        $this->editingId = $coupon->id;
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->min_purchase = $coupon->min_purchase;
        $this->valid_from = $coupon->valid_from;
        $this->valid_until = $coupon->valid_until;
        $this->usage_limit = $coupon->usage_limit;
        $this->is_active = $coupon->is_active;
        $this->is_shipping = $coupon->is_shipping;
        
        $this->isCreating = true;
    }

    public function save()
    {
        $this->validate();
        
        if ($this->editingId && $existing = Coupon::where('code', $this->code)->where('id', '!=', $this->editingId)->first()) {
            $this->addError('code', 'Kode kupon ini sudah digunakan.');
            return;
        } elseif (!$this->editingId && Coupon::where('code', $this->code)->exists()) {
            $this->addError('code', 'Kode kupon ini sudah digunakan.');
            return;
        }

        $data = [
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'value' => $this->value,
            'min_purchase' => $this->min_purchase ?: null,
            'valid_from' => $this->valid_from ?: null,
            'valid_until' => $this->valid_until ?: null,
            'usage_limit' => $this->usage_limit ?: null,
            'is_active' => $this->is_active,
            'is_shipping' => $this->is_shipping,
        ];

        if ($this->editingId) {
            Coupon::find($this->editingId)->update($data);
            session()->flash('message', 'Kupon berhasil diperbarui.');
        } else {
            Coupon::create($data);
            session()->flash('message', 'Kupon berhasil ditambahkan.');
        }

        $this->isCreating = false;
        $this->resetForm();
    }

    public function toggleActive($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon) {
            $coupon->update(['is_active' => !$coupon->is_active]);
        }
    }

    public function delete($id)
    {
        Coupon::find($id)?->delete();
        session()->flash('message', 'Kupon berhasil dihapus.');
    }

    public function cancel()
    {
        $this->isCreating = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->code = '';
        $this->type = 'fixed';
        $this->value = '';
        $this->min_purchase = '';
        $this->valid_from = '';
        $this->valid_until = '';
        $this->usage_limit = '';
        $this->is_active = true;
        $this->is_shipping = false;
        $this->resetValidation();
    }
}
