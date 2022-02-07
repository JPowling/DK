<?php

require_once("DB.php");


class SQL
{
    private PDO $pdo;


    public function __construct(bool $is_w = true)
    {
        $pdo = DB::connect($is_w);
    }


    public function sql_request(String $command = ";")
    {
        $cmd = $this::$pdo->prepare($command);
        $cmd->execute();
        echo $cmd->fetchAll();
    }
}
