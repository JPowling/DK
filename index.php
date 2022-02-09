<?php
declare(strict_types = 1);

session_start();

require "disallowed/authenticate.php";
require_once "disallowed/model/database/SQL.php";

#------------- Actual start -------------#

$xml = new SimpleXMLElement("<xml/>");

$xml->addChild('xslcontent', 'mainpage');
$xml->addChild('content', 'Email: '.(isset($_SESSION['user']) ? $_SESSION['user']->email : "not logged in"));
$xml->addChild('loggedin', strval(is_loggedin()));

$xsl = new DOMDocument();
$xsl->load("disallowed/xsl/base.xsl");

$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);
$html = $xslt->transformToXml($xml);

echo $html;
