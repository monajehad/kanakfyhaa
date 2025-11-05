<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artifact;
use App\Models\City;
use App\Models\Landmark;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ArtifactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $artifacts = Artifact::query();

        if ($request->filled('search')) {
            $artifacts->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('landmark')) {
            $artifacts->where('landmark_id', $request->landmark);
        }

        $artifacts = $artifacts->with('landmark', 'media', 'landmark.city')
                               ->orderBy('updated_at', 'desc')
                               ->paginate(20)
                               ->withQueryString();

        $landmarks = Landmark::with('city')->get();

        return response()->view('admin.artifacts.index', compact('artifacts', 'landmarks'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $landmarks = Landmark::with('city')->get();
        return response()->view('admin.artifacts.create', compact('landmarks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'landmark_id' => 'required|exists:landmarks,id',
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'sub_images' => 'nullable|array',
            'sub_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $validated['uuid'] = \Illuminate\Support\Str::uuid()->toString();

        $artifact = Artifact::create($validated);

        // Main image
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $path = $mainImage->store('media/artifacts', 'public');
            $artifact->media()->create([
                'type' => 'image',
                'role' => 'main',
                'url' => '/storage/' . $path,
                'order' => 0,
                'alt_text' => $validated['name'],
            ]);
        }

        // Sub images
        if ($request->hasFile('sub_images')) {
            foreach ($request->file('sub_images') as $index => $subImage) {
                $path = $subImage->store('media/artifacts', 'public');
                $artifact->media()->create([
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
            'redirect' => route('admin.artifacts.index'),
            'message' => 'تم إنشاء الأثر بنجاح.',
            'artifact' => $artifact->load('media', 'landmark', 'landmark.city'),
        ]);
    }


    /**
     * Display the specified resource.
     */
       public function show(Artifact $artifact)
    {
        $artifact->load('landmark', 'media', 'landmark.city');
        return response()->view('admin.artifacts.show', compact('artifact'));
    }
    /**
     * Show the form for editing the specified resource.
     */
     public function edit(Artifact $artifact)
    {
        $landmarks = Landmark::with('city')->get();
        $artifact->load('media', 'landmark', 'landmark.city');
        return response()->view('admin.artifacts.edit', compact('artifact', 'landmarks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artifact $artifact)
    {
        $validated = $request->validate([
            'landmark_id' => 'required|exists:landmarks,id',
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'sub_images' => 'nullable|array',
            'sub_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $artifact->update($validated);

        if ($request->hasFile('main_image')) {
            $oldMain = $artifact->media()->where('role', 'main')->first();
            if ($oldMain) $oldMain->delete();

            $mainImage = $request->file('main_image');
            $path = $mainImage->store('media/artifacts', 'public');
            $artifact->media()->create([
                'type' => 'image',
                'role' => 'main',
                'url' => '/storage/' . $path,
                'order' => 0,
                'alt_text' => $validated['name'],
            ]);
        }

        if ($request->hasFile('sub_images')) {
            foreach ($request->file('sub_images') as $index => $subImage) {
                $path = $subImage->store('media/artifacts', 'public');
                $artifact->media()->create([
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
            'redirect' => route('admin.artifacts.index'),
            'message' => 'تم تحديث الأثر بنجاح.',
            'artifact' => $artifact->load('media', 'landmark', 'landmark.city'),
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artifact $artifact)
    {
        foreach ($artifact->media as $media) {
            $media->delete();
        }

        $artifact->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الأثر بنجاح.',
        ]);
    }
}
   