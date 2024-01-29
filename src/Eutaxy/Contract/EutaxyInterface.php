<?php

namespace Ghbjayce\MagicSocket\Common\Work\Action\Contract;

use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Entity\Param\Param;

interface EutaxyInterface
{
    public function execute(array $config, Param $param, Context $context): mixed;
}