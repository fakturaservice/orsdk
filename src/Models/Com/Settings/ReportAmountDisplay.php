<?php


namespace OrSdk\Models\Com;

use OrSdk\Util\BasicEnum;

abstract class ReportAmountDisplay extends BasicEnum
{
    const singleColSigned      	= 'singleColSigned';
    const twoColDebitCredit    	= 'twoColDebitCredit';
    const twoColWithdrawDeposit	= 'twoColWithdrawDeposit';
}