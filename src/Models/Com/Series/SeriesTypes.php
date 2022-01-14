<?php


namespace OrSdk\Models\Com\Series;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class SeriesTypes extends BasicEnum
{
    const id     	= dataType::INT;
    const name   	= dataType::VARCHAR;
    const nextNo 	= dataType::INT;
    const prefix 	= dataType::VARCHAR;
    const postfix	= dataType::VARCHAR;
    const zeroPad	= dataType::INT;
}