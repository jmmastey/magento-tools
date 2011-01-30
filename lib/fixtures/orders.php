<?php

function fixture_product() {
    $sqlst  = "select entity_id from catalog_product_entity order by rand() limit 1";
    $res    = mysql_query($sqlst);
    if(!$res) {
        throw new Exception("Couldn't find a product");
    }

    $row    = mysql_fetch_array($res);
    return $row['entity_id'];
}

function process_order($entry, $context, $entity) {
    $quote      = Mage::getModel("sales/quote");

    $payment    = Mage::getModel("sales/order_payment");
    $payment->setMethod('free');
    $entity->addPayment($payment);

    $qshipping = Mage::getModel("sales/quote_address");
    $qshipping->importCustomerAddress(Mage::getModel("customer/address")->load($entry['shipping_address_id']));
    $oshipping = Mage::getModel("sales/order_address");
    Mage::helper("core")->copyFieldset(
        "sales_convert_quote_address",
        "to_order_address",
        $qshipping,
        $oshipping
    );
    $entity->setShippingAddress($oshipping);

    $qbilling = Mage::getModel("sales/quote_address");
    $qbilling->importCustomerAddress(Mage::getModel("customer/address")->load($entry['billing_address_id']));
    $obilling = Mage::getModel("sales/order_address");
    Mage::helper("core")->copyFieldset(
        "sales_convert_quote_address",
        "to_order_address",
        $qbilling,
        $obilling
    );
    $entity->setBillingAddress($obilling);

    $num_items  = $entry['total_item_count'];
    $qty        = $entry['total_qty_ordered'];
    if($qty < $num_items) {
        $qty    = $num_items;
        $entity->setTotalQtyOrdered($qty);
    }
    
    $items      = Mage::getModel("catalog/product")
        ->getCollection()
        ->setPageSize($num_items);
    $items->getSelect()->order('rand()');
    $items      = array_values($items->getItems());

    if(count($items) < $num_items) {
        throw new Exception("There aren't enough items to generate orders.");
    }

    for($i = 0; $i < $num_items; $i++) {
        $thisqty    = ($i == ($num_items-1))?$qty:1;
        $qty        -= $thisqty;

        $product    = $items[$i];
        $sitem      = Mage::getModel("cataloginventory/stock_item")
            ->loadByProduct($product);
        $product->setStockItem($sitem);
        $qitem      = Mage::getModel("sales/quote_item")
            ->setQuote($quote)
            ->setProduct($product)
            ->setOriginalPrice($product->getPrice())
            ->setCalculationPrice($product->getPrice())
            ->setQty($thisqty);

        $oitem      = Mage::getModel("sales/order_item");
        Mage::helper("core")->copyFieldset(
            "sales_convert_quote_item",
            "to_order_item",
            $qitem,
            $oitem
        );
        $oitem->setProductId($product->getId());
        $entity->addItem($oitem);
    }
}

