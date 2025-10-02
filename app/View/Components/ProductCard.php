<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Product; // <-- Impor model Product

class ProductCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Product $product // <-- Terima data produk
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure
    {
        return view('components.product-card');
    }
}