<?php

namespace App\ElasticSearch\Rules;

use ScoutElastic\SearchRule;

class RecipeSearchRuleFirst extends SearchRule
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
                    'name' => [
                        'query' => $this->builder->query,
                        'analyzer' => 'russian',
                        'minimum_should_match' => '100%'
                    ],
                ],
            ]
        ];
    }
}