<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity\Context;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;

class IdentifyTargetType extends Action
{
    public const TARGET_TYPE_DIRECTORY = 1;
    public const TARGET_TYPE_FILE = 2;
    public const TARGET_TYPE_CLASS_WITH_NAMESPACE = 3;
    /**
     * @param Context $context
     * @param ShipshapeContext $shipshapeContext
     * @return mixed
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        $classesWithNamespace = [];
        $filesWithAbsolutePath = [];
        $targetType = 0;

        $target = $context->getTarget();
        $isFile = is_file($target);
        $isDir = is_dir($target);
        $isClass = class_exists($target);
        if (!($isClass || $isDir || $isFile)) {
            throw new \RuntimeException('Not file absolute path or class name or directory.');
        }
        if ($isDir) {
            $targetType = self::TARGET_TYPE_DIRECTORY;
            $finder = new Finder();
            $finder->files()->in([$target])->name('*.php');
            foreach ($finder as $file) {
                $filesWithAbsolutePath[] = $file->getRealPath();
            }
        }
        if ($isFile) {
            $targetType = self::TARGET_TYPE_FILE;
            $filesWithAbsolutePath[] = $target;
        }
        if ($isClass) {
            $targetType = self::TARGET_TYPE_CLASS_WITH_NAMESPACE;
            $classesWithNamespace[] = $target;
        }
        return $context->setFilesWithAbsolutePath($filesWithAbsolutePath)
            ->setClassesWithNamespace($classesWithNamespace)
            ->setTargetType($targetType);
    }
}