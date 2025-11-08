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
    public function index()
    {
        $sliders = Slider::orderBy('order')->paginate(16);
        // Retain blade view for index, since listing is usually a page
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'nullable|string|max:255',
            'subtitle'     => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
            'button_text'  => 'nullable|string|max:255',
            'button_url'   => 'nullable|string|max:255',
            'order'        => 'nullable|integer',
            'active'       => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $data['image'] = $file->store('sliders', 'public');
        }

        $data['active'] = $request->has('active') ? $request->boolean('active') : true;
        $slider = Slider::create($data);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء السلايدر بنجاح.',
            'slider' => $slider
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        // Optionally return JSON for single slider if needed by Axios, otherwise retain view
        return response()->json([
            'success' => true,
            'slider' => $slider,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title'        => 'nullable|string|max:255',
            'subtitle'     => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
            'button_text'  => 'nullable|string|max:255',
            'button_url'   => 'nullable|string|max:255',
            'order'        => 'nullable|integer',
            'active'       => 'nullable|boolean',
        ]);

        // Handle image upload or keep old one
        if ($request->hasFile('image')) {
            // Remove old image if it exists
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $file = $request->file('image');
            $data['image'] = $file->store('sliders', 'public');
        }

        $data['active'] = $request->has('active') ? $request->boolean('active') : $slider->active;
        $slider->update($data);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث السلايدر بنجاح.',
            'slider' => $slider
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        // Delete image if exists
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف السلايدر بنجاح.'
        ]);
    }
}
