<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity\Context;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\Original\PropertyCommentOriginal;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class ReportScore extends Action
{

    /**
     * @param Context $context
     * @param ShipshapeContext $shipshapeContext
     * @return mixed
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        PropertyCommentOriginal::reportScoreBoard($context->getScoreBoard());
        return null;
    }
}