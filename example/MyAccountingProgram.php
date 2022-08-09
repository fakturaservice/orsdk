<?php
/**
 * Copyright (c) 2021. Fakturaservice A/S - All Rights Reserved 
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential
 * Written by Torben Wrang Laursen <twl@fakturaservice.dk>, February 2021
 */

/**
 * Created by PhpStorm.
 * User: twl2
 * Date: 09-03-2021
 * Time: 13:16
 */

/** Composer Autoload to define Namespaces */
if ( file_exists(dirname(__FILE__).'/../vendor/autoload.php') ) {
    require_once dirname(__FILE__) . '/../vendor/autoload.php';
}


use OrSdk\Client;
use OrSdk\Models\Com\Settings\Settings;
use OrSdk\Models\Com\Documents\{
    Documents,
    DocumentType,
    PaymentNotificationSend,
    DispatchMethod,
    DueDateMethod,
    DocumentStatus
};

use OrSdk\Util\ORException;
use OrSdk\Models\Com\Accounts\{
    Accounts,
    AccountsTypes,
    AccountType,
    Active,
    BankAccount,
    BankGiroType
};
use OrSdk\Models\Com\Contacts\{
    Contacts,
    ContactType
};
use OrSdk\Models\Com\Entries\
{
    CurrencyIso,
    Entries,
    EntryType,
    Prime,
    Status
};
use OrSdk\Models\Com\Items\{
    Items
};
use OrSdk\Models\Com\VatCodes\{
    VatCodes
};
use OrSdk\Models\Com\Series\{
    Series
};

/**
 * Class MyAccountingProgram
 * @package OrApi\Tests
 */
class MyAccountingProgram extends Client
{
    private $_settings;

    /**
     * MyAccountingProgram constructor.
     * @throws ORException
     */
    public function __construct()
    {
        echo "Input DB login:\n";
        $host       = $this->getUserInput("Host", null, "http://api.onlineregnskab.test/");
        $user       = $this->getUserInput("User", null, "testbruger");
        $password   = $this->getUserInput("Password");
        $ledgersId  = $this->getUserInput("LedgerId", null, "101");

        parent::__construct($host, $user, $password, $ledgersId);

        $this->_settings = new Settings();
        $this->modelGet($this->_settings);
    }

    /**
     * @return void
     * @throws ORException
     */
    public function userInterface()
    {
        $cmd = $this->getUserInput("Command", [
            "Type what command you want to run:",
            "\t1. Get setting",
            "\t2. Get all documents",
            "\t3. Create new invoice",
            "\t4. Download account statements"
        ]);
        switch ($cmd)
        {
            case "1":
            {
                print_r($this->settings());
                return;
            }
            case "2": print_r($this->getDocuments());return;
            case "4":
            {
                $startDate = $this->getUserInput("Start date", [
                    "Type the start date in the format yyyy-mm-dd:"
                ]);
                $endDate = $this->getUserInput("End date", [
                    "Type the last date in the format yyyy-mm-dd:"
                ]);
                $excludeEmptyAccounts   = $this->getUserInput("Exclude empty accounts", [
                    "\tYes (get only accounts with balance and/or movements)",
                    "\tNo (get all accounts. Also accounts without balance and/or movements)"
                ]);
                $path   = $this->getUserInput("Download path", [
                    "Path to download destination"
                ]);
                $this->downloadAllAccountStatements($startDate, $endDate, $excludeEmptyAccounts, $path);
                break;
            }
        }
    }

