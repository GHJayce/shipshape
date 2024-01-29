<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Contract;

use Ghbjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

interface ScriptInterface
{
    public function execute(mixed $config, EutaxyContext $context): mixed;
}