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

$tools_path   = $_SERVER['MAGENTO_TOOLS_PATH'];
require_once(dirname(__FILE__)."/../profiles/profile");
