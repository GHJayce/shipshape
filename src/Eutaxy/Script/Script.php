<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Script;

use Ghbjayce\MagicSocket\Eutaxy\Contract\EutaxyInterface;
use Ghbjayce\MagicSocket\Eutaxy\Contract\ScriptInterface;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Enum\ConfigEnum;
use Ghbjayce\MagicSocket\Eutaxy\Tool\Config\MappingTool;
use Ghbjayce\MagicSocket\Eutaxy\Tool\ConfigTool;
use Ghbjayce\MagicSocket\Eutaxy\Tool\ScriptTool;
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
        data_set(
            $config,
            ConfigTool::getHookBeforeKeyName(),
            ScriptTool::getClassInstanceByContainer(ConfigTool::getHookBeforeCallable($config), $this->container)
        );
        data_set(
            $config,
            ConfigTool::getHookProcessKeyName(),
            ScriptTool::getClassInstanceByContainer(ConfigTool::getHookProcessCallable($config), $this->container)
        );
        data_set(
            $config,
            ConfigTool::getHookAfterKeyName(),
            ScriptTool::getClassInstanceByContainer(ConfigTool::getHookAfterCallable($config), $this->container)
        );
        var_dump([567567, $config['hook']['after']]);
        return $config;
    }
}
