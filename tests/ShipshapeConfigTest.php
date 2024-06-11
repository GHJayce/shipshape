<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape;

use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\BrushTeething;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\CleanTheCup;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\FillCupWithWater;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\SqueezingTheTube;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\TakeTheCup;
use GhjayceExample\Shipshape\Cases\BrushTeeth\BrushTeeth;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Enum;
use GhjayceExample\Shipshape\Mock\Container;
use GhjayceExample\Shipshape\Mock\ShipshapeConfigMock;
use PHPUnit\Framework\TestCase;

class ShipshapeConfigTest extends TestCase
{
    public function testGenerateByClass(): void
    {
        $names = Enum::NAMES;
        $class1 = BrushTeeth::class;
        $result = ShipshapeConfigMock::generateByClass($names, $class1);
        $this->assertSame(array_combine($names, [
            [$class1, 'takeTheCup'],
            [$class1, 'cleanTheCup'],
            [$class1, 'fillCupWithWater'],
            [$class1, 'squeezingTheTube'],
            [$class1, 'brushTeething'],
        ]), $result);


        $class2 = new BrushTeeth();
        $result = ShipshapeConfigMock::generateByClass($names, $class2);
        $this->assertSame(array_combine($names, [
            [$class2, 'takeTheCup'],
            [$class2, 'cleanTheCup'],
            [$class2, 'fillCupWithWater'],
            [$class2, 'squeezingTheTube'],
            [$class2, 'brushTeething'],
        ]), $result);
    }

    public function testGenerateByNamespace(): void
    {
        $names = Enum::NAMES;
        $namespace = trim('\\GhjayceTest\\Shipshape\\Cases\\BrushTeeth\\Action\\', '\\');
        $result = ShipshapeConfigMock::generateByNamespace($names, $namespace);
        $forecast = [];
        foreach ($names as $name) {
            $forecast[$name] = [$namespace.'\\'.ucfirst($name), ActionEnum::ACTION_EXECUTE_METHOD_NAME];
        }
        $this->assertSame($forecast, $result);
    }

    public function testGenerateByActions(): void
    {
        $names = [
            TakeTheCup::class,
            CleanTheCup::class,
            FillCupWithWater::class,
            SqueezingTheTube::class,
            BrushTeething::class,
        ];
        $result = ShipshapeConfigMock::generateByActions($names);
        $forecast = [];
        foreach ($names as $name) {
            $forecast[$name] = [$name, ActionEnum::ACTION_EXECUTE_METHOD_NAME];
        }
        $this->assertSame($forecast, $result);
    }

    public function testHookIntoCallable(): void
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

    public function testTakeClassInstance(): void
    {
        $this->assertEquals(new BrushTeeth(), ShipshapeConfigMock::make()->takeClassInstance(BrushTeeth::class));
        $this->assertEquals(null, ShipshapeConfigMock::make()->takeClassInstance('\a\b\c'));

        $container = (new Container())->set(BrushTeeth::class, new TakeTheCup());
        $this->assertEquals(new TakeTheCup(), ShipshapeConfigMock::make()->setContainer($container)->takeClassInstance(BrushTeeth::class));
    }

    public function testIntoCallable(): void
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

    public function testCustomHandleWorkByNullActions(): void
    {
        $config = ShipshapeConfigMock::make();
        $methodName = ActionEnum::ACTION_EXECUTE_METHOD_NAME;
        $works = $config
            ->setActions([])
            ->appendTheEndActionToWorks([], $methodName);
        $this->assertEquals([
            TheEnd::class => [TheEnd::class, $methodName]
        ], $works);
    }
}