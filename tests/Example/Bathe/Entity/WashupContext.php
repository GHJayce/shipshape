<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Example\Bathe\Entity;

use Ghjayce\Shipshape\Entity\Context\ClientContext;

/**
 * @method BatheContext getBatheContext()
 * @method $this setBatheContext(BatheContext $batheContext)
 */
class WashupContext extends ClientContext
{
    public BatheContext $batheContext;
}