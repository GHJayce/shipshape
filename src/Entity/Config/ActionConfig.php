<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use Psr\Container\ContainerInterface;

/**
 * @method $this setActions(array $actions)
 * @method array getActions()
 * @method $this setHook(Hook|null $hook)
 * @method Hook|null getHook()
 * @method $this setContainer(ContainerInterface $container)
 * @method ContainerInterface getContainer()
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

    protected function generate(): array
    {
        return $this->generateActions($this->getActions());
    }
}
