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
                'button_text' => 'nullable|string|max:100',
                'button_url' => 'nullable|string|url',
                'media' => 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm,avi|max:102400',
                'active' => 'boolean',
                'order' => 'nullable|integer|min:0',
            ], [
                'media.required' => 'الصورة أو الفيديو مطلوب.',
                'media.max' => 'يجب ألا يتجاوز الملف 100 ميجابايت.',
                'media.mimes' => 'الملف يجب أن يكون صورة أو فيديو من الأنواع المدعومة.',
            ]);

            if (!isset($validated['active'])) {
                $validated['active'] = true;
            }

            if (!isset($validated['order'])) {
                $validated['order'] = Slider::max('order') + 1;
            }

            $slider = Slider::create($validated);

            if ($request->hasFile('media')) {
                $media = $request->file('media');
                $mimeType = $media->getMimeType();
                $fileType = str_starts_with($mimeType, 'video') ? 'video' : 'image';
                
                $path = $media->store('sliders', 'public');

                $slider->media()->create([
                    'type' => $fileType,
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
                'button_text' => 'nullable|string|max:100',
                'button_url' => 'nullable|string|url',
                'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm,avi|max:102400',
                'active' => 'boolean',
                'order' => 'nullable|integer|min:0',
            ], [
                'media.max' => 'يجب ألا يتجاوز الملف 100 ميجابايت.',
                'media.mimes' => 'الملف يجب أن يكون صورة أو فيديو من الأنواع المدعومة.',
            ]);

            if (!isset($validated['active'])) {
                $validated['active'] = true;
            }

            if (!isset($validated['order'])) {
                $validated['order'] = $slider->order;
            }

            $slider->update($validated);

            // Handle media upload/update
            if ($request->hasFile('media')) {
                $media = $request->file('media');
                $mimeType = $media->getMimeType();
                $fileType = str_starts_with($mimeType, 'video') ? 'video' : 'image';
                
                $path = $media->store('sliders', 'public');

                // Delete old main image if exists
                $mainMedia = $slider->mainImage;
                if ($mainMedia) {
                    $originalUrl = $mainMedia->attributes['url'] ?? null;
                    if ($originalUrl && Storage::disk('public')->exists($originalUrl)) {
                        Storage::disk('public')->delete($originalUrl);
                    }
                    $mainMedia->delete();
                }

                // Add new media as main
                $slider->media()->create([
                    'type' => $fileType,
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
