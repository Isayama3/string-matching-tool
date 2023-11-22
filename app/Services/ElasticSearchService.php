<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticSearchService
{
    public static function search(array $data)
    {
        $client = ClientBuilder::create()->build();

        $matching_results = [];
        $chunkSize = 50;

        collect($data)->chunk($chunkSize)->each(function ($chunk) use ($client, &$matching_results) {
            foreach ($chunk as $row) {
                $description_ar_query = [
                    'index' => 'main_data_index',
                    'size' => 1,
                    'body' => [
                        'query' => [
                            'match' => [
                                'description_ar' =>  $row['description_ar'],
                            ],
                        ],
                        'highlight' => [
                            'fields' => [
                                'description_ar' => new \stdClass(),
                            ],
                        ],
                    ],
                ];

                $description_en_query = [
                    'index' => 'main_data_index',
                    'size' => 1,
                    'body' => [
                        'query' => [
                            'match' => [
                                'description_en' => $row['description_en'],
                            ],
                        ],
                        'highlight' => [
                            'fields' => [
                                'description_en' => new \stdClass(),
                            ],
                        ],
                    ],
                ];

                $description_lt_query = [
                    'index' => 'main_data_index',
                    'size' => 1,
                    'body' => [
                        'query' => [
                            'match' => [
                                'description_lt' => $row['description_lt'],
                            ],
                        ],
                        'highlight' => [
                            'fields' => [
                                'description_lt' => new \stdClass(),
                            ],
                        ],
                    ],
                ];

                $ar_matching_result = $client->search($description_ar_query)['hits'] ?? [];
                $en_matching_result = $client->search($description_en_query)['hits'] ?? [];
                $lt_matching_result = $client->search($description_lt_query)['hits'] ?? [];

                similar_text($row['description_ar'], $ar_matching_result['hits'][0]['_source']['description_ar'] ?? '', $ar_similarity_percentage);
                similar_text($row['description_en'], $en_matching_result['hits'][0]['_source']['description_en'] ?? '', $en_similarity_percentage);
                similar_text($row['description_lt'], $lt_matching_result['hits'][0]['_source']['description_lt'] ?? '', $lt_similarity_percentage);

                $matching_row = [
                    'description_ar' => $ar_matching_result['hits'][0]['_source']['description_ar'] ?? null,
                    'description_ar_matching_percentage' => (int)$ar_similarity_percentage,
                    'description_en' => $en_matching_result['hits'][0]['_source']['description_en'] ?? null,
                    'description_en_matching_percentage' => (int)$en_similarity_percentage,
                    'description_lt' => $lt_matching_result['hits'][0]['_source']['description_lt'] ?? null,
                    'description_lt_matching_percentage' => (int)$lt_similarity_percentage,
                ];

                $matching_results[] = $matching_row;
            }
        });

        return $matching_results;
    }

    public static function storeData(string $index, array $document_data)
    {
        $client = ClientBuilder::create()->build();

        if ($client->indices()->exists(['index' => $index])) {
            $client->indices()->delete(['index' => $index]);
        }

        $bulk_params = [
            'body' => []
        ];

        foreach ($document_data as $data) {
            $bulk_params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_type' => '_doc'
                ]
            ];

            $bulk_params['body'][] = $data;
        }

        $response = $client->bulk($bulk_params);

        if ($response['errors'] === false)
            return true;

        return false;
    }

    public static function getAllData(string $index)
    {
        $client = ClientBuilder::create()->build();

        $params = [
            'index' => $index,
            'body' => [
                'size' => 1000,
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ];

        $response = $client->search($params);

        if (isset($response['hits']['hits'])) {
            $hits = $response['hits'];
            return $hits;
        }

        return false;
    }
}
