<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use Psr\Container\ContainerInterface;

/**
 * @method array getWorks()
 * @method self setWorks(array $works)
 * @method self setBeforeHandleHook(?callable $beforeHandleHook)
 * @method callable|null getBeforeHandleHook()
 * @method self setProcessHandleHook(?callable $processHandleHook)
 * @method callable|null getProcessHandleHook()
 * @method self setAfterHandleHook(?callable $afterHandleHook)
 * @method callable|null getAfterHandleHook()
 * @method self setNames(array $names)
 * @method array getNames()
 * @method self setClass(string $class)
 * @method string getClass()
 * @method string getNamespace()
 * @method self setActions(array $actions)
 * @method array getActions()
 * @method self setContainer(ContainerInterface $container)
 * @method ContainerInterface getContainer()
 */
class ShipshapeHookConfig extends ShipshapeConfig
{
    protected function customHandleWorks(array $works): array
    {
        $works = $this->appendTheEndToWorks($works);
        $theEndActionName = $this->theEndActionName();
        if (!isset($works[$theEndActionName])) {
            $works[$theEndActionName] = [TheEnd::class, $this->actionExecuteMethodName()];
        }
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

    protected function appendTheEndToWorks(array $works): array
    {
        $theEndActionName = $this->theEndActionName();
        if (!isset($works[$theEndActionName])) {
            $works[$theEndActionName] = [TheEnd::class, $this->actionExecuteMethodName()];
        }
        return $works;
    }

    protected function theEndActionName(): string
    {
        return ActionEnum::THE_END_ACTION_NAME;
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
