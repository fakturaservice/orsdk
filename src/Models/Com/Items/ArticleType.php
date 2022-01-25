<?php


namespace OrSdk\Models\Com\Items;

use OrSdk\Util\BasicEnum;

abstract class ArticleType extends BasicEnum
{
    const item   	= 'item';
    const service	= 'service';
    const dummy  	= 'dummy';
}