    /**
     * @throws ORException
     */
    private function downloadAllAccountStatements($startDate, $endDate, $excludeEmptyAccounts, $path)
    {
        $res = $this->get("ext/reports/balance_sheet", [
            "entryDate" => "><$startDate;$endDate",
            "excludeEmptyAccounts" => "$excludeEmptyAccounts"
        ]);
        $total = count(array_filter($res["balance_sheet"],function($element) {
            return (
                ($element['accountType'] == AccountType::income) ||
                ($element['accountType'] == AccountType::status) ||
                ($element['accountType'] == AccountType::expense)
            );
        }));

        $done   = 0;
        echo "Downloading:\n";

        foreach ($res["balance_sheet"] as $account)
        {
            switch($account["accountType"])
            {
                case AccountType::income:
                case AccountType::status:
                case AccountType::expense:
                {
                    $fileName = "{$account["accountNumber"]} {$account["name"]}";
                    $fileName = $path . preg_replace('/[^a-å\d]/i', '_', $fileName) . ".pdf";
                    $this->progressBar($done++, $total, $fileName);
                    if(!$this->downloadFile("ext/reports/account_statement", [
                        "entryDate" => "><$startDate;$endDate",
                        "accountsId" => $account["id"],
                        "format" => "pdf",
                        "fileName" => $fileName,
                        "orderBy" => "entryDate;desc",
                        "limit" => "500",
                    ], $fileName))
                    {
                        echo "Failed at '$fileName'";
                        return;
                    }
                }
            }
        }
        $this->progressBar($done, $total, "See: $path");
        echo "\nDone\n";
    }
    private function progressBar($done, $total, $name) {
        $perc = floor(($done / $total) * 100);
        $left = 100 - $perc;
        $write = sprintf("\033[0G\033[2K[%'={$perc}s>%-{$left}s] - $perc%% - $done/$total ($name)", "", "");
        fwrite(STDERR, $write);
    }

    /**
     * @param string $param
     * @param array|null $description
     * @param $default
     * @return string
     */
    private function getUserInput(string $param, array $description=null, $default=""): string
    {
        if(isset($description))
        {
            $sizeOfDescriptionDecorator = 0;
            echo "\n";
            foreach ($description as $str)
            {
                echo "$str\n";
                $sizeOfDescriptionDecorator = max(strlen($str), $sizeOfDescriptionDecorator);
            }
            echo str_repeat("#", $sizeOfDescriptionDecorator);
            echo "\n";
        }
        echo "\n\t$param ($default):\t";
        $userInput = trim(fgets(STDIN));
        return (empty($userInput)?$default:$userInput);
    }

    /**
     * @param bool $format
     * @return false|string
     */
    public function settings(bool $format=false)
    {
        return json_encode($this->_settings);
    }

    /**
     * @param bool $format
     * @return false|string
     * @throws ORException
     */
    public function getDocuments(bool $format=false)
    {
        $doc = new Documents(["documentType" => "income"]);
        $this->modelGet($doc);

        return json_encode($doc);
    }

