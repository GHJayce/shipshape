<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Script;

use Ghjayce\MagicSocket\Eutaxy\Contract\EutaxyInterface;
use Ghjayce\MagicSocket\Eutaxy\Contract\ScriptInterface;
use Ghjayce\MagicSocket\Eutaxy\Entity\Config\ScriptConfig;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;
use Psr\Container\ContainerInterface;

class Script implements ScriptInterface
{
    public function __construct(
        protected EutaxyInterface $eutaxy,
        protected ContainerInterface $container,
    )
    {
    }

    public function execute(ScriptConfig $config, EutaxyContext $context): mixed
    {
        $configHandles = ['installRoster', 'beforeBuild', 'build', 'afterBuild'];
        foreach ($configHandles as $handleName) {
            $config = $this->$handleName($config);
        }
        return $this->eutaxy->execute($config, $context);
    }

    public function getRoster(): array
    {
        return [];
    }

    protected function installRoster(ScriptConfig $config): ScriptConfig
    {
        $roster = $this->getRoster();
        if ($roster) {
            $config->setRoster($roster);
        }
        return $config;
    }

    protected function build(ScriptConfig $config): ScriptConfig
    {
        return $config->build()->usable($this->container);
    }

    protected function beforeBuild(ScriptConfig $config): ScriptConfig
    {
        return $config;
    }

    protected function afterBuild(ScriptConfig $config): ScriptConfig
    {
        return $config;
    }
}
