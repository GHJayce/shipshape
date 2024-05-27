<?php
declare(strict_types=1);

namespace Ghjayce\Shipshape\Action;

use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class TheEnd extends Action
{
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        return null;
    }

    public function return(ClientContext $context, ShipshapeContext $shipshapeContext): bool
    {
        return true;
    }
}