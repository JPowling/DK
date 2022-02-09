<?php
# Owner: Paul
declare(strict_types = 1);

session_start();

require_once "disallowed/authenticate.php";
require_once "disallowed/backend/database/SQL.php";

#------------- Actual start -------------#

$xml = new SimpleXMLElement("<xml/>");


if ($_GET && isset($_GET["route"]) && $_GET["route"] !== "") {
    $route = $_GET["route"];

    while (substr($route, strlen($route) - 1, strlen($route)) == "/") {
        $route = substr($route, 0, strlen($route) - 1);
    }

    $filename_php = "disallowed/scripts/$route.php";
    $filename_xsl = "disallowed/xsl/$route.xsl";

    if (file_exists($filename_xsl) && $route !== "base") {
        $xslcontent = $route;
        
        // If the php file exists, run it
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

require "disallowed/xsl_loader.php";
