<?php
// Load local configuration information
//
// @author 		Joseph Mastey <joseph.mastey@gmail.com>
// @author		$Author$
// @version		$Id$
// @copyright	Copyright (c) JRM Ventures LLC, 2010-
//

require_once("base.php");

$local_file = "$magento/app/etc/local.xml";
$app_config = simplexml_load_file($local_file);
if(!$app_config) {
	throw new Exception("Failed to load config XML file.");
}

$db_config = $app_config->global->resources->default_setup->connection;
$db_config_array	= array(
	'host'			=> (string)$db_config->host,
	'username'		=> (string)$db_config->username,
	'password'		=> (string)$db_config->password,
	'dbname'        => (string)$db_config->dbname,
);
