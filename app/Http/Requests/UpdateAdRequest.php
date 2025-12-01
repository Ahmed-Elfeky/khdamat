<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
            'reward'      => 'required_if:type,request|nullable|numeric|min:0',
               // في حالة التبادل
            'exchange'    => 'required_if:type,exchange|nullable|string|max:255',
            'type'        => 'nullable|in:ads,service,exchange,request',
            'category_id' => 'nullable|exists:categories,id',
            'city_id'     => 'nullable|exists:cities,id',
            'region_id'   => 'nullable|exists:regions,id',
            'status'      => 'nullable|in:active,archived,deleted,finished',
            'media'       => 'nullable|array|min:1',
            'media.*'     => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',

        ];
    }


    public function messages(): array
    {
        return [
            'reward.required_if' => 'القيمة المكافئة مطلوبة في حالة الطلب',
            'exchange.required_if' => ' يجب ادخال المنتج المطلوب تبادله في القابل',
            'title.max'          => 'عنوان الإعلان لا يمكن أن يتجاوز 255 حرفًا.',
            'price.numeric'      => 'السعر يجب أن يكون رقمًا.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',
            'media.*.mimetypes'  => 'يجب أن تكون الملفات صورًا أو فيديوهات فقط.',
            'type.in'            => 'نوع الخدمة يجب أن يكون show أو service أو exchange.',
        ];
    }
}
