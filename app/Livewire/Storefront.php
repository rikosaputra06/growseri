<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

#[Layout('components.layouts.app')]
class Storefront extends Component
{
    public $categories = [];
    public $selectedCategory = 'Semua';
    public $search = '';
    
    // Popup state
    public $showPopup = false;
    public $selectedProduct = null;
    public $selectedVariant = null;
    public $quantity = 1;
    public $itemNote = '';

    // Cart state
    // Cart state
    public $cart = [];
    public $showCartModal = false;
    
    // Order History state
    public $showHistoryModal = false;
    public $historyPhone = '';
    public $historyOrders = [];
    public $hasSearchedHistory = false;
    
    // View mode state
    public $viewMode = 'desktop';

    public $promo_banners = [];
    public $grid_banners = [];
    public $info_accordion = [];
    public $is_slider_autoplay = true;

    #[Computed]
    public function settings()
    {
        return \App\Models\StoreSetting::first();
    }

    public function mount()
    {
        $this->viewMode = session()->get('view_mode', 'mobile');
        
        $sessionCart = session()->get('cart', []);
        $this->cart = [];
        foreach ($sessionCart as $item) {
            $key = $item['product_id'] . '_' . ($item['variant_id'] ?? '0');
            $this->cart[$key] = $item;
        }

        $settings = \App\Models\StoreSetting::first();
        if ($settings) {
            $this->promo_banners = $settings->promo_banners ?? [];
            $this->grid_banners = $settings->grid_banners ?? [];
            $this->info_accordion = $settings->info_accordion ?? [];
            $this->is_slider_autoplay = $settings->is_slider_autoplay ?? true;
        }
        $this->loadCategories();
    }
    
    public function loadCategories()
    {
        // Get unique categories
        $this->categories = Product::where('is_active', true)
                            ->whereNotNull('category')
                            ->where('category', '!=', '')
                            ->distinct()
                            ->pluck('category');
    }
                            
    #[Computed]
    public function products()
    {
        $query = Product::with('variants')->where('is_active', true);
        
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->selectedCategory !== 'Semua') {
            $query->where('category', $this->selectedCategory);
        }
        
