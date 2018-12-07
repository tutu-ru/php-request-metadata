<?php
declare(strict_types=1);

namespace TutuRu\Tests\RequestMetadata;

trait ContextFixturesTrait
{
    protected function getContextData(): array
    {
        return [
            'count' => 5,
            'items' => [
                [
                    'name' => 'user ip'
                ],
                [
                    'name' => 'user agent'
                ]
            ]
        ];
    }

    protected function getContextString(): string
    {
        return '{"count":5,"items":[{"name":"user ip"},{"name":"user agent"}]}';
    }
}
