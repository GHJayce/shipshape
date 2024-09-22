<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Entity;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Action\CompareLastScanResult;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Action\Execute;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch\Action\ScanDirectoryClassFiles;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;

class PcwConfig extends ShipshapeConfig
{
    protected array $actions = [
        ScanDirectoryClassFiles::class,
        CompareLastScanResult::class,
        Execute::class,
    ];
}