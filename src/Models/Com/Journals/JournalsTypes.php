<?php


namespace OrSdk\Models\Com\Journals;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class JournalsTypes extends BasicEnum
{
    const id  	= dataType::INT;
    const name	= dataType::VARCHAR;
    const type	= dataType::ENUM;
}