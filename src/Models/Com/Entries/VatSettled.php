<?php


namespace OrSdk\Models\Com\Entries;

use OrSdk\Util\BasicEnum;

abstract class VatSettled extends BasicEnum
{
    const yes	= 'yes';
    const no 	= 'no';
}