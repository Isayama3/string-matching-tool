<?php

namespace App\Http\Requests\Api;

class StockholderDataRequest extends BaseRequest
{
    public function prepareForValidation()
    {
        if (app()->runningInConsole()) {
            return;
        }
    }

    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.*.code' => 'required',
            'data.*.description_ar' => 'required|string',
            'data.*.description_ar_matching_percentage' => 'required|numeric',
            'data.*.description_en' => 'required|string',
            'data.*.description_en_matching_percentage' => 'required|numeric',
            'data.*.description_lt' => 'required|string',
            'data.*.description_lt_matching_percentage' => 'required|numeric',
        ];
    }
}
