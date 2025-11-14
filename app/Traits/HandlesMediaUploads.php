<?php

namespace App\Traits;

use App\Models\ServiceAdMedia;

trait HandlesMediaUploads
{
    /**
     * رفع وحفظ ملفات الميديا (صور / فيديوهات)
     */
    public function handleMediaUpload($request, $adId)
    {
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());

                // تحديد نوع الملف
                $type = in_array($extension, ['mp4', 'mov', 'avi', 'webm']) ? 'video' : 'image';

                // حفظ الملف داخل مجلد التخزين
                $path = $file->store('uploads/service_ads', 'public');

                // إنشاء سجل داخل جدول service_ad_media
                ServiceAdMedia::create([
                    'service_ad_id' => $adId,
                    'file_path' => $path,
                    'type' => $type,
                ]);
            }
        }
    }
}
