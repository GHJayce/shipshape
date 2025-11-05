<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Ghjayce\Shipshape\Action\TheEnd;
use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use Phparm\Entity\Attribute;
use Psr\Container\ContainerInterface;

/**
 * @method $this setHook(Hook|null $hook)
 * @method Hook|null getHook()
 * @method $this setContainer(ContainerInterface $container)
 * @method ContainerInterface getContainer()
 */
abstract class Config extends Attribute
{
    protected array $works = [];
    public ?Hook $hook = null;
    public ?ContainerInterface $container = null;

    abstract protected function generate(): array;

    public function setWork(string $name, mixed $callable): self
    {
        $this->works[$name] = $callable;
        return $this;
    }

    public function getWorks(): array
    {
        return $this->works;
    }

    public function build(): self
    {
        $works = $this->generate();
        $works = $this->customWorks($works);
        $works = $this->makeCallable($works);
        $this->works = $works;
        $this->hookMakeCallable();
        return $this;
    }

    protected function hookMakeCallable(): void
    {
        $hook = $this->getHook();
        $items = [
            'before' => $hook?->getBefore(),
            'process' => $hook?->getProcess(),
            'after' => $hook?->getAfter(),
        ];
        $result = $this->makeCallable($items);
        foreach ($items as $key => $value) {
            $this->hook->$key = $result[$key] ?? null;
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

    protected function makeCallable(array $items): array
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
            $instance = $this->makeClass($callable[0], $callable);
            if (!$instance || !is_callable([$instance, $callable[1]])) {
                $notWorking[] = $name;
                continue;
            }
            $items[$name][0] = $instance;
        }
        return array_diff_key($items, array_fill_keys($notWorking, 0));
    }

    protected function makeClass(string $className, mixed $callable = null): mixed
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
