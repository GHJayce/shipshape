<?php

declare(strict_types=1);

namespace ShipshapeConfig;

use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use GhjayceExample\Shipshape\Cases\BrushTeeth\Enum;
use GhjayceExample\Shipshape\Mock\ConfigMock;
use PHPUnit\Framework\TestCase;

class GenerateByNamespaceTest extends TestCase
{
    public function testCaseA(): void
    {
        $names = Enum::NAMES;
        $namespace = trim('\\GhjayceTest\\Shipshape\\Cases\\BrushTeeth\\Action\\', '\\');
        $result = ConfigMock::generateByNamespace($names, $namespace);
        $forecast = [];
        foreach ($names as $name) {
            $forecast[$name] = [$namespace.'\\'.ucfirst($name), ActionEnum::ACTION_EXECUTE_METHOD_NAME];
        }
        $this->assertSame($forecast, $result);
    }
}
