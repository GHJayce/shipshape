<?php

namespace Ghbjayce\MagicSocket\Common\Work\Action\Contract;

use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Entity\Param\Param;

interface ScriptInterface
{
    public function execute(mixed $config, Param $param, Context $context): mixed;
}