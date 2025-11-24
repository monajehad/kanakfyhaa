<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPackage;
use Illuminate\Http\Request;

class ProductPackageController extends Controller
{
    /**
     * Show packages for a product
     */
    public function index(Product $product)
    {
        $package = $product->package; // one-to-one

        return view('admin.products.packages.index', [
            'product' => $product,
            'package' => $package,
        ]);
    }

    /**
     * Show form for creating a new package
     */
    public function create(Product $product)
    {
        return view('admin.products.packages.create', [
            'product' => $product,
        ]);
    }

    /**
     * Store a new package
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.name_ar' => 'nullable|string',
            'items.*.name_en' => 'nullable|string',
            'items.*.description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'shipping_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $validated['name'],
            'name_ar' => $validated['name_ar'] ?? null,
            'name_en' => $validated['name_en'] ?? null,
            'description' => $validated['description'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'items' => $validated['items'],
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? $validated['price'],
            'discount' => $validated['discount'] ?? 0,
            'shipping_price' => $validated['shipping_price'] ?? 0,
            'quantity' => $validated['quantity'] ?? 1,
            'is_active' => $validated['is_active'] ?? true,
            'order' => $validated['order'] ?? 0,
        ];

        if ($product->package) {
            $product->package->update($data);
            $package = $product->package;
        } else {
            $package = $product->package()->create($data);
        }

        return redirect()->route('admin.products.packages.index', $product)
            ->with('success', __('Package Created'));
    }

    /**
     * Show form for editing a package
     */
    public function edit(Product $product, ProductPackage $productPackage)
    {
        if ($productPackage->product_id !== $product->id) {
            abort(404);
        }

        return view('admin.products.packages.edit', [
            'product' => $product,
            'package' => $productPackage,
        ]);
    }

    /**
     * Update a package
     */
    public function update(Request $request, Product $product, ProductPackage $productPackage)
    {
        if ($productPackage->product_id !== $product->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.name_ar' => 'nullable|string',
            'items.*.name_en' => 'nullable|string',
            'items.*.description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'shipping_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $productPackage->update([
            'name' => $validated['name'],
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'],
            'description' => $validated['description'],
            'description_ar' => $validated['description_ar'],
            'description_en' => $validated['description_en'],
            'items' => $validated['items'],
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? $validated['price'],
            'discount' => $validated['discount'] ?? 0,
            'shipping_price' => $validated['shipping_price'] ?? 0,
            'quantity' => $validated['quantity'] ?? 1,
            'is_active' => $validated['is_active'] ?? true,
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()->route('admin.products.packages.index', $product)
            ->with('success', __('Package Updated'));
    }

    /**
     * Delete a package
     */
    public function destroy(Product $product, ProductPackage $productPackage)
    {
        if ($productPackage->product_id !== $product->id) {
            abort(404);
        }

        $productPackage->delete();

        return redirect()->route('admin.products.packages.index', $product)
            ->with('success', __('Package Deleted'));
    }
}
