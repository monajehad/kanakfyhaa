<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsBar;
use App\Models\NewsBarItem;

class NewsBarController extends Controller
{
    /**
     * Display a listing of the news bar resources.
     */
    public function index()
    {
        $newsBars = NewsBar::with('items')->get();
        return view('admin.news_bar.index', compact('newsBars'));
    }

    /**
     * Show the form for creating a new news bar.
     */
    public function create()
    {
        return view('admin.news_bar.create');
    }

    /**
     * Store a newly created news bar in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'speed' => 'required|numeric|min:1',
            'direction' => 'required|string',
            'pause_on_hover' => 'required|boolean',
            'theme' => 'nullable|string',
            'item_space' => 'nullable|integer|min:0',
            'items' => 'array',
            'items.*.text' => 'required|string',
            'items.*.active' => 'boolean',
            'items.*.order' => 'integer|min:0',
        ]);

        // Only mass assign NewsBar fields
        $newsBar = NewsBar::create([
            'speed' => $validated['speed'],
            'direction' => $validated['direction'],
            'pause_on_hover' => $validated['pause_on_hover'],
            'theme' => $validated['theme'] ?? null,
            'item_space' => $validated['item_space'] ?? null,
        ]);

        $createdItems = [];
        if (isset($validated['items']) && is_array($validated['items'])) {
            foreach ($validated['items'] as $itemData) {
                $createdItems[] = $newsBar->items()->create($itemData);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'NewsBar created successfully.',
            'data' => [
                'newsBar' => $newsBar->load('items'),
            ]
        ]);
    }

    /**
     * Display the specified news bar.
     */
    public function show(string $id)
    {
        $newsBar = NewsBar::with('items')->findOrFail($id);
        return view('admin.news_bar.show', compact('newsBar'));
    }

    /**
     * Show the form for editing the specified news bar.
     */
    public function edit(string $id)
    {
        $newsBar = NewsBar::with('items')->findOrFail($id);
        return view('admin.news_bar.edit', compact('newsBar'));
    }

    /**
     * Update the specified news bar in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'speed' => 'required|numeric|min:1',
                'direction' => 'required|string',
                'pause_on_hover' => 'required|boolean',
                'theme' => 'nullable|string',
                'item_space' => 'nullable|integer|min:0',
                'items' => 'array',
                'items.*.id' => 'nullable|integer|exists:news_bar_items,id',
                'items.*.text' => 'required|string',
                'items.*.active' => 'boolean',
                'items.*.order' => 'integer|min:0',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle DB connection error gracefully for AJAX/axios requests
            return response()->json([
                'success' => false,
                'message' => 'تعذر الاتصال بقاعدة البيانات، الرجاء التأكد من الإعدادات أو المحاولة لاحقاً.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Other validation/general errors
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من صحة البيانات.',
                'error' => $e->getMessage(),
            ], 500);
        }

        try {
            $newsBar = NewsBar::findOrFail($id);
            $newsBar->update([
                'speed' => $validated['speed'],
                'direction' => $validated['direction'],
                'pause_on_hover' => $validated['pause_on_hover'],
                'theme' => $validated['theme'] ?? null,
                'item_space' => $validated['item_space'] ?? null,
            ]);

            $updatedItems = [];
            $inputItems = isset($validated['items']) && is_array($validated['items']) ? $validated['items'] : [];

            // Gather IDs of items sent from client
            $inputItemIds = collect($inputItems)
                ->pluck('id')
                ->filter()
                ->map(function ($id) {
                    return (int) $id;
                })
                ->toArray();

            // Get all current item IDs in the database for this news bar
            $existingItemIds = $newsBar->items()->pluck('id')->toArray();

            // Delete items that exist in DB but are not present in the new input
            $idsToDelete = array_diff($existingItemIds, $inputItemIds);
            if (!empty($idsToDelete)) {
                NewsBarItem::whereIn('id', $idsToDelete)->delete();
            }

            // Update existing and add new
            foreach ($inputItems as $itemData) {
                if (isset($itemData['id'])) {
                    $item = NewsBarItem::find($itemData['id']);
                    if ($item && $item->news_bar_id == $newsBar->id) {
                        $item->update($itemData);
                        $updatedItems[] = $item;
                    }
                } else {
                    $updatedItems[] = $newsBar->items()->create($itemData);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'NewsBar updated successfully.',
                'data' => [
                    'newsBar' => $newsBar->load('items'),
                ]
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle DB connection error gracefully for AJAX/axios requests
            return response()->json([
                'success' => false,
                'message' => 'تعذر الاتصال بقاعدة البيانات، الرجاء التأكد من الإعدادات أو المحاولة لاحقاً.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث شريط الأخبار.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified news bar from storage.
     */
    public function destroy(string $id)
    {
        $newsBar = NewsBar::findOrFail($id);
        $newsBar->items()->delete();
        $newsBar->delete();

        return response()->json([
            'success' => true,
            'message' => 'NewsBar deleted successfully.',
        ]);
    }
}
