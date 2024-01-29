<?php
declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Contract;


use Ghbjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

interface EutaxyInterface
{
    public function execute(array $config, EutaxyContext $context): mixed;
}