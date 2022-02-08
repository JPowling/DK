<?php

session_start();

require "disallowed/authenticate.php";
require_once "disallowed/model/database/SQL.php";

if (is_loggedin()) {
    logout();
} else {
    login("jens.rosenbauer@ohgw.de", "12345");
}

$xml = new SimpleXMLElement("<xml/>");

$xml->addChild('xslcontent', 'mainpage');
$xml->addChild('content', 'Email: '.(isset($_SESSION['user']) ? $_SESSION['user']->email : "not logged in"));
$xml->addChild('loggedin', is_loggedin());

$xsl = new DOMDocument();
$xsl->load("disallowed/xsl/base.xsl");

$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);

echo $html;
