<?php

namespace OrSdk\Models\Com\Contacts;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class ContactsTypes extends BasicEnum
{
    const id                   	= dataType::INT;
    const paymentMethod        	= dataType::ENUM;
    const daysAfterBasis       	= dataType::INT;
    const name                 	= dataType::VARCHAR;
    const add1                 	= dataType::VARCHAR;
    const add2                 	= dataType::VARCHAR;
    const postalCode           	= dataType::VARCHAR;
    const city                 	= dataType::VARCHAR;
    const countryIso           	= dataType::ENUM;
    const languageIso          	= dataType::ENUM;
    const mail                 	= dataType::VARCHAR;
    const contactName          	= dataType::VARCHAR;
    const endpointType         	= dataType::ENUM;
    const endpoint             	= dataType::VARCHAR;
    const mobile               	= dataType::VARCHAR;
    const currencyIso          	= dataType::ENUM;
    const vat_codesId          	= dataType::INT;
    const companyRegistrationNo	= dataType::VARCHAR;
    const active               	= dataType::ENUM;
    const customerType         	= dataType::ENUM;
    const customerIdentify     	= dataType::VARCHAR;
    const contactType          	= dataType::ENUM;
    const contactGroups        	= dataType::INT;
}