<?php


namespace OrSdk\Models\Com\SubscribedApps;

use OrSdk\Util\BasicEnum;
class State extends BasicEnum
{
    const uninstalled	= 'uninstalled';
    const initiated  	= 'initiated';
    const installed  	= 'installed';
}