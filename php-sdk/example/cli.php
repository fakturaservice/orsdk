<?php
/**
 * Created by PhpStorm.
 * User: twl2
 * Date: 02-08-2021
 * Time: 13:16
 */

namespace OrSdk\Tests;

require_once "MyAccountingProgram.php";

use OrSdk\Util\ORException;
use OrSdk\Tests\MyAccountingProgram;

$myAccountingProgram = new MyAccountingProgram();

try
{
    $myAccountingProgram->userInterface();
} catch (ORException $e)
{
}
