<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Work\Tool\Config;

use Ghbjayce\MagicSocket\Eutaxy\Work\Entity\Enum\ConfigEnum;
use function Hyperf\Collection\data_get;

class ConfigTool
{
    public static function toConfig(array $roster, mixed $config): array
    {
        if (is_string($config)) {
            if (class_exists($config)) {
                $class = $config;
                $mapping = ConfigMappingTool::buildByClass($roster, $class);
            } elseif (is_dir($config) || in_array($config[strlen($config) - 1], ['/', '\\'], true)) {
                $path = $config;
                $mapping = ConfigMappingTool::buildByPath($roster, $path);
            }
        } elseif (is_array($config)) {
            // mapping > path > class
            $class = $config[ConfigEnum::CLASS_NAME] ?? '';
            $path = $config[ConfigEnum::DIRECTORY_PATH_OR_NAMESPACE] ?? '';
            $classMapping = ConfigMappingTool::buildByClass($roster, $class);
            $pathMapping = ConfigMappingTool::buildByPath($roster, $path);
            $mapping = array_merge(
                $classMapping,
                $pathMapping,
                $config[ConfigEnum::NAME_ACTION_MAPPING] ?? []
            );
        }
        return [
            ConfigEnum::NAME_LIST => $roster,
            ConfigEnum::DIRECTORY_PATH_OR_NAMESPACE => $path ?? '',
            ConfigEnum::CLASS_NAME => $class ?? '',
            ConfigEnum::NAME_ACTION_MAPPING => $mapping ?? [],
            ConfigEnum::HOOK => [
                ConfigEnum::HOOK_BEFORE => null,
                ConfigEnum::HOOK_PROCESS => null,
                ConfigEnum::HOOK_AFTER => null,
            ],
        ];
    }

    public static function getHookBeforeCallable(array $config, array $default = null)
    {
        return data_get($config, ConfigEnum::HOOK.'.'.ConfigEnum::HOOK_BEFORE, $default);
    }

    public static function getHookProcessCallable(array $config, array $default = null)
    {
        return data_get($config, ConfigEnum::HOOK.'.'.ConfigEnum::HOOK_PROCESS, $default);
    }

    public static function getHookAfterCallable(array $config, array $default = null)
    {
        return data_get($config, ConfigEnum::HOOK.'.'.ConfigEnum::HOOK_AFTER, $default);
    }
}
