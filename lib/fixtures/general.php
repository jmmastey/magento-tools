<?php

// FIXTURE HELPER METHODS TO GENERATE DUMMY DATA

function fixture_string($entry, $context) {
    return get_flw($context).get_flw($context);
}

function fixture_capsed_string($entry, $context) {
    return ucfirst(fixture_string($entry, $context));
}

function fixture_sentence($entry, $context) {
    $lipsum = get_lipsum($context);
    shuffle($lipsum);

    return $lipsum[0];
}

function fixture_paragraph($entry, $context) {
    $lipsum = get_lipsum($context);
    shuffle($lipsum);

    $sentences  = rand(3, 5);
    $txt        = array();
    for($i = 0; $i < $sentences; $i++) {
        $txt[]  = $lipsum[$i];
    }

    return implode(" ", $txt);
}

function fixture_page($entry, $context) {
    $paragraphs = rand(3,6);
    $txt        = array();

    for($i = 0; $i < $paragraphs; $i++) {
        $txt[]  = fixture_paragraph($entry, $context);
    }

    return implode("\n", $txt);
}

function fixture_past_date() {
    return date("Y-m-d h:i:s", strtotime("-".rand(30,365)." days"));
}

function fixture_future_date() {
    return date("Y-m-d h:i:s", strtotime("+".rand(30,365)." days"));
}

function fixture_store_name() {
    return Mage::app()->getStore()->getName();
}

function fixture_long_store_name() {
    $store = fixture_website_name()."\n".
             fixture_store_group_name()."\n".
             fixture_store_name();

    return $store;
}

function fixture_store_id() {
    return Mage::app()->getStore()->getId();
}

function fixture_website_id() {
    return Mage::app()->getStore()->getWebsite()->getId();
}

function fixture_website_name() {
    return Mage::app()->getStore()->getWebsite()->getName();
}

function fixture_store_group_name() {
    return Mage::app()->getStore()->getGroup()->getName();
}

function fixture_entity_type($entry, $context) {
    if(!isset($context->data['entity_type'])) {
        throw new Exception("Need to specify an entity type.");
    }

    $cfg = Mage::getSingleton("eav/config");
    return $cfg->getEntityType($context->data['entity_type'])->getEntityTypeId();
}

function get_flw($context) {
    if(!isset($context->four_letter_words) || !count($context->four_letter_words)) {
        $context->four_letter_words = get_four_letter_words();
    }

    shuffle($context->four_letter_words);
    return array_shift($context->four_letter_words);
}

function get_four_letter_words() {
    global $support_dir;
    return explode("\n", file_get_contents("$support_dir/fourletterwords.txt"));
}

function get_lipsum($context) {
    global $support_dir;

    if(!isset($context->lipsum_sentences)) {
        $context->lipsum_sentences = explode("\n", trim(file_get_contents("$support_dir/lipsum.txt")));
    }

    return $context->lipsum_sentences;
}

