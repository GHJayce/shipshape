<?php
declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Action;

use Ghjayce\MagicSocket\Common\Entity\Context\Context;
use Ghjayce\MagicSocket\Common\Entity\Param\Param;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

class TheEnd extends Action
{
    public function handle(Param $param, Context $context, EutaxyContext $eutaxyContext): array
    {
        return [];
    }

    public function return(Param $param, Context $context, EutaxyContext $eutaxyContext): bool
    {
        return true;
    }
}