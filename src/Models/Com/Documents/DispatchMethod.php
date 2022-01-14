<?php


namespace OrSdk\Models\Com\Documents;

use OrSdk\Util\BasicEnum;

abstract class DispatchMethod extends BasicEnum
{
    const email    	= 'email';
    const GLN      	= 'GLN';
    const snailMail	= 'snailMail';
}