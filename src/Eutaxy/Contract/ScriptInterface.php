<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Contract;

use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

interface ScriptInterface
{
    public function execute(mixed $config, EutaxyContext $context): mixed;
}