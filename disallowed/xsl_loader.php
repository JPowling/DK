<?php

$xsl_ns = "http://www.w3.org/1999/XSL/Transform";

$xml->addChild('include_xsl', $xslcontent);
$xml->addChild('loggedin', strval(is_loggedin()));

$xsl = new DOMDocument();
$xsl->load("disallowed/xsl/base.xsl");

# Load needed subxsl file into base.xsl

$stylesheet = $xsl->getElementsByTagName("stylesheet")->item(0);

$include = $stylesheet->appendChild(new DOMElement("xsl:include", null, $xsl_ns));
$include->setAttribute("href", $xslcontent.".xsl");

$template = $stylesheet->appendChild(new DOMElement("xsl:template", null, $xsl_ns));
$template->setAttribute("name", "getcontent");

$apply_templates = $template->appendChild(new DOMElement("xsl:apply-templates", null, $xsl_ns));
$apply_templates->setAttribute("select", "/");
$apply_templates->setAttribute("mode", "mode");

$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);

echo $html;
