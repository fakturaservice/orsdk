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
 * Date: 18-02-2019
 * Time: 15:57
 */
namespace OrSdk\Util;

use Exception;

/**
 * Class ORException
 */
class ORException extends Exception
{
    const CH_SYS_LIB            = 0;            //CH 0  00000000 00000000 00000000 00000000 0x0000 0000
    const CH_ACCOUNTS           = 1;            //CH 1  00000000 00000000 00000000 00000010 0x0000 0001
    const CH_APPS               = 2;            //CH 2  00000000 00000000 00000000 00000100 0x0000 0002
    const CH_BALANCE            = 4;            //CH 3  00000000 00000000 00000000 00001000 0x0000 0004
    const CH_COMPANY            = 8;            //CH 4  00000000 00000000 00000000 00010000 0x0000 0008
    const CH_CONTACT            = 16;           //CH 5  00000000 00000000 00000000 00100000 0x0000 0010
    const CH_COUNTRY            = 32;           //CH 6  00000000 00000000 00000000 01000000 0x0000 0020
    const CH_CURRENCY           = 64;           //CH 7  00000000 00000000 00000000 10000000 0x0000 0040
    const CH_CVR                = 128;          //CH 8  00000000 00000000 00000001 00000000 0x0000 0080
    const CH_DASHBOARD          = 256;          //CH 9  00000000 00000000 00000010 00000000 0x0000 0100
    const CH_DOCUMENT_NOTES     = 512;          //CH 10 00000000 00000000 00000100 00000000 0x0000 0200
    const CH_DOCUMENTS          = 1024;         //CH 11 00000000 00000000 00001000 00000000 0x0000 0400
    const CH_DOCUMENTS_OUT      = 2048;         //CH 12 00000000 00000000 00010000 00000000 0x0000 0800
    const CH_ENTRIES            = 4096;         //CH 13 00000000 00000000 00100000 00000000 0x0000 1000
    const CH_ITEMS              = 8192;         //CH 14 00000000 00000000 01000000 00000000 0x0000 2000
    const CH_LEDGER             = 16384;        //CH 15 00000000 00000000 10000000 00000000 0x0000 4000
    const CH_PERIODS            = 32768;        //CH 16 00000000 00000001 00000000 00000000 0x0000 8000
    const CH_PERMISSION         = 65536;        //CH 17 00000000 00000010 00000000 00000000 0x0001 0000
    const CH_POSTAL_CODE        = 131072;       //CH 18 00000000 00000100 00000000 00000000 0x0002 0000
    const CH_RECONCILIATION     = 262144;       //CH 19 00000000 00001000 00000000 00000000 0x0004 0000
    const CH_RESET_PW           = 524288;       //CH 21 00000000 00010000 00000000 00000000 0x0008 0000
    const CH_SETTINGS           = 1048576;      //CH 22 00000000 00100000 00000000 00000000 0x0010 0000
    const CH_SUBSCRIBED_APPS    = 2097152;      //CH 23 00000000 01000000 00000000 00000000 0x0020 0000
    const CH_SUBSCRIPTION       = 4194304;      //CH 24 00000000 10000000 00000000 00000000 0x0040 0000
    const CH_TEST_TOOL          = 8388608;      //CH 25 00000001 00000000 00000000 00000000 0x0080 0000
    const CH_TOKEN              = 16777216;     //CH 26 00000010 00000000 00000000 00000000 0x0100 0000
    const CH_USER               = 33554432;     //CH 27 00000100 00000000 00000000 00000000 0x0200 0000
    const CH_VAT_CODES          = 67108864;     //CH 28 00001000 00000000 00000000 00000000 0x0400 0000
    const CH_VOUCHER            = 134217728;    //CH 29 00010000 00000000 00000000 00000000 0x0800 0000
    /******* PHP does not support unsigned integers ***********/

    const CH_ALL    =
        self::CH_ACCOUNTS           | self::CH_APPS             | self::CH_BALANCE          | self::CH_COMPANY      |
        self::CH_CONTACT            | self::CH_COUNTRY          | self::CH_CURRENCY         | self::CH_CVR          |
        self::CH_DASHBOARD          | self::CH_DOCUMENT_NOTES   | self::CH_DOCUMENTS        | self::CH_DOCUMENTS_OUT|
        self::CH_ENTRIES            | self::CH_ITEMS            | self::CH_LEDGER           | self::CH_PERIODS      |
        self::CH_PERMISSION         | self::CH_POSTAL_CODE      | self::CH_RECONCILIATION   | self::CH_RESET_PW     |
        self::CH_SETTINGS           | self::CH_SUBSCRIBED_APPS  | self::CH_SUBSCRIPTION     | self::CH_TEST_TOOL    |
        self::CH_TOKEN              | self::CH_USER             | self::CH_VAT_CODES        | self::CH_VOUCHER;

