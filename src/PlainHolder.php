<?php
declare(strict_types=1);

namespace TutuRu\RequestMetadata;

class PlainHolder
{
    private $attributes = [];


    public function set(string $name, $value): void
    {
        $this->attributes[$name] = $value instanceof ValueProviderInterface ? $value->getValue() : $value;
    }


    public function get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }


    public function reset(string $name): void
    {
        unset($this->attributes[$name]);
    }


    public function getList(): array
    {
        return $this->attributes;
    }
}
