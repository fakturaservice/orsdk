<?php
/**
 * Created by PhpStorm.
 * User: twl2
 * Date: 02-08-2021
 * Time: 13:16
 */


require_once __DIR__ . "/MyAccountingProgram.php";

use OrSdk\Util\ORException;

$myAccountingProgram = new MyAccountingProgram();

try
{
    $myAccountingProgram->userInterface();
} catch (ORException $e)
{
}
