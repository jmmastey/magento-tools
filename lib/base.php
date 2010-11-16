<?php
// Requisite checks + loading for magento plugin creation support. Not a
// functional module on its own - relies on additional scripts for real
// work.
//
// @author 		Joseph Mastey <joseph.mastey@gmail.com>
// @author		$Author$
// @version		$Id$
// @copyright	Copyright (c) JRM Ventures LLC, 2010-
//

if(!isset($_SERVER['argc'])) {
	throw new Exception("You don't appear to be on command line. Go away.");
}

require_once("profile.php");
require_once(dirname(__FILE__)."/functions.php");
$support_dir    = dirname(__FILE__)."/../data";

$magento        = "";
$path           = trim(`pwd`);
while(false !== strpos($path, "/") && $path != "/") {
    $target         = "$path/LICENSE.txt";
    if(file_exists($target)) {
        $magento    = $path;
        break;
    }

    $path           = dirname($path);
}

if(!$magento) {
    throw new Exception("There is no Magento to be found. My hands are tied!");
}
