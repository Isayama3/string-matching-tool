<?php

namespace App\Console\Commands;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

class MakeMainDataElasticsearchIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-main-data-elasticsearch-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = ClientBuilder::create()->build();

        $params = [
            'index' => 'main_data_index', // Replace with your desired index name
            'body' => [
                'settings' => [
                    'number_of_shards' => 1, // Set the number of shards
                    'number_of_replicas' => 0 // Set the number of replicas
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'description_ar' => [
                            'type' => 'text'
                        ],
                        'description_en' => [
                            'type' => 'text'
                        ],
                        'description_lt' => [
                            'type' => 'text'
                        ],
                    ]
                ]
            ]
        ];

        $response = $client->indices()->create($params);

        var_dump($response);
    }
}
