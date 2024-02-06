<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Script;

use Ghjayce\MagicSocket\Eutaxy\Action\TheEnd;
use Ghjayce\MagicSocket\Eutaxy\Entity\Config\ScriptConfig;
use Ghjayce\MagicSocket\Eutaxy\Entity\Enum\ActionEnum;
use Ghjayce\MagicSocket\Eutaxy\Entity\Enum\Action\RosterEnum;
use Ghjayce\MagicSocket\Eutaxy\Entity\Enum\ScriptEnum;
use Ghjayce\MagicSocket\Eutaxy\Support\Tool\Config\MappingTool;

class HookScript extends Script
{
    protected function beforeBuild(ScriptConfig $config): ScriptConfig
    {
        return $config->setRoster(
            $this->addBeforeHook(
                $this->appendTheEndName(
                    $config->getRoster()
                )
            )
        );
    }

    protected function afterBuild(ScriptConfig $config): ScriptConfig
    {
        return $config->setMapping(
            $this->appendTheEndAction($config->getMapping())
        );
    }

    protected function appendTheEndName(array $roster): array
    {
        if (!in_array(RosterEnum::NAME_OF_THE_END, $roster, true)) {
            $roster[] = RosterEnum::NAME_OF_THE_END;
        }
        return $roster;
    }

    protected function addBeforeHook(array $roster): array
    {
        $result = [];
        foreach ($roster as $name) {
            $result[] = ScriptEnum::HOOK_PREFIX_NAME.ucfirst($name);
            $result[] = $name;
        }
        return $result;
    }

    protected function appendTheEndAction(array $mapping): array
    {
        if (empty($mapping[RosterEnum::NAME_OF_THE_END])) {
            $mapping[RosterEnum::NAME_OF_THE_END] = [
                MappingTool::getClassFromContainer(TheEnd::class, $this->container),
                ActionEnum::ACTION_METHOD_NAME_BY_PATH
            ];
        }
        return $mapping;
    }
}
