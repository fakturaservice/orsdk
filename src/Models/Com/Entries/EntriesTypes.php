<?php


namespace OrSdk\Models\Com\Entries;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class EntriesTypes extends BasicEnum
{
    const id              	= dataType::INT;
    const entryDate       	= dataType::DATE;
    const amount          	= dataType::DECIMAL;
    const amountCurrency  	= dataType::DECIMAL;
    const currencyIso     	= dataType::ENUM;
    const currencyRate    	= dataType::DECIMAL;
    const voucherNo       	= dataType::VARCHAR;
    const accountsId      	= dataType::INT;
    const vat_codesId     	= dataType::INT;
    const text            	= dataType::VARCHAR;
    const additionalText  	= dataType::TEXT;
    const contactsId      	= dataType::INT;
    const documentsId     	= dataType::INT;
    const discountPercent 	= dataType::DECIMAL;
    const articleNo       	= dataType::VARCHAR;
    const quantity        	= dataType::DECIMAL;
    const unit            	= dataType::VARCHAR;
    const dueDate         	= dataType::DATE;
    const entryType       	= dataType::ENUM;
    const prime           	= dataType::ENUM;
    const status          	= dataType::ENUM;
    const vatJournalId    	= dataType::INT;
    const documentsSubId  	= dataType::INT;
    const unitPrice       	= dataType::DECIMAL;
    const documentPosition	= dataType::INT;
    const itemsId         	= dataType::INT;
    const vatSettled      	= dataType::ENUM;
}