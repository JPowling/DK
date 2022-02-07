<?php

session_start();
require_once("php/DB.php");

$pdo = DB::connect();
require "disallowed/authenticate.php";

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



loadPage("base");

foreach ($_POST as $a) {
    loadPage($a);
}


function loadPage(String $var)
{
    // echo "---" . $var . "---" . ;
    $xml = new DOMDocument();
    $xml->load("xml/base.xml");
    $doc = $xml->documentElement;

    $xsl = new DOMDocument();
    $xsl->load("xsl/" . $var . ".xsl");

    $xslt = new XSLTProcessor();
    $xslt->importStylesheet($xsl);
    $html = $xslt->transformToXml($xml);

    echo $html;

}

// require("php/useDB.php");
