<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Common\Tool;


use Hyperf\Context\ApplicationContext;

class FunctionTool
{
    public static function call($callback, ...$params)
    {
        return call_user_func($callback, ...$params);
    }

    public static function callWithIgnore($callback, ...$params)
    {
        if (is_callable($callback)) {
            return self::call($callback, ...$params);
        }
        return null;
    }
}