<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Closure;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use Psr\Container\ContainerInterface;

/**
 * ========== property_hook_method ==========
 * @method array getActions()
 * @method Hook|null getHook()
 * @method ContainerInterface|Closure|null getContainer()
 *
 * @method $this setActions(array $actions)
 * @method $this setHook(Hook|null $hook)
 * @method $this setContainer(ContainerInterface|Closure|null $container)
 * ========== property_hook_method ==========
 */
class ActionConfig extends Config
{
    public array $actions = [];

    protected function generateActions(array $actions, string $methodName = ActionEnum::ACTION_EXECUTE_METHOD_NAME): array
    {
        $result = [];
        foreach ($actions as $name => $action) {
            if (!is_string($name)) {
                if (is_string($action)) {
                    $name = $action;
                } else if (is_object($action)) {
                    $name = get_class($action);
                }
            }
            $result[$name] = [$action, $methodName];
        }
        return $result;
    }

    protected function generate(?Option $option = null): array
    {
        return $this->generateActions($this->getActions());
    }
}
