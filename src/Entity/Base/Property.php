<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Base;

use Ghjayce\Shipshape\Exception\ShipshapeEntityException;

class Property
{
    public static function make(): static
    {
        return new static;
    }

    public function takeAllProperty(): array
    {
        foreach ($this as $key => $value) {
            $result[$key] = $value;
        }
        return $result ?? [];
    }

    public function fillProperty(array $properties): self
    {
        foreach ($properties as $property => $value) {
            if (!is_string($property) || !property_exists($this, $property)) {
                continue;
            }
            $this->$property = $value ?? null;
        }
        return $this;
    }

    /**
     * @throws ShipshapeEntityException
     */
    public function __call($methodName, array $args = [])
    {
        $offset = 0;
        $length = 3;
        $action = substr($methodName, $offset, $length);
        $attribute = lcfirst(substr($methodName, $length + $offset));
        switch ($action) {
            case 'get':
                return $this->$attribute ?? null;
            case 'set':
                $this->$attribute = $args[0];
                return $this;
        }
        throw new ShipshapeEntityException("unknown method name: {$methodName}");
    }
}
