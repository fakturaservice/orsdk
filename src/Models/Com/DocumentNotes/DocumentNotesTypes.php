<?php


namespace OrSdk\Models\Com\DocumentNotes;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;

class DocumentNotesTypes extends BasicEnum
{
    const id       	= dataType::INT;
    const timestamp	= dataType::TIMESTAMP;
    const docId    	= dataType::INT;
    const noteDa   	= dataType::TEXT;
    const noteBlob 	= dataType::LONGBLOB;
}