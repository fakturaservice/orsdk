<?php
/**
 * Created by PhpStorm.
 * User: twl
 * Date: 28-11-2018
 * Time: 09:37
 */

namespace OrSdk\Util;
/**
 * Class AppIds
 */
abstract class AppIds extends BasicEnum
{
    const EAN           = 1;
    const Reminder      = 2;
    const InvoiceLayout = 3;
    const BankConnect   = 4;
    const SMTP          = 5;
}