<?php

namespace OrSdk\Models\Com\Currencies;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class CurrenciesTypes extends BasicEnum
{
    const id        	= dataType::INT;
    const code      	= dataType::VARCHAR;
    const name      	= dataType::VARCHAR;
    const rate      	= dataType::DECIMAL;
    const updateTime	= dataType::DATETIME;
}