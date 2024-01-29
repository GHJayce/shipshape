<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Common\Base;

use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

class Basic
{
    #[Inject]
    public ContainerInterface $container;
}
