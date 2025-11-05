<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ShipshapeConfig;

use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\TakeTheCup;
use GhjayceExample\Shipshape\Cases\BrushTeeth\BrushTeeth;
use GhjayceExample\Shipshape\Mock\Container;
use GhjayceExample\Shipshape\Mock\ConfigMock;
use PHPUnit\Framework\TestCase;

class TakeClassInstanceTest extends TestCase
{
    public function testCaseA(): void
    {
        $this->assertEquals(new BrushTeeth(), ConfigMock::make()->makeClass(BrushTeeth::class));
        $this->assertEquals(null, ConfigMock::make()->makeClass('\a\b\c'));

        $container = (new Container())->set(BrushTeeth::class, new TakeTheCup());
        $this->assertEquals(new TakeTheCup(), ConfigMock::make()->setContainer($container)->makeClass(BrushTeeth::class));
    }
}
