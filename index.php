<?php

$xml = new DOMDocument();
$xml->load("zugdaten.xml");
$doc = $xml->documentElement;

$xsl = new DOMDocument();
$xsl->load("zugdaten.xsl");

$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);



echo $html;
