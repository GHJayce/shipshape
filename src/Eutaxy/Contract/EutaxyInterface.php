<?php
declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Contract;


use Ghjayce\MagicSocket\Eutaxy\Entity\Config\EutaxyConfig;
use Ghjayce\MagicSocket\Eutaxy\Entity\Context\EutaxyContext;

interface EutaxyInterface
{
    public function execute(EutaxyConfig $config, EutaxyContext $context): mixed;
}