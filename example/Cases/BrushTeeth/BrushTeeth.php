<?php

declare(strict_types=1);

namespace GhjayceExample\Shipshape\Cases\BrushTeeth;

use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class BrushTeeth
{
    public function takeTheCup(ClientContext $context, ShipshapeContext $shipshapeContext): ShipshapeContext
    {
        return $shipshapeContext;
    }

    public function cleanTheCup(ClientContext $context, ShipshapeContext $shipshapeContext): ShipshapeContext
    {
        return $shipshapeContext;
    }

    public function fillCupWithWater(ClientContext $context, ShipshapeContext $shipshapeContext): ShipshapeContext
    {
        return $shipshapeContext;
    }

    public function squeezingTheTube(ClientContext $context, ShipshapeContext $shipshapeContext): ShipshapeContext
    {
        return $shipshapeContext;
    }

    public function brushTeething(ClientContext $context, ShipshapeContext $shipshapeContext): ShipshapeContext
    {
        return $shipshapeContext
            ->setReturnData('My teeth are very clean now.')
            ->markReturnSignal();
    }
}
