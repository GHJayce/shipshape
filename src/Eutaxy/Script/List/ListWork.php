<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Script\List;

use Ghjayce\MagicSocket\Common\Entity\Context\Context;
use Ghjayce\MagicSocket\Common\Entity\Param\Param;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;
use Ghjayce\MagicSocket\Eutaxy\Script\List\Contract\WorkInterface;
use Ghjayce\MagicSocket\Eutaxy\Work\Work;

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
