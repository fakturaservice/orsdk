<?php

namespace OrSdk\Models\Com\Currencies;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class CurrenciesTypes extends BasicEnum
{
    const currencyCode        	= dataType::VARCHAR;
    const currencyExchangeRate	= dataType::DECIMAL;
    const currencyName        	= dataType::VARCHAR;
    const id                  	= dataType::INT;
}