<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

 public function rules()
{
    return [
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'price'       => 'nullable|numeric|min:0',
        // الكافئة
        'reward'      => 'required_if:type,request|nullable|numeric|min:0',
        // في حالة التبادل
        'exchange'    => 'required_if:type,exchange|nullable|string|max:255',
        'type'        => 'required|in:ads,service,exchange,request',
        'category_id' => 'required|exists:categories,id',
        'city_id'     => 'nullable|exists:cities,id',
        'region_id'   => 'nullable|exists:regions,id',
        'is_active'   => 'nullable|boolean',
    ];
}



    public function messages(): array
    {
        return [
            'reward.required_if' => 'القيمة المكافئة مطلوبة في حالة الطلب',
            'exchange.required_if' => ' يجب ادخال المنتج المطلوب تبادله في القابل',
            'title.required'     => 'عنوان الإعلان مطلوب.',
            'title.max'          => 'عنوان الإعلان لا يمكن أن يتجاوز 255 حرفًا.',
            'price.numeric'      => 'السعر يجب أن يكون رقمًا.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',
            'media.*.mimetypes'  => 'يجب أن تكون الملفات صورًا أو فيديوهات فقط.',
            'type.in'            => 'نوع الخدمة يجب أن يكون show أو service أو exchange.',
        ];
    }
}
