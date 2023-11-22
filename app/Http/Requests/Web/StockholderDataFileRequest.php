<?php

namespace App\Http\Requests\Web;

class StockholderDataFileRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'data_excel_file' => 'required|mimes:xlsx,csv,excel',
        ];
    }
}
