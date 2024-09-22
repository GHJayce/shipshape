<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Entity;

use Composer\Autoload\ClassLoader;
use Ghjayce\Shipshape\Entity\Base\Property;

/**
 * @method string getTarget()
 * @method $this setTarget(string $target)
 * @method array getIgnoreClassesWithNamespace()
 * @method $this setIgnoreClassesWithNamespace(array $ignoreClassesWithNamespace)
 * @method ClassLoader getClassLoader()
 * @method $this setClassLoader(ClassLoader $classLoader)
 * @method int getIntervalMin()
 * @method $this setIntervalMin(int $intervalMin)
 * @method int getIntervalMax()
 * @method $this setIntervalMax(int $intervalMax)
 * @method $this setInterval(int $interval)
 * @method array getLastScanResult()
 * @method $this setLastScanResult(array $lastScanResult)
 * @method array getCcDecqq()
 * @method $this setCcDecqq(array $ccDecqq)
 */
class PcwParam extends Property
{
    protected string $target = '';
    protected array $ignoreClassesWithNamespace = [];
    protected ClassLoader $classLoader;
    protected int $intervalMin = 5;
    protected int $intervalMax = 600;
    protected int $interval;
    protected array $lastScanResult = [];
    protected array $cc = [];

    public function getInterval(): int
    {
        $this->interval = $this->interval ?? $this->intervalMin;
        return $this->interval;
    }
}