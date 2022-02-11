<?php
# Jens

require_once("disallowed/backend/database/db.php");
require_once("disallowed/backend/database/result.php");

class SQL {
    private PDO $pdo;

    public function __construct(bool $is_w = false) {
        $this->pdo = DB::connect($is_w);
    }


    public function sql_request(String $command = ";") {
        $cmd = $this->pdo->prepare($command);
        $cmd->execute();
        $result = $cmd->fetchAll();
        return new Result($result);
    }
}
