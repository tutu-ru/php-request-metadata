<?php
declare(strict_types=1);

namespace TutuRu\RequestMetadata;

use TutuRu\RequestMetadata\Exception\AccessDeniedAttributeException;

class RequestMetadata
{
    public const ATTR_REQUEST_ID = 'RequestId';
    public const ATTR_SESSION_ID = 'sid';
    public const ATTR_USER_ID = 'uid';
    public const ATTR_LOCALIZATION = 'localization';
    public const ATTR_CURRENCY = 'currency';
    public const ATTR_JWT = 'jwt';
    public const ATTR_TUTUID_JWT = 'tutuid_jwt';

    private const DEFAULT_LOG_EXCLUDED_ATTR = [
        self::ATTR_JWT,
        self::ATTR_TUTUID_JWT,
    ];
    
    /** @var PlainHolder */
    private $plainHolder;

    /** @var ContextHolder */
    private $contextHolder;


    public function __construct()
    {
        $this->clear();
    }


    public function init()
    {
        $this->plainHolder->set(self::ATTR_REQUEST_ID, new Uuid4ValueProvider());
    }


    public function clear()
    {
        $this->plainHolder = new PlainHolder();
        $this->contextHolder = new ContextHolder($this->plainHolder);
    }


    public function get(string $attributeName)
    {
        if ($this->contextHolder->isContextAttribute($attributeName)) {
            throw new AccessDeniedAttributeException('Must be used method "getContext" for context property');
        }

        return $this->plainHolder->get($attributeName);
    }


    public function set(string $attributeName, $value)
    {
        $this->plainHolder->set($attributeName, $value);
    }


    public function getAttributes(): array
    {
        return $this->plainHolder->getList();
    }


    public function getPlainAttributes(): array
    {
        $result = [];
        foreach ($this->plainHolder->getList() as $name => $value) {
            if (!$this->contextHolder->isContextAttribute($name)) {
                $result[$name] = $value;
            }
        }

        return $result;
    }
    
    public function getLoggableAttributes(): array
    {
        $attributes = $this->getPlainAttributes();
        foreach (self::DEFAULT_LOG_EXCLUDED_ATTR as $attribute) {
            if (isset($attributes[$attribute])) {
                unset($attributes[$attribute]);
            }
        }
        
        return $attributes;
    }


    public function hasContextPacker(string $name): bool
    {
        return $this->contextHolder->hasPacker($name);
    }


    public function setContextPacker(string $name, ContextPacker $packer): void
    {
        $this->contextHolder->setPacker($name, $packer);
    }


    public function setContext(string $name, Context $context): void
    {
        $this->contextHolder->set($name, $context);
    }


    public function getContext(string $name): Context
    {
        return $this->contextHolder->get($name);
    }


    public function resetContext(string $name): void
    {
        $this->contextHolder->reset($name);
    }
}
