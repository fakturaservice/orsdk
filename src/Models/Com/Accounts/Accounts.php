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
 * Date: 22-12-2020
 * Time: 10:12
 * Path: /secure/com/accounts/Accounts.php
 */

namespace OrSdk\Models\Com\Accounts;

use OrSdk\Models\BaseModels;

class Accounts extends BaseModels
{
    public $id;
    public $accountNumber;
    public $name;
    public $vat_codesId;
    public $accountType;
    public $sumFrom;
    public $contraAccountsId;
    public $active;
    public $bankName;
    public $bankAccountNo;
    public $bankRegistrationNo;
    public $bankIbanNo;
    public $bankSwiftCode;
    public $bankGiroType;
    public $bankGiroCreditorNo;
    public $bankAccount;
    public $comment;
}