    /**
     * @return false|string
     * @throws ORException
     */
    public function createInvoice()
    {
        $today      = new \DateTime();
        $dueDate    = clone $today;

        $acc    = new Accounts();
        $con    = new Contacts();
        $itm    = new Items();
        $vat    = new VatCodes();
        $ser    = new Series();
        $doc    = new Documents();
        $ent    = new Entries();

        $acc->id    = $this->_settings->defaultBankAccountsId;
        $this->modelGet($acc);
        
        $con->contactType       = ContactType::customer;
        $con->customerIdentify  = "820";
        $this->modelGet($con);
        $dueDate->add(new \DateInterval("P{$con->daysAfterBasis}D"));

        $itm->articleNo = "19";
        $this->modelGet($itm);

        $vat->code  = "U25";
        $this->modelGet($vat);

        $ser->name  = "income";
        $this->modelGet($ser);

        $doc->documentType                 = DocumentType::income;
        $doc->name                         = "billingDocument";
        $doc->paymentNotificationSend      = PaymentNotificationSend::no;
//        $doc->documentIdentifier           = $this->formatSeries($ser);
        $doc->documentDate                 = $today->format("Y-m-d");
        $doc->paymentDate                  = $dueDate->format("Y-m-d");
        $doc->dispatchMethod               = DispatchMethod::email;
        $doc->paymentMethod                = $this->_settings->defaultBankAccountsId;
        $doc->contactsId                   = $con->id;
        $doc->dueDateMethod                = DueDateMethod::net;
        $doc->senderName                   = $this->_settings->name;
        $doc->senderAdd1                   = $this->_settings->add1;
        $doc->senderAdd2                   = $this->_settings->add2;
        $doc->senderCity                   = $this->_settings->city;
        $doc->senderPostalCode             = $this->_settings->postalZone;
        $doc->senderContact                = $this->_settings->contactName;
        $doc->senderCompanyReg             = $this->_settings->companyRegistrationNo;
        $doc->senderPhone                  = $this->_settings->mobile;
        $doc->senderEmail                  = $this->_settings->mail;
        $doc->buyersOrderId                = null;
        $doc->sellersOrderId               = null;
        $doc->cardTypeOrBankReg            = $acc->bankRegistrationNo;
        $doc->paymentIdentiferOrAccount    = $acc->bankAccountNo;
        $doc->creditorNumberOrBankName     = $acc->bankName;
        $doc->documentNote                 = "API test";
        $doc->attachmentPdf                = null;
        $doc->documentOutId                = null;//TODO: Remove from client model!!
        $doc->daysAfterBasis               = $con->daysAfterBasis;
        $doc->journalId                    = null;
        $doc->documentStatus               = DocumentStatus::notPayed;
        $this->modelPost($doc);

        $ent->empty();
        $ent->entryDate         = $today->format("Y-m-d");
        $ent->amount            = $itm->price * -1;
        $ent->amountCurrency    = $itm->price * -1;
        $ent->currencyIso       = CurrencyIso::DKK;
        $ent->currencyRate      = "100";
//        $ent->voucherNo         = $this->formatSeries($ser);
        $ent->accountsId        = $itm->accountsId;
        $ent->vat_codesId       = $vat->id;
        $ent->text              = $itm->name;
        $ent->additionalText    = "API test";
        $ent->contactsId        = null;
        $ent->documentsId       = $doc->id;
        $ent->articleNo         = $itm->articleNo;
        $ent->quantity          = 1;
        $ent->unit              = "stk";
        $ent->entryType         = EntryType::main;
        $ent->prime             = Prime::no;
        $ent->status            = Status::draft;
        $ent->unitPrice         = $itm->price;
        $this->modelPost($ent);

        $ent->empty();
        $ent->entryDate         = $today->format("Y-m-d");
        $ent->amount            = $itm->price * $vat->rate * -1;
        $ent->amountCurrency    = $itm->price * $vat->rate * -1;
        $ent->currencyIso       = CurrencyIso::DKK;
        $ent->currencyRate      = "100";
//        $ent->voucherNo         = $this->formatSeries($ser);
        $ent->accountsId        = $this->_settings->vatSales;
        $ent->vat_codesId       = null;
        $ent->text              = "Faktura nr. {$this->formatSeries($ser)}";
        $ent->additionalText    = "API test";
        $ent->contactsId        = null;
        $ent->documentsId       = $doc->id;
        $ent->entryType         = EntryType::vat;
        $ent->prime             = Prime::no;
        $ent->status            = Status::draft;
        $this->modelPost($ent);

        $ent->empty();
        $ent->entryDate         = $today->format("Y-m-d");
        $ent->amount            = $itm->price * ($vat->rate + 1);
        $ent->amountCurrency    = $itm->price * ($vat->rate + 1);
        $ent->currencyIso       = CurrencyIso::DKK;
        $ent->currencyRate      = "100";
//        $ent->voucherNo         = $this->formatSeries($ser);
        $ent->accountsId        = $this->_settings->debtorAccountsId;
        $ent->vat_codesId       = null;
        $ent->text              = "Faktura nr. {$this->formatSeries($ser)}";
        $ent->additionalText    = "API test";
        $ent->contactsId        = $con->id;
        $ent->documentsId       = $doc->id;
        $ent->entryType         = EntryType::contra;
        $ent->prime             = Prime::no;
        $ent->status            = Status::draft;
        $this->modelPost($ent);        
        
        $ready = $this->book($doc->id);
        
        return json_encode($ready);
    }

    /**
     * @param $docId
     * @return void
     */
    public function sendInvoice($docId)
    {

    }

    /**
     * @param Series $series
     * @return string
     */
    private function formatSeries(Series $series): string
    {
        $seriesNo  = $series->prefix ?? "";
        $seriesNo .=  str_pad($series->nextNo, $series->zeroPad, 0, STR_PAD_LEFT);
        $seriesNo .= (isset($series->postfix))?$series->postfix:"";

        return $seriesNo;
    }

}