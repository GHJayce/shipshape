<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape;

use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\BrushTeething;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\CleanTheCup;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\SqueezingTheTube;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Action\TakeTheCup;
use GhjayceExample\Shipshape\Mock\ShipshapeHookConfigMock;
use PHPUnit\Framework\TestCase;

class ShipshapeHookConfigTest extends TestCase
{
    public function testCustomHandleWorkByNamespace(): void
    {
        $names = [
            'takeTheCup',
            'cleanTheCup',
            'squeezingTheTube',
            'brushTeething',
        ];
        $config = ShipshapeHookConfigMock::make();
        $mergeWorks = $config
            ->setNames($names)
            ->setNamespace('\GhjayceExample\Shipshape\Cases\BrushTeeth\Action\\')
            ->merge();
        $works = $config->customHandleWorks($mergeWorks);
        $result = [];
        $expect = $config->appendTheEndActionToWorks($mergeWorks);
        foreach ($expect as $name => $action) {
            $hookName = $config->hookPrefixActionName().ucfirst($name);
            $result[$hookName] = [$config->getNamespace().ucfirst($hookName), $config->actionExecuteMethodName()];
            $result[$name] = $action;
        }
        //var_dump($result);
        $this->assertEquals($result, $works);
    }

    public function testCustomHandleWorkByActions(): void
    {
        $config = ShipshapeHookConfigMock::make();
        $mergeWorks = $config
            ->setActions([
                TakeTheCup::class,
                CleanTheCup::class,
                SqueezingTheTube::class,
                BrushTeething::class,
            ])
            ->merge();
        $works = $config->customHandleWorks($mergeWorks);
        $namespace = strtr(dirname(strtr(trim(TakeTheCup::class, '\\'), ['\\' => '/'])), ['/' => '\\']);
        $result = [];
        $expect = $config->appendTheEndActionToWorks($mergeWorks);
        foreach ($expect as $name => $action) {
            $hookName = $config->hookPrefixActionName().ucfirst($name);
            $result[$hookName] = [$namespace.'\\'.ucfirst($hookName), $config->actionExecuteMethodName()];
            $result[$name] = $action;
        }
        $this->assertEquals($result, $works);
    }
}
