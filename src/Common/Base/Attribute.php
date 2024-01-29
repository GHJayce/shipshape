<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Common\Base;

class Attribute
{
    public function _getAttributes(): array
    {
        foreach ($this as $key => $value) {
            $result[$key] = $value;
        }
        return $result ?? [];
    }

    public function _fillAttributes(array $variables): void
    {
        foreach ($variables as $variable => $value) {
            $this->$variable = $value;
        }
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
                return $this->$attribute;
            case 'set':
                $this->$attribute = $args[0];
                return $this;
        }
        throw new \RuntimeException("unknown method name: {$methodName}");
    }
}