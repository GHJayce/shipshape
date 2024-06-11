<?php

declare(strict_types=1);

namespace GhjayceExample\Shipshape\Mock;

trait IgnoreAccessPermission
{
    public function __call($name, array $args = [])
    {
        if (method_exists($this, $name)) {
            return $this->$name(...$args);
        }
        return parent::__call($name, $args);
    }

    public function __get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }

    public function __set(string $name, mixed $value): void
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    public function __isset(string $name): bool
    {
        return property_exists($this, $name);
    }
}
