<?php
declare(strict_types=1);

namespace TutuRu\Tests\RequestMetadata;

use TutuRu\RequestMetadata\Context;
use TutuRu\RequestMetadata\ContextPacker;
use TutuRu\RequestMetadata\RequestMetadata;

class PlainValuesTest extends BaseTest
{
    use ContextFixturesTrait;


    public function testInitAndSetter()
    {
        $requestMetadata = new RequestMetadata();

        $this->assertNull($requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));
        $this->assertEquals([], $requestMetadata->getAttributes());
        $this->assertEquals([], $requestMetadata->getPlainAttributes());

        $requestMetadata->init();
        $this->assertNotNull($requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));

        $testValue = 'xyz';
        $requestMetadata->set(RequestMetadata::ATTR_REQUEST_ID, $testValue);
        $this->assertEquals($testValue, $requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));

        $requestMetadata->init();
        $this->assertEquals(36, strlen($requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID)));
    }


    public function testClear()
    {
        $requestMetadata = new RequestMetadata();

        $requestId = 'test-id';
        $requestMetadata->set(RequestMetadata::ATTR_REQUEST_ID, $requestId);
        $contextName = 'analytics-common';
        $requestMetadata->setContextPacker($contextName, new ContextPacker());
        $requestMetadata->setContext($contextName, new Context($this->getContextData()));
        $this->assertEquals(5, $requestMetadata->getContext($contextName)->getProperty('count'));
        $this->assertEquals($requestId, $requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));
        $this->assertNotEmpty($requestMetadata->getAttributes());
        $this->assertNotEmpty($requestMetadata->getPlainAttributes());
        $this->assertTrue($requestMetadata->hasContextPacker($contextName));

        $requestMetadata->clear();

        $this->assertFalse($requestMetadata->hasContextPacker($contextName));
        $this->assertNull($requestMetadata->get(RequestMetadata::ATTR_REQUEST_ID));
        $this->assertEmpty($requestMetadata->getAttributes());
        $this->assertEmpty($requestMetadata->getPlainAttributes());
    }
}
