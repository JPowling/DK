<?php

require_once("php/DB.php");

$pdo = DB::connect();



loadPage("base");

foreach ($_POST as $a) {
    loadPage($a);
}


function loadPage(String $var)
{
    // echo "---" . $var . "---" . ;
    $xml = new DOMDocument();
    $xml->load("xml/base.xml");
    $doc = $xml->documentElement;

    $xsl = new DOMDocument();
    $xsl->load("xsl/" . $var . ".xsl");

    $xslt = new XSLTProcessor();
    $xslt->importStylesheet($xsl);
    $html = $xslt->transformToXml($xml);

    echo $html;

}

// require("php/useDB.php");