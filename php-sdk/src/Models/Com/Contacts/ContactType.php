<?php


namespace OrSdk\Models\Com\Contacts;

use OrSdk\Util\BasicEnum;

abstract class ContactType extends BasicEnum
{
    const customer	= 'customer';
    const supplier	= 'supplier';
}