<?php

function fixture_tax_class() {
    $sqlst = "select class_id from tax_class where class_type = 'PRODUCT' order by rand() limit 1";
    $res = mysql_query($sqlst);
    if(!$res) {
        throw new Exception("Couldn't find a suitable tax class");
    }

    $row = mysql_fetch_array($res);
    return $row['class_id'];
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
