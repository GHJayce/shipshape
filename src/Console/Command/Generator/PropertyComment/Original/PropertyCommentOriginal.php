<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\Original;

use Composer\Autoload\ClassLoader;
use Ghjayce\Shipshape\Entity\Base\Property;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;
use Ghjayce\Shipshape\Tool\ClassTool;
use Symfony\Component\Finder\Finder;

class PropertyCommentOriginal
{
    public const PROPERTY_CLASS_WITH_NAMESPACE = 'Ghjayce\Shipshape\Entity\Base\Property';
    public const CODE_CLASS_FILE_NOT_EXISTS    = 1000;
    public const CODE_NOT_EXTEND_FROM_PROPERTY = 1001;
    public const CODE_IN_IGNORE_LIST           = 1002;
    public const CODE_PROPERTIES_NOT_SET       = 1003;
    public const CODE_WRITE_DOC_COMMENT_FAIL   = 1004;
    public const CODE_TEXT_MAP                 = [
        self::CODE_CLASS_FILE_NOT_EXISTS    => 'Class does not exist',
        self::CODE_NOT_EXTEND_FROM_PROPERTY => 'Class not extends from \'' . self::PROPERTY_CLASS_WITH_NAMESPACE . '\'',
        self::CODE_IN_IGNORE_LIST           => 'Class in ignore list',
        self::CODE_PROPERTIES_NOT_SET       => 'Class not set properties',
        self::CODE_WRITE_DOC_COMMENT_FAIL   => 'Class write doc comment failed',
    ];

