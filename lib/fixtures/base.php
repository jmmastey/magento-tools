<?php

function load_fixture($fixture_name) {
    $data = get_fixture_data("$fixture_name");
    foreach($data as $sec => $sec_data) {
        print "  starting import for $sec\n";

        $context            = new stdClass();
        $context->data      = $sec_data;
        $context->title     = $sec;
        $context->bases     = load_bases($context);

        foreach($sec_data['entries'] as $ent => $ent_data) {
            print "    importing $ent\n";
            $context->entry         = $ent_data;
            $context->entry_title   = $ent;
            $context->iterator      = get_iterator($context);

            // perform import
            $iterator = $context->iterator;
            $iterator($context);
        }

        // run post-actions (index updates et al)
        if(isset($context->data['entity_type'])) {
            $postFunc = "post_fixture_".$context->data['entity_type'];
            if(function_exists($postFunc)) {
                print "  running post-actions for $sec";
                $postFunc();
            }
        }
    }

}

function get_entity($context) {
    if(!isset($context->data) || !isset($context->data['entity'])) {
        throw new Exception("Can't get entity data for {$context->title}");
    }

    $class_handle = $context->data['entity'];
    return Mage::getModel($class_handle);
}

// right now it looks like I should be able to ignore the bases themselves.
// this method is for informational output and as a stub for initialization
// of features later.
function load_bases($context) {
    if(!isset($context->data) || !isset($context->data['bases'])) {
        return array();
    }

    foreach($context->data['bases'] as $base => $base_data) {
        print "    found base '$base'\n";
    }

    return array();
}

// get a function callback for the correct iterator
function get_iterator($context) {
    if(!isset($context->entry) || !isset($context->entry['iterate'])) {
        $context->entry['iterate'] = 1;
    }

    $iterate = $context->entry['iterate'];

    if(is_numeric($iterate)) {
        return "iterate_numeric";
    } else if(function_exists("iterate_$iterate")) {
        return "iterate_$iterate";
    } else {
        throw new Exception("Unknown iterator: $iterate");
    }
}

function get_fixture_data($type) {
    global $support_dir;

    if(!file_exists("$support_dir/fixtures/$type.yml")) {
        throw new Exception("No fixture data found for $type");
    }

    return sfYaml::load("$support_dir/fixtures/$type.yml");
}

function iterate_numeric($context) {
    $ct = $context->entry['iterate'];
    for($i = 0; $i < $ct; $i++) {
        save_record($context->entry, $context);
    }
}

function iterate_numeric_prompt($context) {
    $num = (int)user_text("    How many entries for $context->entry_title", null, "/^\d+$/");
    $context->entry['iterate'] = $num;
    print "\n";
    iterate_numeric($context);
}

function save_record(array $entry, $context) {
    $entry  = $entry + array(); //take an array copy
    $entity = get_entity($context);
    $context->entity = $entity;

    foreach($entry as $key => $value) {
        if($value && function_exists($value)) {
            $entry[$key] = $value($entry, $context);
        } else if(0 === strpos($value, "fixture_")) {
            throw new Exception("Please define a mock data method $value for {$context->title}");
        }
    }

    if(isset($context->data['entity_type'])) {
        $process_func = "process_".$context->data['entity_type'];
        if(function_exists($process_func)) {
            $process_func($entry, $context, $entity);
        }
    }

    $entity->setData($entry);
    if(!$entity->save()) {
        throw new Exception("Save didn't happen");
    }

    if(isset($context->data['entity_type'])) {
        $process_func = "post_process_".$context->data['entity_type'];
        if(function_exists($process_func)) {
            $process_func($entry, $context, $entity);
        }
    }
}
