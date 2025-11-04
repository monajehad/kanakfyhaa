<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::query();

    // 
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
        //
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
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        $category = \App\Models\Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => ' تم إضافة التصنيف بنجاح.',
            'category' => $category,
            'redirect' => route('admin.categories.index') // رابط صفحة العرض

        ], 201);
    } 
    catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => ' فشل التحقق من صحة البيانات.',
            'errors' => $e->errors(),
        ], 422);
    } 
    catch (\Exception $e) {
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
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => ' تم تحديث التصنيف بنجاح.',
            'category' => $category,
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
