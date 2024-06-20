<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ShipshapeConfig;

use GhjayceExample\Shipshape\Mock\ShipshapeConfigMock;
use PHPUnit\Framework\TestCase;

class IntoCallableTest extends TestCase
{
    public function testCaseA(): void
    {
        $config = ShipshapeConfigMock::make();
        $items = [
            'beforeHandleHook' => $config->getBeforeHandleHook(),
            'processHandleHook' => $config->getProcessHandleHook(),
            'afterHandleHook' => $config->getAfterHandleHook(),
        ];
        $result = $config->intoCallable($items);
        $this->assertEquals([], $result);
    }

    public function testCaseB(): void
    {
        $config = ShipshapeConfigMock::make();
        $items = [
            'beforeHandleHook' => [$this, 'testCaseB'],
            'processHandleHook' => [$this, 'testCaseC'],
            'afterHandleHook' => $config->getAfterHandleHook(),
        ];
        $result = $config->intoCallable($items);
        $this->assertEquals([
            'beforeHandleHook' => [$this, 'testCaseB'],
        ], $result);
    }
}
