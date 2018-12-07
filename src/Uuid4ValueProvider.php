<?php
declare(strict_types=1);

namespace TutuRu\RequestMetadata;

class Uuid4ValueProvider implements ValueProviderInterface
{
    public function getValue()
    {
        return sprintf(
            '%02x%s-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xff),
            date('dmy'),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
