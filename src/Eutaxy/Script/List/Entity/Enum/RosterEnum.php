<?php

declare(strict_types=1);

namespace Ghbjayce\MagicSocket\Eutaxy\Script\List\Entity\Enum;

class RosterEnum
{
    public const NAME_OF_HANDLE_REQUEST_PARAM = 'handleParam';
    public const NAME_OF_HANDLE_QUERY_CONDITION = 'queryCondition';
    public const NAME_OF_GET_DATA = 'getData';
    public const NAME_OF_HANDLE_DATA_FORMAT = 'dataFormat';

    public const ACTION = [
        self::NAME_OF_HANDLE_REQUEST_PARAM,
        self::NAME_OF_HANDLE_QUERY_CONDITION,
        self::NAME_OF_GET_DATA,
        self::NAME_OF_HANDLE_DATA_FORMAT,
    ];

}
