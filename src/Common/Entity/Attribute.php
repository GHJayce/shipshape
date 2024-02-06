<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Common\Entity;

class Attribute
{
    public static function make(): static
    {
        return new static;
    }

    public function _getAttributes(): array
    {
        foreach ($this as $key => $value) {
            $result[$key] = $value;
        }
        return $result ?? [];
    }

    public function _fillAttributes(array $variables): self
    {
        foreach ($variables as $variable => $value) {
            if (!is_string($variable)) {
                continue;
            }
            $this->$variable = $value ?? null;
        }
        return $this;
    }

    public function __call($methodName, array $args = [])
    {
        $prefix = '';
        $offset = 0;
        if ($methodName[0] === '_') {
            $offset = 1;
            $prefix = '_';
        }
        $length = 3;
        $action = substr($methodName, $offset, $length);
        $attribute = $prefix.lcfirst(substr($methodName, $length + $offset));
        switch ($action) {
            case 'get':
                return $this->$attribute ?? null;
            case 'set':
                $this->$attribute = $args[0];
                return $this;
        }
        throw new \RuntimeException("unknown method name: {$methodName}");
    }
}
