<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use Psr\Container\ContainerInterface;

/**
 * @method array getWorks()
 * @method $this setWorks(array $works)
 * @method $this setBeforeHandleHook(?callable $beforeHandleHook)
 * @method callable|null getBeforeHandleHook()
 * @method $this setProcessHandleHook(?callable $processHandleHook)
 * @method callable|null getProcessHandleHook()
 * @method $this setAfterHandleHook(?callable $afterHandleHook)
 * @method callable|null getAfterHandleHook()
 * @method $this setNames(array $names)
 * @method array getNames()
 * @method $this setClass(string $class)
 * @method string getClass()
 * @method string getNamespace()
 * @method $this setActions(array $actions)
 * @method array getActions()
 * @method $this setContainer(ContainerInterface $container)
 * @method ContainerInterface getContainer()
 * @method $this setAppendTheEndAction(bool $appendTheEndAction)
 * @method bool getAppendTheEndAction()
 */
class ShipshapeHookConfig extends ShipshapeConfig
{
    protected function customHandleWorks(array $works): array
    {
        $works = parent::customHandleWorks($works);
        $result = [];
        foreach ($works as $name => $action) {
            $hookActionName = $this->hookPrefixActionName().ucfirst($name);
            $class = $this->getClass();
            if ($class) {
                $hookAction = [$class, $hookActionName];
            }
            $namespace = $this->getNamespace();
            $firstAction = $this->getActions()[0] ?? null;
            if (!$namespace && $firstAction) {
                $namespace = strtr(dirname(strtr($firstAction, ['\\' => '/'])), ['/' => '\\']).'\\';
            }
            if ($namespace) {
                $hookAction = [$namespace.ucfirst($hookActionName), $this->actionExecuteMethodName()];
            }
            if (isset($hookAction)) {
                $result[$hookActionName] = $hookAction;
            }
            $result[$name] = $action;
        }
        return $result;
    }

    protected function actionExecuteMethodName(): string
    {
        return ActionEnum::ACTION_EXECUTE_METHOD_NAME;
    }

    protected function hookPrefixActionName(): string
    {
        return ActionEnum::HOOK_PREFIX_ACTION_NAME;
    }
}
