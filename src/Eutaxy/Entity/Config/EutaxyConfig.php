<?php

declare(strict_types=1);

namespace Ghjayce\MagicSocket\Eutaxy\Entity\Config;

use Ghjayce\MagicSocket\Common\Entity\Attribute;

/**
 * @method array getMapping()
 * @method self setMapping(array $mapping)
 * @method self setBeforeExecuteHook(?callable $beforeExecuteHook)
 * @method callable|null getBeforeExecuteHook()
 * @method self setProcessExecuteHook(?callable $processExecuteHook)
 * @method callable|null getProcessExecuteHook()
 * @method self setAfterExecuteHook(?callable $afterExecuteHook)
 * @method callable|null getAfterExecuteHook()
 */
class EutaxyConfig extends Attribute
{
    protected array $mapping = [];
    protected $beforeExecuteHook = null;
    protected $processExecuteHook = null;
    protected $afterExecuteHook = null;
}
