<?php

// ask the user for a configuration value.
function user_text($question, $default = null, $match = "/.+/") {
    $default_prompt = strlen($default)?" [$default]":"";
    while(true) {
        print "$question$default_prompt: ";
        $result = trim(fgets(STDIN)); 

        $value = strlen($result)?$result:$default;
        if(!$match || preg_match($match, $value)) {
            print "Selected $value\n";
            return $value;
        }

        print "Sorry, not a valid answer. Answer should match $match.\n";
    }
}

// let the user choose yes or no
function user_yesno($question) {
    while(true) {
        print "$question [Y/n]: ";
        $result = trim(fgets(STDIN));
        return (0 !== stripos($result, "n"));
    }
}

// let the user choose one of several options
function user_array_choice($question, $choices, $default = null) {
    $default = is_null($default)?array_shift(array_values($choices)):$default;
    while(true) {
        print "$question [$default]: ";
        $result = trim(fgets(STDIN));

        $value  = strlen($result)?$result:$default;
        if(in_array($value, $choices)) {
            print "Selected $value\n";
            return $value;
        }

        print "Sorry, not a valid answer. Try again.\n";
    }
}

// have the user select a module
function user_module_path($question, $default = null) {
    $default_prompt = strlen($default)?" [$default]":"";
    while(true) {
        print "$question$default_prompt: ";
        $result = trim(fgets(STDIN)); 

        $value = strlen($result)?$result:$default;
        if(module_exists($value)) {
            print "Selected $value\n";
            return module_path($value);
        }

        print "Sorry, not a valid answer. Answer should be a valid module.\n";
    }
}

// get the substitutions that *should* be done for a template file
function get_file_substitution_values($handle, $type, $author, $override) {
    $subs                 = array();
    $subs['dir']          = handle_to_path($handle, $type);

    if(0 == strcmp("controller", $type)) {
        $subs['class']    = handle_to_controller($handle);
    } else {
        $subs['class']    = handle_to_class($handle, $type);
    }

    list($plugin, $class) = explode("/", $handle);
    list($codepool, $company, $plugin_dir) = explode("/", module_path($plugin));

    $subs['filename']     = handle_to_file( $handle, $type );
    $subs['output_file']  = "{$subs['dir']}/{$subs['filename']}";
    $subs['package']      = $company;
    $subs['description']  = user_text("Enter a description for the file");
    $subs['plugin']       = $plugin_dir;
    $subs['author']       = user_text("Who is the author of the plugin", $author);
    $subs['copyright']    = date("Y")." ".$subs['author'];

    if(file_exists($subs['output_file'])) { throw new Exception("Class already exists, so I can't create it again, amirite?"); }

    // FIXME
    $subs['extends']      = "";
    if($override) { $subs['extends'] = " extends $override"; }

    return $subs;
}

// verify a set of values
function verify_choices( array &$choices, $change_mind_question = null ) {
    if($change_mind_question && user_yesno($change_mind_question)) { return; }
        
	$keys = array_keys($choices);
	while(true) {
		print_error("\n");
		$i = 0;
		foreach($choices as $key => $value) {
			$i++;
			print_error("$i. $key [{$value}]\n");
		}
		print_error("Select value to change or enter to continue: ");
		$choice 		= trim(fgets(STDIN));
	
		if(0 == strlen($choice)) {
			break;
		} else if(!array_key_exists(($choice-1), $keys)) {
			print_error("Invalid choice. Try again.\n\n");
			continue;
		}
	
		$key			= $keys[$choice-1];
		print_error("Select a new value for $key: ");
		$choices[$key] = trim(fgets(STDIN));
	}
}

// loop through actions repeatedly to let the user make selections
function user_action($actions, $vars) {
    while(true) {
        _user_actions($actions, count($vars->query_stack));
        $action = user_array_choice("Please select an action", array_keys($actions));
        $actions[$action]['callback']($vars);
    }
}

function _user_actions($actions, $changes_waiting) {
    $title  = "Choices";
    if($changes_waiting) { $title .= " ($changes_waiting waiting)"; }
    print "\n\n$title:\n";
    print "===============================\n";
    foreach($actions as $code => $params) {
        printf("  (%s)\t%-20s\n", $code, $params['name']);
    }

}

function user_action_vars(array $params) {
    $vars = new stdClass();
    $vars->query_stack = array();
    foreach($params as $key => $value) {
        $vars->$key = $value;
    }

    return $vars;
}

function write_user_action_changes($vars) {
    print "Writing changes to the database.\n";
    if(!count($vars->query_stack)) {
        return;
    }

    start_db_transaction();
    try {
        foreach($vars->query_stack as $query) {
            $result = mysql_query($query);
            if(false === $result) { throw new Exception(mysql_error()); }
        }

        commit_db_transaction();
    } catch(Exception $e) {
        rollback_db_transaction();
        print "DB error while writing changes: ".$e->getMessage();
    }

    $vars->query_stack = array();
}

function abandon_user_action_changes() {
    print "Abandoning changes. Goodbye Dave...\n";
    exit;
}
