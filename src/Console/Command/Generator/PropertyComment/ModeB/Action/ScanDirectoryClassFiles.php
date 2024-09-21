<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Entity\ModeBContext;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Service\ModeBService;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class ScanDirectoryClassFiles extends Action
{

    /**
     * @param ModeBContext $context
     * @param ShipshapeContext $shipshapeContext
     * @return mixed
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        if (!in_array($context->getTargetType(), [ModeBService::TARGET_TYPE_DIRECTORY, ModeBService::TARGET_TYPE_FILE], true)) {
            return null;
        }
        $filesWithAbsolutePath = $context->getService()->getFilesWithAbsolutePath(
            $context->getTargetType(),
            $context->getParam()->getTarget()
        );
        $classesWithNamespace = $context->getClassesWithNamespace();
        array_push(
            $classesWithNamespace,
            ...$context->getService()->getClassesWithNamespaceByFilesWithAbsolutePath($filesWithAbsolutePath)
        );
        return $context->setClassesWithNamespace($classesWithNamespace);
    }
}