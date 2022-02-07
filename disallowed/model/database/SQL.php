<?php

require_once("disallowed/model/database/DB.php");


class SQL
{
    private PDO $pdo;


    public function __construct(bool $is_w = true)
    {
        echo "hallo";
        $this->pdo = DB::connect($is_w);
        if (isset($this->pdo)){
            echo "!is set";
        };
    }


    public function sql_request(String $command = ";")
    {
        
        $cmd = $this->pdo->prepare($command);
        $cmd->execute();
        $result = $cmd->fetchAll();
        foreach($result as $key => $row){
            foreach($row as $key2 => $value){
                echo $value;
            }
        }
    }
}
