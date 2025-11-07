<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceAd;
use App\Models\ServiceAdMedia;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceAdMediaController extends Controller
{
    public function store(Request $request, $adId)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480', // 20MB
        ]);

        $ad = ServiceAd::find($adId);
        if (!$ad) {
            return ApiResponse::SendResponse(404, 'Service ad not found', []);
        }

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
