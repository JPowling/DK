<?php
declare(strict_types = 1);

session_start();

require_once "disallowed/authenticate.php";
require_once "disallowed/model/database/SQL.php";

$xsl_ns = "http://www.w3.org/1999/XSL/Transform";

#------------- Actual start -------------#

$xml = new SimpleXMLElement("<xml/>");

if (isset($_GET["route"]) && $_GET["route"] !== "") {
    $filename_php = "disallowed/mvcscripts/".$_GET["route"].".php";
    $filename_xsl = "disallowed/xsl/".$_GET["route"].".xsl";

    if (file_exists($filename_xsl)) {
        $xslcontent = $_GET["route"];
        
        if (file_exists($filename_php)) {
            require $filename_php;
        }
    } else {
        // Site not found!
        $xslcontent = 'notfound';
    }
} else {
    // Main page
    $xslcontent = 'mainpage';
}

$xml->addChild('include_xsl', $xslcontent);
$xml->addChild('content', "xml->content");
$xml->addChild('loggedin', strval(is_loggedin()));

$xsl = new DOMDocument();
$xsl->load("disallowed/xsl/base.xsl");

$stylesheet = $xsl->getElementsByTagName("stylesheet")->item(0);

#$files = glob('disallowed/xsl/*.xsl', GLOB_BRACE);
#foreach($files as $file) {
#    $file = str_replace("disallowed/xsl/" , "", $file);
#}


$template = $stylesheet->appendChild(new DOMElement("xsl:template", null, $xsl_ns));
$template->setAttribute("name", "getcontent");

$include = $stylesheet->appendChild(new DOMElement("xsl:include", null, $xsl_ns));
$include->setAttribute("href", $xslcontent.".xsl");

$apply_templates = $template->appendChild(new DOMElement("xsl:apply-templates", null, $xsl_ns));
$apply_templates->setAttribute("select", "/");
$apply_templates->setAttribute("mode", str_replace("/", ".", $xslcontent));

#echo $xsl->saveXML();


$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);

echo $html;
