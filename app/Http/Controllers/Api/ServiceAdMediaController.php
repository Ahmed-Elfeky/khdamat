<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceAd;
use App\Models\ServiceAdMedia;
use App\Helpers\ApiResponse;
use App\Http\Requests\StoreServiceAdRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceAdMediaController extends Controller
{
   public function store(StoreServiceAdRequest $request, $adId)
{
    // Validation
    $request->validated();
    // Check if ad exists
    $ad = ServiceAd::find($adId);
    if (!$ad) {
        return ApiResponse::SendResponse(404, 'Service ad not found', []);
    }

    // ---- NEW: Ensure at least one image exists ----
   // ---- Ensure at least one real image exists ----
$hasImage = false;

foreach ($request->file('files') as $file) {
    $extension = strtolower($file->extension());
    $mime = $file->getMimeType();

    if (in_array($extension, ['jpg', 'jpeg', 'png']) && str_contains($mime, 'image')) {
        $hasImage = true;
        break;
    }
}
    if (!$hasImage) {
        return ApiResponse::SendResponse(422, 'يجب رفع صورة واحدة على الأقل', []);
    }
    // ------------------------------------------------

    // Upload files
    $uploadedFiles = [];
    foreach ($request->file('files') as $file) {

        $path = $file->store('service_ads', 'public');
        $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';

        $media = ServiceAdMedia::create([
            'service_ad_id' => $adId,
            'file_path' => $path,
            'type' => $type,
        ]);

        $uploadedFiles[] = [
            'id' => $media->id,
            'url' => asset('storage/' . $path),
            'type' => $type,
        ];
    }

    return ApiResponse::SendResponse(201, 'Files uploaded successfully', $uploadedFiles);
}


    public function destroy($id)
    {
        $media = ServiceAdMedia::find($id);
        if (!$media) {
            return ApiResponse::SendResponse(404, 'Media file not found', []);
        }

        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return ApiResponse::SendResponse(200, 'File deleted successfully', []);
    }
}
