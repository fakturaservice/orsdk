<?php


namespace OrSdk\Models\Com\VatCodes;

use OrSdk\Util\BasicEnum;

abstract class Type extends BasicEnum
{
    const expense	= 'expense';
    const income 	= 'income';
}