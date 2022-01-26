<?php

$xml = new DOMDocument();
<<<<<<< HEAD
$xml->load("zugdaten.xml");
$doc = $xml->documentElement;

$xsl = new DOMDocument();
$xsl->load("zugdaten.xsl");
=======
$xml->load("test.xml");
$doc = $xml->documentElement;

$xsl = new DOMDocument();
$xsl->load("test.xsl");
>>>>>>> 0aa30151faa08141c8de61ef1a2306e3c81553f9

$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);



<<<<<<< HEAD
#echo file_get_contents("htmltest.html");


echo $html;
=======
#$html->file_get_contents("htmltest.html");


echo $html;
>>>>>>> 0aa30151faa08141c8de61ef1a2306e3c81553f9
