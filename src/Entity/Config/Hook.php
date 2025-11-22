<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Entity\Config;

use Phparm\Entity\Attribute;

/**
 * ========== property_hook_method ==========
 * @method mixed|null getBefore()
 * @method mixed|null getProcess()
 * @method mixed|null getAfter()
 *
 * @method $this setBefore(mixed|null $before)
 * @method $this setProcess(mixed|null $process)
 * @method $this setAfter(mixed|null $after)
 * ========== property_hook_method ==========
 */
class Hook extends Attribute
{
    public mixed $before = null;
    public mixed $process = null;
    public mixed $after = null;
}
