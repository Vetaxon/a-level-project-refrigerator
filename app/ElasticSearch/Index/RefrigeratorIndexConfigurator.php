<?php

namespace App\ElasticSearch\Index;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class RefrigeratorIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /*
     * Index name
     */
    protected $name = 'refrigerator';

    /*
     * Index settings
     */

    protected $settings =
        [
            'analysis' =>
                [
                    "filter" => [
                        "russian_stop" => [
                            "type" => "stop",
                            "stopwords" => "_russian_"
                        ],
                        "russian_stemmer" => [
                            "type" => "stemmer",
                            "language" => "russian",
                        ],
                    ],
                    "analyzer" => [
                        "russian" => [
                            "tokenizer" => "standard",
                            "filter" => [
                                "lowercase",
                                "russian_stop",
                                "russian_stemmer",
                            ]
                        ]
                    ],
                ],

            "number_of_shards" => 50,
            "number_of_replicas" => 1

        ];

}


