<?php

declare(strict_types=1);

namespace GhjayceExample\Shipshape\Cases\BrushTeeth;

use Ghjayce\Shipshape\Entity\Context\ClientContext;

/**
 * @method float getBeginTime()
 * @method self setBeginTime(float $beginTime)
 */
class Context extends ClientContext
{
    protected float $beginTime;
}
