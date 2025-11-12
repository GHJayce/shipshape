<?php

declare(strict_types=1);

namespace Entity\Config;

use Example\FirstCast\Config\AppendHookActionConfig;
use Example\FirstCast\Config\AppendHookClassConfig;
use Example\FirstCast\Config\AppendHookNamespaceConfig;
use Ghjayce\Shipshape\Action\TheEnd;
use GhjayceTest\Shipshape\Example\FirstCast\Action\FirstAction;
use GhjayceTest\Shipshape\Example\FirstCast\Action\SecondAction;
use GhjayceTest\Shipshape\Example\FirstCast\Bathe;
use GhjayceTest\Shipshape\Helper\ClassHelper;
use PHPUnit\Framework\TestCase;

class AppendHookWork extends TestCase
{
    public function testActionConfig(): void
    {
        $config = AppendHookActionConfig::make()
            ->setActions([
                FirstAction::class,
                SecondAction::class,
            ]);
        $works = ClassHelper::callRestrictMethod(
            $config,
            'generate',
        );
        $actual = ClassHelper::callRestrictMethod(
            $config,
            'customWorks',
            [
                $works,
            ]
        );
        $this->assertSame([
            'beforeFirstAction'  => [\GhjayceTest\Shipshape\Example\FirstCast\Action\BeforeFirstAction::class, 'execute'],
            FirstAction::class   => [FirstAction::class, 'execute'],
            'beforeSecondAction' => [\GhjayceTest\Shipshape\Example\FirstCast\Action\BeforeSecondAction::class, 'execute'],
            SecondAction::class  => [SecondAction::class, 'execute'],
            'beforeTheEnd'       => [\GhjayceTest\Shipshape\Example\FirstCast\Action\BeforeTheEnd::class, 'execute'],
            'theEnd'             => [TheEnd::class, 'execute'],
        ], $actual);
    }

    public function testNamespaceConfig(): void
    {
        $config = AppendHookNamespaceConfig::make()
            ->setNames([
                'firstAction',
                'secondAction',
            ])
            ->setNamespace('\\GhjayceTest\\Shipshape\\Example\\FirstCast\\Action\\');
        $works = ClassHelper::callRestrictMethod(
            $config,
            'generate',
        );
        $actual = ClassHelper::callRestrictMethod(
            $config,
            'customWorks',
            [
                $works,
            ]
        );
        $this->assertSame([
            'beforeFirstAction'  => [\GhjayceTest\Shipshape\Example\FirstCast\Action\BeforeFirstAction::class, 'execute'],
            'firstAction'        => [FirstAction::class, 'execute'],
            'beforeSecondAction' => [\GhjayceTest\Shipshape\Example\FirstCast\Action\BeforeSecondAction::class, 'execute'],
            'secondAction'       => [SecondAction::class, 'execute'],
            'beforeTheEnd'       => [\GhjayceTest\Shipshape\Example\FirstCast\Action\BeforeTheEnd::class, 'execute'],
            'theEnd'             => [TheEnd::class, 'execute'],
        ], $actual);
    }

    public function testClassConfig(): void
    {
        $config = AppendHookClassConfig::make()
            ->setNames([
                'undressing',
                'washing',
                'wipeUp',
                'getDressed',
            ])
            ->setClass(Bathe::class);
        $works = ClassHelper::callRestrictMethod(
            $config,
            'generate',
        );
        $actual = ClassHelper::callRestrictMethod(
            $config,
            'customWorks',
            [
                $works,
            ]
        );
        $this->assertSame([
            'beforeUndressing' => [Bathe::class, 'beforeUndressing'],
            'undressing'       => [Bathe::class, 'undressing'],
            'beforeWashing'    => [Bathe::class, 'beforeWashing'],
            'washing'          => [Bathe::class, 'washing'],
            'beforeWipeUp'     => [Bathe::class, 'beforeWipeUp'],
            'wipeUp'           => [Bathe::class, 'wipeUp'],
            'beforeGetDressed' => [Bathe::class, 'beforeGetDressed'],
            'getDressed'       => [Bathe::class, 'getDressed'],
            'beforeTheEnd'     => [Bathe::class, 'beforeTheEnd'],
            'theEnd'           => [TheEnd::class, 'execute'],
        ], $actual);
    }
}