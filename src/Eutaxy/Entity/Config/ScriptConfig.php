<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Entity\Config;

use Ghjayce\MagicSocket\Eutaxy\Support\Tool\Config\MappingTool;
use Psr\Container\ContainerInterface;

/**
 * @method array getMapping()
 * @method self setMapping(array $mapping)
 * @method self setBeforeExecuteHook(?callable $beforeExecuteHook)
 * @method callable|null getBeforeExecuteHook()
 * @method self setProcessExecuteHook(?callable $processExecuteHook)
 * @method callable|null getProcessExecuteHook()
 * @method self setAfterExecuteHook(?callable $afterExecuteHook)
 * @method callable|null getAfterExecuteHook()
 *
 * @method self setRoster(array $roster)
 * @method array getRoster()
 * @method self setClass(string $class)
 * @method string getClass()
 * @method self setPath(string $path)
 * @method string getPath()
 * @method self setCustomMapping(array $customMapping)
 * @method array getCustomMapping()
 */
class ScriptConfig extends EutaxyConfig
{
    protected array $roster = [];
    protected string|object $class = '';
    protected string $path = '';
    protected array $customMapping = [];

    public function build(): self
    {
        $classMapping = $this->classMappingGenerate();
        $pathMapping = $this->pathMappingGenerate();
        $this->mapping = array_merge(
            $classMapping,
            $pathMapping,
            $this->customMapping,
        );
        return $this;
    }

    public function usable(ContainerInterface $container): self
    {
        $this->mappingUsable($container)
            ->hookUsable($container);
        return $this;
    }

    protected function mappingUsable(ContainerInterface $container): self
    {
        $mapping = [];
        foreach ($this->mapping as $name => $callable) {
            if (!$callable = MappingTool::toUsableCallable($callable, $container)) {
                continue;
            }
            $mapping[$name] = $callable;
        }
        $this->mapping = $mapping;
        return $this;
    }

    protected function hookUsable(ContainerInterface $container): self
    {
        $hookCallable = ['beforeExecuteHook', 'processExecuteHook', 'afterExecuteHook'];
        foreach ($hookCallable as $variableName) {
            $callable = MappingTool::toUsableCallable($this->$variableName, $container);
            $this->$variableName = $callable ?: null;
        }
        return $this;
    }

    protected function classMappingGenerate(): array
    {
        return MappingTool::classGenerate($this->roster, $this->class);
    }

    protected function pathMappingGenerate(): array
    {
        return MappingTool::pathGenerate($this->roster, $this->path);
    }
}
