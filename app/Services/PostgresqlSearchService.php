<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PostgresqlSearchService
{
    public static function search(array $data)
    {
        $matching_results = [];
        $chunkSize = 50;
        collect($data)->chunk($chunkSize)->each(function ($chunk) use (&$matching_results) {
            foreach ($chunk as $row) {
                $ar_matching_result = DB::table('main_data')
                    ->select('description_ar')
                    ->selectRaw("word_similarity(description_ar, '{$row['description_ar']}') * 100 AS matching_percentage")
                    ->orderByDesc('matching_percentage')
                    ->limit(1)
                    ->get();

                $en_matching_result = DB::table('main_data')
                    ->select('description_en')
                    ->selectRaw("word_similarity(description_en, '{$row['description_en']}') * 100 AS matching_percentage")
                    ->orderByDesc('matching_percentage')
                    ->limit(1)
                    ->get();

                $lt_matching_result = DB::table('main_data')
                    ->select('description_lt')
                    ->selectRaw("word_similarity(description_lt, '{$row['description_lt']}') * 100 AS matching_percentage")
                    ->orderByDesc('matching_percentage')
                    ->limit(1)
                    ->get();

                $matching_row = [
                        'description_ar' => $ar_matching_result->isNotEmpty() ? $ar_matching_result[0]->description_ar : null,
                        'description_ar_matching_percentage' => $ar_matching_result->isNotEmpty() ? (int)$ar_matching_result[0]->matching_percentage : null,
                        'description_en' => $en_matching_result->isNotEmpty() ? $en_matching_result[0]->description_en : null,
                        'description_en_matching_percentage' => $en_matching_result->isNotEmpty() ? (int)$en_matching_result[0]->matching_percentage : null,
                        'description_lt' => $lt_matching_result->isNotEmpty() ? $lt_matching_result[0]->description_lt : null,
                        'description_lt_matching_percentage' => $lt_matching_result->isNotEmpty() ? (int)$lt_matching_result[0]->matching_percentage : null,
                ];

                $matching_results[] = $matching_row;
            }
        });

        return $matching_results;
    }
}
