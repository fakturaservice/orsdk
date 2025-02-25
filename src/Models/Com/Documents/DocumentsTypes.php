<?php


namespace OrSdk\Models\Com\Documents;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class DocumentsTypes extends BasicEnum
{
    const id                       	= dataType::INT;
    const parrentDocumentId        	= dataType::INT;
    const documentType             	= dataType::ENUM;
    const name                     	= dataType::VARCHAR;
    const dataHtml                 	= dataType::LONGBLOB;
    const dataPdf                  	= dataType::LONGBLOB;
    const dataOioubl               	= dataType::LONGBLOB;
    const paymentNotificationSend  	= dataType::ENUM;
    const reminderState            	= dataType::ENUM;
    const lastReminderDate         	= dataType::DATE;
    const vatPeriodStart           	= dataType::DATE;
    const vatPeriodEnd             	= dataType::DATE;
    const documentIdentifier       	= dataType::VARCHAR;
    const documentDate             	= dataType::DATE;
    const paymentDate              	= dataType::DATE;
    const deliveryDate             	= dataType::DATE;
    const paymentMethod            	= dataType::INT;
    const dispatchMethod           	= dataType::ENUM;
    const dispatchStatus           	= dataType::ENUM;
    const endpointType              = dataType::CHAR;
    const endpoint                 	= dataType::VARCHAR;
    const contactsId               	= dataType::INT;
    const currency                 	= dataType::VARCHAR;
    const senderName               	= dataType::VARCHAR;
    const senderAdd1               	= dataType::VARCHAR;
    const senderAdd2               	= dataType::VARCHAR;
    const senderCity               	= dataType::VARCHAR;
    const senderPostalCode         	= dataType::VARCHAR;
    const senderContact            	= dataType::VARCHAR;
    const senderCompanyReg         	= dataType::VARCHAR;
    const senderPhone              	= dataType::VARCHAR;
    const senderEmail              	= dataType::VARCHAR;
    const buyersOrderId            	= dataType::VARCHAR;
    const sellersOrderId           	= dataType::VARCHAR;
    const cardTypeOrBankReg        	= dataType::VARCHAR;
    const paymentIdentiferOrAccount	= dataType::VARCHAR;
    const creditorNumberOrBankName 	= dataType::VARCHAR;
    const documentNote             	= dataType::TEXT;
    const attachmentPdf            	= dataType::LONGBLOB;
    const documentOutId            	= dataType::INT;
    const dueDateMethod            	= dataType::ENUM;
    const daysAfterBasis           	= dataType::INT;
    const documentStatus           	= dataType::ENUM;
    const journalId                	= dataType::INT;
}