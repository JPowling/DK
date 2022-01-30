<?php

require_once("php/DB_config.php");

class DB
{
    public static function connect()
    {
      $dsn  =              DBConfig::DRIVER . ':';
      $dsn .= 'host='    . DBConfig::HOST   . ';';
      $dsn .= 'port='    . DBConfig::PORT   . ';';
      $dsn .= 'dbname='  . DBConfig::NAME   . ';';
      $dsn .= 'charset=' . DBConfig::CHARSET;
  
      $options = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT         => true);
  
      $pdo1 = new PDO($dsn, DBConfig::USER, DBConfig::PASSWORD, $options);
  
      return $pdo1;
    }
  
    public static function convertStr($str)
    {
      return strlen($str) == 0 ? null : $str;
    }
  
}
