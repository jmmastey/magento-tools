<?php

function fixture_phone() {
    return "800-555-1212";
}

function fixture_country($entry, $context, $return_obj = false) {
    if(!isset($context->countries)) {
        $context->countries = array_values(Mage::getModel("directory/country")->getCollection()->getItems());
    }

    $country = $context->countries[rand(0, count($context->countries)-1)];

    // recurse if we didn't find a country w/ regions
    if(!count($country->getRegions())) { $country = fixture_country($entry, $context, true); }

    $context->entry_country = $country;
    return $return_obj? $country : $country->getCountryId();
}

function fixture_region($entry, $context) {
    if(!isset($context->entry_country) && !$entry['country_id']) {
        throw new Exception("Couldn't get country code for entry - cannot find resulting regions.");
    }

    $country_id = $entry['country_id'];

    if(!isset($context->entry_country)) {
        $context->entry_country = Mage::getModel("directory/country")->loadByCode($country_id);
    }


    if(!isset($context->regions)) { $context->regions = array(); }
    if(!isset($context->regions[$country_id])) {
        $regions = $context->entry_country->getRegions()->getItems();
        $regionData = array();
        foreach($regions as $key => $value) {
            // this array is backwards to make access in fixture_region_id faster
            $regionData[$value->getName()] = $key;
        }
        $context->regions[$country_id] = $regionData;
    }

    if(!count($context->regions[$country_id])) {
        return fixture_string($entry, $context);
    } else {
        $keys = array_keys($context->regions[$country_id]);
        shuffle($keys);
        return array_shift($keys);
    }
}

function fixture_region_id($entry, $context) {
    if(!isset($entry['country_id']) || !isset($entry['region'])) {
        throw new Exception("Couldn't construct region_id from entity params.");
    }

    $country = $entry['country_id'];
    $regions = $context->regions[$country];

    // some countries have no listed regions
    if(!count($regions) || !isset($regions[$entry['region']])) {
        return null;
    }

    return $regions[$entry['region']];
}


function fixture_zip() {
    return rand(10000, 99999);

}

function fixture_street($entry, $context) {
    $streets    = rand(1,2);
    $str        = "";
    for($i = 0; $i < $streets; $i++) {
        $str    .= rand(10,99999)." ".fixture_capsed_string($entry, $context)."\n";
    }

    return trim($str);
}
function fixture_address($entry, $context) {
    $sqlst = "select entity_id from customer_address_entity order by rand() limit 1";
    $res = mysql_query($sqlst);
    if(!$res) {
        throw new Exception("Couldn't get an address");
    }

    $row = mysql_fetch_array($res);
    return $row['entity_id']*1;
}

function fixture_customer_by_addresses($entry, $context) {
    # try to select by fewest addresses
    $sqlst = "select entity_id, 
        (select count(*) from customer_address_entity a where a.parent_id = e.entity_id) addresses
        from customer_entity e order by addresses asc, rand() limit 1";
    $res = mysql_query($sqlst);
    if(!$res) {
        throw new Exception("Couldn't get a customer entity");
    }

    $row = mysql_fetch_array($res);
    $entity_id = $row['entity_id']*1;
    $context->customer_id = $entity_id;
    return $entity_id;
}

function fixture_customer($entry, $context) {
    $sqlst = "select entity_id from customer_entity e order by rand() limit 1";
    $res = mysql_query($sqlst);
    if(!$res) {
        throw new Exception("Couldn't get a customer entity");
    }

    $row = mysql_fetch_array($res);
    $entity_id = $row['entity_id']*1;
    $context->customer_id = $entity_id;
    return $entity_id;
}

function fixture_customer_group($entry, $context) {
    if(!isset($context->customer_id)) {
        throw new Exception("Can't get address for non-existent customer.");
    }

    $customer = Mage::getModel("customer/customer")->load($context->customer_id);
    return $customer->getGroupId();
}

function fixture_customer_email($entry, $context) {
    if(!isset($context->customer_id)) {
        throw new Exception("Can't get address for non-existent customer.");
    }

    $customer = Mage::getModel("customer/customer")->load($context->customer_id);
    return $customer->getEmail();
}

function fixture_customer_address($entry, $context) {
    if(!isset($context->customer_id)) {
        throw new Exception("Can't get address for non-existent customer.");
    }

    $customer = Mage::getModel("customer/customer")->load($context->customer_id);
    $addresses = $customer->getAddressesCollection();
    if(!count($addresses)) {
        throw new Exception("Customer didn't have any addresses.");
    }

    $address = $addresses->getFirstItem();
    return $address->getId();
}

function fixture_email($entry, $context) {
    global $email;
    list($address, $domain) = explode("@", $email);
    $postfix = fixture_string($entry, $context);

    return "$address+$postfix@$domain";
}

function fixture_group_id() {
    return Mage::getStoreConfig(Mage_Customer_Model_Group::XML_PATH_DEFAULT_ID);
}

function fixture_password_hash() {
    return get_hash("password", 2);
}

