<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\NamespaceConfig;

use GhjayceTest\Shipshape\Example\FirstCast\Action\FirstAction;
use GhjayceTest\Shipshape\Example\FirstCast\Action\SecondAction;
use Ghjayce\Shipshape\Entity\Config\NamespaceConfig;
use GhjayceTest\Shipshape\Helper\ClassHelper;
use PHPUnit\Framework\TestCase;

class GenerateNamespace extends TestCase
{
    public function testCase(): void
    {
        $names = [
            'firstAction',
            'secondAction',
        ];
        $namespace = '\\GhjayceTest\\Shipshape\\Example\\FirstCast\\Action';
        $config = NamespaceConfig::make();
        $actual = ClassHelper::callRestrictMethod(
            $config,
            'generateNamespace',
            [
                $names,
                $namespace,
            ]
        );
        $expect = [
            'firstAction'  => [FirstAction::class, 'execute'],
            'secondAction' => [SecondAction::class, 'execute'],
        ];
        $this->assertSame($expect, $actual);
    }
}