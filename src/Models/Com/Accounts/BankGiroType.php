<?php

namespace OrSdk\Models\Com\Accounts;

use OrSdk\Util\BasicEnum;

abstract class BankGiroType extends BasicEnum
{
    const _01	= '+01';
    const _04	= '+04';
    const _15	= '+15';
    const _71	= '+71';
    const _73	= '+73';
    const _75	= '+75';
}