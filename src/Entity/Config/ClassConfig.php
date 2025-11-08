<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Psr\Container\ContainerInterface;

/**
 * @method $this setNames(array $names)
 * @method array getNames()
 * @method $this setClass(string|object $class)
 * @method string|object getClass()
 * @method $this setHook(Hook|null $hook)
 * @method Hook|null getHook()
 * @method $this setContainer(ContainerInterface $container)
 * @method ContainerInterface getContainer()
 */
class ClassConfig extends Config
{
    public array $names = [];
    public string|object $class = '';

    protected function generateByClass(array $names, string|object $class): array
    {
        if (empty($class)) {
            return [];
        }
        foreach ($names as $name) {
            $result[$name] = [
                $class,
                $name,
            ];
        }
        return $result ?? [];
    }

    protected function generate(): array
    {
        return $this->generateByClass($this->getNames(), $this->getClass());
    }
}
