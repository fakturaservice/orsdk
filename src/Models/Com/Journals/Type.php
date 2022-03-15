<?php


namespace OrSdk\Models\Com\Journals;

use OrSdk\Util\BasicEnum;

abstract class Type extends BasicEnum
{
    const standard   	= 'standard';
    const import     	= 'import';
    const bankconnect	= 'bankconnect';
    const bankfile   	= 'bankfile';
    const danlon        = 'danlon';
}