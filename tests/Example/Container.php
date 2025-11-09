<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Example;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    protected array $container = [];

    public function get(string $id)
    {
    }

    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }
}
