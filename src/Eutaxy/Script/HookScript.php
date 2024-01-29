<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy;

use Ghbjayce\MagicSocket\Common\Work\Action\TheEnd;
use Ghbjayce\MagicSocket\Eutaxy\Work\Entity\Enum\ConfigEnum;
use Ghbjayce\MagicSocket\Eutaxy\Work\Entity\Enum\RosterEnum;
use Ghbjayce\MagicSocket\Eutaxy\Work\Tool\Config\ConfigMappingTool;
use Ghbjayce\MagicSocket\Eutaxy\Work\Tool\Config\ConfigTool;

abstract class HookScript extends Script
{
    protected function addBeforeHook(array $roster): array
    {
        $return = [];
        foreach ($roster as $name) {
            $return[] = 'before'.ucfirst($name);
            $return[] = $name;
        }
        return $return;
    }

    protected function addTheEndRoster(array $roster): array
    {
        if (!in_array(RosterEnum::NAME_OF_THE_END, $roster, true)) {
            $roster[] = RosterEnum::NAME_OF_THE_END;
        }
        return $roster;
    }

    public function getConfig(mixed $config): array
    {
        $roster = $this->addBeforeHook(
            $this->addTheEndRoster(
                $this->getRoster()
            )
        );
        $config = ConfigTool::toConfig($roster, $config);
        $config[ConfigEnum::NAME_ACTION_MAPPING] = ConfigMappingTool::filterNotCallable(
            ConfigMappingTool::injectCallable(
                $this->appendTheEndAction(
                    ConfigMappingTool::filterNotExistCallable($config[ConfigEnum::NAME_ACTION_MAPPING])
                ),
                $this->container
            )
        );
        return $config;
    }

    private function appendTheEndAction(array $mapping): array
    {
        if (empty($mapping[RosterEnum::NAME_OF_THE_END])) {
            $mapping[RosterEnum::NAME_OF_THE_END] = [TheEnd::class, 'handle'];
        }
        return $mapping;
    }
}
