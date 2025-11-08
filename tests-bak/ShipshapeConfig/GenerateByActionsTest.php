<?php

declare(strict_types=1);

namespace ShipshapeConfig;

use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\BrushTeething;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\CleanTheCup;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\FillCupWithWater;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\SqueezingTheTube;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\TakeTheCup;
use GhjayceExample\Shipshape\Mock\ConfigMock;
use PHPUnit\Framework\TestCase;

class GenerateByActionsTest extends TestCase
{
    public function testCaseA(): void
    {
        $names = [
            TakeTheCup::class,
            CleanTheCup::class,
            FillCupWithWater::class,
            SqueezingTheTube::class,
            BrushTeething::class,
        ];
        $result = ConfigMock::generateByActions($names);
        $forecast = [];
        foreach ($names as $name) {
            $forecast[$name] = [$name, ActionEnum::ACTION_EXECUTE_METHOD_NAME];
        }
        $this->assertSame($forecast, $result);
    }
}
