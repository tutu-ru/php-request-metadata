<?php
declare(strict_types=1);

namespace TutuRu\RequestMetadata;

use TutuRu\RequestMetadata\Exception\OverwritingContextException;
use TutuRu\RequestMetadata\Exception\OverwritingGottenContextException;
use TutuRu\RequestMetadata\Exception\UndefinedContextPackerException;

class ContextHolder
{
    public const PREFIX_ATTRIBUTE = 'context_';

    /** @var ContextPacker[] */
    private $packerList = [];

    /** @var Context[] */
    private $contextList = [];

    /** @var PlainHolder */
    private $plainHolder;


    public function __construct(PlainHolder $plainHolder)
    {
        $this->plainHolder = $plainHolder;
    }


    public function hasPacker(string $name): bool
    {
        return isset($this->packerList[$name]);
    }


    public function setPacker(string $name, ContextPacker $packer): void
    {
        if (isset($this->packerList[$name])) {
            throw new OverwritingContextException('Overwrite packer context "' . $name . '"');
        }

        $this->packerList[$name] = $packer;
    }


    public function isContextAttribute(string $attributeName): bool
    {
        return strpos($attributeName, self::PREFIX_ATTRIBUTE) === 0;
    }


    public function get(string $name): Context
    {
        if (isset($this->contextList[$name])) {
            return $this->contextList[$name];
        }

        $this->validateDefinedPacker($name);

        $source = $this->plainHolder->get($this->getAttributeName($name));
        $this->contextList[$name] = $this->packerList[$name]->unpack($source);
        return $this->contextList[$name];
    }


    public function set(string $name, Context $context): void
    {
        if (!empty($this->plainHolder->get($this->getAttributeName($name)))) {
            throw new OverwritingContextException('Overwriting context "' . $name . '"');
        }

        if (!empty($this->contextList[$name])) {
            /**
             * Ошибка возможна если контекст был запрошен на доступ к данным,
             * до того как он был инициализирован. Из за чего могла произойти несогласованность данных.
             * Решается тем что нужно инициализировать контекст раньше чем будет хоть одно обращение к контексту
             */
            throw new OverwritingGottenContextException('Overwriting gotten context "' . $name . '"');
        }

        $this->validateDefinedPacker($name);

        $this->contextList[$name] = $context;
        $this->plainHolder->set($this->getAttributeName($name), $this->packerList[$name]->pack($context));
    }


    public function reset(string $name): void
    {
        unset($this->contextList[$name]);
        $this->plainHolder->reset($this->getAttributeName($name));
    }


    private function validateDefinedPacker(string $name): void
    {
        if (!isset($this->packerList[$name])) {
            throw new UndefinedContextPackerException('Undefined packer for context "' . $name . '"');
        }
    }


    private function getAttributeName(string $name): string
    {
        return self::PREFIX_ATTRIBUTE . $name;
    }
}
