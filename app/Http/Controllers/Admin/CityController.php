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
                'name'         => 'required|string|max:255',
                'name_ar'      => 'nullable|string|max:255',
                'name_en'      => 'nullable|string|max:255',
                'native_name'  => 'nullable|string|max:255',
                'region'       => 'nullable|string|max:100',
                'subregion'    => 'nullable|string|max:100',
                'latitude'     => 'nullable|numeric',
                'longitude'    => 'nullable|numeric',
                'population'   => 'nullable|integer|min:0',
            ]);

            $city = City::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المدينة بنجاح.',
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
            ]);

            $city->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المدينة بنجاح.',
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
