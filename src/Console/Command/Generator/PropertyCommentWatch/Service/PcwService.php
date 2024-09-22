<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Service;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\PropertyCommentService;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Contract\ServiceInterface;
use Ghjayce\Shipshape\Tool\ClassTool;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser\Php7;
use Symfony\Component\Finder\Finder;

class PcwService implements ServiceInterface
{
    public const COMPARE_FLAG_FIRST_TIME = 1;
    public const COMPARE_FLAG_HAS_CHANGE = 2;
    public const COMPARE_FLAG_NOT_ANY_CHANGE = 3;

    public function scanParseDirectoryFiles(string $directory): array
    {
        $result = [];
        $finder = new Finder();
        $finder->files()->in([$directory])->name('*.php');

        $lexer = new Emulative();
        $astParser = new Php7($lexer);
        foreach ($finder as $file) {
            $stmts = $astParser->parse($file->getContents());
            $filePath = $file->getRealPath();
            $temp = [
                'fileName' => $file->getFilename(),
                'filePath' => $filePath,
                'modifyTime' => $file->getMTime(),
                'className' => ClassTool::getClassNameByStmts($stmts),
            ];
            $result[$filePath] = $temp;
        }
        return $result;
    }

    public function compareScanResult(array $currentScanResult, array $lastScanResult = []): array
    {
        $result = [
            'flag' => !$lastScanResult ? self::COMPARE_FLAG_FIRST_TIME : 0,
            'classes' => [],
        ];
        foreach ($currentScanResult as $filePath => $item) {
            $oldModifyTime = $lastScanResult[$filePath]['modifyTime'] ?? 0;
            if ($item['modifyTime'] > $oldModifyTime) {
                $result['classes'][] = $item['className'];
            }
        }
        if (!$result['flag']) {
            $result['flag'] = $result['classes'] ? self::COMPARE_FLAG_HAS_CHANGE : self::COMPARE_FLAG_NOT_ANY_CHANGE;
        }
        return $result;
    }

    public function calcNextRoundInterval(int $compareFlag, int $currentInterval, int $intervalMin, int $intervalMax): int
    {
//        if ($compareFlag === self::COMPARE_FLAG_NOT_ANY_CHANGE) {
//            $interval = $currentInterval * 2;
//            $interval = min($interval, $intervalMax);
//        } else {
            $interval = $intervalMin;
//        }
        return $interval;
    }

    /**
     * @throws \JsonException
     */
    public function executeClasses(array $classesWithNamespace, array $ignoreClassesWithNamespace = []): void
    {
        $scoreBoard = PropertyCommentService::eachClassesWriteDocComment($classesWithNamespace, $ignoreClassesWithNamespace);
        PropertyCommentService::reportScoreBoard($scoreBoard);
    }
}