    const LV_LOG        = 1;
    const LV_DEBUG      = 2;
    const LV_WARNING    = 3;
    const LV_FATAL      = 4;

    private int $_channels;
    private int $_level;

    public function __construct(string $message = "", int $channel = self::CH_ALL, int $level = self::LV_LOG,
                                $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->_channels    = $channel;
        $this->_level       = $level;
    }


    public function getLevel(): int
    {
        return $this->_level;
    }


    public function getCh(): int
    {
        return $this->_channels;
    }

    /**
     * @return string
     */
    public function getLevelStr(): string
    {
        return match ($this->_level) {
            self::LV_LOG => "LOG",
            self::LV_DEBUG => "DEBUG",
            self::LV_WARNING => "WARNING",
            self::LV_FATAL => "FATAL",
            default => "NO LEVEL",
        };
    }

    /**
     * @param bool $short
     * @return string
     */
    public function getChStr(bool $short=false): string
    {
        if($short)
        {
            $chStr = "Ch[";

            $chStr .= ($this->_channels & self::CH_SYS_LIB)?        "CH1,":"";
            $chStr .= ($this->_channels & self::CH_ACCOUNTS)?       "CH2,":"";
            $chStr .= ($this->_channels & self::CH_APPS)?           "CH3,":"";
            $chStr .= ($this->_channels & self::CH_BALANCE)?        "CH4,":"";
            $chStr .= ($this->_channels & self::CH_COMPANY)?        "CH5,":"";
            $chStr .= ($this->_channels & self::CH_CONTACT)?        "CH6,":"";
            $chStr .= ($this->_channels & self::CH_COUNTRY)?        "CH7,":"";
            $chStr .= ($this->_channels & self::CH_CURRENCY)?       "CH8,":"";
            $chStr .= ($this->_channels & self::CH_CVR)?            "CH9,":"";
            $chStr .= ($this->_channels & self::CH_DASHBOARD)?      "CH10,":"";
            $chStr .= ($this->_channels & self::CH_DOCUMENT_NOTES)? "CH11,":"";
            $chStr .= ($this->_channels & self::CH_DOCUMENTS)?      "CH12,":"";
            $chStr .= ($this->_channels & self::CH_DOCUMENTS_OUT)?  "CH13,":"";
            $chStr .= ($this->_channels & self::CH_ENTRIES)?        "CH14,":"";
            $chStr .= ($this->_channels & self::CH_ITEMS)?          "CH15,":"";
            $chStr .= ($this->_channels & self::CH_LEDGER)?         "CH16,":"";
            $chStr .= ($this->_channels & self::CH_PERIODS)?        "CH17,":"";
            $chStr .= ($this->_channels & self::CH_PERMISSION)?     "CH18,":"";
            $chStr .= ($this->_channels & self::CH_POSTAL_CODE)?    "CH19,":"";
            $chStr .= ($this->_channels & self::CH_RECONCILIATION)? "CH20,":"";
            $chStr .= ($this->_channels & self::CH_RESET_PW)?       "CH21,":"";
            $chStr .= ($this->_channels & self::CH_SETTINGS)?       "CH22,":"";
            $chStr .= ($this->_channels & self::CH_SUBSCRIBED_APPS)?"CH23,":"";
            $chStr .= ($this->_channels & self::CH_SUBSCRIPTION)?   "CH24,":"";
            $chStr .= ($this->_channels & self::CH_TEST_TOOL)?      "CH25,":"";
            $chStr .= ($this->_channels & self::CH_TOKEN)?          "CH26,":"";
            $chStr .= ($this->_channels & self::CH_USER)?           "CH27,":"";
            $chStr .= ($this->_channels & self::CH_VAT_CODES)?      "CH28,":"";
            $chStr .= ($this->_channels & self::CH_VOUCHER)?        "CH29,":"";
            $chStr = substr($chStr, 0, -1) . "]";

        }
        else
        {
            $chStr = "\tChannels\n\t[\n\t\t";

            $chStr .= ($this->_channels & self::CH_SYS_LIB)?         "CH1 [X] ":"CH1 [ ] ";
            $chStr .= ($this->_channels & self::CH_ACCOUNTS)?       "CH2 [X] ":"CH2 [ ] ";
            $chStr .= ($this->_channels & self::CH_APPS)?           "CH3 [X] ":"CH3 [ ] ";
            $chStr .= ($this->_channels & self::CH_BALANCE)?        "CH4 [X] ":"CH4 [ ] ";
            $chStr .= ($this->_channels & self::CH_COMPANY)?        "CH5 [X] ":"CH5 [ ] ";
            $chStr .= ($this->_channels & self::CH_CONTACT)?        "CH6 [X] ":"CH6 [ ] ";
            $chStr .= ($this->_channels & self::CH_COUNTRY)?        "CH7 [X] ":"CH7 [ ] ";
            $chStr .= ($this->_channels & self::CH_CURRENCY)?       "CH8 [X] ":"CH8 [ ] ";
            $chStr .= ($this->_channels & self::CH_CVR)?            "CH9 [X] ":"CH9 [ ] ";
            $chStr .= ($this->_channels & self::CH_DASHBOARD)?      "CH10[X] ":"CH10[ ] "; $chStr .= "\n\t\t";
            $chStr .= ($this->_channels & self::CH_DOCUMENT_NOTES)? "CH11[X] ":"CH11[ ] ";
            $chStr .= ($this->_channels & self::CH_DOCUMENTS)?      "CH12[X] ":"CH12[ ] ";
            $chStr .= ($this->_channels & self::CH_DOCUMENTS_OUT)?  "CH13[X] ":"CH13[ ] ";
            $chStr .= ($this->_channels & self::CH_ENTRIES)?        "CH14[X] ":"CH14[ ] ";
            $chStr .= ($this->_channels & self::CH_ITEMS)?          "CH15[X] ":"CH15[ ] ";
            $chStr .= ($this->_channels & self::CH_LEDGER)?         "CH16[X] ":"CH16[ ] ";
            $chStr .= ($this->_channels & self::CH_PERIODS)?        "CH17[X] ":"CH17[ ] ";
            $chStr .= ($this->_channels & self::CH_PERMISSION)?     "CH18[X] ":"CH18[ ] ";
            $chStr .= ($this->_channels & self::CH_POSTAL_CODE)?    "CH19[X] ":"CH19[ ] ";
            $chStr .= ($this->_channels & self::CH_RECONCILIATION)? "CH20[X] ":"CH20[ ] "; $chStr .= "\n\t\t";
            $chStr .= ($this->_channels & self::CH_RESET_PW)?       "CH21[X] ":"CH21[ ] ";
            $chStr .= ($this->_channels & self::CH_SETTINGS)?       "CH22[X] ":"CH22[ ] ";
            $chStr .= ($this->_channels & self::CH_SUBSCRIBED_APPS)?"CH23[X] ":"CH23[ ] ";
            $chStr .= ($this->_channels & self::CH_SUBSCRIPTION)?   "CH24[X] ":"CH24[ ] ";
            $chStr .= ($this->_channels & self::CH_TEST_TOOL)?      "CH25[X] ":"CH25[ ] ";
            $chStr .= ($this->_channels & self::CH_TOKEN)?          "CH26[X] ":"CH26[ ] ";
            $chStr .= ($this->_channels & self::CH_USER)?           "CH27[X] ":"CH27[ ] ";
            $chStr .= ($this->_channels & self::CH_VAT_CODES)?      "CH28[X] ":"CH28[ ] ";
            $chStr .= ($this->_channels & self::CH_VOUCHER)?        "CH29[X] ":"CH29[ ] ";
            $chStr .= "\n\t]";
        }


        return $chStr;
    }

    /**
     * @param bool $short
     */
    public function log(bool $short=true): void
    {
        $trace = $this->getTrace();

        if($short)
        {
            $msg = "DEBUG " . $this->getLevelStr() . ":\t" . $this->getMessage() . " in " .
                $this->getFile() . " on line " . $this->getLine();
        }
        else
        {
            $msg =  "[{$this->getChStr($short)}] DEBUG " . $this->getLevelStr() . ":\t" . $this->getMessage() . " in " .
                $this->getFile() . " on line " . $this->getLine();
        }

        error_log($msg);

        if(count($trace) > 0)
        {
            error_log("DEBUG Stack trace:");
            foreach ($trace as $k => $v)
            {
                $msg = "DEBUG\t" . ($k+1) . ". " . $v["class"] . $v["type"] . $v["function"] . "(";
                foreach ($v["args"][0] as $arg)
                {
                    if (!isset($arg))
                        $arg = "null";
                    $msg .= $arg . ", ";
                }
                $msg = rtrim($msg, ", ") . ") ";
                $msg .= $v["file"] . ":" . $v["line"];

                error_log($msg);
            }
        }

    }
}