<?php


namespace OrSdk\Models\Com\Reconciliations;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class ReconciliationsTypes extends BasicEnum
{
    const id               	= dataType::INT;
    const documentsId      	= dataType::INT;
    const amount           	= dataType::DECIMAL;
    const contraDocumentsId	= dataType::INT;
    const entryDate        	= dataType::DATE;
}