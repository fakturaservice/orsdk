<?php


namespace OrSdk\Models\Com\Items;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class ItemsTypes extends BasicEnum
{
    const id         	= dataType::INT;
    const articleType	= dataType::ENUM;
    const articleNo  	= dataType::VARCHAR;
    const name       	= dataType::VARCHAR;
    const unit       	= dataType::VARCHAR;
    const price      	= dataType::DECIMAL;
    const vat        	= dataType::ENUM;
    const accountsId 	= dataType::INT;
    const active     	= dataType::ENUM;
}