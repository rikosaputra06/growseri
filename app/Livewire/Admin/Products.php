<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class Products extends Component
{
    use WithFileUploads;
    public $editingProductId = null;
    public $isCreating = false;
    
    // Form fields
    public $productId;
    public $name;
    public $description;
    public $image_url;
    public $has_variants = false;
    public $base_price;
    public $stock = 0;
    public $is_active = true;
    public $category;
    public $discount_price;
    public $is_discount_active = false;
    public $is_out_of_stock = false;
    public $image; // Uploaded file
    
    // Variants array
    public $variants = [];

    public function mount()
    {
    }

    #[Computed]
    public function products()
    {
        return Product::with('variants')
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createProduct()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isCreating = true;
        $this->editingProductId = null;
    }

    public function editProduct($id)
    {
        $this->resetValidation();
        $this->resetForm();
        
        if ($id) {
            $product = Product::with('variants')->find($id);
            if ($product) {
                $this->productId = $product->id;
                $this->name = $product->name;
                $this->description = $product->description;
                $this->image_url = $product->image_url;
                $this->has_variants = $product->has_variants;
                $this->base_price = $product->base_price;
                $this->stock = $product->stock;
                $this->is_active = $product->is_active;
                $this->category = $product->category;
                $this->discount_price = $product->discount_price;
                $this->is_discount_active = $product->is_discount_active;
                $this->is_out_of_stock = $product->is_out_of_stock;
                
                foreach ($product->variants as $variant) {
                    $this->variants[] = [
                        'id' => $variant->id,
                        'variant_name' => $variant->variant_name,
                        'price' => $variant->price,
                        'discount_price' => $variant->discount_price,
                        'is_discount_active' => $variant->is_discount_active,
                        'is_out_of_stock' => $variant->is_out_of_stock,
                        'stock' => $variant->stock
                    ];
                }
                
                $this->isCreating = false;
                $this->editingProductId = $product->id;
            }
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
        $this->isCreating = false;
        $this->editingProductId = null;
    }

    public function resetForm()
    {
        $this->productId = null;
        $this->name = '';
        $this->description = '';
        $this->image_url = '';
        $this->has_variants = false;
        $this->base_price = '';
        $this->stock = 0;
        $this->is_active = true;
        $this->category = '';
        $this->discount_price = '';
        $this->is_discount_active = false;
        $this->is_out_of_stock = false;
        $this->image = null;
        $this->variants = [];
    }
    
    public function addVariant()
    {
        $this->variants[] = [
            'id' => null,
            'variant_name' => '',
            'price' => '',
            'discount_price' => '',
            'is_discount_active' => false,
            'is_out_of_stock' => false,
            'stock' => 0
        ];
    }
    
    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function saveProduct()
    {
        $this->validate([
            'name' => 'required|min:3',
            'base_price' => $this->has_variants ? 'nullable' : 'required|numeric|min:0',
            'stock' => $this->has_variants ? 'nullable' : 'required|numeric|min:0',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . rand(100, 999),
            'description' => $this->description,
            'image_url' => $this->image_url,
            'has_variants' => $this->has_variants,
            'base_price' => $this->has_variants ? null : (empty($this->base_price) ? 0 : $this->base_price),
            'stock' => $this->has_variants ? 0 : (empty($this->stock) ? 0 : $this->stock),
            'is_active' => $this->is_active,
            'category' => $this->category,
            'discount_price' => $this->has_variants ? null : (empty($this->discount_price) ? null : $this->discount_price),
            'is_discount_active' => $this->has_variants ? false : $this->is_discount_active,
            'is_out_of_stock' => $this->has_variants ? false : $this->is_out_of_stock,
        ];
        
        if ($this->image) {
            $path = $this->image->store('products', 'public');
            $data['image_url'] = '/storage/' . $path;
        }
        
        // If editing, we keep the original slug if name hasn't changed much
        if ($this->productId) {
            $product = Product::find($this->productId);
            if ($product->name === $this->name) {
                unset($data['slug']); // Keep old slug
            }
            $product->update($data);
        } else {
            $product = Product::create($data);
        }

        // Handle variants
        if ($this->has_variants) {
            $variantIds = [];
            foreach ($this->variants as $v) {
                if (!empty($v['variant_name']) && !empty($v['price'])) {
                    if (!empty($v['id'])) {
                        $variant = ProductVariant::find($v['id']);
                        $variant->update([
                            'variant_name' => $v['variant_name'],
                            'price' => $v['price'],
                            'discount_price' => empty($v['discount_price']) ? null : $v['discount_price'],
                            'is_discount_active' => $v['is_discount_active'] ?? false,
                            'is_out_of_stock' => $v['is_out_of_stock'] ?? false,
                            'stock' => empty($v['stock']) ? 0 : $v['stock'],
                        ]);
                        $variantIds[] = $variant->id;
                    } else {
                        $variant = $product->variants()->create([
                            'variant_name' => $v['variant_name'],
                            'price' => $v['price'],
                            'discount_price' => empty($v['discount_price']) ? null : $v['discount_price'],
                            'is_discount_active' => $v['is_discount_active'] ?? false,
                            'is_out_of_stock' => $v['is_out_of_stock'] ?? false,
                            'stock' => empty($v['stock']) ? 0 : $v['stock'],
                        ]);
                        $variantIds[] = $variant->id;
                    }
                }
            }
            // Delete variants that were removed
            $product->variants()->whereNotIn('id', $variantIds)->delete();
        } else {
            // Delete all variants if has_variants is unchecked
            $product->variants()->delete();
        }

        $this->cancelEdit();
        
        session()->flash('message', 'Produk berhasil disimpan!');
    }
    
    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            session()->flash('message', 'Produk berhasil dihapus!');
        }
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            Product::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    public function render()
    {
        return view('livewire.admin.products');
    }
}
