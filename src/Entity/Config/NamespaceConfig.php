<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Ghjayce\Shipshape\Entity\Enum\ActionEnum;
use Psr\Container\ContainerInterface;

/**
 * @method $this setNames(array $names)
 * @method array getNames()
 * @method string getNamespace()
 * @method $this setHook(Hook|null $hook)
 * @method Hook|null getHook()
 * @method $this setContainer(ContainerInterface $container)
 * @method ContainerInterface getContainer()
 */
class NamespaceConfig extends Config
{
    public array $names = [];
    public string $namespace = '';

    public function setNamespace(string $namespace): self
    {
        $this->namespace = trim($namespace, '\\') . '\\';
        return $this;
    }

    protected function generateNamespace(array $names, string $namespace, string $methodName = ActionEnum::ACTION_EXECUTE_METHOD_NAME): array
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
                "{$namespace}\\" . ucfirst($name),
                $methodName,
            ];
        }
        return $result ?? [];
    }

    protected function generate(?Option $option = null): array
    {
        return $this->generateNamespace($this->getNames(), $this->getNamespace());
    }
}
