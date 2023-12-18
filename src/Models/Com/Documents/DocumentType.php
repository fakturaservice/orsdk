<?php


namespace OrSdk\Models\Com\Documents;

use OrSdk\Util\BasicEnum;

abstract class DocumentType extends BasicEnum
{
    const income             	= 'income';
    const expense            	= 'expense';
    const finance            	= 'finance';
    const bankstatement      	= 'bankstatement';
    const vatreport          	= 'vatreport';
    const subdocument        	= 'subdocument';
    const reminder           	= 'reminder';
    const applicationresponse	= 'applicationresponse';
}