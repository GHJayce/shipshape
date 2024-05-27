<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Ghjayce\Shipshape\Entity\Base\Property;
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

    public function setNamespace(string $namespace): self
    {
        $this->namespace = trim($namespace, '\\').'\\';
        return $this;
    }

    public function addWork(string $name, callable $callable): self
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
                $className = $action;
                if (is_object($action)) {
                    $className = get_class($action);
                }
                $name = basename(strtr($className, ['\\' => '/']));
            }
            $result[$name] = [$action, $methodName];
        }
        return $result;
    }

    protected function customHandleWorks(array $works): array
    {
        return $works;
    }

    protected function intoCallable(array $items): array
    {
        $notWorking = [];
        $rescueWorking = [];
        foreach ($items as $name => $callable) {
            if (is_array($callable) && isset($callable[0], $callable[1])) {
                if (!method_exists(...$callable)) {
                    $notWorking[] = $name;
                    continue;
                }
                if (!is_callable($callable)) {
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
