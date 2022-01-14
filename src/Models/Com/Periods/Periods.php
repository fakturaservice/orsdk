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
 * Date: 11-02-2020
 * Time: 01:02
 * Path: /secure/com/periods/Periods.php
 */

namespace OrSdk\Models\Com\Periods;

use OrSdk\Models\BaseModels;


class Periods extends BaseModels
{
    public $id;
    public $periodStart;
    public $periodEnd;
    public $state;
    public $vatInterval;
    public $financeVoucherNo;
    public $prefixFinanceVoucherNo;
    public $postfixFinanceVoucherNo;
    public $zeroPadFinanceVoucherNo;
}