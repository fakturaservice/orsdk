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
    const EAN           = 1;//Deprecated
    const Reminder      = 2;
    const InvoiceLayout = 3;
    const BankConnect   = 4;
    const SMTP          = 5;
    const Danlon        = 6;
    const Datalon       = 7;
    const Salary        = 8;
    const MobilePay     = 9;
    const International = 10;
    const Prolon        = 11;
    const ReceiveEAN    = 12;//Deprecated
    const VATReporting  = 13;
    const eDelivery     = 14;
    const MobilePayQR   = 15;
}