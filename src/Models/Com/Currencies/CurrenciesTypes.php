<?php

namespace OrSdk\Models\Com\Currencies;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class CurrenciesTypes extends BasicEnum
{
    const id                  	= dataType::INT;
    const currencyCode        	= dataType::VARCHAR;
    const currencyName        	= dataType::VARCHAR;
    const currencyExchangeRate	= dataType::DECIMAL;
}