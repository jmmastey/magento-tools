<?php

function print_graph() {
    print "
===============================================
| MAGENTO TAXES                               |
===============================================
|                                             |
|                     /-> tax COUNTRY         |
|                    /                        |
| TAX_CALCULATION_RATE -> tax REGION          |
|      ^                                      |
|      |             \                        |
|      |              \-> tax rate (pct)      |
|      |                                      |
|      |              /-> product TAX_CLASS   |
|      |             /                        |
|     TAX_CALCULATION                         |
|               ^    \                        |
|              (n)    \-> customer TAX_CLASS  |
|               |                             |
|              (1)                            |
|             TAX_RULE -> priority            |
|                                             |
===============================================
";
}


function tax_classes() {
    $ret    = array();
    $sqlst  = "select class_id, class_name, class_type from tax_class";
    $res    = mysql_query($sqlst) or die(mysql_error());
    if(mysql_num_rows($res)) {
        while($row = mysql_fetch_array($res)) {
            if(!isset($ret[$row['class_type']])) { $ret[$row['class_type']] = array(); }

            $ret[$row['class_type']][$row['class_id']] = $row['class_name'];
        }
    }

    return $ret;
}

function tax_rates() {
    $ret    = array();
    $sqlst  = "select tax_country_id, tax_region_id, tax_postcode, code, rate from tax_calculation_rate";
    $res    = mysql_query($sqlst) or die(mysql_error());
    if(mysql_num_rows($res)) {
        while($row = mysql_fetch_assoc($res)) {
            $ret[$row['code']] = $row;
        }
    }

    return $ret;
}

function tax_rules() {
    $ret    = array();
    $sqlst  = "select tax_calculation_rule_id, code, position, priority, (select count(*) from tax_calculation tc where tc.tax_calculation_rule_id = tcr.tax_calculation_rule_id) num_rules from tax_calculation_rule tcr order by position asc, priority asc";
    $res    = mysql_query($sqlst) or die(mysql_error());
    if(mysql_num_rows($res)) {
        while($row = mysql_fetch_assoc($res)) {
            $ret[$row['tax_calculation_rule_id']] = $row;
        }
    }

    return $ret;
}

function directory_countries() {
    $sqlst  = "select country_id from directory_country order by country_id";
    $res    = mysql_query($sqlst) or die(mysql_error());
    $ret    = array();

    if(!mysql_num_rows($res)) {
        return $ret;
    }

    while($row = mysql_fetch_array($res)) {
        $ret[] = $row['country_id'];
    }

    return $ret;
}

function directory_regions($country_id) {
    $sqlst  = "select code, region_id from directory_country_region where country_id = '$country_id' order by code";
    $res    = mysql_query($sqlst) or die(mysql_error());
    $ret    = array();

    if(!mysql_num_rows($res)) {
        return $ret;
    }

    while($row = mysql_fetch_array($res)) {
        $ret[$row['code']] = $row['region_id'];
    }

    return $ret;
}
