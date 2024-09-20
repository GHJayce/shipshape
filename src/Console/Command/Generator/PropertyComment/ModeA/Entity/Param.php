<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity;

use Ghjayce\Shipshape\Entity\Base\Property;
use Composer\Autoload\ClassLoader;

class Param extends Property
{
    protected string $target = '';
    protected array $ignoreClassesWithNamespace = [];
    protected ClassLoader $classLoader;
}