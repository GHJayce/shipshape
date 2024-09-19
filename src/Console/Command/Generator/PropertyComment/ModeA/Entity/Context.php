<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity;

use Ghjayce\Shipshape\Entity\Context\ClientContext;

class Context extends ClientContext
{
    protected array $classesWithNamespace = [];
    protected array $filesWithAbsolutePath = [];
}