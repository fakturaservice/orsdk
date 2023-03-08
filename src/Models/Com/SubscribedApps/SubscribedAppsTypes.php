<?php


namespace OrSdk\Models\Com\SubscribedApps;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class SubscribedAppsTypes extends BasicEnum
{
    const id               	= dataType::INT;
    const publicParams     	= dataType::LONGBLOB;
    const privateParams    	= dataType::LONGBLOB;
    const state            	= dataType::ENUM;
    const paramTestCallback	= dataType::VARCHAR;
}