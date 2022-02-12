<?php
# Owner: Paul
declare(strict_types=1);

session_start();

require_once "disallowed/authenticate.php";
require_once "disallowed/backend/database/sql.php";

#------------- Actual start -------------#

$xml = new SimpleXMLElement("<xml/>");

# When the route points to an existing xsl file it gets loaded into base.xsl
# When no xsl file is found it loads notfound.xsl into base.xsl
# When no xsl file is found BUT a php script is, this script gets executed and the user is forwarded to base directory
if ($_GET && isset($_GET["route"]) && $_GET["route"] !== "") {
    $route = $_GET["route"];

    # PHP is shit: This is a String::ends_with function workaround
    while (substr($route, strlen($route) - 1, strlen($route)) == "/") {
        $route = substr($route, 0, strlen($route) - 1);
    }

    $filename_php = "disallowed/scripts/$route.php";
    $filename_xsl = "disallowed/xsl/$route.xsl";

    $php = file_exists($filename_php);
    $xsl = file_exists($filename_xsl) && $route !== "base";

    if ($php && !$xsl) {
        require $filename_php;
        header("Location: /");
        return;
    } else {
        # If the php file exists, run it
        if ($php) {
            require $filename_php;
        }
        if ($xsl) {
            $xslcontent = $route;
        } else {
            # Site not found!
            $xslcontent = 'notfound';
        }
    }
} else {
    # Main page
    require "disallowed/scripts/mainpage.php";
    $xslcontent = 'mainpage';
}

if (is_loggedin()) {
    $user = new User($_SESSION["email"]);
    $xml->addChild("forename", $user->forename);
}

require "disallowed/xsl_loader.php";
