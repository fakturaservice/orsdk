<?php

namespace OrSdk\Models\Com\Accounts;

use OrSdk\Util\BasicEnum;

abstract class AccountType extends BasicEnum
{
    const income 	= 'income';
    const expense	= 'expense';
    const status 	= 'status';
    const heading	= 'heading';
    const sum    	= 'sum';

}