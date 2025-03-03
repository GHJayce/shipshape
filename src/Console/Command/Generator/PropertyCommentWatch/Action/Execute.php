<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Entity\PcwContext;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Service\PcwService;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class Execute extends Action
{

    /**
     * @param PcwContext $context
     * @param ShipshapeContext $shipshapeContext
     * @return null
     * @throws \JsonException
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        if (in_array($context->getCompareFlag(), [PcwService::COMPARE_FLAG_FIRST_TIME, PcwService::COMPARE_FLAG_HAS_CHANGE], true)) {
            $context->getService()->executeClasses($context->getClasses(), $context->getParam()->getIgnoreClassesWithNamespace());
        }
        return null;
    }

    /**
     * @param PcwContext $context
     * @param ShipshapeContext $shipshapeContext
     * @return array
     */
    public function returnData(ClientContext $context, ShipshapeContext $shipshapeContext): array
    {
        return [
            'interval' => $context->getService()->calcNextRoundInterval(
                $context->getCompareFlag(),
                $context->getParam()->getInterval(),
                $context->getParam()->getIntervalMin(),
                $context->getParam()->getIntervalMax()
            ),
            'scanResult' => $context->getService()->scanParseDirectoryFiles($context->getParam()->getTarget()),
        ];
    }
}