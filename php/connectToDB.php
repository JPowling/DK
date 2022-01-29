<?php


$db_driver  = 'mysql';
$db_host    = '93.213.178.252';
$db_port    = 3306;
$db_name    = 'dk';
$db_user    = 'website';
$db_passwd  = 'websitejenspauldk';
$db_charset = 'utf8';

$dsn  =              $db_driver . ':';
$dsn .= 'host='    . $db_host   . ';';
$dsn .= 'port='    . $db_port   . ';';
$dsn .= 'dbname='  . $db_name   . ';';
$dsn .= 'charset=' . $db_charset;

$pdo_options = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_PERSISTENT         => true
);

$pdo = new PDO($dsn, $db_user, $db_passwd, $pdo_options);


$sql  = 'SELECT a.Name AS BahnhofA, b.Name, v.Dauer 
FROM Verbindungen as v 
Inner Join Bahnhofe as a on v.BahnhofA = a.Kennzeichnung
Inner Join Bahnhofe as b on v.BahnhofA = b.Kennzeichnung';

$cmd = $pdo->prepare($sql);
$cmd->execute();




$row = $cmd->fetch();
do {
    foreach ($row as $value) {
        echo $value . " ";
    }
    echo "<br/>";
    $row = $cmd->fetch();
} while ($row);



class DBConfig
{
  const DRIVER   = 'mysql';
  const HOST     = '93.213.178.252';
  const PORT     = '3306';
  const NAME     = 'dk';
  const USER     = 'website';
  const PASSWORD = 'websitejenspauldk';
  const CHARSET  = 'utf8';
}

