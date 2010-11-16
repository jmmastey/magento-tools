<?php
// Load YAML files and work with them
//
// @author 		Joseph Mastey <joseph.mastey@gmail.com>
// @author		$Author$
// @version		$Id$
// @copyright	Copyright (c) JRM Ventures LLC, 2010-
//

require_once("base.php");

require_once(dirname(__FILE__)."/yaml/sfYaml.php");
require_once(dirname(__FILE__)."/yaml/sfYamlInline.php");
require_once(dirname(__FILE__)."/yaml/sfYamlParser.php");
require_once(dirname(__FILE__)."/yaml/sfYamlDumper.php");

function get_fixture_data($type) {
    global $support_dir;

    if(!file_exists("$support_dir/fixtures/$type.yml")) {
        throw new Exception("No fixture data found for $type");
    }

    return sfYaml::load("$support_dir/fixtures/$type.yml");
}
