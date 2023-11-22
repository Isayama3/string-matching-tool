<?php

namespace App\Traits;

trait SendResponse
{
    /**
     * send the response
     *
     * @param object $result
     * @param string $message
     * @param bool $is_success
     * @param int $status_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message = 'Success.', $is_success = true, $status_code = 200, $withmeta = false, $permissions = false, $actions = [], $columns = [], $filter = [])
    {

        $result_key = $is_success ? 'data' : 'errors';
        $meta = ($withmeta) ? [
            'total' => $result->total(),
            'from' => $result->firstItem(),
            'to' => $result->lastItem(),
            'count' => $result->count(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            "pages" => 0,
        ] : [];

        $response = [
            'endpointName' => app('request')->route()->getName(),
            'is_success' => $is_success,
            'status_code' => $status_code,
            'message' => $message,
            ...$meta,
        ];

        if ($permissions) {
            $response['columns'] = $columns;
            $response['actions'] = $actions;
            $response['filter'] = $filter;
        }
        $response[$result_key] = $result;




        if (($withmeta)) $response["pages"] = ceil($response["total"] / $response["per_page"]);

        return response()->json($response, $status_code);
    }


    public function easyResponse($result = [], $status = 200)
    {
        return response()->json($result, $status);
    }

    /**
     * send the exception response
     *
     * @param \Exception $e
     * @param bool $report
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendExceptionResponse($e, $report = true)
    {
        if ($report) {
            report($e);
        }

        $message = 'OOPS! there is a problem in our side! we got your problem and we will fix that very soon.';

        return $this->sendResponse([], $message, false, 500);
    }

    public function ErrorMessage($msg)
    {
        $response = [
            'endpointName' => app()->runningInConsole() ? null : app('request')->route()->getName(),
            'is_success' => false,
            'status_code' => 422,
            'message' => $msg,
            'errors' => [
                'msg' => $msg
            ],
        ];
        return response()->json($response, 422);
    }
    public function ErrorValidate($errors)
    {
        $response = [
            'endpointName' => app()->runningInConsole() ? null : app('request')->route()->getName(),
            'is_success' => false,
            'status_code' => 422,
            'message' => 'validation error',
            'errors' => $errors

        ];
        return response()->json($response, 422);
    }
    public function SuccessMessage($msg = '')
    {
        $response = [
            'endpointName' => app()->runningInConsole() ? null : app('request')->route()->getName(),
            'is_success' => true,
            'status_code' => 200,
            'message' => $msg,
            'data' => [
                'msg' => $msg
            ],
        ];
        return response()->json($response, 200);
    }

    public function shortSuccess($data, $msg = null)
    {
        $response = [
            'endpointName' => app()->runningInConsole() ? null : app('request')->route()->getName(),
            'is_success' => true,
            'status_code' => 200,
            'message' =>  $msg ?? 'تم',
            'data' => $data
        ];
        return response()->json($response, 200);
    }
}
