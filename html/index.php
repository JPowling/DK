<?php

$xml = new DOMDocument();
$xml->load("base.xml");
$doc = $xml->documentElement;

$xsl = new DOMDocument();
$xsl->load("base.xsl");

$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);

echo $html;
