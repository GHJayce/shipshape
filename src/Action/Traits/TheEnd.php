<?php
declare(strict_types=1);
namespace Ghjayce\Shipshape\Action\Traits;

use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

trait TheEnd
{
    public function theEnd(ClientContext $context, ShipshapeContext $shipshapeContext): ShipshapeContext
    {
        $shipshapeContext->markReturnSignal();
        return $shipshapeContext;
    }
}