<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Entity;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action\EachClassWriteDocComment;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action\IdentifyTargetType;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action\ParamPreprocess;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action\ReportScore;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action\ScanDirectoryClassFiles;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;

class ModeBConfig extends ShipshapeConfig
{
    protected array $actions = [
        ParamPreprocess::class,
        IdentifyTargetType::class,
        ScanDirectoryClassFiles::class,
        EachClassWriteDocComment::class,
        ReportScore::class
    ];
}