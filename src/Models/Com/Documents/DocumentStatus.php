<?php


namespace OrSdk\Models\Com\Documents;

use OrSdk\Util\BasicEnum;

abstract class DocumentStatus extends BasicEnum
{
    const payed   	= 'payed';
    const notPayed	= 'notPayed';
    const pending 	= 'pending';
}