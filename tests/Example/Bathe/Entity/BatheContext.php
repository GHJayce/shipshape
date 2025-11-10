<?php

declare(strict_types=1);

namespace Example\Bathe\Entity;

use Ghjayce\Shipshape\Entity\Context\ClientContext;

/**
 * @method string getStatus()
 * @method $this setStatus(string $status)
 * @method Time getTime()
 * @method $this setTime(Time $time)
 */
class BatheContext extends ClientContext
{
    public string $status = '';
    public Time $time;
}