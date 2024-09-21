<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity;

use Ghjayce\Shipshape\Entity\Context\ClientContext;

/**
 * @method ModeAParam getParam()
 * @method $this setParam(ModeAParam $param)
 * @method array getClassesWithNamespace()
 * @method $this setClassesWithNamespace(array $classesWithNamespace)
 * @method array getFilesWithAbsolutePath()
 * @method $this setFilesWithAbsolutePath(array $filesWithAbsolutePath)
 * @method string getTarget()
 * @method $this setTarget(string $target)
 * @method int getTargetType()
 * @method $this setTargetType(int $targetType)
 * @method array getScoreBoard()
 * @method $this setScoreBoard(array $scoreBoard)
 */
class ModeAContext extends ClientContext
{
    protected ModeAParam $param;
    protected array $classesWithNamespace = [];
    protected array $filesWithAbsolutePath = [];
    protected string $target;
    protected int $targetType;
    protected array $scoreBoard;
}