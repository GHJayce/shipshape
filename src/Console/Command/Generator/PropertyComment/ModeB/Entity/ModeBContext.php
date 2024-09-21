<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Entity;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Contract\ServiceInterface;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Service\ModeBService;
use Ghjayce\Shipshape\Entity\Context\ClientContext;

/**
 * @method ModeBParam getParam()
 * @method $this setParam(ModeBParam $param)
 * @method ServiceInterface|ModeBService getService()
 * @method $this setService(ServiceInterface|ModeBService $service)
 * @method array getClassesWithNamespace()
 * @method $this setClassesWithNamespace(array $classesWithNamespace)
 * @method string getTarget()
 * @method $this setTarget(string $target)
 * @method int getTargetType()
 * @method $this setTargetType(int $targetType)
 * @method array getScoreBoard()
 * @method $this setScoreBoard(array $scoreBoard)
 */
class ModeBContext extends ClientContext
{
    protected ModeBParam $param;
    protected ServiceInterface|ModeBService $service;
    protected array $classesWithNamespace = [];
    protected string $target;
    protected int $targetType;
    protected array $scoreBoard;
}