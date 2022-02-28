<?php
ensure_loggedin();


$user = new User($_SESSION["email"]);

$xml->addChild("title", "Dein Profil: $user->forename | BD");

$xml->addChild("fullname", "$user->forename $user->surname");
$xml->addChild("fulladdress", "$user->street $user->house");
$xml->addChild("fullresidence", "$user->postal $user->residence");


$xml->addChild("forename", "$user->forename");
$xml->addChild("surname", "$user->surname");
$xml->addChild("email", "$user->email");
$xml->addChild("phone", "$user->phone");
$xml->addChild("residence", "$user->residence");
$xml->addChild("postal", "$user->postal");
$xml->addChild("street", "$user->street");
$xml->addChild("house", "$user->house");
$xml->addChild("creation_date", "$user->creation_date");
