<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Script\List;


use Ghjayce\MagicSocket\Eutaxy\Script\HookScript;
use Ghjayce\MagicSocket\Eutaxy\Script\List\Support\Enum\RosterEnum;

class ListScript extends HookScript
{
    public function getRoster(): array
    {
        return RosterEnum::ACTION;
    }
}
