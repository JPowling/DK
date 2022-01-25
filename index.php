<?php

$xml = new DOMDocument();
#$xml->load("test.xml");
#$doc = $xml->documentElement;

#$xsl = new DOMDocument();
#$xsl->load("test.xsl");

#$xslt = new XSLTProcessor();
#$xslt->importStylesheet($xsl);
#$html = $xslt->transformToXml($xml);



echo file_get_contents("htmltest.html");


#echo $html;
