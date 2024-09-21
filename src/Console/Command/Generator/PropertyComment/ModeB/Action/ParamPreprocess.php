<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Entity\ModeBContext;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class ParamPreprocess extends Action
{

    /**
     * @param ModeBContext $context
     * @param ShipshapeContext $shipshapeContext
     * @return mixed
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        $target = $context->getParam()->getTarget();
        $afterTarget = $context->getService()->targetPreprocess($target);
        if (!$afterTarget) {
            throw new \RuntimeException("'{$target}' is invalid");
        }
        return $context->setTarget($afterTarget);
    }
}