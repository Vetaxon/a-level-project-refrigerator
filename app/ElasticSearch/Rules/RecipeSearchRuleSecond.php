<?php

namespace App\ElasticSearch\Rules;

use ScoutElastic\SearchRule;

class RecipeSearchRuleSecond extends SearchRule
{
    /**
     * @inheritdoc
     */
    public function buildHighlightPayload()
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'plain',
                ],
                'text' => [
                    'type' => 'plain',
                ],
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
                'multi_match' => [
                        'query' => $this->builder->query,
                        'analyzer' => 'russian',
                        'fields' => ['name', 'text'],
                        'minimum_should_match' => '100%'
                    ,
                ],
            ]
        ];
    }
}