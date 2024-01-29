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
//            var_dump([345345, $class, $container->has($class)]);
            if ($container->has($class)) {
                return $container->get($class);
            }
        }
        return $class;
    }
}
