<?php


namespace OrSdk\Models\Com\Periods;

use OrSdk\Util\BasicEnum;

abstract class State extends BasicEnum
{
    const open  	= 'open';
    const closed	= 'closed';
}