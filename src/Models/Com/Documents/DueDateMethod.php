<?php


namespace OrSdk\Models\Com\Documents;

use OrSdk\Util\BasicEnum;

abstract class DueDateMethod extends BasicEnum
{
    const net  	= 'net';
    const month	= 'month';
}