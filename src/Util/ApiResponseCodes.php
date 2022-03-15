<?php
/**
 * Copyright (c) 2021. Fakturaservice A/S - All Rights Reserved 
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential
 * Written by Torben Wrang Laursen <twl@fakturaservice.dk>, February 2021
 */

/**
 * Created by PhpStorm.
 * User: twl
 * Date: 20-09-2019
 * Time: 13:41
 */
namespace OrSdk\Util;

/**
 * Class ApiResponseCodes
 */
abstract class ApiResponseCodes extends BasicEnum
{
    const SYS_ERROR         = 000;

    /*********************** System errors *********************************/

    const OK                = 0;
    const TOKEN_EXPIRED     = 1;
    const INVALID_ARGUMENTS = 2;
    const UNAUTHORISED      = 3;
    const SQL_FAILED        = 4;
    const DO_NOT_EXISTS     = 5;
    const DEPRECATED        = 6;
    const NOT_IMPLEMENTED   = 7;
    const SYSTEM_ERROR      = 9;

    /*********************** System warnings *********************************/
    const SYS_WARNING       = 50;

    const EMAIL_SERVER_NO_RESPONSE  = self::SYS_WARNING + 1;
    const EAN_SERVER_NO_RESPONSE    = self::SYS_WARNING + 2;

    /*********************** Apps warnings *********************************/

    const APP_ERROR             = 100;
    const LEDGER_NOT_EXTENDED   = self::APP_ERROR + 1;

    /*********************** Apps - EAN (App id: 1) *********************************/
    const EAN_ERROR = self::APP_ERROR + (AppIds::EAN * 10); // 110

    const EAN_UNINSTALLED                   = self::EAN_ERROR + 1;
    const EAN_WAS_INSTALLED                 = self::EAN_ERROR + 2;
    const EAN_XML_INVALID                   = self::EAN_ERROR + 3;
    const EAN_ENDPOINT_INVALID_LENGTH       = self::EAN_ERROR + 4;
    const EAN_ENDPOINT_INVALID_CHECK_DIGITS = self::EAN_ERROR + 5;

    /*********************** Apps - SMTP (App id: 2) *********************************/
    const REMINDER_ERROR    = self::APP_ERROR + (AppIds::Reminder * 10); // 120

    const REMINDER_UNINSTALLED              = self::REMINDER_ERROR + 1;
    const REMINDER_WAS_INSTALLED            = self::REMINDER_ERROR + 2;
    const REMINDER_ILLEGAL_FEE              = self::REMINDER_ERROR + 3;
    const REMINDER_ILLEGAL_WAITING_PERIOD   = self::REMINDER_ERROR + 4;
    const REMINDER_NOT_YET_MATURE           = self::REMINDER_ERROR + 5;
    const REMINDER_UNKNOWN_ACCOUNT_NO       = self::REMINDER_ERROR + 6;

    /*********************** Apps - InvoiceLayout (App id: 3) *********************************/
    const INVOICE_LAYOUT_ERROR    = self::APP_ERROR + (AppIds::InvoiceLayout * 10); // 130

    const INVOICE_LAYOUT_UNINSTALLED    = self::INVOICE_LAYOUT_ERROR + 1;
    const INVOICE_LAYOUT_NO_LOGO        = self::INVOICE_LAYOUT_ERROR + 2;
    const INVOICE_LAYOUT_NO_FIG         = self::INVOICE_LAYOUT_ERROR + 3;

    /*********************** Apps - Bank connect (App id: 4) *********************************/
    const BANK_CONNECT_ERROR    = self::APP_ERROR + (AppIds::BankConnect * 10); // 140

    const BANK_CONNECT_UNINSTALLED              = self::BANK_CONNECT_ERROR + 1;
    const BANK_CONNECT_NOT_CONFIGURED           = self::BANK_CONNECT_ERROR + 2;
    const BANK_CONNECT_NOT_ACTIVATED            = self::BANK_CONNECT_ERROR + 3;
    const BANK_CONNECT_KEY_IS_TO_BE_EXPIRED     = self::BANK_CONNECT_ERROR + 4;
    const BANK_CONNECT_KEY_IS_EXPIRED           = self::BANK_CONNECT_ERROR + 5;
    const BANK_CONNECT_PAUSED                   = self::BANK_CONNECT_ERROR + 6;
    const BANK_CONNECT_NO_CAM_XML               = self::BANK_CONNECT_ERROR + 7;

    /*********************** Apps - SMTP (App id: 5) *********************************/
    const SMTP_ERROR    = self::APP_ERROR + (AppIds::SMTP * 10); // 150

