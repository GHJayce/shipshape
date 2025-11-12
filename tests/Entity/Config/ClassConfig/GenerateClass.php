<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Entity\Config\ClassConfig;

use GhjayceTest\Shipshape\Example\FirstCast\Bathe;
use Ghjayce\Shipshape\Entity\Config\ClassConfig;
use GhjayceTest\Shipshape\Helper\ClassHelper;
use PHPUnit\Framework\TestCase;

class GenerateClass extends TestCase
{
    public function testName(): void
    {
        $names = [
            'undressing',
            'washing',
            'wipeUp',
            'getDressed',
        ];
        $className = Bathe::class;
        $config = ClassConfig::make();
        $actual = ClassHelper::callRestrictMethod(
            $config,
            'generateClass',
            [
                $names,
                $className,
            ]
        );
        $expect = [
            'undressing' => [$className, 'undressing'],
            'washing'    => [$className, 'washing'],
            'wipeUp'     => [$className, 'wipeUp'],
            'getDressed' => [$className, 'getDressed'],
        ];
        $this->assertSame($expect, $actual);
    }

    public function testInstance(): void
    {
        $names = [
            'undressing',
            'washing',
            'wipeUp',
            'getDressed',
        ];
        $classInstance = new Bathe();
        $config = ClassConfig::make();
        $actual = ClassHelper::callRestrictMethod(
            $config,
            'generateClass',
            [
                $names,
                $classInstance,
            ]
        );
        $expect = [
            'undressing' => [$classInstance, 'undressing'],
            'washing'    => [$classInstance, 'washing'],
            'wipeUp'     => [$classInstance, 'wipeUp'],
            'getDressed' => [$classInstance, 'getDressed'],
        ];
        $this->assertSame($expect, $actual);
    }
}