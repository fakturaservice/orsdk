<?php


namespace OrSdk\Models\Com\Periods;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class PeriodsTypes extends BasicEnum
{
    const id                     	= dataType::INT;
    const periodStart            	= dataType::DATE;
    const periodEnd              	= dataType::DATE;
    const state                  	= dataType::ENUM;
    const vatInterval            	= dataType::ENUM;
    const financeVoucherNo       	= dataType::INT;
    const prefixFinanceVoucherNo 	= dataType::VARCHAR;
    const postfixFinanceVoucherNo	= dataType::VARCHAR;
    const zeroPadFinanceVoucherNo	= dataType::INT;
}