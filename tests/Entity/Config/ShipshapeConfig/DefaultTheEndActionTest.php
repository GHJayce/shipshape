<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ShipshapeConfig;

use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use GhjayceExample\Shipshape\Mock\ShipshapeConfigMock;
use PHPUnit\Framework\TestCase;

class DefaultTheEndActionTest extends TestCase
{
    public function testCaseA(): void
    {
        $config = ShipshapeConfigMock::make();
        $methodName = ActionEnum::ACTION_EXECUTE_METHOD_NAME;
        $works = $config
            ->setActions([])
            ->appendTheEndActionToWorks([]);
        $this->assertEquals([
            ActionEnum::THE_END_ACTION_NAME => [TheEnd::class, $methodName]
        ], $works);
    }

    public function testCaseB(): void
    {
        $defaultWorks = [
            ActionEnum::THE_END_ACTION_NAME => [static::class, 'testCaseB'],
        ];
        $config = ShipshapeConfigMock::make();
        $works = $config
            ->setActions([])
            ->appendTheEndActionToWorks($defaultWorks);
        $this->assertEquals($defaultWorks, $works);
    }
}
