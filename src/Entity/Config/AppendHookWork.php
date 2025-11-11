<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Ghjayce\Shipshape\Entity\Enum\ActionEnum;

trait AppendHookWork
{
    protected function customWorks(array $works): array
    {
        $works = parent::customWorks($works);
        return $this->appendHookPrefixWorks($works);
    }

    protected function appendHookPrefixWorks(
        array $works,
        string $actionExecuteMethodName = ActionEnum::ACTION_EXECUTE_METHOD_NAME,
        string $appendHookPrefixName = ActionEnum::APPEND_HOOK_PREFIX_NAME,
    ): array
    {
        $result = [];
        foreach ($works as $name => $action) {
            $namespace = $hookAction = null;
            $hookActionName = $appendHookPrefixName . ucfirst($name);
            if ($this instanceof ClassConfig) {
                $class = $this->getClass();
                if ($class) {
                    $hookAction = [$class, $hookActionName];
                }
            }
            if ($this instanceof NamespaceConfig) {
                $namespace = $this->getNamespace();
            }
            if ($this instanceof ActionConfig) {
                if (str_contains($name, '\\')) {
                    $hookActionName = $appendHookPrefixName . ucfirst(basename(strtr($name, ['\\' => '/'])));
                }
                $firstAction = $this->getActions()[0] ?? null;
                if ($firstAction) {
                    $namespace = strtr(dirname(strtr($firstAction, ['\\' => '/'])), ['/' => '\\']) . '\\';
                }
            }
            if ($namespace) {
                $hookAction = [$namespace . ucfirst($hookActionName), $actionExecuteMethodName];
            }
            if (isset($hookAction)) {
                $result[$hookActionName] = $hookAction;
            }
            $result[$name] = $action;
        }
        return $result;
    }
}
