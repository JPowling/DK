<?php
declare(strict_types = 1);

session_start();

require_once "disallowed/authenticate.php";
require_once "disallowed/model/database/SQL.php";

$xsl_ns = "http://www.w3.org/1999/XSL/Transform";

#------------- Actual start -------------#

if (isset($_GET["route"]) && $_GET["route"] !== "") {
    $filename_php = "disallowed/mvcscripts/".$_GET["route"].".php";
    $filename_xsl = "disallowed/xsl/".$_GET["route"].".xsl";

    if (file_exists($filename_xsl)) {
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
