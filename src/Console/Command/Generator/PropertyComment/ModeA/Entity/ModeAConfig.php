<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Entity;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Action\EachClassWriteDocComment;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Action\IdentifyTargetType;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Action\FindNamespaceDirectoryPath;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Action\ReportScore;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeA\Action\ScanDirectoryClassFiles;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;

class ModeAConfig extends ShipshapeConfig
{
    protected array $actions = [
        FindNamespaceDirectoryPath::class,
        IdentifyTargetType::class,
        ScanDirectoryClassFiles::class,
        EachClassWriteDocComment::class,
        ReportScore::class
    ];
}