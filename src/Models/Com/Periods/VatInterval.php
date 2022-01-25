<?php


namespace OrSdk\Models\Com\Periods;

use OrSdk\Util\BasicEnum;

abstract class VatInterval extends BasicEnum
{
    const _6M	= '6M';
    const _3M	= '3M';
    const _1M	= '1M';
    const _12M	= '12M';
}