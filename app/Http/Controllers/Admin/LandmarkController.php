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
            'slug' => 'nullable|string|max:255|unique:landmarks,slug',
            'type' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'sub_images' => 'nullable|array',
            'sub_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        $validated['slug'] = $validated['slug'] ?? \Illuminate\Support\Str::slug($validated['name']);

        $validated['uuid'] = \Illuminate\Support\Str::uuid()->toString();

        $landmark = Landmark::create($validated);

        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $path = $mainImage->store('media/landmarks', 'public');
            $landmark->media()->create([
                'type' => 'image',
                'role' => 'main',
                'url' => '/storage/' . $path,
                'order' => 0,
                'alt_text' => $validated['name'],
            ]);
        }

      
        if ($request->hasFile('sub_images')) {
            foreach ($request->file('sub_images') as $index => $subImage) {
                $path = $subImage->store('media/landmarks', 'public');
                $landmark->media()->create([
                    'type' => 'image',
                    'role' => 'sub',
                    'url' => '/storage/' . $path,
                    'order' => $index + 1,
                    'alt_text' => $validated['name'] . ' صورة فرعية ' . ($index + 1),
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
           'slug' => 'nullable|string|max:255|unique:landmarks,slug,' . $landmark->id,
            'type' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'sub_images' => 'nullable|array',
            'sub_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
         $validated['slug'] = $validated['slug'] ?? \Illuminate\Support\Str::slug($validated['name']);

        $landmark->update($validated);

        if ($request->hasFile('main_image')) {
            $oldMain = $landmark->media()->where('role', 'main')->first();
            if ($oldMain) {
                $oldMain->delete();
            }
            $mainImage = $request->file('main_image');
            $path = $mainImage->store('media/landmarks', 'public');
            $landmark->media()->create([
                'type' => 'image',
                'role' => 'main',
                'url' => '/storage/' . $path,
                'order' => 0,
                'alt_text' => $validated['name'],
            ]);
        }

        if ($request->hasFile('sub_images')) {
            foreach ($request->file('sub_images') as $index => $subImage) {
                $path = $subImage->store('media/landmarks', 'public');
                $landmark->media()->create([
                    'type' => 'image',
                    'role' => 'sub',
                    'url' => '/storage/' . $path,
                    'order' => $index + 1,
                    'alt_text' => $validated['name'] . ' صورة فرعية ' . ($index + 1),
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
   