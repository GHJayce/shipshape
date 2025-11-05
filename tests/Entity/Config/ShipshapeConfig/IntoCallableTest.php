<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ShipshapeConfig;

use GhjayceExample\Shipshape\Mock\ConfigMock;
use PHPUnit\Framework\TestCase;

class IntoCallableTest extends TestCase
{
    public function testCaseA(): void
    {
        $config = ConfigMock::make();
        $items = [
            'beforeHandleHook' => $config->getBeforeHandleHook(),
            'processHandleHook' => $config->getProcessHandleHook(),
            'afterHandleHook' => $config->getAfterHandleHook(),
        ];
        $result = $config->makeCallable($items);
        $this->assertEquals([], $result);
    }

    public function testCaseB(): void
    {
        $config = ConfigMock::make();
        $items = [
            'beforeHandleHook' => [$this, 'testCaseB'],
            'processHandleHook' => [$this, 'testCaseC'],
            'afterHandleHook' => $config->getAfterHandleHook(),
        ];
        $result = $config->makeCallable($items);
        $this->assertEquals([
            'beforeHandleHook' => [$this, 'testCaseB'],
        ], $result);
    }
}
