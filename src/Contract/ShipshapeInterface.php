<?php
declare(strict_types=1);

namespace Ghjayce\Shipshape\Contract;

use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

interface ShipshapeInterface
{
    public function execute(ShipshapeConfig $config, ShipshapeContext $context): mixed;
}