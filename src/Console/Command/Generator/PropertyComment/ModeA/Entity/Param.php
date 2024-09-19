<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity;

use Ghjayce\Shipshape\Entity\Base\Property;

class Param extends Property
{
    protected string $target = '';
    protected array $ignoreClassesWithNamespace = [];
}