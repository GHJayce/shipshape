<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment;

use Composer\Autoload\ClassLoader;
use Ghjayce\Shipshape\Entity\Base\Property;
use Ghjayce\Shipshape\Tool\ClassTool;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionUnionType;

class PropertyCommentService
{

    public const PROPERTY_CLASS_WITH_NAMESPACE = 'Ghjayce\Shipshape\Entity\Base\Property';
    public const OPERATION_SUCCESSFUL = 0;
    public const UNKNOWN_EXCEPTION = 1000;
    public const CODE_CLASS_FILE_NOT_EXISTS = 1001;
    public const CODE_NOT_EXTEND_FROM_PROPERTY = 1002;
    public const CODE_IN_IGNORE_LIST = 1003;
    public const CODE_PROPERTIES_NOT_SET = 1004;
    public const CODE_WRITE_DOC_COMMENT_FAIL = 1005;
    public const CODE_TEXT_MAP = [
        self::OPERATION_SUCCESSFUL => 'Successful write doc comment classes',
        self::UNKNOWN_EXCEPTION => 'Encountered an unknown exception',
        self::CODE_CLASS_FILE_NOT_EXISTS => 'Class does not exist',
        self::CODE_NOT_EXTEND_FROM_PROPERTY => 'Class not extends from \'' . self::PROPERTY_CLASS_WITH_NAMESPACE . '\'',
        self::CODE_IN_IGNORE_LIST => 'Class in ignore list',
        self::CODE_PROPERTIES_NOT_SET => 'Class not set properties',
        self::CODE_WRITE_DOC_COMMENT_FAIL => 'Class write doc comment failed',
    ];

    public static function reportScoreBoard(array $scoreBoard = []): void
    {
        if (!$scoreBoard) {
            return;
        }
        $codeTextMap = self::CODE_TEXT_MAP;
        krsort($scoreBoard);
        echo "\n", array_reduce(['Executive report', ''], static fn (mixed $carry, string $item) => $carry.str_repeat('=', 10).$item), "\n\n";
        foreach ($scoreBoard as $code => $item) {
            echo implode("\n", [
                "[{$item['score']}] " . ($codeTextMap[$code] ?? '') . ': ',
                ...array_map(static fn($class) => "- {$class}", $item['classes']),
                $item['message'] ? "\nMessage:\n{$item['message']}" : ""
            ]), "\n\n";
        }
    }

    public static function getIgnoreClasses(array $ignoreClasses): array
    {
        return array_values(array_unique(array_filter($ignoreClasses)));
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
        $classes = array_values(array_unique(array_filter($classes)));
        foreach ($classes as $className) {
            $message = [];
            try {
                self::handleSingleClass($className, $ignoreClasses);
                $code = 0;
            } catch (\Throwable $exception) {
                $code = $exception->getCode() ?: self::UNKNOWN_EXCEPTION;
                $code === self::UNKNOWN_EXCEPTION && $message = [
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTrace(),
                ];
                continue;
            } finally {
                $codeScoreBoard[$code] ??= [
                    'score'   => 0,
                    'classes' => [],
                    'message' => [],
                ];
                $codeScoreBoard[$code]['score']++;
                $codeScoreBoard[$code]['classes'][] = $className;
                $codeScoreBoard[$code]['message'] = $message ? json_encode($message, JSON_THROW_ON_ERROR) : '';
            }
        }
        return $codeScoreBoard;
    }

    /**
     * @throws ReflectionException
     */
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

        $newDocComment = self::generateDocComment($propertiesData, $reflection);

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

    public static function generateDocComment(array $propertiesData, ReflectionClass $reflectionClass): string
    {
        $className = $reflectionClass->getShortName();
        $methods = [];
        foreach ($propertiesData as $property) {
            $methodName = ucfirst($property['name']);
            $types = implode('|', $property['types']);
            $getMethodName = "get{$methodName}";
            $temp = [];
            if (!$reflectionClass->hasMethod($getMethodName)) {
                $temp[] = " * @method {$types} {$getMethodName}()";
            }
            $setMethodName = "set{$methodName}";
            if (!$reflectionClass->hasMethod($setMethodName)) {
                $temp[] = " * @method \$this {$setMethodName}({$types} \${$property['name']})";
            }
            $methods[] = implode("\n", $temp);
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
        // TODO 在watch命令下，当修改属性或者新增属性以后再去读取，结果竟然不变，怀疑有缓存，待解决
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $typeOnlyName = $typeWithNamespace = [];
            $reflectionType = $property->getType();
            $reflectionNamedTypes = [];
            if ($reflectionType instanceof ReflectionNamedType) {
                $reflectionNamedTypes[] = $reflectionType;
            }
            if ($reflectionType instanceof ReflectionUnionType) {
                array_push($reflectionNamedTypes, ...$reflectionType->getTypes());
            }
            foreach ($reflectionNamedTypes as $reflectionNamedType) {
                $nameWithNamespace = $reflectionNamedType->getName();
                $typeWithNamespace[] = $nameWithNamespace;
                $typeOnlyName[] = $typeWithNamespace ? basename(ClassTool::namespaceToPath($nameWithNamespace)) : '';
            }
            $temp = [
                'name'              => $property->getName(),
                'types'              => $typeOnlyName,
                'typesWithNamespace' => $typeWithNamespace,
            ];
            $result[] = $temp;
        }
        var_dump(array_column($result, 'name'));
        return $result;
    }
}
