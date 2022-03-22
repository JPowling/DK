<?php
# Jens

require_once("disallowed/backend/database/db.php");
require_once("disallowed/backend/database/result.php");

class SQL {
    private PDO $pdo;

    public function __construct(bool $is_w = false) {
        $this->pdo = DB::connect($is_w);
    }

    //@Deprecated
    public function sql_request(String $command = ";") {
        $cmd = $this->pdo->prepare($command);
        $cmd->execute();
        $result = $cmd->fetchAll();
        return new Result($result);
    }


    public function request(String $command = ";", array $bindValues = null) {
        if ($bindValues == null) {
            $bindValues = array();
        }

        $cmd = $this->pdo->prepare($command);

        foreach ($bindValues as $key => $value) {
            $cmd->bindValue($key, $value, PDO::PARAM_STR);
        }

        $cmd->execute();
        $result = $cmd->fetchAll();
        return new Result($result);
    }


    public function post(String $command = ";", array $bindValues = null) {
        if ($bindValues == null) {
            $bindValues = array();
        }
        $cmd = $this->pdo->prepare($command);

        foreach ($bindValues as $key => $value) {
            $cmd->bindValue($key, $value, PDO::PARAM_STR);
        }

        $cmd->execute();
    }


    public function transaction(array $commands, array $bindValues) {
        try {

            $this->pdo->beginTransaction();

            foreach ($commands as $key => $commamd) {
                $cmd = $this->pdo->prepare($commamd);

                if (isset($bindValues[$key])) {
                    foreach ($bindValues[$key] as $bind_key => $value) {
                        $cmd->bindValue($bind_key, $value, PDO::PARAM_STR);
                    }
                }

                $cmd->execute();
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            print_r($e);
            $this->pdo->rollBack();
            return false;
        }
        return true;
    }
}
