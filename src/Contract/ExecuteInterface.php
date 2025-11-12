<?php
declare(strict_types=1);

namespace Ghjayce\Shipshape\Contract;

use Ghjayce\Shipshape\Entity\Context\ExecuteContext;

interface ExecuteInterface
{
    public function execute(ExecuteContext $executeContext): mixed;
}