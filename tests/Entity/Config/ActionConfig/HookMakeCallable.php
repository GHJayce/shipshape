<?php

declare(strict_types=1);

namespace Entity\Config\ActionConfig;

use GhjayceTest\Shipshape\Example\FirstCast\Action\FirstAction;
use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use Ghjayce\Shipshape\Entity\Config\Hook;
use GhjayceTest\Shipshape\Example\Container;
use GhjayceTest\Shipshape\Helper\ClassHelper;
use PHPUnit\Framework\TestCase;

class HookMakeCallable extends TestCase
{
    public function testCallable(): void
    {
        $anonymousClass = new class {
            public function run(): void
            {
                echo __METHOD__;
            }
        };
        $firstAction = new FirstAction();
        $container = new Container();
        $container->set(FirstAction::class, $firstAction);
        $hook = Hook::make()
            ->setBefore([$anonymousClass, 'run'])
            ->setAfter([FirstAction::class, 'execute']);
        $actionConfig = ActionConfig::make()
            ->setHook($hook)
            ->setContainer($container);
        ClassHelper::callRestrictMethod(
            $actionConfig,
            'hookMakeCallable',
            []
        );
        $actual = $actionConfig->getHook()?->all();
        $expect = [
            'before'  => [$anonymousClass, 'run'],
            'process' => null,
            'after'   => [$firstAction, 'execute'],
        ];
        $this->assertSame($expect, $actual);
    }
}