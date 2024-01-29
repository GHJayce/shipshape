<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Work\Tool\Config;

use Psr\Container\ContainerInterface;

class ConfigMappingTool
{
    public static function buildByClass(array $roster, string $class): array
    {
        foreach ($roster as $actionName) {
            $result[$actionName] = [
                $class,
                $actionName
            ];
        }
        return $result ?? [];
    }

    public static function buildByPath(array $roster, string $path): array
    {
        $namespace = ucwords(
            trim(
                strtr($path, [
                    BASE_PATH => '',
                    '/' => '\\',
                ]),
                '\\'
            )
        );
        foreach ($roster as $actionName) {
            $result[$actionName] = [
                "{$namespace}\\".ucfirst($actionName),
                'handle'
            ];
        }
        return $result ?? [];
    }

    public static function filterNotCallable(array $mapping): array
    {
        return array_filter($mapping, static function ($callable) {
            return is_callable($callable);
        });
    }

    public static function filterNotExistCallable(array $mapping): array
    {
        return array_filter($mapping, static function ($callable) {
            if (is_array($callable)) {
                return method_exists(...$callable);
            }
            return $callable;
        });
    }

    public static function injectCallable(array $mapping, ContainerInterface $container): array
    {
        foreach ($mapping as &$callable) {
            if (is_array($callable)) {
                $class = $callable[0] ?? '';
                if (!empty($class) && $container->has($class)) {
                    $callable[0] = $container->get($class);
                }
            }
        }
        return $mapping;
    }
}
