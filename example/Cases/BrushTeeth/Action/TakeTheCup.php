<?php

declare(strict_types=1);

namespace GhjayceExample\Shipshape\Cases\BrushTeeth\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class TakeTheCup extends Action
{
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): array
    {
        $beginTime = microtime(true);
        return compact('beginTime');
    }
}
