<?php
declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Contract;


use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

interface EutaxyInterface
{
    public function execute(array $config, EutaxyContext $context): mixed;
}