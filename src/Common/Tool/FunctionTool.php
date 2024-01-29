<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Common\Tool;


use Hyperf\Context\ApplicationContext;

class FunctionTool
{
    public static function call($callback, ...$params)
    {
        if (is_array($callback)) {
            $class = &$callback[0];
            $container = ApplicationContext::getContainer();
            if (is_string($class)) {
                if ($container->has($class)) {
                    $class = $container->get($class);
                }
            }
        }
        return call_user_func($callback, ...$params);
    }

    public static function callWithIgnore($callback, ...$params)
    {
        if (is_array($callback)) {
            $class = &$callback[0];
            if (!class_exists($class)) {
                return null;
            }
            $container = ApplicationContext::getContainer();
            if (is_string($class)) {
                if ($container->has($class)) {
                    $class = $container->get($class);
                }
            }
        }
        if (is_callable($callback)) {
            return call_user_func($callback, ...$params);
        }
        return null;
    }

    public static function checkCall($callback, ...$params)
    {
        if (!is_callable($callback)) {
            throw new \Exception('Not callable: ' . serialize($callback));
        }
        return self::call($callback, ...$params);
    }
}