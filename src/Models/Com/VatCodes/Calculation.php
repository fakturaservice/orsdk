<?php

namespace OrSdk\Models\Com\VatCodes;

use OrSdk\Util\BasicEnum;

abstract class Calculation extends BasicEnum
{
    const gross = 'gross';
    const net   = 'net';
}