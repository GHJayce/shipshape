<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Entity;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action\EachClassWriteDocComment;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action\ReportScore;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeB\Action\ScanDirectoryClassFiles;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;

class Config extends ShipshapeConfig
{
    protected array $actions = [
        ParamPreprocess::class,
        IdentifyParamType::class,
        ScanDirectoryClassFiles::class,
        EachClassWriteDocComment::class,
        ReportScore::class
    ];
}