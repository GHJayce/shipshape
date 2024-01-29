<?php
declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Action;

use Ghbjayce\MagicSocket\Common\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Entity\Param\Param;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

class TheEnd extends Action
{
    public function handle(Param $param, Context $context, EutaxyContext $eutaxyContext): ?array
    {
        return [];
    }

    public function return(Param $param, Context $context, EutaxyContext $eutaxyContext): bool
    {
        return true;
    }
}