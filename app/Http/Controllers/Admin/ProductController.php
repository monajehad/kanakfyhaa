<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::query();

        // Filter by search keyword
        if ($request->filled('search')) {
            $products->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%')
                      ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $categoryId = $request->category;
            $products->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // Filter by min price
        if ($request->filled('price_min')) {
            $products->where('price_sell', '>=', $request->price_min);
        }

        // Filter by max price
        if ($request->filled('price_max')) {
            $products->where('price_sell', '<=', $request->price_max);
        }
        $products = $products->withCount('categories')->orderBy('updated_at', 'desc');
        $products = $products->withCount('categories');
        $categories = Category::all();

        $products = $products->paginate(20)->withQueryString();

        return response()->view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = \App\Models\City::select('id', 'name', 'name_ar', 'name_en')->get();
        return response()->view('admin.products.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'name_ar' => 'nullable|string|max:255',
                'name_en' => 'nullable|string|max:255',
                'title' => 'nullable|string|max:255',
                'short_description' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'color' => 'nullable|string|max:255',
                'colors' => 'nullable|array',
                'colors.*' => 'string|max:7',
                'sizes' => 'nullable|array',
                'sizes.*' => 'string|max:10',
                'price_cost' => 'nullable|numeric|min:0',
                'price_sell' => 'nullable|numeric|min:0',
                'price' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'city_id' => 'nullable|exists:cities,id',
                'image' => 'nullable|string|max:500',
                'is_package' => 'nullable|boolean',
                'published' => 'boolean',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id',
                // All possible common image mimes:
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,bmp,tiff,tif,webp,heif,heic,avif|max:5120',
                'sub_images' => 'nullable|array',
                'sub_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp,tiff,tif,webp,heif,heic,avif|max:5120',
            ]);

            $validated['uuid'] = \Illuminate\Support\Str::uuid()->toString();

            if (isset($validated['sizes'])) {
                $validated['sizes'] = array_values($validated['sizes']);
            }
            
            if (isset($validated['colors'])) {
                $validated['colors'] = array_values($validated['colors']);
            }

            $product = \App\Models\Product::create($validated);

            // Attach categories if provided
            if (isset($validated['categories'])) {
                $product->categories()->sync($validated['categories']);
            }

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                $mainImage = $request->file('main_image');
                $mainImagePath = $mainImage->store('media/products', 'public');
                $product->media()->create([
                    'type' => 'image',
                    'role' => 'main',
                    'url' => '/storage/' . $mainImagePath,
                    'thumbnail' => null,
                    'alt_text' => $validated['name'],
                    'order' => 0
                ]);
            }

            // Handle sub images upload
            if ($request->hasFile('sub_images')) {
                foreach ($request->file('sub_images') as $index => $subImage) {
                    $subImagePath = $subImage->store('media/products', 'public');
                    $product->media()->create([
                        'type' => 'image',
                        'role' => 'sub',
                        'url' => '/storage/' . $subImagePath,
                        'thumbnail' => null,
                        'alt_text' => $validated['name'] . ' الصورة الفرعية ' . ($index + 1),
                        'order' => $index + 1
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المنتج بنجاح.',
                'product' => $product->load('categories', 'media', 'city'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'فشل التحقق من صحة البيانات.',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Fetch all categories for selection
        $categories = Category::all();
        $cities = \App\Models\City::select('id', 'name', 'name_ar', 'name_en')->get();
        // Load relationships needed for editing
        $product->load('categories', 'media', 'city');
        return response()->view('admin.products.edit', compact('product', 'categories', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'name_ar' => 'nullable|string|max:255',
                'name_en' => 'nullable|string|max:255',
                'title' => 'nullable|string|max:255',
                'short_description' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'color' => 'nullable|string|max:255',
                'colors' => 'nullable|array',
                'colors.*' => 'string|max:7',
                'sizes' => 'nullable|array',
                'sizes.*' => 'string|max:10',
                'price_cost' => 'nullable|numeric|min:0',
                'price_sell' => 'nullable|numeric|min:0',
                'price' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'city_id' => 'nullable|exists:cities,id',
                'image' => 'nullable|string|max:500',
                'is_package' => 'nullable|boolean',
                'published' => 'boolean',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id',
                // All possible common image mimes:
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,bmp,tiff,tif,webp,heif,heic,avif|max:5120',
                'sub_images' => 'nullable|array',
                'sub_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,bmp,tiff,tif,webp,heif,heic,avif|max:5120',
            ]);

            if (isset($validated['sizes'])) {
                $validated['sizes'] = array_values($validated['sizes']);
            }
            
            if (isset($validated['colors'])) {
                $validated['colors'] = array_values($validated['colors']);
            }

            $product->update($validated);

            // Update categories
            if (isset($validated['categories'])) {
                $product->categories()->sync($validated['categories']);
            } else {
                $product->categories()->detach();
            }

            // Main image update: Remove old main, add new
            if ($request->hasFile('main_image')) {
                // Delete old main image if exists
                $oldMain = $product->media()->where('role', 'main')->first();
                if ($oldMain) {
                    // Optionally delete physical file
                    $oldMain->delete();
                }
                $mainImage = $request->file('main_image');
                $mainImagePath = $mainImage->store('media/products', 'public');
                $product->media()->create([
                    'type' => 'image',
                    'role' => 'main',
                    'url' => '/storage/' . $mainImagePath,
                    'thumbnail' => null,
                    'alt_text' => $validated['name'],
                    'order' => 0
                ]);
            }

            // Handle sub images: add new ones, keep old if not deleted
            if ($request->hasFile('sub_images')) {
                foreach ($request->file('sub_images') as $index => $subImage) {
                    $subImagePath = $subImage->store('media/products', 'public');
                    $product->media()->create([
                        'type' => 'image',
                        'role' => 'sub',
                        'url' => '/storage/' . $subImagePath,
                        'thumbnail' => null,
                        'alt_text' => $validated['name'] . ' الصورة الفرعية ' . ($index + 1),
                        'order' => $index + 1
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المنتج بنجاح.',
                'product' => $product->load('categories', 'media', 'city'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'فشل التحقق من صحة البيانات.',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Delete all related media
            foreach ($product->media as $media) {
                // Optionally delete physical file from storage here
                $media->delete();
            }
            $product->categories()->detach();
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج بنجاح.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحذف.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
