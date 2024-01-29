<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Contract;

use Ghbjayce\MagicSocket\Common\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Entity\Param\Param;
use Ghbjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

interface ActionInterface
{
    public function handle(Param $param, Context $context, EutaxyContext $eutaxyContext): array;
    public function execute(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
    public function return(Param $param, Context $context, EutaxyContext $eutaxyContext): bool;
    public function returnData(Param $param, Context $context, EutaxyContext $eutaxyContext): mixed;
}