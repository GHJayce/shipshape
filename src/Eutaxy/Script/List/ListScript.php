<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Script\List;


use Ghbjayce\MagicSocket\Eutaxy\Script\HookScript;
use Ghbjayce\MagicSocket\Eutaxy\Script\List\Entity\Enum\RosterEnum;

class ListScript extends HookScript
{
    public function getRoster(): array
    {
        return RosterEnum::ACTION;
    }
}
