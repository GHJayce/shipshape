<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Entity;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Action\Handle;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Action\ParamProcess;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Action\ReportResult;
use Ghjayce\Shipshape\Entity\Config\ShipshapeConfig;

class ModeCConfig extends ShipshapeConfig
{
    protected array $actions = [
        ParamProcess::class,
        Handle::class,
        ReportResult::class,
    ];
}