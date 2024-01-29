<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Script;

use Ghbjayce\MagicSocket\Eutaxy\Action\TheEnd;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Enum\ActionEnum;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Enum\Action\RosterEnum;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Enum\ConfigEnum;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Enum\ScriptEnum;
use Ghbjayce\MagicSocket\Eutaxy\Tool\Config\MappingTool;
use Ghbjayce\MagicSocket\Eutaxy\Tool\ConfigTool;

abstract class HookScript extends Script
{
    protected function addBeforeHook(array $roster): array
    {
        $return = [];
        foreach ($roster as $name) {
            $return[] = ScriptEnum::HOOK_PREFIX_NAME.ucfirst($name);
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
        $config = ConfigTool::build($roster, $config);
        $config[ConfigEnum::NAME_ACTION_MAPPING] = $this->appendTheEndAction(
            MappingTool::filterNotExistCallable($config[ConfigEnum::NAME_ACTION_MAPPING])
        );
        return $config;
    }

    private function appendTheEndAction(array $mapping): array
    {
        if (empty($mapping[RosterEnum::NAME_OF_THE_END])) {
            $mapping[RosterEnum::NAME_OF_THE_END] = [TheEnd::class, ActionEnum::ACTION_METHOD_NAME_BY_PATH];
        }
        return $mapping;
    }
}
