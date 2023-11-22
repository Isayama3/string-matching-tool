<?php

namespace App\Http\Requests\Web;

class StockholderDataRequest extends BaseRequest
{
    public function rules()
    {
        return [
            // 'code' => 'required',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_lt' => 'nullable|string'
        ];
    }
}
