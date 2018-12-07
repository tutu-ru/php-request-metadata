<?php
declare(strict_types=1);

namespace TutuRu\Tests\RequestMetadata;

use TutuRu\RequestMetadata\Context;
use TutuRu\RequestMetadata\ContextFactory;
use TutuRu\RequestMetadata\Exception\OverwritingContextException;
use TutuRu\RequestMetadata\RequestMetadata;

class DefinedContextTest extends BaseTest
{
    use ContextFixturesTrait;

    private const _CONTEXT_NAME = 'test';


    public function testAccessContext()
    {
        $requestMetadata = $this->createRequestMetadataWithContext();
        $data = $this->getContextData();
        $context = $requestMetadata->getContext(self::_CONTEXT_NAME);
        $this->assertEquals($data['count'], $context->getProperty('count'));
        $this->assertEquals($data['items'], $context->getProperty('items'));
    }


    public function testListAttributes()
    {
        $requestMetadata = $this->createRequestMetadataWithContext();
        $requestMetadata->set(RequestMetadata::ATTR_USER_ID, 100);
        $expected = [
            RequestMetadata::ATTR_USER_ID => 100,
            'context_test'                => $this->getContextString(),
        ];
        $this->assertEquals($expected, $requestMetadata->getAttributes());
        $this->assertEquals(
            [RequestMetadata::ATTR_USER_ID => 100],
            $requestMetadata->getPlainAttributes()
        );
    }


    public function testOverwrite()
    {
        $requestMetadata = $this->createRequestMetadataWithContext();
        $this->expectException(OverwritingContextException::class);
        $requestMetadata->setContext(self::_CONTEXT_NAME, new Context(['test' => 'asd']));
    }


    public function testReset()
    {
        $requestMetadata = $this->createRequestMetadataWithContext();
        $requestMetadata->resetContext(self::_CONTEXT_NAME);

        $context = $requestMetadata->getContext(self::_CONTEXT_NAME);
        $this->assertEmpty($context->getData());
        $this->assertNull($context->getProperty('count'));
    }


    private function createRequestMetadataWithContext(): RequestMetadata
    {
        $requestMetadata = new RequestMetadata();
        $factory = new ContextFactory();
        $requestMetadata->setContextPacker(self::_CONTEXT_NAME, $factory->createPacker());
        $data = $this->getContextData();
        $requestMetadata->setContext(self::_CONTEXT_NAME, $factory->createContext($data));
        return $requestMetadata;
    }
}
