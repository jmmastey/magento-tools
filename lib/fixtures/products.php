<?php

function _classes($context) {
    if(!isset($context->taxclasses)) {
        $classes = array();
        $sqlst = "select class_id from tax_class where class_type = 'PRODUCT' order by rand() limit 1";
        $res = mysql_query($sqlst);
        
        while($row = mysql_fetch_array($res)) {
            $classes[] = $row['class_id'];
        }
        $context->taxclasses = $classes;
    }

    shuffle($context->taxclasses);
    return $context->taxclasses;
}

function fixture_tax_class($entry, $context) {
    $classes = _classes($context);
    return $classes[0];
}

function post_process_catalog_product($entry, $context, $entity) {
    $inv = Mage::getModel("cataloginventory/stock_item");
    $inv->setData($entry);
    $inv->setProductId($entity->getEntityId());
    $inv->save();

    $num = $entry['num_categories'];
    if($num) {
        $cats = array();
        for($i = 0; $i < $num; $i++) {
            $cats[] = fixture_visible_category();
        }
        $entity->setCategoryIds($cats)->save();
    }
}
