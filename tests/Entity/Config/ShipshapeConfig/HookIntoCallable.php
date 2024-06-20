<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ShipshapeConfig;

use GhjayceExample\Shipshape\Cases\BrushTeeth\BrushTeeth;
use GhjayceExample\Shipshape\Mock\ShipshapeConfigMock;
use PHPUnit\Framework\TestCase;

class HookIntoCallable extends TestCase
{
    public function testCaseA(): void
    {
        $config = ShipshapeConfigMock::make()
            ->setBeforeHandleHook([BrushTeeth::class, 'takeTheCup'])
            ->setProcessHandleHook([BrushTeeth::class, 'eat'])
            ->setAfterHandleHook(null)
            ->build();
        $this->assertEquals([new BrushTeeth(), 'takeTheCup'], $config->getBeforeHandleHook());
        $this->assertEquals(null, $config->getProcessHandleHook());
        $this->assertEquals(null, $config->getAfterHandleHook());
    }
}
