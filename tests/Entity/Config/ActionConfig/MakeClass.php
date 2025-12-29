<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ActionConfig;

use GhjayceTest\Shipshape\Example\FirstCast\Action\FirstAction;
use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use GhjayceTest\Shipshape\Example\Container;
use GhjayceTest\Shipshape\Helper\ClassHelper;
use PHPUnit\Framework\TestCase;

class MakeClass extends TestCase
{
    public function testWithContainer(): void
    {
        $firstAction = new FirstAction();
        $container = new Container();
        $container->set(FirstAction::class, $firstAction);
        $actionConfig = ActionConfig::make()
            ->setContainer($container);
        $actual = ClassHelper::callRestrictMethod(
            $actionConfig,
            'makeClass',
            [
                FirstAction::class,
            ]
        );
        $expect = $firstAction;
        $this->assertSame($expect, $actual);
    }

    public function testWithoutContainer(): void
    {
        $actionConfig = ActionConfig::make();
        $actual = ClassHelper::callRestrictMethod(
            $actionConfig,
            'makeClass',
            [
                FirstAction::class,
            ]
        );
        $expect = new FirstAction();
        $this->assertEquals($expect, $actual);
    }

    public function testWithClosureContainer(): void
    {
        $firstAction = new FirstAction();
        $container = new Container();
        $container->set(FirstAction::class, $firstAction);
        $containerCallback = static fn() => $container;
        $actionConfig = ActionConfig::make()
            ->setContainer($containerCallback);
        $actual = ClassHelper::callRestrictMethod(
            $actionConfig,
            'makeClass',
            [
                FirstAction::class,
            ]
        );
        $expect = $firstAction;
        $this->assertSame($expect, $actual);
    }

    public function testWithClosureContainer2(): void
    {
        $firstAction = new FirstAction();
        $container = new Container();
        $container->set(FirstAction::class, $firstAction);
        $containerCallback = static fn(string $class) => $container->get($class);
        $actionConfig = ActionConfig::make()
            ->setContainer($containerCallback);
        $actual = ClassHelper::callRestrictMethod(
            $actionConfig,
            'makeClass',
            [
                FirstAction::class,
            ]
        );
        $expect = $firstAction;
        $this->assertSame($expect, $actual);
    }

    public function testNotExists(): void
    {
        $actionConfig = ActionConfig::make();
        $actual = ClassHelper::callRestrictMethod(
            $actionConfig,
            'makeClass',
            [
                NotExistsClass::class,
            ]
        );
        $expect = null;
        $this->assertSame($expect, $actual);
    }
}