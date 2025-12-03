<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Landmark;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class LandmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $landmarks = Landmark::query();

        if ($request->filled('search')) {
            $landmarks->where('name', 'like', '%' . $request->search . '%');
        }

    
        if ($request->filled('city')) {
            $landmarks->where('city_id', $request->city);
        }

        $landmarks = $landmarks->with('city', 'media')
                               ->orderBy('updated_at', 'desc')
                               ->paginate(20)
                               ->withQueryString();

        $cities = City::all();

        return response()->view('admin.landmarks.index', compact('landmarks', 'cities'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $cities = City::all();
        return response()->view('admin.landmarks.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:landmarks,slug',
            'type' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'short_description_ar' => 'nullable|string|max:255',
            'short_description_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'ambient_description' => 'nullable|string',
            'ambient_description_ar' => 'nullable|string',
            'ambient_description_en' => 'nullable|string',
            'timeline' => 'nullable|json',
            'main_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,avi|max:102400',
            'sub_images' => 'nullable|array',
            'sub_images.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,webm,avi|max:102400',
        ], [
            'city_id.required' => 'المدينة مطلوبة',
            'city_id.exists' => 'المدينة المحددة غير موجودة',
            'name.required' => 'اسم المعلم مطلوب',
            'name.string' => 'يجب أن يكون اسم المعلم نصاً',
            'name.max' => 'يجب ألا يتجاوز اسم المعلم 255 حرفاً',
            'name_ar.max' => 'يجب ألا يتجاوز الاسم بالعربية 255 حرفاً',
            'name_en.max' => 'يجب ألا يتجاوز الاسم بالإنجليزية 255 حرفاً',
            'slug.max' => 'يجب ألا يتجاوز الرابط 255 حرفاً',
            'slug.unique' => 'هذا الرابط مستخدم بالفعل',
            'type.max' => 'يجب ألا يتجاوز النوع 255 حرفاً',
            'short_description.max' => 'يجب ألا يتجاوز الوصف المختصر 255 حرفاً',
            'short_description_ar.max' => 'يجب ألا يتجاوز الوصف المختصر بالعربية 255 حرفاً',
            'short_description_en.max' => 'يجب ألا يتجاوز الوصف المختصر بالإنجليزية 255 حرفاً',
            'timeline.json' => 'يجب أن يكون الخط الزمني بصيغة JSON صحيحة',
            'main_image.file' => 'يجب أن تكون الصورة الرئيسية ملفاً صحيحاً',
            'main_image.mimes' => 'يجب أن تكون الصورة الرئيسية بصيغة: jpeg, png, jpg, gif, mp4, webm, أو avi',
            'main_image.max' => 'يجب ألا يتجاوز حجم الصورة الرئيسية 100 ميجابايت',
            'sub_images.array' => 'يجب أن تكون الصور الفرعية مصفوفة',
            'sub_images.*.file' => 'يجب أن تكون كل صورة فرعية ملفاً صحيحاً',
            'sub_images.*.mimes' => 'يجب أن تكون الصور الفرعية بصيغة: jpeg, png, jpg, gif, mp4, webm, أو avi',
            'sub_images.*.max' => 'يجب ألا يتجاوز حجم كل صورة فرعية 100 ميجابايت',
        ]);
        $validated['slug'] = $validated['slug'] ?? \Illuminate\Support\Str::slug($validated['name']);

        $validated['uuid'] = \Illuminate\Support\Str::uuid()->toString();

        // Parse timeline if it's a string
        if (isset($validated['timeline']) && is_string($validated['timeline'])) {
            $validated['timeline'] = json_decode($validated['timeline'], true) ?? [];
        }

        $landmark = Landmark::create($validated);

        if ($request->hasFile('main_image')) {
            $mainFile = $request->file('main_image');
            $mimeType = $mainFile->getMimeType();
            
            // Determine file type
            $fileType = str_starts_with($mimeType, 'video') ? 'video' : 'image';
            
            // Store file
            $path = $mainFile->store('media/landmarks', 'public');
            
            // Create main media
            $landmark->media()->create([
                'type' => $fileType,
                'role' => 'main',
                'url' => $path,
                'alt_text' => $validated['name'],
            ]);
        }

      
        if ($request->hasFile('sub_images')) {
            foreach ($request->file('sub_images') as $index => $subFile) {
                $mimeType = $subFile->getMimeType();
                $fileType = str_starts_with($mimeType, 'video') ? 'video' : 'image';
                
                $path = $subFile->store('media/landmarks', 'public');
                $landmark->media()->create([
                    'type' => $fileType,
                    'role' => 'sub',
                    'url' => $path,
                    'alt_text' => $validated['name'] . ' ملف فرعي ' . ($index + 1),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'redirect' => route('admin.landmarks.index'),
            'message' => 'تم إنشاء المعلم بنجاح.',
            'landmark' => $landmark->load('media', 'city'),
        ]);
    }

    /**
     * Display the specified resource.
     */
       public function show(Landmark $landmark)
    {
        return response()->view('admin.landmarks.show', compact('landmark'));
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit(Landmark $landmark)
    {
        $cities = City::all();
        $landmark->load('media', 'city');
        return response()->view('admin.landmarks.edit', compact('landmark', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Landmark $landmark)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
           'slug' => 'nullable|string|max:255|unique:landmarks,slug,' . $landmark->id,
            'type' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'short_description_ar' => 'nullable|string|max:255',
            'short_description_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'ambient_description' => 'nullable|string',
            'ambient_description_ar' => 'nullable|string',
            'ambient_description_en' => 'nullable|string',
            'timeline' => 'nullable|json',
            'main_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,avi|max:102400',
            'sub_images' => 'nullable|array',
            'sub_images.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,webm,avi|max:102400',
        ], [
            'city_id.required' => 'المدينة مطلوبة',
            'city_id.exists' => 'المدينة المحددة غير موجودة',
            'name.required' => 'اسم المعلم مطلوب',
            'name.string' => 'يجب أن يكون اسم المعلم نصاً',
            'name.max' => 'يجب ألا يتجاوز اسم المعلم 255 حرفاً',
            'name_ar.max' => 'يجب ألا يتجاوز الاسم بالعربية 255 حرفاً',
            'name_en.max' => 'يجب ألا يتجاوز الاسم بالإنجليزية 255 حرفاً',
            'slug.max' => 'يجب ألا يتجاوز الرابط 255 حرفاً',
            'slug.unique' => 'هذا الرابط مستخدم بالفعل',
            'type.max' => 'يجب ألا يتجاوز النوع 255 حرفاً',
            'short_description.max' => 'يجب ألا يتجاوز الوصف المختصر 255 حرفاً',
            'short_description_ar.max' => 'يجب ألا يتجاوز الوصف المختصر بالعربية 255 حرفاً',
            'short_description_en.max' => 'يجب ألا يتجاوز الوصف المختصر بالإنجليزية 255 حرفاً',
            'timeline.json' => 'يجب أن يكون الخط الزمني بصيغة JSON صحيحة',
            'main_image.file' => 'يجب أن تكون الصورة الرئيسية ملفاً صحيحاً',
            'main_image.mimes' => 'يجب أن تكون الصورة الرئيسية بصيغة: jpeg, png, jpg, gif, mp4, webm, أو avi',
            'main_image.max' => 'يجب ألا يتجاوز حجم الصورة الرئيسية 100 ميجابايت',
            'sub_images.array' => 'يجب أن تكون الصور الفرعية مصفوفة',
            'sub_images.*.file' => 'يجب أن تكون كل صورة فرعية ملفاً صحيحاً',
            'sub_images.*.mimes' => 'يجب أن تكون الصور الفرعية بصيغة: jpeg, png, jpg, gif, mp4, webm, أو avi',
            'sub_images.*.max' => 'يجب ألا يتجاوز حجم كل صورة فرعية 100 ميجابايت',
        ]);
         $validated['slug'] = $validated['slug'] ?? \Illuminate\Support\Str::slug($validated['name']);

        // Parse timeline if it's a string
        if (isset($validated['timeline']) && is_string($validated['timeline'])) {
            $validated['timeline'] = json_decode($validated['timeline'], true) ?? [];
        }

        $landmark->update($validated);

        if ($request->hasFile('main_image')) {
            $mainFile = $request->file('main_image');
            $mimeType = $mainFile->getMimeType();
            
            // Determine file type
            $fileType = str_starts_with($mimeType, 'video') ? 'video' : 'image';
            
            // Store file
            $path = $mainFile->store('media/landmarks', 'public');
            
            // Delete old main media
            $landmark->media()->where('role', 'main')->delete();
            
            // Create new main media
            $landmark->media()->create([
                'type' => $fileType,
                'role' => 'main',
                'url' => $path,
                'alt_text' => $validated['name'],
            ]);
        }

        if ($request->hasFile('sub_images')) {
            // Delete old sub media
            $landmark->media()->where('role', 'sub')->delete();
            
            foreach ($request->file('sub_images') as $index => $subFile) {
                $mimeType = $subFile->getMimeType();
                $fileType = str_starts_with($mimeType, 'video') ? 'video' : 'image';
                
                $path = $subFile->store('media/landmarks', 'public');
                $landmark->media()->create([
                    'type' => $fileType,
                    'role' => 'sub',
                    'url' => $path,
                    'alt_text' => $validated['name'] . ' ملف فرعي ' . ($index + 1),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'redirect' => route('admin.landmarks.index'),
            'message' => 'تم تحديث المعلم بنجاح.',
            'landmark' => $landmark->load('media', 'city'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Landmark $landmark)
    {
        foreach ($landmark->media as $media) {
            $media->delete();
        }

        $landmark->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المعلم بنجاح.',
        ]);
    }
}
   