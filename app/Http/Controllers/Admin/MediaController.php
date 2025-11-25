<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Delete a media file
     */
    public function destroy(Media $media)
    {
        try {
            // Delete file from storage
            if ($media->url && Storage::disk('public')->exists($media->url)) {
                Storage::disk('public')->delete($media->url);
            }

            // Delete thumbnail if exists
            if ($media->thumbnail && Storage::disk('public')->exists($media->thumbnail)) {
                Storage::disk('public')->delete($media->thumbnail);
            }

            // Delete media record
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الملف بنجاح.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الملف.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
