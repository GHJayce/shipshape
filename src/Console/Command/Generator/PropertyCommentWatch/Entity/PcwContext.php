<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Entity;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Contract\ServiceInterface;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Service\PcwService;
use Ghjayce\Shipshape\Entity\Context\ClientContext;

/**
 * @method PcwParam getParam()
 * @method $this setParam(PcwParam $param)
 * @method ServiceInterface|PcwService getService()
 * @method $this setService(ServiceInterface|PcwService $service)
 * @method array getScanResult()
 * @method $this setScanResult(array $scanResult)
 * @method int getCompareFlag()
 * @method $this setCompareFlag(int $compareFlag)
 * @method array getClasses()
 * @method $this setClasses(array $classes)
 */
class PcwContext extends ClientContext
{
    protected PcwParam $param;
    protected ServiceInterface|PcwService $service;
    protected array $scanResult;
    protected int $compareFlag;
    protected array $classes;
}