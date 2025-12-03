<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $categories->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $categories
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return response()->view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048'
            ], [
                'name.required' => 'اسم التصنيف مطلوب',
                'name.string' => 'يجب أن يكون الاسم نصاً',
                'name.max' => 'يجب ألا يتجاوز الاسم 255 حرفاً',
                'name.unique' => 'هذا الاسم مستخدم بالفعل، يرجى اختيار اسم آخر',
                'image.image' => 'يجب أن يكون الملف صورة',
                'image.mimes' => 'يجب أن تكون الصورة بصيغة: jpg, jpeg, png, gif, أو webp',
                'image.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميجابايت',
            ]);

            $validated['slug'] = Str::slug($validated['name']);

            $category = Category::create($validated);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->store('categories', 'public');

                // تغيير 'file' إلى 'url'
                $category->media()->create([
                    'type' => 'image', // إضافة type لأنه مطلوب في الـ migration
                    'url' => $path,
                    'role' => 'main',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => ' تم إضافة التصنيف بنجاح.',
                'category' => $category->load('mainImage'),
                'redirect' => route('admin.categories.index')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => ' فشل التحقق من صحة البيانات.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع أثناء الإضافة.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return response()->view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048'
            ], [
                'name.required' => 'اسم التصنيف مطلوب',
                'name.string' => 'يجب أن يكون الاسم نصاً',
                'name.max' => 'يجب ألا يتجاوز الاسم 255 حرفاً',
                'name.unique' => 'هذا الاسم مستخدم بالفعل، يرجى اختيار اسم آخر',
                'image.image' => 'يجب أن يكون الملف صورة',
                'image.mimes' => 'يجب أن تكون الصورة بصيغة: jpg, jpeg, png, gif, أو webp',
                'image.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميجابايت',
            ]);

            $validated['slug'] = Str::slug($validated['name']);

            $category->update($validated);

            // Handle image upload/update
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->store('categories', 'public');

                // Delete old main image if exists
                $mainImage = $category->mainImage;
                if ($mainImage) {
                    // استخدام attributes['url'] للحصول على القيمة الأصلية بدون الـ accessor
                    $originalUrl = $mainImage->attributes['url'] ?? null;
                    if ($originalUrl && Storage::disk('public')->exists($originalUrl)) {
                        Storage::disk('public')->delete($originalUrl);
                    }
                    $mainImage->delete();
                }

                // Add new image as main - تغيير 'file' إلى 'url'
                $category->media()->create([
                    'type' => 'image', // إضافة type لأنه مطلوب
                    'url' => $path,
                    'role' => 'main',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => ' تم تحديث التصنيف بنجاح.',
                'category' => $category->load('mainImage'),
                'redirect' => route('admin.categories.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => ' فشل التحقق من صحة البيانات.',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => ' حدث خطأ غير متوقع أثناء التحديث.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Delete main image file from storage (if exists)
            $mainImage = $category->mainImage;
            if ($mainImage) {
                // استخدام attributes['url'] للحصول على القيمة الأصلية
                $originalUrl = $mainImage->attributes['url'] ?? null;
                if ($originalUrl && Storage::disk('public')->exists($originalUrl)) {
                    Storage::disk('public')->delete($originalUrl);
                }
                $mainImage->delete();
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => ' تم حذف التصنيف بنجاح.',
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