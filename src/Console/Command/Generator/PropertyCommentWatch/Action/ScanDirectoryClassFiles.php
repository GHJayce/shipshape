<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Entity\PcwContext;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class ScanDirectoryClassFiles extends Action
{

    /**
     * @param PcwContext $context
     * @param ShipshapeContext $shipshapeContext
     * @return PcwContext
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): PcwContext
    {
        $scanResult = $context->getService()->scanParseDirectoryFiles($context->getParam()->getTarget());
        return $context->setScanResult($scanResult);
    }
}