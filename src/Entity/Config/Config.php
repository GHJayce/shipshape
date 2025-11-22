<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use Phparm\Entity\Attribute;
use Psr\Container\ContainerInterface;

/**
 * ========== property_hook_method ==========
 * @method Hook|null getHook()
 * @method ContainerInterface|null getContainer()
 *
 * @method $this setHook(Hook|null $hook)
 * @method $this setContainer(ContainerInterface|null $container)
 * ========== property_hook_method ==========
 */
abstract class Config extends Attribute
{
    public ?Hook $hook = null;
    public ?ContainerInterface $container = null;

    protected array $works = [];

    private bool $built = false;

    abstract protected function generate(?Option $option = null): array;

    public function setWork(string $name, mixed $callable): self
    {
        $this->works[$name] = $callable;
        return $this;
    }

    public function getWorks(): array
    {
        return $this->works;
    }

    public function reset(?Option $option = null): self
    {
        $this->resetBuilt();
        return $this;
    }

    protected function resetBuilt(): self
    {
        $this->built = false;
        return $this;
    }

    public function isBuilt(): bool
    {
        return $this->built;
    }

    public function build(?Option $option = null): self
    {
        $works = $this->generate($option);
        $works = $this->customWorks($works);
        $works = $this->makeCallable($works, $option);
        $this->works = $works;
        $this->hookMakeCallable($option);
        $this->built = true;
        return $this;
    }

    protected function hookMakeCallable(?Option $option = null): void
    {
        $hook = $this->getHook();
        $items = [
            'before'  => $hook?->getBefore(),
            'process' => $hook?->getProcess(),
            'after'   => $hook?->getAfter(),
        ];
        $result = $this->makeCallable($items, $option);
        foreach ($items as $field => $value) {
            if (!$hook) {
                continue;
            }
            $method = 'set' . ucfirst($field);
            $this->hook->$method($result[$field] ?? null);
        }
    }

    protected function customWorks(array $works): array
    {
        return $this->appendTheEndAction($works);
    }

    protected function appendTheEndAction(array $works): array
    {
        $name = ActionEnum::THE_END_ACTION_NAME;
        if (!isset($works[$name])) {
            $works[$name] = [TheEnd::class, ActionEnum::ACTION_EXECUTE_METHOD_NAME];
        }
        return $works;
    }

    protected function makeCallable(array $items, ?Option $option = null): array
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
            $instance = $this->makeClass($callable[0], $option);
            if (!$instance || !is_callable([$instance, $callable[1]])) {
                $notWorking[] = $name;
                continue;
            }
            $items[$name][0] = $instance;
        }
        return array_diff_key($items, array_fill_keys($notWorking, 0));
    }

    protected function makeClass(string $className, ?Option $option = null): mixed
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
