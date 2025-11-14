<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceAdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price'       => 'sometimes|nullable|numeric|min:0',
            'type'        => 'sometimes|in:show,service,exchange',
            'category_id' => 'sometimes|exists:categories,id',
            'city_id'     => 'sometimes|nullable|exists:cities,id',
            'region_id'   => 'sometimes|nullable|exists:regions,id',
            'is_active'   => 'sometimes|boolean',
            'media'       => 'sometimes|array',
            'media.*'     => 'sometimes|file|mimetypes:image/*,video/*|max:20480',
        ];
    }
    public function messages(): array
    {
        return [
            'title.max'          => 'عنوان الإعلان لا يمكن أن يتجاوز 255 حرفًا.',
            'price.numeric'      => 'السعر يجب أن يكون رقمًا.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',
            'media.*.mimetypes'  => 'يجب أن تكون الملفات صورًا أو فيديوهات فقط.',
            'type.in'            => 'نوع الخدمة يجب أن يكون show أو service أو exchange.',
        ];
    }
}
