<?php

declare(strict_types=1);

namespace Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Contract;

use Ghjayce\Shipshape\Console\Command\Generator\PropertyComment\ModeC\Entity\ModeCParam;

interface ServiceInterface
{
    public function paramProcess(ModeCParam $param): ModeCParam;
    public function handle(ModeCParam $param): array;
    public function reportHandleResult(array $result): mixed;
}