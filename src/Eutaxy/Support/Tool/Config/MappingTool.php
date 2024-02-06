<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Support\Tool\Config;

use Ghjayce\MagicSocket\Eutaxy\Entity\Enum\ActionEnum;
use Ghjayce\MagicSocket\Eutaxy\Support\Tool\ScriptTool;
use Psr\Container\ContainerInterface;

class MappingTool
{
    public static function classGenerate(array $roster, string|object $class): array
    {
        if (empty($class)) {
            return [];
        }
        foreach ($roster as $name) {
            $result[$name] = [
                $class,
                $name
            ];
        }
        return $result ?? [];
    }

    public static function removeBasePath(string $path, string $basePath): string
    {
        return strtr($path, [
            $basePath => '',
        ]);
    }

    public static function pathGenerate(
        array $roster,
        string $path,
        string $methodName = ActionEnum::ACTION_METHOD_NAME_BY_PATH
    ): array
    {
        if (empty($path)) {
            return [];
        }
        $namespace = ucwords(
            trim(
                strtr($path, [
                    '/' => '\\',
                ]),
                '\\'
            )
        );
        foreach ($roster as $name) {
            $result[$name] = [
                "{$namespace}\\".ucfirst($name),
                $methodName
            ];
        }
        return $result ?? [];
    }

    public static function isArrayCallable($callable): bool
    {
        return is_array($callable) && isset($callable[0], $callable[1]);
    }

    public static function toUsableCallable($callable, ContainerInterface $container): callable|bool
    {
        if (self::isArrayCallable($callable)) {
            if (!method_exists(...$callable)) {
                return false;
            }
            $callable[0] = self::getClassFromContainer($callable[0], $container);
        }
        if (is_callable($callable)) {
            return $callable;
        }
        return false;
    }

    public static function getClassFromContainer(mixed $class, ContainerInterface $container)
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
