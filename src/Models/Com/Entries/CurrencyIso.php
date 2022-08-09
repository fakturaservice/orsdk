<?php


namespace OrSdk\Models\Com\Entries;

use OrSdk\Util\BasicEnum;

abstract class CurrencyIso extends \BasicEnum
{
    const DKK	= 'DKK';
    const SEK	= 'SEK';
    const NOK	= 'NOK';
    const USD	= 'USD';
    const EUR	= 'EUR';
    const CNY	= 'CNY';
    const GBP	= 'GBP';
}