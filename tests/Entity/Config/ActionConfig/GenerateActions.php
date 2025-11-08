<?php

declare(strict_types=1);

namespace Entity\Config\ActionConfig;

use Example\FirstAction;
use Example\SecondAction;
use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use GhjayceTest\Shipshape\Helper\ClassHelper;
use PHPUnit\Framework\TestCase;

class GenerateActions extends TestCase
{
    public function testMix(): void
    {
        $actionConfig = ActionConfig::make();
        $actual = ClassHelper::callRestrictMethod(
            $actionConfig,
            'generateActions',
            [
                [
                    FirstAction::class,
                    new SecondAction(),
                    'ActionOne',
                    new class {
                    },
                    'CustomAction' => new class {
                    },
                    'secondAction',
                    'thirdAction',
                ],
            ]
        );
        $expect = [];
        $this->assertSame($expect, $actual);
    }
}