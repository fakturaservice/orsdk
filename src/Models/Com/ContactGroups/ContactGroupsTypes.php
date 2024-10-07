<?php
namespace OrSdk\Models\Com\ContactGroups;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class ContactGroupsTypes extends BasicEnum
{
    const id       	= dataType::INT;
    const name     	= dataType::VARCHAR;
    const groupType	= dataType::ENUM;
}