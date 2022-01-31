<?php

$xml = new SimpleXMLElement("<xml/>");

$xml->addChild('xslcontent', 'mainpage');
$xml->addChild('lustig', rand());

$xsl = new DOMDocument();
$xsl->load("background/xsl/base.xsl");

$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);

echo $html;

foreach($_GET as $a) {
    echo $a;
}