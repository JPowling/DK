<?php

require_once("disallowed/model/database/db_config.php");

class DB
{
  public static function connect(bool $is_w = false)
  {
    $dsn  =              DBConfig::DRIVER . ':';
    $dsn .= 'host='    . DBConfig::HOST   . ';';
    $dsn .= 'port='    . DBConfig::PORT   . ';';
    $dsn .= 'dbname='  . DBConfig::NAME   . ';';
    $dsn .= 'charset=' . DBConfig::CHARSET;

    if ($is_w) {
      $options = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT         => true
      );

      return new PDO($dsn, DBConfig::USER_RW, DBConfig::PASSWORD_RW, $options);
    } else {
      $options = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT         => true
      );

      return new PDO($dsn, DBConfig::USER_R, DBConfig::PASSWORD_R, $options);
    }
  }

  public static function convertStr($str)
  {
    return null; //TODO
  }
}
