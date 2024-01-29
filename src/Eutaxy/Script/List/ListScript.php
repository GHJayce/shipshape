<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\List;

use Ghbjayce\MagicSocket\Eutaxy\HookScript;
use Ghbjayce\MagicSocket\Eutaxy\List\Entity\Enum\RosterEnum;

class ListScript extends HookScript
{
    public function getRoster(): array
    {
        return RosterEnum::ACTION;
    }
}
