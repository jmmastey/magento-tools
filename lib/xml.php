<?php

// get a simplexml element from a more vague input
function get_xml($xml) {
    if($xml instanceof SimpleXMLElement) {
        return $xml;
    } else if(file_exists($xml)) {
        return simplexml_load_file($xml);
    } else {
        return simplexml_load_string($xml);
    }
}

// get the XML output of a simpleXML element, formatted
// with pretty linebreaks and everything. likely to be a little
// overzealous about those linebreaks.
function get_xml_output($simplexml) {
    $dom        = new DomDocument('1.0');
    $dom->preserveWhiteSpace    = false;
    $dom->formatOutput          = true;
    $loaded = $dom->loadXML($simplexml->asXML());

    if(!$loaded) {
        throw new Exception("Invalid XML detected.");
    }

    $xml_output = $dom->saveXML();

    // big groups get a little spacing
    $xml_output = preg_replace("/\n  <([a-z]+)>\n/i", "\n\n  <\\1>\n", $xml_output);
    $xml_output = str_replace("</config>", "\n</config>", $xml_output);

    return $xml_output;
}

// write some XML to a file, after formatting it nicely.
function write_xml(SimpleXMLElement $xml, $filename) {
    $fp = fopen($filename, "w+");
    if(!$fp) {
        throw new Exception("Couldn't open $filename for writing");
    }

    fwrite($fp, get_xml_output($xml));
    fclose($fp);
}

// recursively merge two xml structures
function merge_xml($xml1, $xml2) {
    $xml1 = get_xml($xml1);
    $xml2 = get_xml($xml2);

    foreach($xml2 as $tag => $elem2) {
        if(!$xml1->{$tag}) {
            $xml1->{$tag} = trim((string)$elem2);
        }

        if(count($elem2->attributes())) {
            foreach($elem2->attributes() as $attr => $value) {
                $xml1->{$tag}->addAttribute($attr, $value);
            }
        }
        if($elem2->count()) {
            merge_xml($xml1->{$tag}, $elem2);
        }
    }

    return $xml1;
}
