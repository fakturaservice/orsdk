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
 * Date: 04-10-2019
 * Time: 06:10
 * Path: /secure/com/contacts/Contacts.php
 */

namespace OrSdk\Models\Com\Contacts;

use OrSdk\Models\BaseModels;

class Contacts extends BaseModels
{
    public $id;
    public $paymentMethod;
    public $daysAfterBasis;
    public $name;
    public $add1;
    public $add2;
    public $postalCode;
    public $city;
    public $countryIso;
    public $languageIso;
    public $mail;
    public $contactName;
    public $endpointType;
    public $endpoint;
    public $mobile;
    public $currencyIso;
    public $vat_codesId;
    public $companyRegistrationNo;
    public $active;
    public $customerType;
    public $customerIdentify;
    public $contactType;
    public $contactGroups;
}