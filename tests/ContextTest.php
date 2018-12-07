<?php
declare(strict_types=1);

namespace TutuRu\Tests\RequestMetadata;

use TutuRu\RequestMetadata\Context;
use TutuRu\RequestMetadata\ContextPacker;
use TutuRu\RequestMetadata\Exception\AccessDeniedAttributeException;
use TutuRu\RequestMetadata\Exception\OverwritingGottenContextException;
use TutuRu\RequestMetadata\RequestMetadata;

class ContextTest extends BaseTest
{
    use ContextFixturesTrait;


    public function testUndefinedContext()
    {
        $requestMetadata = new RequestMetadata();
        $requestMetadata->setContextPacker('test', new ContextPacker());
        $context = $requestMetadata->getContext('test');
        $this->assertEmpty($context->getData());
        $this->assertNull($context->getProperty('fake_props'));
    }


    public function testUnpack()
    {
        $requestMetadata = new RequestMetadata();
        $requestMetadata->set('context_test', $this->getContextString());
        $requestMetadata->setContextPacker('test', new ContextPacker());
        $context = $requestMetadata->getContext('test');
        $this->assertInstanceOf(Context::class, $context);
        $this->assertEquals($this->getContextData(), $requestMetadata->getContext('test')->getData());
        $this->assertEquals(5, $context->getProperty('count'));
        $this->assertCount(2, $context->getProperty('items'));
    }


    public function testAccessDeniedContentByAttr()
    {
        $requestMetadata = new RequestMetadata();
        $requestMetadata->set('context_test', $this->getContextString());

        $this->expectException(AccessDeniedAttributeException::class);
        $requestMetadata->get('context_test');
    }


    public function testAccessDeniedContentByAttrWithPacker()
    {
        $requestMetadata = new RequestMetadata();
        $requestMetadata->set('context_test', $this->getContextString());
        $requestMetadata->setContextPacker('test', new ContextPacker());

        $this->expectException(AccessDeniedAttributeException::class);
        $requestMetadata->get('context_test');
    }


    public function testReadBeforeSet()
    {
        $requestMetadata = new RequestMetadata();
        $requestMetadata->setContextPacker('test', new ContextPacker());
        $requestMetadata->getContext('test');

        $this->expectException(OverwritingGottenContextException::class);
        $requestMetadata->setContext('test', new Context(['field' => 'value']));
    }


    public function testReset()
    {
        $requestMetadata = new RequestMetadata();
        $requestMetadata->setContextPacker('test', new ContextPacker());
        $requestMetadata->setContext('test', new Context(['field' => 'old value']));

        $requestMetadata->resetContext('test');
        $requestMetadata->setContext('test', new Context(['field' => 'value']));
        $context = $requestMetadata->getContext('test');
        $this->assertEquals('value', $context->getProperty('field'));
    }
}
