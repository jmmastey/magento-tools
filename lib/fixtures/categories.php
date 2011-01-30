<?php

function _category_id($level) {
    $sqlst  = "select entity_id from catalog_category_entity where level in ($level) order by rand() limit 1";
    $res    = mysql_query($sqlst);
    if(!$res) {
        throw new Exception("Couldn't find a category of levels: $level.");
    }

    $row    = mysql_fetch_array($res);
    return $row['entity_id'];
}

function fixture_category_id() {
    return _category_id("1,2");
}

function fixture_root_category() {
    return _category_id("1");
}

function fixture_toplevel_category() {
    return _category_id("2");
}

function fixture_visible_category() {
    return _category_id("2,3");
}

function fixture_event_category() {
    $sqlst  = "select entity_id from catalog_category_entity where level in (2,3) and entity_id not in (select category_id from enterprise_catalogevent_event) order by rand() limit 1";
    $res    = mysql_query($sqlst);
    if(!$res) {
        throw new Exception("Couldn't find a category without an event.");
    }

    $row    = mysql_fetch_array($res);
    return $row['entity_id'];

}

function fixture_child_level($entry) {
    $parent = $entry['parent_id'];
    $parent_obj = Mage::getModel("catalog/category")->load($parent);
    return $parent_obj->getLevel() + 1;
}

function fixture_next_child_position() {
    $sqlst  = "select max(position)+1 as position from catalog_category_entity";
    $res    = mysql_query($sqlst);
    if(!$res) {
        throw new Exception("Couldn't find a product");
    }

    return $res['position'];
}

function fixture_url_key($entry) {
    return strtolower(str_replace(" ", "-", $entry['name']));
}

function fixture_url_path($entry) {
    return strtolower(str_replace(" ", "-", $entry['name'])).".html";
}

function post_process_category($entry, $context, $entity) {
    $parent_id = $entry['parent_id'];
    $parent = Mage::getModel("catalog/category")->load($entry['parent_id']);
    $path = $parent->getPath()."/".$entity->getId();

    $sqlst  = "update catalog_category_entity set level = {$entry['level']}, parent_id = {$entry['parent_id']}, path = '$path' where entity_id = ".$entity->getId();
    mysql_query($sqlst);
}

function process_catalogevent($entry, $context, $entity) {
    if(strtotime($entry['date_start']) < strtotime($entry['date_end'])) {
        $end = $entry['date_end'];
        $entry['date_end'] = $entry['date_start'];
        $entry['date_start'] = $end;
    }
}

function post_fixture_category() {
    $sqlst = "select parent_id, count(*) ct from catalog_category_entity group by parent_id";
    $res = mysql_query($sqlst);
    while($row = mysql_fetch_array($res)) {
        $sqlst = "update catalog_category_entity set children_count = {$row['ct']} where entity_id = {$row['parent_id']}";
        mysql_query($sqlst);
    }
}
