<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Entity\ModeCContext;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class Handle extends Action
{

    /**
     * @param ModeCContext $context
     * @param ShipshapeContext $shipshapeContext
     * @return mixed
     * @throws \JsonException
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        $result = $context->getService()->handle($context->getParam());
        return $context->setHandleResult($result);
    }
}