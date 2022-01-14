<?php


namespace OrSdk\Models\Com\Documents;

use OrSdk\Util\BasicEnum;

abstract class ReminderState extends BasicEnum
{
    const none        	= 'none';
    const warned      	= 'warned';
    const _1          	= '1';
    const _2          	= '2';
    const _3          	= '3';
    const collect     	= 'collect';
    const collectsleep	= 'collectsleep';
    const collectended	= 'collectended';
}