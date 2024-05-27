<?php

declare(strict_types=1);

namespace GhjayceExample\Shipshape\Cases\BrushTeeth\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Context;

class BrushTeething extends Action
{
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        // do something...
        return null;
    }

    /**
     * @param Context $context
     * @param ShipshapeContext $shipshapeContext
     * @return string
     */
    public function returnData(ClientContext $context, ShipshapeContext $shipshapeContext): string
    {
        return 'I\'m done. Usage time: ' . microtime(true) - $context->getBeginTime();
    }

    public function return(ClientContext $context, ShipshapeContext $shipshapeContext): bool
    {
        return true;
    }
}
