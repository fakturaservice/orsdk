<?php

/**
 * Created by PhpStorm.
 * User: twl
 * Date: 07-11-2017
 * Time: 07:55
 */

namespace OrSdk\Util;

/**
 * Class dataType
 */
abstract class dataType extends BasicEnum
{
    const INT           = 0;
    const VARCHAR       = 1;
    const ENUM          = 2;
    const LONGBLOB      = 3;
    const DATE          = 4;
    const TEXT          = 5;
    const DECIMAL       = 6;
    const DATETIME      = 7;
    const TIMESTAMP     = 8;
    const ANYTYPE       = 9;
    const MEDIUMTEXT    = 10;
    const TINYTEXT      = 11;
    const TINYINT       = 12;
    const CHAR          = 13;
    const BLOB          = 14;

    const NULL          = 15;
}
