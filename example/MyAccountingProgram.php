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

use OrSdk\Models\Com\Accounts\{
    Accounts,
    AccountType
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
    private Settings $_settings;

    /**
     * MyAccountingProgram constructor.
     */
    public function __construct()
    {
        echo "Input DB login:\n";
        $host       = $this->getUserInput("Host", null, "http://api.onlineregnskab.test/");
        $user       = $this->getUserInput("User", null, "testbruger");
        $password   = $this->getUserInput("Password", null, "", true);
        $ledgersId  = $this->getUserInput("LedgerId", null, "101");

        parent::__construct($host, $user, $password, $ledgersId);
        if(!$this->isTokenRenewed())
        {
            echo "Token was expired.\n";
            exit(0);
        }

        $this->_settings = new Settings();
        $this->modelGet($this->_settings);
    }


    /**
     * @return void
     */
    public function userInterface(): void
    {

        $cmd = $this->getUserInput("Command", [
            "\n\nType what command you want to run:",
            "\t1. Get setting",
            "\t2. Get all documents",
            "\t3. Create new invoice",
            "\t4. Download account statements",
            "\t5. Quit"
        ]);
        switch ($cmd)
        {
            case "1":print_r($this->settings());break;
            case "2": print_r($this->getDocuments());break;
            case "3": echo "\n\t* Not implemented *\n";break;
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
            case "5":
            {
                echo "Bye\n";
                return;
            }
        }
        $this->userInterface();

    }


    /**
     * @param $startDate
     * @param $endDate
     * @param $excludeEmptyAccounts
     * @param $path
     * @return void
     */
    private function downloadAllAccountStatements($startDate, $endDate, $excludeEmptyAccounts, $path): void
    {
        $excludeEmptyAccounts = preg_match('/^y(es)?$/i', trim($excludeEmptyAccounts)) ? 'yes' : 'no';
        $res = $this->get("ext/reports/balance_sheet", [
            "entryDate" => "><$startDate;$endDate",
            "excludeEmptyAccounts" => "$excludeEmptyAccounts"
        ]);
        if($res === null)
        {
            echo "Failed to get all balance sheet\n";
            return;
        }
        else if($res["error_code"] != 0) {
            print_r($res);
            return;
        }
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

    /**
     * @param $done
     * @param $total
     * @param $name
     * @return void
     */
    private function progressBar($done, $total, $name): void
    {
        if($total == 0)
        {
            $write      = sprintf("\033[0G\033[2K[%'=0s>%-0s] - 0%% - $done/$total (No downloads)", "", "");
            fwrite(STDERR, $write);
            return;
        }
        $percent    = floor(($done / $total) * 100);
        $left       = 100 - $percent;
        $write      = sprintf("\033[0G\033[2K[%'={$percent}s>%-{$left}s] - $percent%% - $done/$total ($name)", "", "");
        fwrite(STDERR, $write);
    }

    /**
     * @param string $param
     * @param array|null $description
     * @param string $default
     * @param bool $hidden
     * @return string
     */
    private function getUserInput(string $param, array $description = null, string $default = "", bool $hidden = false): string
    {
        if ($description !== null) {
            $sizeOfDescriptionDecorator = 0;
            echo "\n";
            foreach ($description as $str) {
                echo "$str\n";
                $sizeOfDescriptionDecorator = max(strlen($str), $sizeOfDescriptionDecorator);
            }
            echo str_repeat("#", $sizeOfDescriptionDecorator) . "\n";
        }

        // Always print the prompt from PHP
        echo "\n\t$param" . ($default !== "" ? " ($default)" : "") . ":\t";

        if ($hidden) {
            if (PHP_OS_FAMILY === 'Windows') {
                // Windows: read hidden input via PowerShell, no prompt there
                $psCmd = '$pwd = Read-Host -AsSecureString; ' .
                    '$BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($pwd); ' .
                    '[System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)';
                $cmd = 'powershell -NoProfile -Command ' . escapeshellarg($psCmd);
                $userInput = rtrim(shell_exec($cmd));
                echo "\n";
            } else {
                // Unix / Cygwin PHP: use stty
                system('stty -echo');
                $userInput = trim(fgets(STDIN));
                system('stty echo');
                echo "\n";
            }
        } else {
            $userInput = trim(fgets(STDIN));
        }

        return $userInput === '' ? $default : $userInput;
    }

    /**
     * @return false|string
     */
    public function settings(): false|string
    {
        return json_encode($this->_settings,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @return false|string
     */
    public function getDocuments(): false|string
    {
        $doc = new Documents(["documentType" => "income"]);
        $this->modelGet($doc);

        return json_encode($doc,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @return false|string
     */
    public function createInvoice(): false|string
    {
        $today      = new DateTime();
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
        $dueDate->add(new DateInterval("P{$con->daysAfterBasis}D"));

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