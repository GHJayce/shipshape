<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Service;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Contract\ServiceInterface;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Entity\ModeCParam;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\PropertyCommentService;
use Ghjayce\Shipshape\Tool\ClassTool;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser\Php7;
use Symfony\Component\Finder\Finder;

class ModeCService implements ServiceInterface
{
    public function paramProcess(ModeCParam $param): ModeCParam
    {
        $target = $param->getTarget();
        $classLoader = $param->getClassLoader();
        if ($target[0] === '\\') {
            $target = PropertyCommentService::findNamespaceDirPath($target, $classLoader);
            if (!$target) {
                throw new \RuntimeException("Class namespace '{$target}' not found");
            }
        }
        return $param->setTarget($target);
    }

    /**
     * @throws \JsonException
     */
    public function handle(ModeCParam $param): array
    {
        $lexer = new Emulative();
        $astParser = new Php7($lexer);
        $absolutePathFiles = $classes = [];

        $target = $param->getTarget();
        $ignoreClasses = $param->getIgnoreClassesWithNamespace();

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
        return  PropertyCommentService::eachClassesWriteDocComment($classes, $ignoreClasses);
    }

    public function reportHandleResult(array $result): null
    {
        PropertyCommentService::reportScoreBoard($result);
        return null;
    }
}
