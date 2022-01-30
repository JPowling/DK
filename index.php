<?php

require_once("php/DB.php");

$pdo = DB::connect();


$xml = new DOMDocument();
$xml->load("xml/base.xml");
$doc = $xml->documentElement;

$xsl = new DOMDocument();
$xsl->load("xsl/base.xsl");

$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);

#echo $html;

require("php/useDB.php");