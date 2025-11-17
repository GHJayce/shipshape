<?php

declare(strict_types=1);

namespace GhjayceTest\Shipshape\Shipshape;

use GhjayceTest\Shipshape\Example\Bathe\Action\GetDressed;
use GhjayceTest\Shipshape\Example\Bathe\Action\Undressing;
use GhjayceTest\Shipshape\Example\Bathe\Action\Washing;
use GhjayceTest\Shipshape\Example\Bathe\Action\WipeUp;
use GhjayceTest\Shipshape\Example\Bathe\Entity\BatheContext;
use GhjayceTest\Shipshape\Example\Bathe\Entity\Time;
use Ghjayce\Shipshape\Entity\Config\ActionConfig;
use Ghjayce\Shipshape\Entity\Context\ExecuteContext;
use Ghjayce\Shipshape\Shipshape;
use PHPUnit\Framework\TestCase;

class Overall extends TestCase
{
    public function testMix(): void
    {
        $config = ActionConfig::make()
            ->setActions([
                Undressing::class,
                Washing::class,
                WipeUp::class,
                GetDressed::class,
            ]);
        $clientContext = BatheContext::make()
            ->setTime(Time::make());
        $context = ExecuteContext::make()
            ->setConfig($config)
            ->setClientContext($clientContext);
        $shipshape = new Shipshape();
        $result = $shipshape->execute($context);

        // 检查clientContext第一层属性值改动情况
        $this->assertSame('getDressed', $clientContext->getStatus());
        // 检查clientContext第二层、嵌套层属性值改动情况
        $this->assertSame(1275, $clientContext->getTime()->getSecond());
        // 检查最终返回结果
        $this->assertSame([
            'status' => 'getDressed',
            'time'   => [
                'second' => 1275,
            ],
        ], $result);
    }
}