<?php

namespace Ghbjayce\MagicSocket\Common\Work\Action\Contract;

use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Entity\Param\Param;

interface ActionInterface
{
    public function handle(Param $param, Context $context):? array;
    public function execute(Param $param, Context $context): array;
    public function return(Param $param, Context $context): bool;
    public function returnData(Param $param, Context $context): mixed;
}