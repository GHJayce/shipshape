<?php

declare(strict_types=1);

namespace Entity\Config\ActionConfig;

use GhjayceTest\Shipshape\Example\FirstCast\Action\FirstAction;
use GhjayceTest\Shipshape\Example\FirstCast\Action\SecondAction;
use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use GhjayceTest\Shipshape\Example\Container;
use GhjayceTest\Shipshape\Helper\ClassHelper;
use PHPUnit\Framework\TestCase;

class MakeCallable extends TestCase
{
    public function testMix(): void
    {
        $anonymousClass = new class {
            public function run(): void
            {
                echo __METHOD__;
            }
        };
        $func = static function () {
            echo __METHOD__;
        };
        $firstAction = new FirstAction();
        $secondAction = new SecondAction();
        $works = [
            FirstAction::class  => [FirstAction::class, 'execute'],
            SecondAction::class => [SecondAction::class, 'execute'],
            'anonymousClass'    => [$anonymousClass, 'run'],
            'fake'              => ['fake', 'execute'],
            'notExists'         => [NotExists::class, 'test'],
            'thirdAction'       => [FirstAction::class, 'run'],
            'func'              => $func,
        ];
        $container = new Container();
        $container->set(FirstAction::class, $firstAction)
            ->set(SecondAction::class, $secondAction);
        $actionConfig = ActionConfig::make()
            ->setContainer($container);
        $actual = ClassHelper::callRestrictMethod(
            $actionConfig,
            'makeCallable',
            [
                $works,
            ]
        );
        $expect = [
            FirstAction::class  => [$firstAction, 'execute'],
            SecondAction::class => [$secondAction, 'execute'],
            'anonymousClass'    => [$anonymousClass, 'run'],
            'func'              => $func,
        ];
        $this->assertSame($expect, $actual);
    }
}