        return $query->orderBy('sort_order', 'asc')->get();
    }

    public function selectCategory($category)
    {
        $this->selectedCategory = $category;
    }

    public function updatedSearch()
    {
    }
    
    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        session()->put('view_mode', $mode);
    }

    public function openPopup($productId)
    {
        $this->selectedProduct = Product::with('variants')->find($productId);
        $this->quantity = 1;
        $this->itemNote = '';
        
        // If product has variants, select the first one by default
        if ($this->selectedProduct->has_variants && $this->selectedProduct->variants->isNotEmpty()) {
            $this->selectedVariant = $this->selectedProduct->variants->first()->id;
        } else {
            $this->selectedVariant = null;
        }
        
        $this->showPopup = true;
    }

    public function closePopup()
    {
        $this->showPopup = false;
        $this->selectedProduct = null;
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if (!$this->selectedProduct) return;

        $price = $this->selectedProduct->is_discount_active ? $this->selectedProduct->discount_price : $this->selectedProduct->base_price;
        $variantName = null;
        $maxStock = $this->selectedProduct->stock;

        if ($this->selectedVariant) {
            $variant = $this->selectedProduct->variants->find($this->selectedVariant);
            if ($variant) {
                $price = $variant->is_discount_active ? $variant->discount_price : $variant->price;
                $variantName = $variant->variant_name;
                $maxStock = $variant->stock;
            }
        }

        $key = $this->selectedProduct->id . '_' . ($this->selectedVariant ?? '0');
        $currentQty = isset($this->cart[$key]) ? $this->cart[$key]['quantity'] : 0;
        $newQty = $currentQty + $this->quantity;

        if ($newQty > $maxStock) {
            $this->addError('quantity', 'Stok tidak mencukupi. Sisa stok: ' . $maxStock);
            return;
        }

        if (isset($this->cart[$key])) {
            $this->cart[$key]['quantity'] = $newQty;
            $this->cart[$key]['subtotal'] = $price * $newQty;
        } else {
            $this->cart[$key] = [
                'product_id' => $this->selectedProduct->id,
                'name' => $this->selectedProduct->name,
                'variant_id' => $this->selectedVariant,
                'variant_name' => $variantName,
                'price' => $price,
                'quantity' => $this->quantity,
                'subtotal' => $price * $this->quantity,
                'image' => $this->selectedProduct->image_url,
                'note' => $this->itemNote,
                'max_stock' => $maxStock,
            ];
        }

        session()->put('cart', array_values($this->cart)); // Convert to sequential array for consistency
        $this->closePopup();
    }

    public function quickAddToCart($productId)
    {
        $product = Product::with('variants')->find($productId);
        if (!$product) return;

        if ($product->has_variants && $product->variants->isNotEmpty()) {
            $this->openPopup($productId);
            return;
        }

        $key = $product->id . '_0';
        $maxStock = $product->stock;
        
        $currentQty = isset($this->cart[$key]) ? $this->cart[$key]['quantity'] : 0;
        
        if ($currentQty + 1 > $maxStock) {
            session()->flash('cart_error_' . $product->id, 'Maksimal stok tercapai.');
            return;
        }

        if (isset($this->cart[$key])) {
            $this->cart[$key]['quantity']++;
            $this->cart[$key]['subtotal'] = $this->cart[$key]['price'] * $this->cart[$key]['quantity'];
        } else {
            $price = $product->is_discount_active ? $product->discount_price : $product->base_price;
            $this->cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'variant_id' => null,
                'variant_name' => null,
                'price' => $price,
                'quantity' => 1,
                'subtotal' => $price * 1,
                'image' => $product->image_url,
                'note' => '',
                'max_stock' => $maxStock,
            ];
        }
        
        session()->put('cart', array_values($this->cart));
    }

    public function incrementCartItem($key)
    {
        if (isset($this->cart[$key])) {
            if ($this->cart[$key]['quantity'] < $this->cart[$key]['max_stock']) {
                $this->cart[$key]['quantity']++;
                $this->cart[$key]['subtotal'] = $this->cart[$key]['price'] * $this->cart[$key]['quantity'];
                session()->put('cart', array_values($this->cart));
            } else {
                session()->flash('cart_error_' . $this->cart[$key]['product_id'], 'Stok tidak mencukupi.');
            }
        }
    }

    public function decrementCartItem($key)
    {
        if (isset($this->cart[$key])) {
            if ($this->cart[$key]['quantity'] > 1) {
                $this->cart[$key]['quantity']--;
                $this->cart[$key]['subtotal'] = $this->cart[$key]['price'] * $this->cart[$key]['quantity'];
            } else {
                unset($this->cart[$key]);
            }
            session()->put('cart', array_values($this->cart));
            
            if (empty($this->cart)) {
                $this->showCartModal = false;
            }
        }
    }

    public function removeCartItem($key)
    {
        if (isset($this->cart[$key])) {
            unset($this->cart[$key]);
            session()->put('cart', array_values($this->cart));
            
            if (empty($this->cart)) {
                $this->showCartModal = false;
            }
        }
    }

    public function getCartTotalProperty()
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getCartItemCountProperty()
    {
        return collect($this->cart)->sum('quantity');
    }

    public function openHistoryModal()
    {
        $this->showHistoryModal = true;
        $this->historyPhone = session()->get('customer_phone', '');
        
        if (!empty($this->historyPhone)) {
            $this->checkHistory();
        } else {
            $this->historyOrders = [];
            $this->hasSearchedHistory = false;
        }
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
    }

    public function checkHistory()
    {
        $this->validate([
            'historyPhone' => 'required|min:10'
        ], [
            'historyPhone.required' => 'Nomor WhatsApp harus diisi.',
            'historyPhone.min' => 'Nomor WhatsApp terlalu pendek.'
        ]);

        $this->historyOrders = \App\Models\Order::with('items')
            ->where('recipient_whatsapp', $this->historyPhone)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $this->hasSearchedHistory = true;
        session()->put('customer_phone', $this->historyPhone);
    }

    public function openCartModal()
    {
        $this->showCartModal = true;
    }

    public function closeCartModal()
    {
        $this->showCartModal = false;
    }

    public function goToCheckout()
    {
        session()->put('cart', array_values($this->cart));
        return redirect()->route('checkout');
    }

    public function render()
    {
        return view('livewire.storefront');
    }
}
