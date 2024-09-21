<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Contract;

interface ServiceInterface
{
    public function targetPreprocess(string $target, array $options = []): string;
    public function getTargetType(string $target): int;
    public function getFilesWithAbsolutePath(int $targetType, string $target): array;
    public function getClassesWithNamespaceByFilesWithAbsolutePath(array $filesWithAbsolutePath): array;
    public function eachClassesWriteDocComment(array $classesWithNamespace, array $ignoreClassesWithNamespace = []): array;
    public function reportHandleResult(array $scoreBoard): mixed;
}