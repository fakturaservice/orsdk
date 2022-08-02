<?php

namespace OrSdk\Models\Com\Contacts;

use OrSdk\Util\BasicEnum;

abstract class CurrencyIso extends \BasicEnum
{
    const dkk	= 'dkk';
    const sek	= 'sek';
    const nok	= 'nok';
    const usd	= 'usd';
    const eur	= 'eur';
    const cny	= 'cny';
    const gbp	= 'gbp';
}