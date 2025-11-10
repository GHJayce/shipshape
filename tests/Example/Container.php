<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Example;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    protected array $container = [];

    public function set(string $id, mixed $value): self
    {
        $this->container[$id] = $value;
        return $this;
    }

    public function get(string $id)
    {
        if (!$this->has($id)) {
            if (class_exists($id)) {
                $this->set($id, new $id);
            }
        }
        return $this->container[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }
}
