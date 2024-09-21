<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Service;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Contract\ServiceInterface;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\PropertyCommentService;
use Ghjayce\Shipshape\Tool\ClassTool;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser\Php7;
use Symfony\Component\Finder\Finder;

class ModeBService implements ServiceInterface
{
    public const TARGET_TYPE_DIRECTORY = 1;
    public const TARGET_TYPE_FILE = 2;
    public const TARGET_TYPE_CLASS_WITH_NAMESPACE = 3;

    public function targetPreprocess(string $target, array $options = []): string
    {
        $classLoader = $options['classLoader'] ?? ClassTool::findLoader();
        if ($target[0] === '\\') {
            $target = PropertyCommentService::findNamespaceDirPath($target, $classLoader);
            if (!$target) {
                $target = '';
            }
        }
        return $target;
    }

    public function getTargetType(string $target): int
    {
        $isFile = is_file($target);
        $isDir = is_dir($target);
        $isClass = class_exists($target);
        $map = [
            self::TARGET_TYPE_DIRECTORY => $isDir,
            self::TARGET_TYPE_FILE => $isFile,
            self::TARGET_TYPE_CLASS_WITH_NAMESPACE => $isClass,
        ];
        return array_search(true, $map, true) ?: 0;
    }

    public function getFilesWithAbsolutePath(int $targetType, string $target): array
    {
        $filesWithAbsolutePath = [];
        switch ($targetType) {
            case self::TARGET_TYPE_DIRECTORY:
                $finder = new Finder();
                $finder->files()->in([$target])->name('*.php');
                foreach ($finder as $file) {
                    $filesWithAbsolutePath[] = $file->getRealPath();
                }
                break;
            case self::TARGET_TYPE_FILE:
                $filesWithAbsolutePath[] = $target;
                break;
        }
        return $filesWithAbsolutePath;
    }

    public function getClassesWithNamespaceByFilesWithAbsolutePath(array $filesWithAbsolutePath): array
    {
        $classesWithNamespace = [];
        $lexer = new Emulative();
        $astParser = new Php7($lexer);
        foreach ($filesWithAbsolutePath as $filePath) {
            $stmts = $astParser->parse(file_get_contents($filePath));
            $classesWithNamespace[] = ClassTool::getClassNameByStmts($stmts);
        }
        return $classesWithNamespace;
    }

    /**
     * @throws \JsonException
     */
    public function eachClassesWriteDocComment(array $classesWithNamespace, array $ignoreClassesWithNamespace = []): array
    {
        return PropertyCommentService::eachClassesWriteDocComment(
            $classesWithNamespace,
            $ignoreClassesWithNamespace
        );
    }

    public function reportHandleResult(array $scoreBoard): null
    {
        PropertyCommentService::reportScoreBoard($scoreBoard);
        return null;
    }
}
