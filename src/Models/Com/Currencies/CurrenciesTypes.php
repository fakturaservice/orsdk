<?php
/**
 * Copyright (c) 2021. Fakturaservice A/S - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential
 * Written by Torben Wrang Laursen <twl@fakturaservice.dk>, February 2021
 */

namespace OrSdk\Models\Com\Currencies;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

/**
 * Class CurrenciesTypes
 *
 * This class represents the currency types available in the system.
 * It extends the BasicEnum class.
 */
class CurrenciesTypes extends BasicEnum
{
    const id            = dataType::INT;
    const code          = dataType::VARCHAR;
    const name          = dataType::VARCHAR;
    const rate          = dataType::DECIMAL;
    const updateTime    = dataType::DATETIME;
}