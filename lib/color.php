<?php
// Allow colorization of output using standard command line flags. This
// can only safely be run after base.php is loaded.
//
// @author 		Joseph Mastey <joseph.mastey@gmail.com>
// @author		$Author$
// @version		$Id$
// @copyright	Copyright (c) JRM Ventures LLC, 2010-
//

require_once("base.php");

foreach($server->argv as $key => $param) {
    if(0 == strcmp("--color=1", $param)) {
        $colorize_output = true;
        unset($server->argv[$key]);
        $server->argc -= 1;
    } else if(0 == strcmp("--color=0", $param)) {
        $colorize_output = false;
        unset($server->argv[$key]);
        $server->argc -= 1;
    }
}

define('C_HI1', "\033[38;5;108m"); // green
define('C_HI2', "\033[38;5;116m"); // blue
define('C_HI3', "\033[38;5;174m"); // red
define('C_HI4', "\033[38;5;151m"); // lime
define('C_LO1', "\033[38;5;245m"); // gray
define('C_LO2', "\033[38;5;223m"); // l. yellow
define('C_LO3', "\033[38;5;181m"); // pink
define('C_VLO1', "\033[38;5;240m"); // dk. gray
define('C_RESET', "\033[37m");

function colorize($string, $color) {
    global $colorize_output;
    if(!$colorize_output) { return $string; }

    return $color.$string.C_RESET;
}

