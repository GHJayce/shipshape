<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity\ModeAContext;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\PropertyCommentService;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class FindNamespaceDirectoryPath extends Action
{

    /**
     * @param ModeAContext $context
     * @param ShipshapeContext $shipshapeContext
     * @return mixed
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        $target = $context->getParam()->getTarget();
        if ($target[0] === '\\') {
            $target = PropertyCommentService::findNamespaceDirPath($target, $context->getParam()->getClassLoader());
            if (!$target) {
                throw new \RuntimeException("Class namespace '{$target}' not found");
            }
        }
        return $context->setTarget($target);
    }
}