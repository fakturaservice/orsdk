<?php


namespace OrSdk\Models\Com;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class SettingsTypes extends BasicEnum
{
    const id                                  	= dataType::INT;
    const companyRegistrationNo               	= dataType::VARCHAR;
    const name                                	= dataType::VARCHAR;
    const add1                                	= dataType::VARCHAR;
    const add2                                	= dataType::VARCHAR;
    const postalZone                          	= dataType::VARCHAR;
    const city                                	= dataType::VARCHAR;
    const mail                                	= dataType::VARCHAR;
    const contactName                         	= dataType::VARCHAR;
    const www                                 	= dataType::VARCHAR;
    const mobile                              	= dataType::VARCHAR;
    const nextInvoiceNo                       	= dataType::INT;
    const prefixInvoiceNo                     	= dataType::VARCHAR;
    const postfixInvoiceNo                    	= dataType::VARCHAR;
    const zeroPadInvoiceNo                    	= dataType::INT;
    const invoiceLogo                         	= dataType::LONGBLOB;
    const debtorAccountsId                    	= dataType::INT;
    const creditorAccountsId                  	= dataType::INT;
    const defaultBankAccountsId               	= dataType::INT;
    const resultAccountsId                    	= dataType::INT;
    const analysisAccountsId                  	= dataType::INT;
    const vatSales                            	= dataType::INT;
    const vatPurchase                         	= dataType::INT;
    const vatGoodsIntPurchase                 	= dataType::INT;
    const vatServicesIntPurchaseReversePayment	= dataType::INT;
    const vatOilAndBottledGas                 	= dataType::INT;
    const vatElectricity                      	= dataType::INT;
    const vatNaturalAndCityGas                	= dataType::INT;
    const vatCoal                             	= dataType::INT;
    const vatCarbonEmission                   	= dataType::INT;
    const vatWater                            	= dataType::INT;
    const vatSettlement                       	= dataType::INT;
    const sendAsAttachment                    	= dataType::ENUM;
    const currentTemplate                     	= dataType::VARCHAR;
    const defaultJournalAccountsId1           	= dataType::INT;
    const defaultJournalAccountsId2           	= dataType::INT;
    const defaultJournalAccountsId3           	= dataType::INT;
    const nextJournalId                       	= dataType::INT;
    const defaultPaymentDays                  	= dataType::INT;
    const businessForm                        	= dataType::ENUM;
    const vatRegistration                     	= dataType::ENUM;
    const reportOrderDirection                	= dataType::ENUM;
    const reportAmountDisplay                 	= dataType::ENUM;
    const interestFeeAccountId                	= dataType::INT;
    const eDeliveryNotice                     	= dataType::ENUM;
}