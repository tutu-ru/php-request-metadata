<?php
declare(strict_types=1);

namespace TutuRu\Tests\RequestMetadata;

use TutuRu\RequestMetadata\RequestMetadata;

class RequestMetadataTest extends BaseTest
{
    public function getLoggableAttributesProvider(): array
    {
        return [
            [[], ['context_test' => 'test', 'uid' => 1], ['uid' => 1]],
            [['excluded_attr'], ['context_test' => 'test', 'uid' => 1, 'excluded_attr' => 'test'], ['uid' => 1]],
            [[], ['context_test' => 'test', 'uid' => 1, 'jwt' => 'f23sdf'], ['uid' => 1]],
        ];
    }
    
    /**
     * @dataProvider getLoggableAttributesProvider
     */
    public function testGetLoggableAttributes(array $excludetAttributes, array $plainAttributes, array $expected): void
    {
        $requestMetaData = new RequestMetadata();
        foreach ($plainAttributes as $key => $value) {
            $requestMetaData->set($key, $value);
        }
        
        $actual = $requestMetaData->getLoggableAttributes($excludetAttributes);
        
        $this->assertEquals($expected, $actual);
    }
}
