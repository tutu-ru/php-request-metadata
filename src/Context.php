<?php
declare(strict_types=1);

namespace TutuRu\RequestMetadata;

use JsonSerializable;

class Context implements JsonSerializable
{
    /** @var array */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getProperty(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
