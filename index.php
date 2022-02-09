<?php
declare(strict_types = 1);

session_start();

require_once "disallowed/authenticate.php";
require_once "disallowed/backend/database/SQL.php";

#------------- Actual start -------------#

$xml = new SimpleXMLElement("<xml/>");

if (isset($_GET["route"]) && $_GET["route"] !== "") {
    $filename_php = "disallowed/scripts/".$_GET["route"].".php";
    $filename_xsl = "disallowed/xsl/".$_GET["route"].".xsl";

    if (file_exists($filename_xsl) && $_GET["route"] !== "base") {
        $xslcontent = $_GET["route"];
        
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
