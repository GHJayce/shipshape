<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator;

use Ghjayce\Shipshape\Console\Command\Command;
use Ghjayce\Shipshape\Entity\Base\Property;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;
use Ghjayce\Shipshape\Tool\ClassTool;
use PhpParser\Lexer\Emulative;
use PhpParser\Parser\Php7;
use ReflectionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use ReflectionClass;

class PropertyComment extends Command
{
    public const CODE_CLASS_FILE_NOT_EXISTS = 1000;
    public const CODE_NOT_EXTEND_FROM_PROPERTY = 1001;
    public const CODE_IN_IGNORE_LIST = 1002;
    public const CODE_PROPERTIES_NOT_SET = 1003;
    public const CODE_WRITE_DOC_COMMENT_FAIL = 1004;

    private array $ignoreClassName = [
        ShipshapeConfig::class,
    ];
    private string $propertyClassWithNamespace = 'Ghjayce\Shipshape\Entity\Base\Property';

    protected Php7 $astParser;

    public function __construct(?string $name = null)
    {
        parent::__construct('generator:propertyComment');
        $lexer = new Emulative();
        $this->astParser = new Php7($lexer);
    }

    public function configure(): void
    {
        parent::configure();
        $this->addArgument('target', InputArgument::REQUIRED, 'file absolute path or class name with namespace or scan the directory files.');
        $this->addArgument('ignoreClassName', InputArgument::OPTIONAL, 'ignore handle some class name with namespace.');
    }

    /**
     * @throws ReflectionException
     */
    public function handle(InputInterface $input, OutputInterface $output): void
    {
        $classLoader = ClassTool::findLoader();
        $target = $input->getArgument('target');
        $this->ignoreClassName = array_values(array_unique(array_merge($input->getArgument('ignoreClassName') ?? [], $this->ignoreClassName)));
        if (!$target) {
            throw new \RuntimeException('Target argument is empty.');
        }
        $absolutePathFiles = $classes = [];
        if ($target[0] === '\\') {
            $target = ClassTool::pathToNamespace(ucfirst(ClassTool::leftTrimNamespace($target)));
            $targetFilePath = ClassTool::getNamespaceDirPathByClassMap($target, $classLoader);
            if (!$targetFilePath) {
                [$isOk, $namespace, $dirPath] = ClassTool::getNamespaceDirPathByPsr4($target, $classLoader);
                if (!$isOk) {
                    throw new \RuntimeException("Class namespace '{$target}' not found");
                }
                $target = ClassTool::namespaceToPath(strtr($target, [
                    $namespace => $dirPath[0].'/',
                ]));
                $target = is_dir($target) ? $target : '';
            } else {
                $target = $targetFilePath;
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
            $stmts = $this->astParser->parse(file_get_contents($filePath));
            $classes[] = ClassTool::getClassNameByStmts($stmts);
        }
        if ($isClass) {
            $classes[] = $target;
        }

        $codeScoreBoard = [];
        foreach ($classes as $className) {
            try {
                $this->handleSingleClass($className);
                $code = 0;
            } catch (\Throwable $exception) {
                $code = $exception->getCode();
                continue;
            } finally {
                $codeScoreBoard[$code] ??= [
                    'score' => 0,
                    'classes' => [],
                ];
                $codeScoreBoard[$code]['score']++;
                $codeScoreBoard[$code]['classes'][] = $className;
            }
        }
        $codeTextMap = [
            self::CODE_CLASS_FILE_NOT_EXISTS => 'Class does not exist',
            self::CODE_NOT_EXTEND_FROM_PROPERTY => "Class not extends from '{$this->propertyClassWithNamespace}'",
            self::CODE_IN_IGNORE_LIST => 'Class in ignore list',
            self::CODE_PROPERTIES_NOT_SET => 'Class not set properties',
            self::CODE_WRITE_DOC_COMMENT_FAIL => 'Class write doc comment failed',
        ];
        $success = $codeScoreBoard[0];
        unset($codeScoreBoard[0]);
        foreach ($codeScoreBoard as $code => $item) {
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

    /**
     * @throws ReflectionException
     */
    private function handleSingleClass(string $classNameWithNamespace): void
    {
        $classNameWithNamespace = ClassTool::leftTrimNamespace($classNameWithNamespace);
        $classFilePath = ClassTool::getFilePath($classNameWithNamespace);
        if (!$classFilePath || !file_exists($classFilePath)) {
            throw new \RuntimeException("The class '{$classNameWithNamespace}' file not exists.", self::CODE_CLASS_FILE_NOT_EXISTS);
        }
        $className = basename(ClassTool::namespaceToPath($classNameWithNamespace));

        $reflection = new ReflectionClass($classNameWithNamespace);
        if (!$reflection->isSubclassOf(Property::class)) {
            throw new \RuntimeException(
                "Not inherit from '{$this->propertyClassWithNamespace}', Skipped '{$classNameWithNamespace}'.",
                self::CODE_NOT_EXTEND_FROM_PROPERTY
            );
        }
        foreach ($this->ignoreClassName as $ignoreClassName) {
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

//        $output->writeln("'{$classNameWithNamespace}' write doc comment Successful.");
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
                $typeOnlyName = $typeWithNamespace ? basename(strtr($typeWithNamespace, ['\\' => '/'])) : '';
            }
            $temp = [
                'name' => $property->getName(),
                'type' => $typeOnlyName,
                'typeWithNamespace' => $typeWithNamespace,
            ];
            $result[] = $temp;
        }
        return $result;
    }
}
