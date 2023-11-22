<?php

namespace App\Http\Controllers\Web;

use App\Exports\DataSampleExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StockholderDataFileRequest as FormRequest;
use App\Imports\StockholderDataImport;
use App\Services\ElasticSearchService;
use App\Services\PostgresqlSearchService;
use Helper\CacheHelper;
use Maatwebsite\Excel\Facades\Excel;

class StockholderDataExcelController extends Controller
{
    public function importExcelFile(FormRequest $request)
    {
        try {
            $dataImport = new StockholderDataImport();
            Excel::import($dataImport, $request->data_excel_file);

            $errors = session('errors', []);
            session(['errors' => []]);

            $row_data = $dataImport->getRowData();

            // MainData::insert($row_data);

            $startPostgreSQL = microtime(true);
            $resultPostgreSQL = PostgresqlSearchService::search($row_data);
            $endPostgreSQL = microtime(true);
            $timeTakenPostgreSQL = $endPostgreSQL - $startPostgreSQL;
            echo "Time taken for PostgreSQL search: " . $timeTakenPostgreSQL . " seconds\n";

            $startElasticSearch = microtime(true);
            $resultElasticSearch = ElasticSearchService::search($row_data);
            $endElasticSearch = microtime(true);
            $timeTakenElasticSearch = $endElasticSearch - $startElasticSearch;
            echo "Time taken for ElasticSearch search: " . $timeTakenElasticSearch . " seconds\n";

            die();

            $result = PostgresqlSearchService::search($row_data);
            // $result = ElasticSearchService::search($row_data);

            // $user_id = auth()->user()->id;
            $user_id = 1;
            CacheHelper::cachePaginatedData($result, config('cache-tags.matching_data') . $user_id);

            if (count($errors) == 0)
                return redirect()->back();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors($errors);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages['row'] = $failure->row(); // row that went wrong
                $messages['attribute'] = $failure->attribute(); // either heading key (if using heading row concern) or column index
                $messages['errors'] = $failure->errors(); // Actual error messages from Laravel validator
                $messages['values'] = $failure->values(); // The values of the row that has failed.
            }
            return $messages;
        }
    }

    public function sampleExcelFile()
    {
        return Excel::download(new DataSampleExport, 'sample.xlsx');
    }
}
