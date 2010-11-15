<?php
// load the user's profile
//
// @author 		Joseph Mastey <joseph.mastey@gmail.com>
// @author		$Author$
// @version		$Id$
// @copyright	Copyright (c) JRM Ventures LLC, 2010-
//

if(!isset($_SERVER['argc'])) {
	throw new Exception("You don't appear to be on command line. Go away.");
}

if(!isset($_SERVER['MAGENTO_TOOLS_PATH'])) {
  throw new Exception("Please set a value for \$MAGENTO_TOOLS_PATH so that config can be loaded.");
}

$tools_path   = $_SERVER['MAGENTO_TOOLS_PATH'];
require_once("$tools_path/profiles/profile");
