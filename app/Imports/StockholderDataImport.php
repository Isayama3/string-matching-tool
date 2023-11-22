<?php

namespace App\Imports;

use App\Http\Requests\Web\StockholderDataRequest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use App\Traits\SendResponse;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class StockholderDataImport implements ToCollection, WithValidation, WithChunkReading, SkipsOnFailure
{
    use Importable, SendResponse, RemembersChunkOffset;

    const CODE_INDEX            = 0;
    const DESCRIPTION_AR_INDEX  = 1;
    const DESCRIPTION_EN_INDEX  = 2;
    const DESCRIPTION_LT_INDEX  = 3;

    private $rowData = [];

    public function chunkSize(): int
    {
        return 100;
    }

    public function prepareForValidation($row, $index)
    {
        if ($row[self::CODE_INDEX] == null || trim($row[self::CODE_INDEX]) == '')
            unset($row[self::CODE_INDEX]);

        // if ($row[self::DESCRIPTION_AR_INDEX] == null || trim($row[self::DESCRIPTION_AR_INDEX]) == '')
        //     unset($row[self::DESCRIPTION_AR_INDEX]);

        // if ($row[self::DESCRIPTION_EN_INDEX] == null || trim($row[self::DESCRIPTION_EN_INDEX]) == '')
        //     unset($row[self::DESCRIPTION_EN_INDEX]);

        // if ($row[self::DESCRIPTION_LT_INDEX] == null || trim($row[self::DESCRIPTION_LT_INDEX]) == '')
        //     unset($row[self::DESCRIPTION_LT_INDEX]);

        return $row;
    }

    public function rules(): array
    {
        return [
            // self::CODE_INDEX => "distinct",
            // self::DESCRIPTION_AR_INDEX => "distinct",
            // self::DESCRIPTION_EN_INDEX => "distinct",
            // self::DESCRIPTION_LT_INDEX => "distinct"
        ];
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->appendSessionErrors($failure->row(), $failure->errors());
        }
    }

    public function customValidationAttributes()
    {
        return [
            self::CODE_INDEX                    => __('data.headings.index'),
            self::DESCRIPTION_AR_INDEX          => __('data.headings.description_ar'),
            self::DESCRIPTION_EN_INDEX          => __('data.headings.description_en'),
            self::DESCRIPTION_LT_INDEX          => __('data.headings.description_lt'),
        ];
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // REMOVING THE HEADERS ROW
        if ($this->getChunkOffset() == 1)
            $collection->forget(0);

        $data = [];
        foreach ($collection as $key => $row) {
            $row_data = [
                'code'              => $row[self::CODE_INDEX],
                'description_ar'    => $row[self::DESCRIPTION_AR_INDEX] ?? '',
                'description_en'    => $row[self::DESCRIPTION_EN_INDEX] ?? '',
                'description_lt'    => $row[self::DESCRIPTION_LT_INDEX] ?? '',
            ];

            // ROW VALIDATION
            $data_request = new StockholderDataRequest();
            $validator = Validator::make(
                $row_data,
                $data_request->rules(),
                $data_request->messages(),
                $data_request->attributes()
            );
            $row_num = $key + 1;

            if ($validator->fails())
                $this->appendSessionErrors($row_num, $validator->errors()->all());
            else
                $data[] = $row_data;
        }

        $this->rowData = $data;

        // if (count(session('errors', [])) == 0) {
        //     foreach ($data as $row_data)
        //         StockholderData::create($row_data);
        // MainData::create($row_data);
        // }
    }

    private function appendSessionErrors(int $row_num, array $new_errors): void
    {
        $session_errors = session('errors', []);

        foreach ($new_errors as $new_error) {
            $error_rows = $session_errors[$new_error][$new_error] ?? [];
            $error_rows[] = $row_num;
            $session_errors[$new_error][$new_error] = $error_rows;
        }

        session(['errors' => $session_errors]);
    }

    public function getRowData()
    {
        return $this->rowData;
    }
}
