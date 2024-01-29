<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy;

use Ghbjayce\MagicSocket\Common\Work\Action\Contract\EutaxyInterface;
use Ghbjayce\MagicSocket\Common\Work\Action\Contract\ScriptInterface;
use Ghbjayce\MagicSocket\Common\Work\Entity\Context\Context;
use Ghbjayce\MagicSocket\Common\Work\Entity\Param\Param;
use Ghbjayce\MagicSocket\Eutaxy\Work\Tool\Config\ConfigTool;
use Psr\Container\ContainerInterface;

abstract class Script implements ScriptInterface
{
    public function __construct(
        protected EutaxyInterface $eutaxy,
        protected ContainerInterface $container,
    )
    {
    }

    abstract public function getRoster(): array;

    public function execute(
        mixed $config,
        Param $param,
        Context $context
    ): mixed
    {
        return $this->eutaxy->execute(
            $this->getConfig($config),
            $this->getParam($param),
            $this->getContext($config, $param, $context)
        );
    }

    protected function getParam(Param $param): Param
    {
        return $param;
    }

    protected function getContext(
        mixed $config,
        Param $param,
        Context $context
    ): Context
    {
        return $context;
    }

    public function getConfig(mixed $config): array
    {
        return ConfigTool::toConfig($this->getRoster(), $config);
    }
}
