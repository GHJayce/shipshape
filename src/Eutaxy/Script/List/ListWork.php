<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Script\List;

use Ghbjayce\MagicSocket\Common\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Entity\Param\Param;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;
use Ghbjayce\MagicSocket\Eutaxy\Script\List\Contract\WorkInterface;
use Ghbjayce\MagicSocket\Eutaxy\Work\Work;

class ListWork extends Work implements WorkInterface
{
    public function handleParam(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext
    {
        return [];
    }

    public function queryCondition(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext
    {
        return [];
    }

    public function getData(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext
    {
        return [];
    }

    public function dataFormat(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext
    {
        return [];
    }
}
