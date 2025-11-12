<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Shipshape;

use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;
use Ghjayce\Shipshape\Shipshape;
use PHPUnit\Framework\TestCase;

class InvalidArgumentException extends TestCase
{
    public function testEmptyConfig(): void
    {
        $this->expectException(\Error::class);
        $shipshape = new Shipshape();
        $shipshape->execute(ExecuteContext::make());
    }

    public function testEmptyClientContext(): void
    {
        $this->expectException(\Error::class);
        $shipshape = new Shipshape();
        $context = ExecuteContext::make()
            ->setConfig(ActionConfig::make());
        $shipshape->execute($context);
    }
}