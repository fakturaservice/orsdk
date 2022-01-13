<?php


namespace OrSdk\Models\Com\VatCodes;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class VatCodesTypes extends BasicEnum
{
    const id              	= dataType::INT;
    const code            	= dataType::VARCHAR;
    const name            	= dataType::VARCHAR;
    const rate            	= dataType::DECIMAL;
    const accountsId      	= dataType::INT;
    const type            	= dataType::ENUM;
    const contraAccountsId	= dataType::INT;
    const vatSection      	= dataType::ENUM;
    const active          	= dataType::ENUM;
}