<?php
/**
 * Copyright (c) 2021. Fakturaservice A/S - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential
 * Written by Torben Wrang Laursen <twl@fakturaservice.dk>, February 2021
 */

/**
 * User: twl
 * Date: 07-10-2024
 * Time: 15:32
 */

namespace OrSdk\Models\Com\ContactGroups;

use OrSdk\Models\BaseModels;

class ContactGroups extends BaseModels
{
    public $id;
    public $name;
    public $groupType;
}