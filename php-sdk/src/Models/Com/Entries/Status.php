<?php


namespace OrSdk\Models\Com\Entries;

use OrSdk\Util\BasicEnum;

abstract class Status extends BasicEnum
{
    const draft 	= 'draft';
    const posted	= 'posted';
}