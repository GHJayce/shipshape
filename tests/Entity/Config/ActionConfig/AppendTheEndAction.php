<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ActionConfig;

use GhjayceTest\Shipshape\Example\FirstCast\Action\FirstAction;
use GhjayceTest\Shipshape\Example\FirstCast\Action\SecondAction;
use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use GhjayceTest\Shipshape\Helper\ClassHelper;
use PHPUnit\Framework\TestCase;

class AppendTheEndAction extends TestCase
{
    public function testWithout(): void
    {
        $works = [
            FirstAction::class  => [FirstAction::class, 'execute'],
            SecondAction::class => [SecondAction::class, 'execute'],
        ];
        $actionConfig = ActionConfig::make();
        $actual = ClassHelper::callRestrictMethod(
            $actionConfig,
            'appendTheEndAction',
            [
                $works,
            ]
        );
        $expect = [
            FirstAction::class  => [FirstAction::class, 'execute'],
            SecondAction::class => [SecondAction::class, 'execute'],
            'theEnd'            => [TheEnd::class, 'execute'],
        ];
        $this->assertSame($expect, $actual);
    }

    public function testWith(): void
    {
        $anonymousClass = new class {
            public function run(): void
            {
                echo __METHOD__;
            }
        };
        $works = [
            FirstAction::class  => [FirstAction::class, 'execute'],
            SecondAction::class => [SecondAction::class, 'execute'],
            'theEnd'            => [$anonymousClass, 'run'],
        ];
        $actionConfig = ActionConfig::make();
        $actual = ClassHelper::callRestrictMethod(
            $actionConfig,
            'appendTheEndAction',
            [
                $works,
            ]
        );
        $expect = [
            FirstAction::class  => [FirstAction::class, 'execute'],
            SecondAction::class => [SecondAction::class, 'execute'],
            'theEnd'            => [$anonymousClass, 'run'],
        ];
        $this->assertSame($expect, $actual);
    }
}