<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Contract;

use Ghjayce\MagicSocket\Common\Entity\Context\Context;
use Ghjayce\MagicSocket\Common\Entity\Param\Param;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

interface ActionInterface
{
    public function handle(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
    public function execute(Param $param, Context $context, EutaxyContext $eutaxyContext): array|EutaxyContext;
    public function return(Param $param, Context $context, EutaxyContext $eutaxyContext): bool;
    public function returnData(Param $param, Context $context, EutaxyContext $eutaxyContext): mixed;
}