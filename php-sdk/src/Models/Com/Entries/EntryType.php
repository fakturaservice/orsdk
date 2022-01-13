<?php


namespace OrSdk\Models\Com\Entries;

use OrSdk\Util\BasicEnum;

abstract class EntryType extends BasicEnum
{
    const main  	= 'main';
    const contra	= 'contra';
    const vat   	= 'vat';
}