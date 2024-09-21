<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Entity;

use Composer\Autoload\ClassLoader;
use Ghjayce\Shipshape\Entity\Base\Property;

/**
 * @method string getTarget()
 * @method $this setTarget(string $target)
 * @method array getIgnoreClassesWithNamespace()
 * @method $this setIgnoreClassesWithNamespace(array $ignoreClassesWithNamespace)
 * @method ClassLoader getClassLoader()
 * @method $this setClassLoader(ClassLoader $classLoader)
 */
class ModeCParam extends Property
{
    protected string $target = '';
    protected array $ignoreClassesWithNamespace = [];
    protected ClassLoader $classLoader;
}