<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $countries = Country::query();

         
        if ($request->filled('search')) {
            $countries->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('native_name', 'like', '%' . $request->search . '%');
        }

        $countries = $countries->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();

        return response()->view('admin.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return response()->view('admin.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'native_name' => 'nullable|string|max:255',
                'iso2' => 'required|string|max:2|unique:countries,iso2',
                'iso3' => 'required|string|max:3|unique:countries,iso3',
                'numeric_code' => 'required|numeric|unique:countries,numeric_code',
                'phone_code' => 'required|string|max:10',
                'capital' => 'nullable|string|max:255',
                'currency_symbol' => 'nullable|string|max:10',
                'currency_name' => 'nullable|string|max:50',
                'region' => 'nullable|string|max:50',
                'subregion' => 'nullable|string|max:50',
                'cities_count' => 'nullable|integer|min:0',
                'flag_url' => 'nullable|url',
                'timezone' => 'nullable|string|max:50',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'population' => 'nullable|integer|min:0',
                'area' => 'nullable|numeric|min:0',
            ]);

            $country = Country::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الدولة بنجاح.',
                'redirect' => route('admin.countries.index'),
                'country' => $country
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
    public function show(Country $country)
    {
        return response()->view('admin.countries.show', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
         return response()->view('admin.countries.edit', compact('country'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
         try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'native_name' => 'nullable|string|max:255',
                'iso2' => 'required|string|max:2|unique:countries,iso2,' . $country->id,
                'iso3' => 'required|string|max:3|unique:countries,iso3,' . $country->id,
                'numeric_code' => 'required|numeric|unique:countries,numeric_code,' . $country->id,
                'phone_code' => 'required|string|max:10',
                'capital' => 'nullable|string|max:255',
                'currency_symbol' => 'nullable|string|max:10',
                'currency_name' => 'nullable|string|max:50',
                'region' => 'nullable|string|max:50',
                'subregion' => 'nullable|string|max:50',
                'cities_count' => 'nullable|integer|min:0',
                'flag_url' => 'nullable|url',
                'timezone' => 'nullable|string|max:50',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'population' => 'nullable|integer|min:0',
                'area' => 'nullable|numeric|min:0',
            ]);

            $country->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الدولة بنجاح.',
                'redirect' => route('admin.countries.index'),
                'country' => $country
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
    public function destroy(Country $country)
    {
      try {
            $country->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الدولة بنجاح.',
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
