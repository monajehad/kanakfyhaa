<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sliders = Slider::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $sliders->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sliders = $sliders
            ->orderBy('order', 'asc')
            ->paginate(10)
            ->withQueryString();

        return response()->view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'link' => 'nullable|string|url',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
                'active' => 'boolean',
                'order' => 'nullable|integer|min:0',
            ], [
                'image.max' => 'يجب ألا تتجاوز الصورة 5 ميجابايت.',
                'image.mimes' => 'الصورة يجب أن تكون من نوع: jpg, jpeg, png, gif, webp',
                'image.image' => 'الملف يجب أن يكون صورة.',
            ]);

            if (!isset($validated['active'])) {
                $validated['active'] = true;
            }

            if (!isset($validated['order'])) {
                $validated['order'] = Slider::max('order') + 1;
            }

            $slider = Slider::create($validated);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->store('sliders', 'public');

                $slider->media()->create([
                    'type' => 'image',
                    'url' => $path,
                    'role' => 'main',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الشريط بنجاح.',
                'slider' => $slider->load('mainImage'),
                'redirect' => route('admin.sliders.index')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل التحقق من صحة البيانات.',
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
    public function show(Slider $slider)
    {
        return response()->view('admin.sliders.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return response()->view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'link' => 'nullable|string|url',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
                'active' => 'boolean',
                'order' => 'nullable|integer|min:0',
            ], [
                'image.max' => 'يجب ألا تتجاوز الصورة 5 ميجابايت.',
                'image.mimes' => 'الصورة يجب أن تكون من نوع: jpg, jpeg, png, gif, webp',
                'image.image' => 'الملف يجب أن يكون صورة.',
            ]);

            if (!isset($validated['active'])) {
                $validated['active'] = true;
            }

            if (!isset($validated['order'])) {
                $validated['order'] = $slider->order;
            }

            $slider->update($validated);

            // Handle image upload/update
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->store('sliders', 'public');

                // Delete old main image if exists
                $mainImage = $slider->mainImage;
                if ($mainImage) {
                    $originalUrl = $mainImage->attributes['url'] ?? null;
                    if ($originalUrl && Storage::disk('public')->exists($originalUrl)) {
                        Storage::disk('public')->delete($originalUrl);
                    }
                    $mainImage->delete();
                }

                // Add new image as main
                $slider->media()->create([
                    'type' => 'image',
                    'url' => $path,
                    'role' => 'main',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الشريط بنجاح.',
                'slider' => $slider->load('mainImage'),
                'redirect' => route('admin.sliders.index')
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
                'message' => 'حدث خطأ غير متوقع أثناء التحديث.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        try {
            // Delete main image file from storage (if exists)
            $mainImage = $slider->mainImage;
            if ($mainImage) {
                $originalUrl = $mainImage->attributes['url'] ?? null;
                if ($originalUrl && Storage::disk('public')->exists($originalUrl)) {
                    Storage::disk('public')->delete($originalUrl);
                }
                $mainImage->delete();
            }

            $slider->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الشريط بنجاح.',
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
