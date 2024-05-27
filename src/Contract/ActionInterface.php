<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Contract;

use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

interface ActionInterface
{
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed;
    public function execute(ClientContext $context, ShipshapeContext $shipshapeContext): ShipshapeContext;
    public function return(ClientContext $context, ShipshapeContext $shipshapeContext): bool;
    public function returnData(ClientContext $context, ShipshapeContext $shipshapeContext): mixed;
}