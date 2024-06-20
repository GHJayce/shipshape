<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ShipshapeConfig;

use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\TakeTheCup;
use GhjayceExample\Shipshape\Cases\BrushTeeth\BrushTeeth;
use GhjayceExample\Shipshape\Mock\Container;
use GhjayceExample\Shipshape\Mock\ShipshapeConfigMock;
use PHPUnit\Framework\TestCase;

class TakeClassInstanceTest extends TestCase
{
    public function testCaseA(): void
    {
        $this->assertEquals(new BrushTeeth(), ShipshapeConfigMock::make()->takeClassInstance(BrushTeeth::class));
        $this->assertEquals(null, ShipshapeConfigMock::make()->takeClassInstance('\a\b\c'));

        $container = (new Container())->set(BrushTeeth::class, new TakeTheCup());
        $this->assertEquals(new TakeTheCup(), ShipshapeConfigMock::make()->setContainer($container)->takeClassInstance(BrushTeeth::class));
    }
}
