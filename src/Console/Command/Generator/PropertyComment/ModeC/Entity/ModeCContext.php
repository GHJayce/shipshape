<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Entity;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Contract\ServiceInterface;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Service\ModeCService;
use Ghjayce\Shipshape\Entity\Context\ClientContext;

/**
 * @method ModeCParam getParam()
 * @method $this setParam(ModeCParam $param)
 * @method ServiceInterface|ModeCService getService()
 * @method $this setService(ServiceInterface|ModeCService $service)
 * @method array getHandleResult()
 * @method $this setHandleResult(array $handleResult)
 */
class ModeCContext extends ClientContext
{
    protected ModeCParam $param;
    protected ServiceInterface|ModeCService $service;
    protected array $handleResult;
}