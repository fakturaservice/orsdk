<?php


namespace OrSdk\Models\Com\Reconciliations;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class ReconciliationsTypes extends BasicEnum
{
    const id                	= dataType::INT;
    const reconciliationsKey	= dataType::INT;
    const entriesId         	= dataType::INT;
    const amount            	= dataType::DECIMAL;
}