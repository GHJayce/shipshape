<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Contract;

interface ServiceInterface
{
    public function scanParseDirectoryFiles(string $directory): array;
    public function compareScanResult(array $currentScanResult, array $lastScanResult = []): array;
    public function calcNextRoundInterval(int $compareFlag, int $currentInterval, int $intervalMin, int $intervalMax): int;
    public function executeClasses(array $classesWithNamespace, array $ignoreClassesWithNamespace = []): void;
}