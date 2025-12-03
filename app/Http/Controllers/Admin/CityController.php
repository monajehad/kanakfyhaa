<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $cities = City::with('country');

        if ($request->filled('search')) {
            $search = $request->search;
            $cities->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('name_ar', 'like', '%' . $search . '%')
                  ->orWhere('name_en', 'like', '%' . $search . '%')
                  ->orWhere('native_name', 'like', '%' . $search . '%');
            });
        }

        $cities = $cities->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();

        return response()->view('admin.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $countries = Country::select('id', 'name')->get();
        return response()->view('admin.cities.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try {
            $validated = $request->validate([
                'country_id'   => 'required|exists:countries,id',
                'name'         => 'required|string|max:255|unique:cities,name',
                'name_ar'      => 'nullable|string|max:255',
                'name_en'      => 'nullable|string|max:255',
                'native_name'  => 'nullable|string|max:255',
                'description'      => 'nullable|string|max:500',
                'region'       => 'nullable|string|max:100',
                'subregion'    => 'nullable|string|max:100',
                'latitude'     => 'nullable|numeric',
                'longitude'    => 'nullable|numeric',
                'population'   => 'nullable|integer|min:0',
                'main_media'   => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,avi|max:102400',
                'sub_media'    => 'nullable|array',
                'sub_media.*'  => 'file|mimes:jpeg,png,jpg,gif,mp4,webm,avi|max:102400',
            ], [
                'country_id.required' => 'الدولة مطلوبة',
                'country_id.exists' => 'الدولة المحددة غير موجودة',
                'name.required' => 'اسم المدينة مطلوب',
                'name.string' => 'يجب أن يكون اسم المدينة نصاً',
                'name.max' => 'يجب ألا يتجاوز اسم المدينة 255 حرفاً',
                'name.unique' => 'هذا الاسم مستخدم بالفعل، يرجى اختيار اسم آخر',
                'name_ar.max' => 'يجب ألا يتجاوز الاسم بالعربية 255 حرفاً',
                'name_en.max' => 'يجب ألا يتجاوز الاسم بالإنجليزية 255 حرفاً',
                'native_name.max' => 'يجب ألا يتجاوز الاسم المحلي 255 حرفاً',
                'description.max' => 'يجب ألا يتجاوز الوصف 500 حرف',
                'region.max' => 'يجب ألا يتجاوز المنطقة 100 حرف',
                'subregion.max' => 'يجب ألا يتجاوز المنطقة الفرعية 100 حرف',
                'latitude.numeric' => 'يجب أن يكون خط العرض رقماً',
                'longitude.numeric' => 'يجب أن يكون خط الطول رقماً',
                'population.integer' => 'يجب أن يكون عدد السكان رقماً صحيحاً',
                'population.min' => 'يجب أن يكون عدد السكان صفر أو أكثر',
                'main_media.file' => 'يجب أن يكون الملف الرئيسي ملفاً صحيحاً',
                'main_media.mimes' => 'يجب أن يكون الملف الرئيسي بصيغة: jpeg, png, jpg, gif, mp4, webm, أو avi',
                'main_media.max' => 'يجب ألا يتجاوز حجم الملف الرئيسي 100 ميجابايت',
                'sub_media.array' => 'يجب أن تكون الملفات الفرعية مصفوفة',
                'sub_media.*.file' => 'يجب أن يكون كل ملف فرعي ملفاً صحيحاً',
                'sub_media.*.mimes' => 'يجب أن تكون الملفات الفرعية بصيغة: jpeg, png, jpg, gif, mp4, webm, أو avi',
                'sub_media.*.max' => 'يجب ألا يتجاوز حجم كل ملف فرعي 100 ميجابايت',
            ]);

            $city = City::create($validated);

            // Handle main media upload
            if ($request->hasFile('main_media')) {
                $mainFile = $request->file('main_media');
                $mimeType = $mainFile->getMimeType();
                
                // Determine file type
                $fileType = str_starts_with($mimeType, 'video') ? 'video' : 'image';
                
                // Store file
                $path = $mainFile->store('cities', 'public');
                
                // Create thumbnail for images
                $thumbnail = null;
                if ($fileType === 'image') {
                    $thumbnail = $path;
                }
                
                // Create main media
                $city->media()->create([
                    'type' => $fileType,
                    'role' => 'main',
                    'url' => $path,
                    'thumbnail' => $thumbnail,
                ]);
            }

            // Handle sub media uploads
            if ($request->hasFile('sub_media')) {
                foreach ($request->file('sub_media') as $file) {
                    $mimeType = $file->getMimeType();
                    $fileType = str_starts_with($mimeType, 'video') ? 'video' : 'image';
                    
                    $path = $file->store('cities', 'public');
                    
                    $thumbnail = null;
                    if ($fileType === 'image') {
                        $thumbnail = $path;
                    }
                    
                    $city->media()->create([
                        'type' => $fileType,
                        'role' => 'sub',
                        'url' => $path,
                        'thumbnail' => $thumbnail,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المدينة والملفات بنجاح.',
                'redirect' => route('admin.cities.index'),
                'city' => $city
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
    public function show(City $city)
    {
        return response()->view('admin.countries.show', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        $countries = Country::select('id', 'name')->get();
        return response()->view('admin.cities.edit', compact('city', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        try {
            $validated = $request->validate([
                'country_id'   => 'required|exists:countries,id',
                'name'         => 'required|string|max:255|unique:cities,name,' . $city->id,
                'name_ar'      => 'nullable|string|max:255',
                'name_en'      => 'nullable|string|max:255',
                'native_name'  => 'nullable|string|max:255',
                'region'       => 'nullable|string|max:100',
                'subregion'    => 'nullable|string|max:100',
                'latitude'     => 'nullable|numeric',
                'longitude'    => 'nullable|numeric',
                'population'   => 'nullable|integer|min:0',
                'main_media'   => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,avi|max:102400',
                'sub_media'    => 'nullable|array',
                'sub_media.*'  => 'file|mimes:jpeg,png,jpg,gif,mp4,webm,avi|max:102400',
            ], [
                'country_id.required' => 'الدولة مطلوبة',
                'country_id.exists' => 'الدولة المحددة غير موجودة',
                'name.required' => 'اسم المدينة مطلوب',
                'name.string' => 'يجب أن يكون اسم المدينة نصاً',
                'name.max' => 'يجب ألا يتجاوز اسم المدينة 255 حرفاً',
                'name.unique' => 'هذا الاسم مستخدم بالفعل، يرجى اختيار اسم آخر',
                'name_ar.max' => 'يجب ألا يتجاوز الاسم بالعربية 255 حرفاً',
                'name_en.max' => 'يجب ألا يتجاوز الاسم بالإنجليزية 255 حرفاً',
                'native_name.max' => 'يجب ألا يتجاوز الاسم المحلي 255 حرفاً',
                'region.max' => 'يجب ألا يتجاوز المنطقة 100 حرف',
                'subregion.max' => 'يجب ألا يتجاوز المنطقة الفرعية 100 حرف',
                'latitude.numeric' => 'يجب أن يكون خط العرض رقماً',
                'longitude.numeric' => 'يجب أن يكون خط الطول رقماً',
                'population.integer' => 'يجب أن يكون عدد السكان رقماً صحيحاً',
                'population.min' => 'يجب أن يكون عدد السكان صفر أو أكثر',
                'main_media.file' => 'يجب أن يكون الملف الرئيسي ملفاً صحيحاً',
                'main_media.mimes' => 'يجب أن يكون الملف الرئيسي بصيغة: jpeg, png, jpg, gif, mp4, webm, أو avi',
                'main_media.max' => 'يجب ألا يتجاوز حجم الملف الرئيسي 100 ميجابايت',
                'sub_media.array' => 'يجب أن تكون الملفات الفرعية مصفوفة',
                'sub_media.*.file' => 'يجب أن يكون كل ملف فرعي ملفاً صحيحاً',
                'sub_media.*.mimes' => 'يجب أن تكون الملفات الفرعية بصيغة: jpeg, png, jpg, gif, mp4, webm, أو avi',
                'sub_media.*.max' => 'يجب ألا يتجاوز حجم كل ملف فرعي 100 ميجابايت',
            ]);

            $city->update($validated);

            // Handle main media upload
            if ($request->hasFile('main_media')) {
                $mainFile = $request->file('main_media');
                $mimeType = $mainFile->getMimeType();
                
                // Determine file type
                $fileType = str_starts_with($mimeType, 'video') ? 'video' : 'image';
                
                // Store file
                $path = $mainFile->store('cities', 'public');
                
                // Create thumbnail for images
                $thumbnail = null;
                if ($fileType === 'image') {
                    // Simple thumbnail - just store the same path (can be enhanced with image processing)
                    $thumbnail = $path;
                }
                
                // Delete old main media
                $city->media()->where('role', 'main')->delete();
                
                // Create new main media
                $city->media()->create([
                    'type' => $fileType,
                    'role' => 'main',
                    'url' => $path,
                    'thumbnail' => $thumbnail,
                ]);
            }

            // Handle sub media uploads
            if ($request->hasFile('sub_media')) {
                // Delete old sub media
                $city->media()->where('role', 'sub')->delete();
                
                foreach ($request->file('sub_media') as $file) {
                    $mimeType = $file->getMimeType();
                    $fileType = str_starts_with($mimeType, 'video') ? 'video' : 'image';
                    
                    $path = $file->store('cities', 'public');
                    
                    $thumbnail = null;
                    if ($fileType === 'image') {
                        $thumbnail = $path;
                    }
                    
                    $city->media()->create([
                        'type' => $fileType,
                        'role' => 'sub',
                        'url' => $path,
                        'thumbnail' => $thumbnail,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المدينة والملفات بنجاح.',
                'redirect' => route('admin.cities.index'),
                'city' => $city
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
    public function destroy(City $city)
    {
      try {
            $city->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المدينة بنجاح.',
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
