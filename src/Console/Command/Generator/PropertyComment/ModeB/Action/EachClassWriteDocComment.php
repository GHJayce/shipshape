<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Entity\ModeBContext;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class EachClassWriteDocComment extends Action
{

    /**
     * @param ModeBContext $context
     * @param ShipshapeContext $shipshapeContext
     * @return mixed
     * @throws \JsonException
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        $codeScoreBoard = $context->getService()->eachClassesWriteDocComment(
            $context->getClassesWithNamespace(),
            $context->getParam()->getIgnoreClassesWithNamespace()
        );
        return $context->setScoreBoard($codeScoreBoard);
    }
}