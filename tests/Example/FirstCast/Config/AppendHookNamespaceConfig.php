<?php

declare(strict_types=1);

namespace Example\FirstCast\Config;

use Ghjayce\Shipshape\Entity\Config\AppendHookWork;
use Ghjayce\Shipshape\Entity\Config\Hook;
use Ghjayce\Shipshape\Entity\Config\NamespaceConfig;
use Psr\Container\ContainerInterface;

/**
 * @method $this setNames(array $names)
 * @method array getNames()
 * @method string getNamespace()
 * @method $this setHook(Hook|null $hook)
 * @method Hook|null getHook()
 * @method $this setContainer(ContainerInterface $container)
 * @method ContainerInterface getContainer()
 */
class AppendHookNamespaceConfig extends NamespaceConfig
{
    use AppendHookWork;
}