    public function handle(string $target, array $ignoreClasses = [])
    {
        $lexer = new Emulative();
        $astParser = new Php7($lexer);
        $classLoader = ClassTool::findLoader();
        $absolutePathFiles = $classes = [];

        $ignoreClasses = self::getIgnoreClasses([ShipshapeConfig::class, ...$ignoreClasses]);
        if ($target[0] === '\\') {
            $target = self::findNamespaceDirPath($target, $classLoader);
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
        if ($isClass) {
            $classes[] = $target;
        }
        $codeScoreBoard = self::eachClassesWriteDocComment($classes, $ignoreClasses);
        self::reportScoreBoard($codeScoreBoard);
    }

    public static function reportScoreBoard(array $scoreBoard = []): void
    {
        if (!$scoreBoard) {
            return;
        }
        $codeTextMap = self::CODE_TEXT_MAP;
        $success = $scoreBoard[0];
        unset($scoreBoard[0]);
        foreach ($scoreBoard as $code => $item) {
            echo implode("\n", [
                "[{$item['score']}] " . ($codeTextMap[$code] ?? '') . ': ',
                ...array_map(static fn($class) => "- {$class}", $item['classes']),
            ]), "\n\n";
        }
        echo implode("\n", [
            '[' . ($success['score'] ?? 0) . '] Successful write doc comment classes: ',
            ...array_map(static fn($class) => "- {$class}", $success['classes'] ?? []),
        ]), "\n";
    }

    public static function getIgnoreClasses(array $ignoreClasses): array
    {
        return array_values(array_unique($ignoreClasses));
    }

    public static function findNamespaceDirPath(string $namespacePath, ?ClassLoader $classLoader = null): string
    {
        $classLoader = $classLoader ?? ClassTool::findLoader();
        $namespacePath = ClassTool::pathToNamespace(ucfirst(ClassTool::leftTrimNamespace($namespacePath)));
        $result = ClassTool::getNamespaceDirPathByClassMap($namespacePath, $classLoader);
        if (!$result) {
            [$isOk, $namespace, $dirPath] = ClassTool::getNamespaceDirPathByPsr4($namespacePath, $classLoader);
            if (!$isOk) {
                return '';
            }
            $length = strlen($namespace);
            $result = $dirPath[0] . '/' . ClassTool::namespaceToPath(substr($namespacePath, $length));
            $result = is_dir($result) ? $result : '';
        }
        return $result;
    }

    public static function eachClassesWriteDocComment(array $classes, array $ignoreClasses): array
    {
        $codeScoreBoard = [];
        foreach ($classes as $className) {
            try {
                self::handleSingleClass($className, $ignoreClasses);
                $code = 0;
            } catch (\Throwable $exception) {
                $code = $exception->getCode();
                continue;
            } finally {
                $codeScoreBoard[$code] ??= [
                    'score'   => 0,
                    'classes' => [],
                ];
                $codeScoreBoard[$code]['score']++;
                $codeScoreBoard[$code]['classes'][] = $className;
            }
        }
        return $codeScoreBoard;
    }

    public static function handleSingleClass(string $classNameWithNamespace, array $ignoreClassesName = []): void
    {
        $classNameWithNamespace = ClassTool::leftTrimNamespace($classNameWithNamespace);
        $classFilePath = ClassTool::getFilePath($classNameWithNamespace);
        if (!$classFilePath || !file_exists($classFilePath)) {
            throw new \RuntimeException("The class '{$classNameWithNamespace}' file not exists.", self::CODE_CLASS_FILE_NOT_EXISTS);
        }
        $className = basename(ClassTool::namespaceToPath($classNameWithNamespace));

        $reflection = new ReflectionClass($classNameWithNamespace);
        if (!$reflection->isSubclassOf(Property::class)) {
            $propertyClassWithNamespace = self::PROPERTY_CLASS_WITH_NAMESPACE;
            throw new \RuntimeException(
                "Not inherit from '{$propertyClassWithNamespace}', Skipped '{$classNameWithNamespace}'.",
                self::CODE_NOT_EXTEND_FROM_PROPERTY
            );
        }
        foreach ($ignoreClassesName as $ignoreClassName) {
            if ($reflection->isSubclassOf($ignoreClassName)) {
                throw new \RuntimeException(
                    "Match ignore handle class name '{$ignoreClassName}', Skipped '{$classNameWithNamespace}'.",
                    self::CODE_IN_IGNORE_LIST
                );
            }
        }
        $propertiesData = self::getPropertiesData($reflection);
        if (!$propertiesData) {
            throw new \RuntimeException("Properties is empty, Skipped '{$classNameWithNamespace}'.", self::CODE_PROPERTIES_NOT_SET);
        }

        $newDocComment = self::generateDocComment($propertiesData, $className);

        $isOk = self::writeDocCommentToFile($className, $classFilePath, $newDocComment);
        if (!$isOk) {
            throw new \RuntimeException('Failed to write doc comment.', self::CODE_WRITE_DOC_COMMENT_FAIL);
        }
    }

    public static function writeDocCommentToFile(string $className, string $filePath, string $content): false|int
    {
        $fileContent = file_get_contents($filePath);
        $pattern = "/(\s+\/[\*|\w|\s|@|\(|\)|$|\?]+\/)?\s+class\s+{$className}/";
        /*
        preg_match($pattern, $fileContent, $matches);
        var_dump($matches);die;
        */
        $res = preg_replace($pattern, $content, $fileContent);
        return file_put_contents($filePath, $res);
    }

    public static function generateDocComment(array $propertiesData, string $className): string
    {
        $methods = [];
        foreach ($propertiesData as $property) {
            $methodName = ucfirst($property['name']);
            $temp = <<<EOF
 * @method {$property['type']} get{$methodName}()
 * @method \$this set{$methodName}({$property['type']} \${$property['name']})
EOF;
            $methods[] = $temp;
        }
        $methods = implode("\n", $methods);
        return <<<EOF


/**
$methods
 */
class {$className}
EOF;
    }

    /**
     * @throws ReflectionException
     */
    public static function getPropertiesData(string|ReflectionClass $target): array
    {
        $result = [];
        $reflection = is_string($target) ? new ReflectionClass($target) : $target;
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $typeOnlyName = $typeWithNamespace = '';
            $reflectionUnionType = $property->getType();
            if ($reflectionUnionType && method_exists($reflectionUnionType, 'getName')) {
                $typeWithNamespace = $reflectionUnionType->getName();
                $typeOnlyName = $typeWithNamespace ? basename(ClassTool::namespaceToPath($typeWithNamespace)) : '';
            }
            $temp = [
                'name'              => $property->getName(),
                'type'              => $typeOnlyName,
                'typeWithNamespace' => $typeWithNamespace,
            ];
            $result[] = $temp;
        }
        return $result;
    }
}