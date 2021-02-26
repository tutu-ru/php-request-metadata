<?php
declare(strict_types=1);

namespace TutuRu\Tests\RequestMetadata;

use TutuRu\RequestMetadata\Context;
use TutuRu\RequestMetadata\ContextPacker;
use TutuRu\RequestMetadata\Exception\JsonPackException;
use TutuRu\RequestMetadata\Exception\OverwritingContextException;
use TutuRu\RequestMetadata\Exception\OverwritingGottenContextException;
use TutuRu\RequestMetadata\Exception\RequestMetadataException;
use TutuRu\RequestMetadata\Exception\UndefinedContextPackerException;
use TutuRu\RequestMetadata\RequestMetadata;

class ContextPackerTest extends BaseTest
{
    public function testUndefined()
    {
        $requestMetadata = new RequestMetadata();
        $this->expectException(UndefinedContextPackerException::class);
        $requestMetadata->getContext('test');
    }


    public function testTwo()
    {
        $requestMetadata = new RequestMetadata();
        $this->assertFalse($requestMetadata->hasContextPacker('test_1'));
        $requestMetadata->setContextPacker('test_1', new ContextPacker());
        $this->assertTrue($requestMetadata->hasContextPacker('test_1'));

        $this->assertFalse($requestMetadata->hasContextPacker('test_2'));
        $requestMetadata->setContextPacker('test_2', new ContextPacker());
        $this->assertTrue($requestMetadata->hasContextPacker('test_2'));
        $this->assertTrue($requestMetadata->hasContextPacker('test_1'));
    }


    public function testOverwrite()
    {
        $requestMetadata = new RequestMetadata();
        $requestMetadata->setContextPacker('test_1', new ContextPacker());
        $this->expectException(OverwritingContextException::class);
        $requestMetadata->setContextPacker('test_1', new ContextPacker());
    }


    public function testJsonPackException()
    {
        $requestMetadata = new RequestMetadata();
        $requestMetadata->setContextPacker('test', new ContextPacker());

        $this->expectException(JsonPackException::class);
        $requestMetadata->setContext('test', new Context(['test' => NAN]));
    }
}
