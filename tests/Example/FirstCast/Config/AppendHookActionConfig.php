<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Example\FirstCast\Config;

use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use Ghjayce\Shipshape\Entity\Config\AppendHookWork;
use Ghjayce\Shipshape\Entity\Config\Hook;
use Psr\Container\ContainerInterface;

/**
 * @method $this setActions(array $actions)
 * @method array getActions()
 * @method $this setHook(Hook|null $hook)
 * @method Hook|null getHook()
 * @method $this setContainer(ContainerInterface $container)
 * @method ContainerInterface getContainer()
 */
class AppendHookActionConfig extends ActionConfig
{
    use AppendHookWork;
}