<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ActionConfig;

use GhjayceTest\Shipshape\Example\FirstCast\Action\FirstAction;
use GhjayceTest\Shipshape\Example\FirstCast\Action\SecondAction;
use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use GhjayceTest\Shipshape\Helper\ClassHelper;
use PHPUnit\Framework\TestCase;

class GenerateActions extends TestCase
{
    public function testMix(): void
    {
        $anonymousClassA = new class {};
        $anonymousClassB = new class {};
        $secondAction = new SecondAction();
        $actions = [
            FirstAction::class,
            $secondAction,
            'ActionOne',
            $anonymousClassA,
            'CustomAction' => $anonymousClassB,
        ];
        $actionConfig = ActionConfig::make();
        $actual = ClassHelper::callRestrictMethod(
            $actionConfig,
            'generateActions',
            [
                $actions,
            ]
        );
        $expect = [
            FirstAction::class => [FirstAction::class, 'execute'],
            SecondAction::class => [$secondAction, 'execute'],
            'ActionOne' => ['ActionOne', 'execute'],
            get_class($anonymousClassA) => [$anonymousClassA, 'execute'],
            'CustomAction' => [$anonymousClassB, 'execute'],
        ];
        $this->assertSame($expect, $actual);
    }
}