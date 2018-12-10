<?php
declare(strict_types=1);

namespace TutuRu\RequestMetadata;

class ContextFactory
{
    public function createPacker(): ContextPacker
    {
        return new ContextPacker();
    }

    public function createContext(array $data): Context
    {
        return new Context($data);
    }
}
