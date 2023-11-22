<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class DataSampleExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return new Collection();
    }

    public function headings(): array
    {
        $translatedHeadings = [];
        $originalHeadings = [
            'code',
            'description_ar',
            'description_en',
            'description_lt',
        ];

        foreach ($originalHeadings as $heading) {
            $translatedHeadings[] = __('data_excel.headings.' . $heading);
        }

        return $translatedHeadings;
    }
}
