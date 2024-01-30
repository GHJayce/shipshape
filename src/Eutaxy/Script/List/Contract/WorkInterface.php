<?php
declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Script\List\Contract;

use Ghjayce\MagicSocket\Common\Entity\Context\Context;
use Ghjayce\MagicSocket\Common\Entity\Param\Param;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

interface WorkInterface
{
    public function handleParam(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
    public function queryCondition(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
    public function getData(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
    public function dataFormat(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
}