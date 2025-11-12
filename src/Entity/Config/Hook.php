<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Phparm\Entity\Attribute;

/**
 * @method $this setBefore(?callable $before)
 * @method callable|null getBefore()
 * @method $this setProcess(?callable $process)
 * @method callable|null getProcess()
 * @method $this setAfter(?callable $after)
 * @method callable|null getAfter()
 */
class Hook extends Attribute
{
    public mixed $before = null;
    public mixed $process = null;
    public mixed $after = null;
}
