<?php

$xml = new DOMDocument();
$xml->load("background/xml/base.xml");
$doc = $xml->documentElement;

$xsl = new DOMDocument();
$xsl->load("background/xsl/base.xsl");

$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);

echo $html;
