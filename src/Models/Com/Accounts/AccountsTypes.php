<?php

namespace OrSdk\Models\Com\Accounts;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

abstract class AccountsTypes extends BasicEnum
{
    const id                	= dataType::INT;
    const accountNumber     	= dataType::INT;
    const name              	= dataType::VARCHAR;
    const vat_codesId       	= dataType::INT;
    const accountType       	= dataType::ENUM;
    const sumFrom           	= dataType::INT;
    const contraAccountsId  	= dataType::INT;
    const active            	= dataType::ENUM;
    const bankName          	= dataType::VARCHAR;
    const bankAccountNo     	= dataType::VARCHAR;
    const bankRegistrationNo	= dataType::VARCHAR;
    const bankIbanNo        	= dataType::VARCHAR;
    const bankSwiftCode     	= dataType::VARCHAR;
    const bankGiroType      	= dataType::ENUM;
    const bankGiroCreditorNo	= dataType::VARCHAR;
    const bankAccount       	= dataType::ENUM;
}