<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Base\Property;
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
class ShipshapeConfig extends Property
{
    protected array $works = [];
    protected $beforeHandleHook = null;
    protected $processHandleHook = null;
    protected $afterHandleHook = null;
    protected array $names = [];
    protected string|object $class = '';
    protected string $namespace = '';
    protected array $actions = [];
    protected ?ContainerInterface $container = null;
    protected bool $appendTheEndAction = true;

    public function setNamespace(string $namespace): self
    {
        $this->namespace = trim($namespace, '\\').'\\';
        return $this;
    }

    public function addWork(string $name, mixed $callable): self
    {
        $this->works[$name] = $callable;
        return $this;
    }

    public function build(): self
    {
        $works = $this->merge();
        $works = $this->customHandleWorks($works);
        $this->setWorks($this->intoCallable($works));
        $this->hookIntoCallable();
        return $this;
    }

    protected function hookIntoCallable(): void
    {
        $items = [
            'beforeHandleHook' => $this->getBeforeHandleHook(),
            'processHandleHook' => $this->getProcessHandleHook(),
            'afterHandleHook' => $this->getAfterHandleHook(),
        ];
        $result = $this->intoCallable($items);
        foreach ($items as $key => $value) {
            $this->$key = $result[$key] ?? null;
        }
    }

    public function merge(): array
    {
        return array_merge(
            self::generateByClass($this->getNames(), $this->getClass()),
            self::generateByNamespace($this->getNames(), $this->getNamespace()),
            self::generateByActions($this->getActions()),
        );
    }

    public static function generateByClass(array $names, string|object $class): array
    {
        if (empty($class)) {
            return [];
        }
        foreach ($names as $name) {
            $result[$name] = [
                $class,
                $name
            ];
        }
        return $result ?? [];
    }

    public static function generateByNamespace(array $names, string $namespace, string $methodName = ActionEnum::ACTION_EXECUTE_METHOD_NAME): array
    {
        if (empty($namespace)) {
            return [];
        }
        $namespace = ucwords(
            trim(
                strtr($namespace, [
                    '/' => '\\',
                ]),
                '\\'
            )
        );
        foreach ($names as $name) {
            $result[$name] = [
                "{$namespace}\\".ucfirst($name),
                $methodName
            ];
        }
        return $result ?? [];
    }

    public static function generateByActions(array $actions, string $methodName = ActionEnum::ACTION_EXECUTE_METHOD_NAME): array
    {
        $result = [];
        foreach ($actions as $name => $action) {
            if (!is_string($name)) {
                if (is_string($action)) {
                    $name = $action;
                } elseif (is_object($action)) {
                    $name = get_class($action);
                }
            }
            $result[$name] = [$action, $methodName];
        }
        return $result;
    }

    protected function customHandleWorks(array $works): array
    {
        if ($this->getAppendTheEndAction()) {
            $works = $this->appendTheEndActionToWorks($works);
        }
        return $works;
    }

    protected function appendTheEndActionToWorks(array $works): array
    {
        $name = ActionEnum::THE_END_ACTION_NAME;
        if (!isset($works[$name])) {
            $works[$name] = [TheEnd::class, ActionEnum::ACTION_EXECUTE_METHOD_NAME];
        }
        return $works;
    }

    public function intoCallable(array $items): array
    {
        $notWorking = [];
        $rescueWorking = [];
        foreach ($items as $name => $callable) {
            if (!$callable) {
                $notWorking[] = $name;
                continue;
            }
            if (is_array($callable) && isset($callable[0], $callable[1])) {
                if (!method_exists(...$callable)) {
                    $notWorking[] = $name;
                    continue;
                }
                if (!is_callable($callable) && is_string($callable[0])) {
                    $rescueWorking[$name] = $callable;
                    continue;
                }
            }
            if (!is_callable($callable)) {
                $notWorking[] = $name;
            }
        }
        foreach ($rescueWorking as $name => $callable) {
            $instance = $this->takeClassInstance($callable[0], $callable);
            if (!$instance || !is_callable([$instance, $callable[1]])) {
                $notWorking[] = $name;
                continue;
            }
            $items[$name][0] = $instance;
        }
        return array_diff_key($items, array_fill_keys($notWorking, 0));
    }

    protected function takeClassInstance(string $className, mixed $callable = null): mixed
    {
        if ($this->container) {
            $instance = $this->container->get($className);
            if ($instance) {
                return $instance;
            }
        }
        if (!class_exists($className)) {
            return null;
        }
        return new $className;
    }
}
