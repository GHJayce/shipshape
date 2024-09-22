<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment;
use Ghjayce\Shipshape\Console\Command\Generator\PropertyCommentWatch;
use Ghjayce\Shipshape\Shipshape;

class Application extends \Symfony\Component\Console\Application
{
    protected array $shipshapeCommands = [
        PropertyComment::class,
        PropertyCommentWatch::class,
    ];

    public function __construct()
    {
        parent::__construct('shipshape', Shipshape::VERSION);
        $this->addShipshapeCommands();
    }

    protected function addShipshapeCommands(): void
    {
        foreach ($this->shipshapeCommands as $command) {
            $this->add(new $command);
        }
    }
}
