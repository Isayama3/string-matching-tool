<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StockholderDataRequest;
use App\Http\Resources\StockHolderDataResource;
use App\Models\StockholderData;
use App\Traits\SendResponse;
use Helper\CacheHelper;
use Illuminate\Http\Request;

class StockholderDataController extends Controller
{
    use SendResponse;

    public function index(Request $request)
    {
        // $user_id = auth()->user()->id;
        $user_id = 1;

        $matching_data = CacheHelper::retrieveCachedPaginatedData('matching-data-' . $user_id, $request->requested_page ?? 1);
        return $this->sendResponse(
            StockHolderDataResource::collection($matching_data),
        );
    }

    public function store(StockholderDataRequest $request)
    {
        $user_id = 1;
        StockholderData::insert($request->validated()['data']);
        CacheHelper::forgetCachedPaginatedData('matching-data-' . $user_id, $request->requested_page ?? 1);

        return $this->sendResponse(
            StockHolderDataResource::collection($request->validated()['data']),
            'تم الاضافة بنجاح',
            true,
            201
        );
    }
}
