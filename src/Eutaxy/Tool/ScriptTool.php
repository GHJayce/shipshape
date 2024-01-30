<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Tool;

use Psr\Container\ContainerInterface;

class ScriptTool
{
    public static function getClassInstanceByContainer(mixed $class, ContainerInterface $container)
    {
        if (empty($class)) {
            return $class;
        }
        if (is_string($class)) {
            if ($container->has($class)) {
                return $container->get($class);
            }
        }
        return $class;
    }
}
