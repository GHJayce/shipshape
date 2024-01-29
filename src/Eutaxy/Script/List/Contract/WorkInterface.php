<?php
declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Script\List\Contract;

use Ghbjayce\MagicSocket\Common\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Entity\Param\Param;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

interface WorkInterface
{
    public function handleParam(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
    public function queryCondition(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
    public function getData(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
    public function dataFormat(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
}