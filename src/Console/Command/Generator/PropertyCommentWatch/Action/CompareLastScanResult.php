<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Entity\PcwContext;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class CompareLastScanResult extends Action
{

    /**
     * @param PcwContext $context
     * @param ShipshapeContext $shipshapeContext
     * @return mixed
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        $compareResult = $context->getService()->compareScanResult(
            $context->getScanResult(),
            $context->getParam()->getLastScanResult()
        );
        return $context->setCompareFlag($compareResult['flag'])
            ->setClasses($compareResult['classes']);
    }
}