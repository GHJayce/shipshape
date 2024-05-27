<?php

declare(strict_types=1);

namespace GhjayceExample\Shipshape\Mock;

trait IgnoreAccessPermission
{
    public function __call($name, array $args = [])
    {
        if (method_exists($this, $name)) {
            return $this->$name(...$args);
        }
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return parent::__call($name, $args);
    }
}
