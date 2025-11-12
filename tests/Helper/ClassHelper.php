<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Helper;

class ClassHelper
{
    public static function callRestrictMethod(object $object, string $methodName, array $args = []): mixed
    {
        $reflectionClass = new \ReflectionClass($object);
        $method = $reflectionClass->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
}