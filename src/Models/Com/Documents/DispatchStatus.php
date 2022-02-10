<?php


namespace OrSdk\Models\Com\Documents;

use OrSdk\Util\BasicEnum;

abstract class DispatchStatus extends BasicEnum
{
    const notToBeSent	= 'notToBeSent';
    const pending    	= 'pending';
    const failed     	= 'failed';
    const sent       	= 'sent';
}