<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Example\Bathe\Entity;

use Phparm\Entity\Attribute;

/**
 * @method int getSecond()
 * @method $this setSecond(int $second)
 */
class Time extends Attribute
{
    public int $second = 0;
}