<?php


namespace OrSdk\Models\Com\Contacts;

use OrSdk\Util\BasicEnum;

abstract class CustomerType extends BasicEnum
{
    const company	= 'company';
    const private	= 'private';
}