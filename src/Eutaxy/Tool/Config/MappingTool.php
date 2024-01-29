<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Tool\Config;

use Ghbjayce\MagicSocket\Eutaxy\Entity\Enum\ActionEnum;
use Ghbjayce\MagicSocket\Eutaxy\Tool\ScriptTool;
use Psr\Container\ContainerInterface;

class MappingTool
{
    public static function buildByClass(array $roster, string $class): array
    {
        if (empty($class)) {
            return [];
        }
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
        if (empty($path)) {
            return [];
        }
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
                ActionEnum::ACTION_METHOD_NAME_BY_PATH
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
                $callable[0] = ScriptTool::getClassInstanceByContainer($callable[0], $container);
            }
        }
        return $mapping;
    }
}
