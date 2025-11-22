<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Psr\Container\ContainerInterface;

/**
 * ========== property_hook_method ==========
 * @method array getNames()
 * @method object|string getClass()
 * @method Hook|null getHook()
 * @method ContainerInterface|null getContainer()
 *
 * @method $this setNames(array $names)
 * @method $this setClass(object|string $class)
 * @method $this setHook(Hook|null $hook)
 * @method $this setContainer(ContainerInterface|null $container)
 * ========== property_hook_method ==========
 */
class ClassConfig extends Config
{
    public array $names = [];
    public string|object $class = '';

    protected function generateClass(array $names, string|object $class): array
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

    protected function generate(?Option $option = null): array
    {
        return $this->generateClass($this->getNames(), $this->getClass());
    }
}
