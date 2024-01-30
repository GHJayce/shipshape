<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Script;

use Ghjayce\MagicSocket\Eutaxy\Contract\EutaxyInterface;
use Ghjayce\MagicSocket\Eutaxy\Contract\ScriptInterface;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;
use Ghjayce\MagicSocket\Eutaxy\Entity\Enum\ConfigEnum;
use Ghjayce\MagicSocket\Eutaxy\Tool\Config\MappingTool;
use Ghjayce\MagicSocket\Eutaxy\Tool\ConfigTool;
use Ghjayce\MagicSocket\Eutaxy\Tool\ScriptTool;
use Psr\Container\ContainerInterface;

use function Hyperf\Collection\data_set;

abstract class Script implements ScriptInterface
{
    public function __construct(
        protected EutaxyInterface $eutaxy,
        protected ContainerInterface $container,
    )
    {
    }

    abstract public function getRoster(): array;

    public function execute(
        mixed $config,
        EutaxyContext $context
    ): mixed
    {
        return $this->eutaxy->execute(
            $this->handleCallable(
                $this->getConfig($config)
            ),
            $context
        );
    }

    public function getConfig(mixed $config): array
    {
        return ConfigTool::build($this->getRoster(), $config);
    }

    public function handleCallable(array $config): array
    {
        $config[ConfigEnum::NAME_ACTION_MAPPING] = MappingTool::filterNotCallable(
            MappingTool::injectCallable(
                $config[ConfigEnum::NAME_ACTION_MAPPING],
                $this->container
            )
        );
        $hook = [
            ConfigTool::getHookBeforeKeyName() => ConfigTool::getHookBeforeCallable($config),
            ConfigTool::getHookProcessKeyName() => ConfigTool::getHookProcessCallable($config),
            ConfigTool::getHookAfterKeyName() => ConfigTool::getHookAfterCallable($config),
        ];
        foreach ($hook as $configKeyName => $hookCallable) {
            if (
                !$hookCallable
                || !is_array($hookCallable)
                || !$hookCallable[0]
                || !$hookCallable[1]
            ) {
                continue;
            }
            $class = &$hookCallable[0];
            $class = ScriptTool::getClassInstanceByContainer($class, $this->container);
            if (is_object($class) && is_callable($hookCallable)) {
                data_set($config, $configKeyName, $hookCallable);
            }
        }
        return $config;
    }
}
