<?php

namespace App\ElasticSearch\Rules;

use ScoutElastic\SearchRule;

class RecipeSearchRuleForUser extends SearchRule
{
    /**
     * @inheritdoc
     */
    public function buildHighlightPayload()
    {
        return [
            'fields' => [
                'ingredients' => [
                    'type' => 'plain',
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function buildQueryPayload()
    {
        return [
            'must' => [
                'match' => [
                    'ingredients' => [
                        'query' => $this->builder->query,
//                        'size' => 100
                    ],
                ],

            ]
        ];
    }
}