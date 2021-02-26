<?php
declare(strict_types=1);

namespace TutuRu\RequestMetadata;

use TutuRu\RequestMetadata\Exception\JsonPackException;

class ContextPacker
{
    public function unpack(?string $source)
    {
        return $this->createFromData($this->decode($source) ?? []);
    }

    public function pack(Context $context): string
    {
        $packed = json_encode($context);
        if (json_last_error()) {
            throw new JsonPackException(sprintf('Unable to encode context data (json error %s)', json_last_error_msg()));
        }
        return $packed;
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
