<?php
declare(strict_types=1);

namespace TutuRu\RequestMetadata;

class ContextPacker
{
    public function unpack(?string $source)
    {
        return $this->createFromData($this->decode($source) ?? []);
    }

    public function pack(Context $context): string
    {
        return json_encode($context);
    }

    protected function decode(?string $source): ?array
    {
        if (empty($source)) {
            return null;
        }
        return json_decode($source, true);
    }

    protected function createFromData(array $data)
    {
        return new Context($data);
    }
}
