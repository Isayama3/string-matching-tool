<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource as Resource;

class StockHolderDataResource extends Resource
{
    public function toArray($request)
    {
        return [
            'description_ar' => $this['description_ar'],
            'description_ar_matching_percentage' => $this['description_ar_matching_percentage'],
            'description_en' => $this['description_en'],
            'description_en_matching_percentage' => $this['description_en_matching_percentage'],
            'description_lt' => $this['description_lt'],
            'description_lt_matching_percentage' => $this['description_lt_matching_percentage'],
        ];
    }
}
