<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\Original;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\PropertyCommentService;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;
use Ghjayce\Shipshape\Tool\ClassTool;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser\Php7;
use Symfony\Component\Finder\Finder;

class PropertyCommentOriginal
{
    /**
     * @throws \JsonException
     */
    public function handle(string $target, array $ignoreClasses = []): void
    {
        $lexer = new Emulative();
        $astParser = new Php7($lexer);
        $classLoader = ClassTool::findLoader();
        $absolutePathFiles = $classes = [];

        $ignoreClasses = PropertyCommentService::getIgnoreClasses([ShipshapeConfig::class, ...$ignoreClasses]);
        if ($target[0] === '\\') {
            $target = PropertyCommentService::findNamespaceDirPath($target, $classLoader);
            if (!$target) {
                throw new \RuntimeException("Class namespace '{$target}' not found");
            }
        }
        $isFile = is_file($target);
        $isDir = is_dir($target);
        $isClass = class_exists($target);
        if (!($isClass || $isDir || $isFile)) {
            throw new \RuntimeException('Not file absolute path or class name or directory.');
        }
        if ($isClass) {
            $classes[] = $target;
        }
        if ($isDir) {
            $finder = new Finder();
            $finder->files()->in([$target])->name('*.php');
            foreach ($finder as $file) {
                $absolutePathFiles[] = $file->getRealPath();
            }
        }
        if ($isFile) {
            $absolutePathFiles[] = $target;
        }
        foreach ($absolutePathFiles as $filePath) {
            $stmts = $astParser->parse(file_get_contents($filePath));
            $classes[] = ClassTool::getClassNameByStmts($stmts);
        }
        $codeScoreBoard = PropertyCommentService::eachClassesWriteDocComment($classes, $ignoreClasses);
        PropertyCommentService::reportScoreBoard($codeScoreBoard);
    }
}