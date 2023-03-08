<?php


namespace OrSdk\Models\Com\ReconciliationsNew;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class ReconciliationsNewTypes extends BasicEnum
{
    const id                	= dataType::INT;
    const reconciliationsKey	= dataType::INT;
    const entriesId         	= dataType::INT;
    const amount            	= dataType::DECIMAL;
}