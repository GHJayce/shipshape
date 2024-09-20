<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Action;

use Ghjayce\Shipshape\Action\Action;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity\Context;
use Ghjayce\Shipshape\Entity\Context\ClientContext;
use Ghjayce\Shipshape\Entity\Context\ShipshapeContext;
use Ghjayce\Shipshape\Tool\ClassTool;

class ScanDirectoryClassFiles extends Action
{

    /**
     * @param Context $context
     * @param ShipshapeContext $shipshapeContext
     * @return mixed
     */
    public function handle(ClientContext $context, ShipshapeContext $shipshapeContext): mixed
    {
        $classesWithNamespace = $context->getClassesWithNamespace();
        $lexer = new Emulative();
        $astParser = new Php7($lexer);
        foreach ($context->getFilesWithAbsolutePath() as $filePath) {
            $stmts = $astParser->parse(file_get_contents($filePath));
            $classesWithNamespace[] = ClassTool::getClassNameByStmts($stmts);
        }
        return $this->setClassesWithNamespace($classesWithNamespace);
    }
}