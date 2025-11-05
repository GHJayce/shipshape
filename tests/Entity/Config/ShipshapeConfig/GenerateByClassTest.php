<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ShipshapeConfig;

use GhjayceExample\Shipshape\Cases\BrushTeeth\BrushTeeth;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Enum;
use GhjayceExample\Shipshape\Mock\ConfigMock;
use PHPUnit\Framework\TestCase;

class GenerateByClassTest extends TestCase
{
    public function testCaseA(): void
    {
        $names = Enum::NAMES;
        $class1 = BrushTeeth::class;
        $result = ConfigMock::generateByClass($names, $class1);
        $this->assertSame(array_combine($names, [
            [$class1, 'takeTheCup'],
            [$class1, 'cleanTheCup'],
            [$class1, 'fillCupWithWater'],
            [$class1, 'squeezingTheTube'],
            [$class1, 'brushTeething'],
        ]), $result);


        $class2 = new BrushTeeth();
        $result = ConfigMock::generateByClass($names, $class2);
        $this->assertSame(array_combine($names, [
            [$class2, 'takeTheCup'],
            [$class2, 'cleanTheCup'],
            [$class2, 'fillCupWithWater'],
            [$class2, 'squeezingTheTube'],
            [$class2, 'brushTeething'],
        ]), $result);
    }
}
