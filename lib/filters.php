<?php

require_once("interaction.php");

// do planned substitutions on file contents
function filter_contents( $string, $subs ) {
  foreach($subs as $filter => $value) {
    $filter_standin         = strtoupper("%$filter%");
    $filter_standin_upper   = "%$filter%";
    $string = str_replace(array($filter_standin, $filter_standin_upper), $value, $string);
  }

  return $string;
}

// do file substitution, then move a blank file into place
function move_filtered_file( $source, $target, $subs ) {
  if(!file_exists($source)) { throw new Exception("Source file $source doesn't exist"); }
  if(file_exists($target)) { throw new Exception("Target file $target already exists"); }

  $contents = filter_contents(file_get_contents($source), $subs);
  create_path(dirname($target));
  $fp = fopen($target, "w+");                                                 
  if(!$fp) { throw new Exception("Couldn't open target $target for write"); }

  fwrite($fp, $contents);
  fclose($fp);                                                                                      
}

function get_filter_values( $source, &$values ) {
    $filters = array();
    //                             :-]
    if(!preg_match_all("/%([a-z0-9_:-]*)%/", $source, $filters)) {
        return $values;
    }
    
    $filters = array_unique($filters[1]);
    foreach($filters as $filter_key) {
        if(isset($values[$filter_key]) && !in_array($filter_key, $values['defaults'])) {
            continue;
        }

        $default = null;
        if(isset($values[$filter_key])) {
            $default = $values[$filter_key];
        }

        $filter_func = "filter_$filter_key";
        if(function_exists($filter_func)) {
            $filter_func($values, $default);
        } else {
            filter_value($filter_key, $values, $default);
        }
    }

    return $values;
}

// specific filters called for required values

function filter_value( $filter_key, &$values, $default = null ) {
    $values[$filter_key] = cmd_switch($filter_key);
    if(!$values[$filter_key]) {
        $values[$filter_key] = user_text("Enter a value for $filter_key", $default);
    }
}

function filter_classtype( &$values, $default = null ) {
    $values['classtype'] = cmd_switch('classtype');
    if(!$values['classtype']) {
        $values['classtype'] = user_array_choice("Select a class type", array("block", "model"), $default);
    }
}

function filter_rewrite_module_lower( &$values ) {
    $default = current_module();
    if(cmd_switch('rewrite_module')) {
        $values['rewrite_module'] = cmd_switch('rewrite_module');
        $values['rewrite_module_lower'] = strtolower($module);
    } else {
        $module = user_module_path("Select a module to override", $default);
        list($codepool, $company, $module) = explode("/", $module);

        $values['rewrite_module'] = $module;
        $values['rewrite_module_lower'] = strtolower($module);
    }
}

function filter_rewrite_handle( &$values, $default ) {
    if(cmd_switch('rewrite_handle')) {
        $values['rewrite_handle'] = cmd_switch('rewrite_handle');
    } else {
        $values['rewrite_handle'] = user_text("Select class handle to override (class only, no module)", $default);
    }
}

function filter_rewrite_class( &$values, $default ) {
    if(cmd_switch('rewrite_handle')) {
        $values['rewrite_class'] = cmd_switch('rewrite_handle');
    } else {
        $class = user_text("Select class to override to", $default);
        $values['rewrite_class'] = $class;
    }
} 