    const SMTP_UNINSTALLED          = self::SMTP_ERROR + 1;
    const SMTP_UNKNOWN_HOST         = self::SMTP_ERROR + 2;
    const SMTP_UNKNOWN_PORT         = self::SMTP_ERROR + 3;
    const SMTP_UNKNOWN_ENCRYPTION   = self::SMTP_ERROR + 4;
    const SMTP_CONNECT_FAILURE      = self::SMTP_ERROR + 5;
    const SMTP_EMAIL_NOT_VALID      = self::SMTP_ERROR + 6;

    /**
     * @param $code
     * @return string
     */
    static public function message($code)
    {
        switch ($code)
        {
            case self::OK:                  return "Success";
            case self::TOKEN_EXPIRED:       return "Invalid or expired token";
            case self::INVALID_ARGUMENTS:   return "Invalid argument in request";
            case self::UNAUTHORISED:        return "User is unauthorised for this API request";
            case self::SQL_FAILED:          return "SQL query failed";
            case self::DO_NOT_EXISTS:       return "Requested entry or item don not exists";
            case self::DEPRECATED:          return "Requested API is deprecated";
            case self::NOT_IMPLEMENTED:     return "Requested API is not implemented";
            case self::SYSTEM_ERROR:        return "Unknown system failure";

            case self::EMAIL_SERVER_NO_RESPONSE:    return "Email server is not responding";
            case self::EAN_SERVER_NO_RESPONSE:      return "EAN server is not responding";

            case self::APP_ERROR:           return "Unknown app failure";
            case self::LEDGER_NOT_EXTENDED: return "Ledger is not extended";

            case self::EAN_ERROR:                           return "EAN app error";
            case self::EAN_UNINSTALLED:                     return "EAN app not configured";
            case self::EAN_WAS_INSTALLED:                   return "EAN app was installed";
            case self::EAN_XML_INVALID:                     return "EAN XML Did not validate";
            case self::EAN_ENDPOINT_INVALID_LENGTH:         return "EAN endpoint address length is not 13";
            case self::EAN_ENDPOINT_INVALID_CHECK_DIGITS:   return "EAN endpoint address control check digits does not compute";

            case self::REMINDER_ERROR:                  return "Reminder app error";
            case self::REMINDER_WAS_INSTALLED:          return "Reminder app was installed";
            case self::REMINDER_UNINSTALLED:            return "Reminder app not configured";
            case self::REMINDER_ILLEGAL_FEE:            return "Reminder fee is outside legal boundary";
            case self::REMINDER_ILLEGAL_WAITING_PERIOD: return "Reminder waiting period is too short";
            case self::REMINDER_NOT_YET_MATURE:         return "Invoice not yet mature for next reminder action";
            case self::REMINDER_UNKNOWN_ACCOUNT_NO:     return "Account number does not exist";

            case self::INVOICE_LAYOUT_ERROR:        return "Invoice layout app error";
            case self::INVOICE_LAYOUT_UNINSTALLED:  return "Invoice layout app not configured";
            case self::INVOICE_LAYOUT_NO_LOGO:      return "Company logo does not exist in settings";
            case self::INVOICE_LAYOUT_NO_FIG:       return "Creditor FIG number does not exist";

            case self::BANK_CONNECT_ERROR:                  return "Bank connect app error";
            case self::BANK_CONNECT_UNINSTALLED:            return "Bank connect app not installed";
            case self::BANK_CONNECT_NOT_CONFIGURED:         return "Bank connect not configured";
            case self::BANK_CONNECT_NOT_ACTIVATED:          return "Bank connect agreement not activated";
            case self::BANK_CONNECT_KEY_IS_TO_BE_EXPIRED:   return "Bank connect key is to be expired";
            case self::BANK_CONNECT_KEY_IS_EXPIRED:         return "Bank connect key is expired";
            case self::BANK_CONNECT_PAUSED:                 return "Bank connect is paused";
            case self::BANK_CONNECT_NO_CAM_XML:             return "No more bank connect entries";

            case self::SMTP_ERROR:              return "Smtp app error";
            case self::SMTP_UNINSTALLED:        return "Smtp app not configured";
            case self::SMTP_UNKNOWN_HOST:       return "Unknown smtp host name";
            case self::SMTP_UNKNOWN_PORT:       return "Unknown smtp port";
            case self::SMTP_UNKNOWN_ENCRYPTION: return "Unknown smtp encryption";
            case self::SMTP_CONNECT_FAILURE:    return "Smtp failed to connect";
            case self::SMTP_EMAIL_NOT_VALID:    return "Smtp e-mail address not valid";

        }
        return "";
    }
}