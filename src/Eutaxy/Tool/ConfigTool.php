<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Tool;

use Ghjayce\MagicSocket\Eutaxy\Entity\Enum\ConfigEnum;
use Ghjayce\MagicSocket\Eutaxy\Tool\Config\MappingTool;
use function Hyperf\Collection\data_get;

class ConfigTool
{
    public static function build(array $roster, mixed $config): array
    {
        if (is_string($config)) {
            if (class_exists($config)) {
                $class = $config;
                $mapping = MappingTool::buildByClass($roster, $class);
            } elseif (is_dir($config) || in_array($config[strlen($config) - 1], ['/', '\\'], true)) {
                $path = $config;
                $mapping = MappingTool::buildByPath($roster, $path);
            }
        } elseif (is_array($config)) {
            // mapping > path > class
            $class = $config[ConfigEnum::CLASS_NAME] ?? '';
            $path = $config[ConfigEnum::DIRECTORY_PATH_OR_NAMESPACE] ?? '';
            $classMapping = MappingTool::buildByClass($roster, $class);
            $pathMapping = MappingTool::buildByPath($roster, $path);
            $mapping = array_merge(
                $classMapping,
                $pathMapping,
                $config[ConfigEnum::NAME_ACTION_MAPPING] ?? []
            );
            $hookBefore = self::getHookBeforeCallable($config);
            $hookProcess = self::getHookProcessCallable($config);
            $hookAfter = self::getHookAfterCallable($config);
        }
        return [
            ConfigEnum::NAME_LIST => $roster,
            ConfigEnum::DIRECTORY_PATH_OR_NAMESPACE => $path ?? '',
            ConfigEnum::CLASS_NAME => $class ?? '',
            ConfigEnum::NAME_ACTION_MAPPING => $mapping ?? [],
            ConfigEnum::HOOK => [
                ConfigEnum::HOOK_BEFORE => $hookBefore ?? null,
                ConfigEnum::HOOK_PROCESS => $hookProcess ?? null,
                ConfigEnum::HOOK_AFTER => $hookAfter ?? null,
            ],
        ];
    }

    public static function getHookBeforeCallable(array $config, array $default = null)
    {
        return data_get($config, self::getHookBeforeKeyName()) ?: $default;
    }

    public static function getHookProcessCallable(array $config, array $default = null)
    {
        return data_get($config, self::getHookProcessKeyName()) ?: $default;
    }

    public static function getHookAfterCallable(array $config, array $default = null)
    {
        return data_get($config, self::getHookAfterKeyName()) ?: $default;
    }

    public static function getHookBeforeKeyName(): string
    {
        return ConfigEnum::HOOK.'.'.ConfigEnum::HOOK_BEFORE;
    }

    public static function getHookProcessKeyName(): string
    {
        return ConfigEnum::HOOK.'.'.ConfigEnum::HOOK_PROCESS;
    }

    public static function getHookAfterKeyName(): string
    {
        return ConfigEnum::HOOK.'.'.ConfigEnum::HOOK_AFTER;
    }
}
