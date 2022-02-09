<?php

$xml = new SimpleXMLElement("<xml/>");

$xml->addChild('include_xsl', $xslcontent);
$xml->addChild('content', "xml content dummy");
$xml->addChild('loggedin', strval(is_loggedin()));

$xsl = new DOMDocument();
$xsl->load("disallowed/xsl/base.xsl");

$stylesheet = $xsl->getElementsByTagName("stylesheet")->item(0);

$template = $stylesheet->appendChild(new DOMElement("xsl:template", null, $xsl_ns));
$template->setAttribute("name", "getcontent");

$include = $stylesheet->appendChild(new DOMElement("xsl:include", null, $xsl_ns));
$include->setAttribute("href", $xslcontent.".xsl");

$apply_templates = $template->appendChild(new DOMElement("xsl:apply-templates", null, $xsl_ns));
$apply_templates->setAttribute("select", "/");
$apply_templates->setAttribute("mode", str_replace("/", ".", $xslcontent));

$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);

echo $html;
