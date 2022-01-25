<?php

namespace OrSdk\Models\Com\Contacts;

use OrSdk\Util\BasicEnum;

abstract class PaymentMethod extends BasicEnum
{
    const net    	= 'net';
    const month  	= 'month';
    const quarter	= 'quarter';
    const year   	= 'year';
}