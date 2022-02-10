<?php
/**
 * Copyright (c) 2021. Fakturaservice A/S - All Rights Reserved 
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential
 * Written by Torben Wrang Laursen <twl@fakturaservice.dk>, February 2021
 */

/**
 * Generated by GenerateModel.php script.
 * User: twl
 * Date: 09-11-2020
 * Time: 02:11
 * Path: /secure/com/documents/Documents.php
 */

namespace OrSdk\Models\Com\Documents;

use OrSdk\Util\dataType;
use OrSdk\Util\BasicEnum;

use OrSdk\Models\BaseModels;


class Documents extends BaseModels
{
    public $id;
    public $parrentDocumentId;
    public $documentType;
    public $name;
    public $dataHtml;
    public $dataPdf;
    public $dataOioubl;
    public $paymentNotificationSend;
    public $reminderState;
    public $lastReminderDate;
    public $vatPeriodStart;
    public $vatPeriodEnd;
    public $documentIdentifier;
    public $documentDate;
    public $paymentDate;
    public $deliveryDate;
    public $paymentMethod;
    public $dispatchMethod;
    public $dispatchStatus;
    public $endpoint;
    public $contactsId;
    public $currency;
    public $senderName;
    public $senderAdd1;
    public $senderAdd2;
    public $senderCity;
    public $senderPostalCode;
    public $senderContact;
    public $senderCompanyReg;
    public $senderPhone;
    public $senderEmail;
    public $buyersOrderId;
    public $sellersOrderId;
    public $cardTypeOrBankReg;
    public $paymentIdentiferOrAccount;
    public $creditorNumberOrBankName;
    public $documentNote;
    public $attachmentPdf;
    public $documentOutId;
    public $dueDateMethod;
    public $daysAfterBasis;
    public $documentStatus;
    public $journalId;
}