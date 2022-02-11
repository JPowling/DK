<?php
# Paul
ensure_admin();

$ownuser = new User($_SESSION["email"]);
$xml->addChild("forename", "$ownuser->forename");

if (isset($_GET["id"])) {
    $sql = new SQL(true);

    $result = $sql->sql_request("SELECT EMail FROM Benutzer WHERE BenutzerID='" . $_GET["id"] . "'"); // SQL INJECTION!!!

    if ($result->get_num_rows() == 0) {
        header("Location: /administration/users");
        return;
    }

    $email = $result->get_from_column("EMail");

    if (isset($_GET["newrole"]) && $email != $_SESSION["email"]) {
        $newrole = $_GET["newrole"];

        # Some kind of user sanitization
        $roleid = "";
        switch ($newrole) {
            case "user":
                $roleid = "1";
                break;
            case "mod":
                $roleid = "2";
                break;
            case "admin":
                $roleid = "3";
                break;
        }
        if (!empty($roleid)) {
            $sql->sql_request("UPDATE Benutzer SET BerechtigungsID='$roleid' WHERE BenutzerID='" . $_GET["id"] . "'"); // SQL INJECTION!!!
        }
    }

    $user = new User($email);

    $xml->addChild("fullname", "$user->forename $user->surname");
    $xml->addChild("fulladdress", "$user->street $user->house");
    $xml->addChild("fullresidence", "$user->postal $user->residence");


    $xml->addChild("forename_other", "$user->forename");
    $xml->addChild("surname", "$user->surname");
    $xml->addChild("email", "$user->email");
    $xml->addChild("phone", "$user->phone");
    $xml->addChild("residence", "$user->residence");
    $xml->addChild("postal", "$user->postal");
    $xml->addChild("street", "$user->street");
    $xml->addChild("house", "$user->house");
    $xml->addChild("creation_date", "$user->creation_date");
    $xml->addChild("id", $_GET["id"]);
    $xml->addChild("rank", $user->get_privileges());
}
